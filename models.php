<?php include 'components/header.php'; ?>

<?php

$metrics = [

    'DT' => [
        'accuracy' => 98,
        'precision' => 97,
        'recall' => 1
    ],

    'KNN' => [
        'accuracy' => 83,
        'precision' => 62,
        'recall' => 68
    ],

    'SVM' => [
        'accuracy' => 98,
        'precision' => 94,
        'recall' => 97
    ]

];

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Machine Learning Models
    </title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<div class="container mt-5">

    <div class="section-title">

        <h1>
            Machine Learning Models
        </h1>

        <p>
            Perbandingan performa model machine learning
            pada dataset Gaming and Mental Health.
        </p>

    </div>

    <!-- CHART -->

    <div class="card-box mb-5">

        <h2 class="mb-4">
            Model Comparison
        </h2>

        <canvas id="accuracyChart"></canvas>

    </div>

    <!-- DECISION TREE -->

    <div class="card-box mb-5">

        <div class="row">

            <div class="col-md-6">

                <h2>
                    Decision Tree
                </h2>

                <p>
                    Decision Tree adalah algoritma machine learning
                    berbasis pohon keputusan yang digunakan
                    untuk melakukan klasifikasi data gaming addiction.
                </p>

                <h4>
                    Kelebihan
                </h4>

                <ul>

                    <li>
                        Mudah dipahami
                    </li>

                    <li>
                        Cepat dalam prediksi
                    </li>

                    <li>
                        Cocok untuk klasifikasi
                    </li>

                </ul>

                <h4>
                    Kekurangan
                </h4>

                <ul>

                    <li>
                        Mudah overfitting
                    </li>

                    <li>
                        Kurang stabil
                    </li>

                </ul>

                <hr>

                <p>
                    Accuracy:
                    <strong>
                        <?= $metrics['DT']['accuracy']; ?>%
                    </strong>
                </p>

            </div>

            <div class="col-md-6 text-center">

                <img
                    src="assets/images/decision-tree.png"
                    width="300"
                >

            </div>

        </div>

    </div>

    <!-- RANDOM FOREST -->

    <div class="card-box mb-5">

        <div class="row">

            <div class="col-md-6">

                <h2>
                    SVM
                </h2>

                <p>
                SVM merupakan algoritma machine learning
                yang bekerja dengan mencari hyperplane terbaik
                untuk memisahkan setiap kelas data.
                </p>

                <h4>
                    Kelebihan
                </h4>

                <ul>

                    <li>
                        Akurasi tinggi
                    </li>

                    <li>
                        Cocok untuk klasifikasi kompleks
                    </li>

                    <li>
                        Efektif pada dataset multidimensi
                    </li>

                </ul>

                <h4>
                    Kekurangan
                </h4>

                <ul>

                    <li>
                        Training lebih lama
                    </li>

                    <li>
                        Sensitif terhadap parameter
                    </li>

                </ul>

                <hr>

                <p>
                    Accuracy:
                    <strong>
                        <?= $metrics['SVM']['accuracy']; ?>%
                    </strong>
                </p>

            </div>

            <div class="col-md-6 text-center">

                <img
                    src="assets/images/svm.png"
                    width="300"
                >

            </div>

        </div>

    </div>

    <!-- KNN -->

    <div class="card-box mb-5">

        <div class="row">

            <div class="col-md-6">

                <h2>
                    K-Nearest Neighbor
                </h2>

                <p>
                    KNN melakukan klasifikasi berdasarkan
                    kemiripan data dengan tetangga terdekat.
                </p>

                <h4>
                    Kelebihan
                </h4>

                <ul>

                    <li>
                        Sederhana
                    </li>

                    <li>
                        Tidak perlu training kompleks
                    </li>

                </ul>

                <h4>
                    Kekurangan
                </h4>

                <ul>

                    <li>
                        Lambat pada dataset besar
                    </li>

                    <li>
                        Sensitif terhadap scaling
                    </li>

                </ul>

                <hr>

                <p>
                    Accuracy:
                    <strong>
                        <?= $metrics['KNN']['accuracy']; ?>%
                    </strong>
                </p>

            </div>

            <div class="col-md-6 text-center">

                <img
                    src="assets/images/knn.png"
                    width="300"
                >

            </div>

        </div>

    </div>

</div>

<!-- CHART JS -->

<script>

const ctx = document.getElementById('accuracyChart');

new Chart(ctx, {

    type: 'bar',

    data: {

        labels: [
            'Decision Tree',
            'KNN',
            'SVM'
        ],

        datasets: [{

            label: 'Accuracy',

            data: [
                98,
                83,
                98
            ]

        }]

    }

});

</script>

</body>

</html>

<?php include 'components/footer.php'; ?>