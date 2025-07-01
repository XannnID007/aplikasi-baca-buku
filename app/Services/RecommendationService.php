<?php

namespace App\Services;

use App\Models\Buku;
use App\Models\RiwayatBacaan;

class RecommendationService
{
     protected $k = 3; // Jumlah cluster untuk K-means

     public function getRecommendationsByCategory($kategoriIds, $userId = null)
     {
          $bukus = Buku::whereHas('kategoris', function ($query) use ($kategoriIds) {
               $query->whereIn('kategori_id', $kategoriIds);
          })->with('kategoris')->get();

          if ($bukus->count() < $this->k) {
               return $bukus->take(10);
          }

          return $this->kMeansClustering($bukus, $userId);
     }

     public function getPersonalizedRecommendations($userId)
     {
          $user = \App\Models\User::find($userId);
          if (!$user || !$user->preferensiUser) {
               return collect();
          }

          $preferensi = $user->preferensiUser;
          return $this->getRecommendationsByCategory($preferensi->kategori_pilihan, $userId);
     }

     protected function kMeansClustering($bukus, $userId = null)
     {
          // Konversi buku ke data points untuk clustering
          $dataPoints = $this->convertBooksToDataPoints($bukus);

          // Inisialisasi centroids secara random
          $centroids = $this->initializeCentroids($dataPoints);

          $maxIterations = 100;
          $tolerance = 0.001;

          for ($iteration = 0; $iteration < $maxIterations; $iteration++) {
               $oldCentroids = $centroids;

               // Assign data points to clusters
               $clusters = $this->assignToClusters($dataPoints, $centroids);

               // Update centroids
               $centroids = $this->updateCentroids($clusters);

               // Check convergence
               if ($this->hasConverged($oldCentroids, $centroids, $tolerance)) {
                    break;
               }
          }

          // Pilih cluster terbaik berdasarkan user history atau rating tertinggi
          $bestCluster = $this->selectBestCluster($clusters, $userId);

          return $bestCluster->take(10);
     }

     protected function convertBooksToDataPoints($bukus)
     {
          $dataPoints = [];

          foreach ($bukus as $buku) {
               $dataPoints[] = [
                    'buku' => $buku,
                    'features' => [
                         'views' => $this->normalizeValue($buku->views, 0, 10000),
                         'halaman' => $this->normalizeValue($buku->halaman, 50, 1000),
                         'tahun_terbit' => $this->normalizeValue($buku->tahun_terbit, 1900, date('Y')),
                         'rating' => $this->normalizeValue($buku->rating_rata_rata, 0, 5),
                         'deskripsi_length' => $this->normalizeValue(strlen($buku->deskripsi), 100, 2000)
                    ]
               ];
          }

          return $dataPoints;
     }

     protected function normalizeValue($value, $min, $max)
     {
          if ($max == $min) return 0;
          return ($value - $min) / ($max - $min);
     }

     protected function initializeCentroids($dataPoints)
     {
          $centroids = [];
          $numFeatures = count($dataPoints[0]['features']);

          for ($i = 0; $i < $this->k; $i++) {
               $centroid = [];
               for ($j = 0; $j < $numFeatures; $j++) {
                    $centroid[] = rand(0, 100) / 100; // Random value between 0 and 1
               }
               $centroids[] = $centroid;
          }

          return $centroids;
     }

     protected function assignToClusters($dataPoints, $centroids)
     {
          $clusters = array_fill(0, $this->k, []);

          foreach ($dataPoints as $point) {
               $minDistance = PHP_FLOAT_MAX;
               $assignedCluster = 0;

               foreach ($centroids as $i => $centroid) {
                    $distance = $this->euclideanDistance(array_values($point['features']), $centroid);

                    if ($distance < $minDistance) {
                         $minDistance = $distance;
                         $assignedCluster = $i;
                    }
               }

               $clusters[$assignedCluster][] = $point;
          }

          return $clusters;
     }

     protected function euclideanDistance($point1, $point2)
     {
          $sum = 0;
          for ($i = 0; $i < count($point1); $i++) {
               $sum += pow($point1[$i] - $point2[$i], 2);
          }
          return sqrt($sum);
     }

     protected function updateCentroids($clusters)
     {
          $newCentroids = [];

          foreach ($clusters as $cluster) {
               if (empty($cluster)) {
                    // Jika cluster kosong, buat centroid random
                    $centroid = [];
                    for ($i = 0; $i < 5; $i++) { // 5 features
                         $centroid[] = rand(0, 100) / 100;
                    }
                    $newCentroids[] = $centroid;
                    continue;
               }

               $centroid = array_fill(0, 5, 0); // 5 features

               foreach ($cluster as $point) {
                    $features = array_values($point['features']);
                    for ($i = 0; $i < count($features); $i++) {
                         $centroid[$i] += $features[$i];
                    }
               }

               // Average
               for ($i = 0; $i < count($centroid); $i++) {
                    $centroid[$i] /= count($cluster);
               }

               $newCentroids[] = $centroid;
          }

          return $newCentroids;
     }

     protected function hasConverged($oldCentroids, $newCentroids, $tolerance)
     {
          for ($i = 0; $i < count($oldCentroids); $i++) {
               $distance = $this->euclideanDistance($oldCentroids[$i], $newCentroids[$i]);
               if ($distance > $tolerance) {
                    return false;
               }
          }
          return true;
     }

     protected function selectBestCluster($clusters, $userId = null)
     {
          if ($userId) {
               // Pilih cluster berdasarkan history user
               $userBooks = RiwayatBacaan::where('user_id', $userId)
                    ->with('buku')
                    ->get()
                    ->pluck('buku');

               $bestScore = -1;
               $bestCluster = collect();

               foreach ($clusters as $cluster) {
                    $score = $this->calculateClusterScore($cluster, $userBooks);
                    if ($score > $bestScore) {
                         $bestScore = $score;
                         $bestCluster = collect($cluster)->pluck('buku');
                    }
               }

               return $bestCluster;
          }

          // Jika tidak ada user, pilih cluster dengan rating tertinggi
          $bestCluster = collect();
          $bestRating = -1;

          foreach ($clusters as $cluster) {
               if (empty($cluster)) continue;

               $avgRating = collect($cluster)->avg(function ($point) {
                    return $point['buku']->rating_rata_rata;
               });

               if ($avgRating > $bestRating) {
                    $bestRating = $avgRating;
                    $bestCluster = collect($cluster)->pluck('buku');
               }
          }

          return $bestCluster;
     }

     protected function calculateClusterScore($cluster, $userBooks)
     {
          if (empty($cluster)) return 0;

          $score = 0;
          $clusterBooks = collect($cluster)->pluck('buku');

          foreach ($clusterBooks as $book) {
               // Tambah score berdasarkan rating
               $score += $book->rating_rata_rata * 0.4;

               // Tambah score jika kategori mirip dengan buku yang pernah dibaca user
               foreach ($userBooks as $userBook) {
                    $commonCategories = $book->kategoris->intersect($userBook->kategoris)->count();
                    $score += $commonCategories * 0.3;
               }

               // Tambah score berdasarkan popularitas
               $score += ($book->views / 1000) * 0.3;
          }

          return $score / count($cluster);
     }
}
