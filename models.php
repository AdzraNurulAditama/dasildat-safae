<?php include 'components/header.php'; ?>

<?php
$metrics = [
    'DT' => [
        'name' => 'Decision Tree',
        'accuracy' => 98,
        'precision' => 97,
        'recall' => 100,
        'color' => '#0ea5e9' // Disesuaikan sedikit lebih pekat untuk light mode (Sky 500)
    ],
    'KNN' => [
        'name' => 'K-Nearest Neighbor',
        'accuracy' => 85,
        'precision' => 70,
        'recall' => 76,
        'color' => '#ec4899' // Pink 500
    ],
    'SVM' => [
        'name' => 'Support Vector Machine',
        'accuracy' => 98,
        'precision' => 94,
        'recall' => 97,
        'color' => '#8b5cf6' // Violet 500
    ]
];
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    important: '#tailwind-scope',
    corePlugins: {
      preflight: false, 
    }
  }
</script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<div id="tailwind-scope" style="margin-top: 200px !important; display: block; clear: both; color: #0f172a; font-family: 'Plus Jakarta Sans', sans-serif;">
    <section class="max-w-5xl mx-auto px-4 pb-24">

        <div class="text-center mb-16 relative block" data-aos="fade-down" data-aos-duration="1000">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-purple-100 border border-purple-200 text-xs font-semibold tracking-widest text-purple-700 uppercase mb-5 shadow-sm font-mono">
                <span class="w-2 h-2 rounded-full bg-purple-600 shadow-[0_0_8px_#9333ea]"></span>
                Machine Learning Analytics
            </span>
            <h1 class="text-4xl md:text-6xl font-black tracking-tight mb-5 bg-gradient-to-r from-purple-600 via-pink-500 to-amber-500 bg-clip-text text-transparent leading-tight py-1 font-extrabold">
                Classification Models
            </h1>
            <p class="text-slate-600 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                Visualisasi hasil perbandingan performa model machine learning yang digunakan untuk memprediksi tingkat risiko kecanduan game berdasarkan dataset Gaming and Mental Health.            </p>
        </div>

        <div class="border border-slate-200 rounded-[40px] p-6 md:p-10 shadow-xl shadow-slate-200/50 mb-12" data-aos="fade-up" style="background-color: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px);">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-8 border-b border-slate-100 pb-4">
                <h2 class="text-2xl font-bold tracking-wide text-slate-900 m-0">Comparative Performance Metrics</h2>
                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold bg-purple-50 border border-purple-100 text-purple-600 font-mono tracking-widest self-start sm:self-auto shadow-sm">LIVE ANALYTICS DASHBOARD</span>
            </div>
            <div class="relative w-full h-[380px]">
                <canvas id="multiMetricChart"></canvas>
            </div>
        </div>

        <div class="space-y-12">

            <div class="group/card border border-slate-200 hover:border-sky-300 rounded-[38px] p-6 md:p-10 transition-all duration-300 shadow-lg hover:shadow-xl relative overflow-hidden bg-white" data-aos="fade-up">
                <div class="absolute w-[350px] h-[350px] rounded-full filter blur-[120px] opacity-[0.15] -top-[120px] -right-[100px]" style="background: <?= $metrics['DT']['color']; ?>;"></div>
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                    <div class="lg:col-span-8 space-y-6">
                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold border bg-sky-50 border-sky-200 text-sky-600 font-mono tracking-wider">RANK #1 - HIGHEST PRECISION</span>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 m-0"><?= $metrics['DT']['name']; ?></h2>
                        <p class="text-slate-600 text-sm md:text-base leading-relaxed m-0">
                            Decision Tree melakukan klasifikasi dengan memetakan atribut data ke dalam struktur pohon keputusan bercabang. Sangat unggul dalam mendeteksi riwayat gaming addiction secara runut dan transparan.
                        </p>

                        <div class="space-y-3 max-w-xl">
                            <?php foreach(['accuracy', 'precision', 'recall'] as $meta): ?>
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-mono tracking-wider text-slate-500 uppercase">
                                    <span><?= $meta; ?></span>
                                    <span class="font-bold text-slate-900"><?= $metrics['DT'][$meta]; ?>%</span>
                                </div>
                                <div class="h-2.5 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(14,165,233,0.3)]" style="width: <?= $metrics['DT'][$meta]; ?>%; background: <?= $metrics['DT']['color']; ?>;"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="pros-cons grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50">
                                <h4 class="text-sm font-bold tracking-wider text-sky-600 uppercase mb-3 font-mono">✓ Core Advantages</h4>
                                <ul class="text-xs text-slate-600 space-y-2 pl-4 m-0">
                                    <li>Struktur keputusan mudah dipahami dan diinterpretasikan.</li>
                                    <li>Proses prediksi dapat dilakukan dengan cepat pada data baru.</li>
                                </ul>
                            </div>
                            <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50">
                                <h4 class="text-sm font-bold tracking-wider text-pink-600 uppercase mb-3 font-mono">✗ Model Limitations</h4>
                                <ul class="text-xs text-slate-600 space-y-2 pl-4 m-0">
                                    <li>Rentan mengalami overfitting jika variasi pohon terlalu dalam.</li>
                                    <li>Sensitif terhadap pergeseran minor pada struktur dataset.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-4 flex justify-center group-hover/card:scale-105 transition-transform duration-500">
                        <img src="assets/images/decision-tree.jpeg" class="w-full max-w-[240px] rounded-3xl opacity-90 filter drop-shadow-xl border border-slate-200">
                    </div>
                </div>
            </div>

            <div class="group/card border border-slate-200 hover:border-violet-300 rounded-[38px] p-6 md:p-10 transition-all duration-300 shadow-lg hover:shadow-xl relative overflow-hidden bg-white" data-aos="fade-up">
                <div class="absolute w-[350px] h-[350px] rounded-full filter blur-[120px] opacity-[0.15] -top-[120px] -right-[100px]" style="background: <?= $metrics['SVM']['color']; ?>;"></div>
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                    <div class="lg:col-span-8 space-y-6">
                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold border bg-violet-50 border-violet-200 text-violet-600 font-mono tracking-wider">RANK #2 - STABLE MULTIDIMENSIONAL</span>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 m-0"><?= $metrics['SVM']['name']; ?></h2>
                        <p class="text-slate-600 text-sm md:text-base leading-relaxed m-0">
                            Support Vector Machine memproyeksikan fitur data ke dalam ruang berdimensi tinggi untuk menemukan sekat pemisah optimal (hyperplane). Sangat andal memproses relasi antar parameter kesehatan mental yang rumit.                        </p>

                        <div class="space-y-3 max-w-xl">
                            <?php foreach(['accuracy', 'precision', 'recall'] as $meta): ?>
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-mono tracking-wider text-slate-500 uppercase">
                                    <span><?= $meta; ?></span>
                                    <span class="font-bold text-slate-900"><?= $metrics['SVM'][$meta]; ?>%</span>
                                </div>
                                <div class="h-2.5 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(139,92,246,0.3)]" style="width: <?= $metrics['SVM'][$meta]; ?>%; background: <?= $metrics['SVM']['color']; ?>;"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="pros-cons grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50">
                                <h4 class="text-sm font-bold tracking-wider text-violet-600 uppercase mb-3 font-mono">✓ Core Advantages</h4>
                                <ul class="text-xs text-slate-600 space-y-2 pl-4 m-0">
                                    <li>Garansi akurasi sangat konsisten pada sebaran dimensi data tinggi.</li>
                                    <li>Kebal terhadap outlier memory.</li>
                                </ul>
                            </div>
                            <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50">
                                <h4 class="text-sm font-bold tracking-wider text-pink-600 uppercase mb-3 font-mono">✗ Model Limitations</h4>
                                <ul class="text-xs text-slate-600 space-y-2 pl-4 m-0">
                                    <li>Membutuhkan waktu pengolahan kalkulasi matrik kernel yang lama.</li>
                                    <li>Kompleksitas hyperparameter tuning tinggi.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-4 flex justify-center group-hover/card:scale-105 transition-transform duration-500">
                        <img src="assets/images/svm.png" class="w-full max-w-[240px] rounded-3xl opacity-90 filter drop-shadow-xl border border-slate-200">
                    </div>
                </div>
            </div>

            <div class="group/card border border-slate-200 hover:border-pink-300 rounded-[38px] p-6 md:p-10 transition-all duration-300 shadow-lg hover:shadow-xl relative overflow-hidden bg-white" data-aos="fade-up">
                <div class="absolute w-[350px] h-[350px] rounded-full filter blur-[120px] opacity-[0.15] -top-[120px] -right-[100px]" style="background: <?= $metrics['KNN']['color']; ?>;"></div>
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                    <div class="lg:col-span-8 space-y-6">
                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold border bg-pink-50 border-pink-200 text-pink-600 font-mono tracking-wider">RANK #3 - INSTANT INSTANCE INSTINCT</span>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 m-0"><?= $metrics['KNN']['name']; ?></h2>
                        <p class="text-slate-600 text-sm md:text-base leading-relaxed m-0">
                            K-Nearest Neighbor (KNN) merupakan algoritma klasifikasi yang menentukan kategori suatu data berdasarkan kemiripannya dengan sejumlah data terdekat. Pada penelitian ini, KNN digunakan untuk mengelompokkan tingkat risiko kecanduan game dengan membandingkan karakteristik responden terhadap data yang telah diketahui kategorinya.
                        </p>

                        <div class="space-y-3 max-w-xl">
                            <?php foreach(['accuracy', 'precision', 'recall'] as $meta): ?>
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-mono tracking-wider text-slate-500 uppercase">
                                    <span><?= $meta; ?></span>
                                    <span class="font-bold text-slate-900"><?= $metrics['KNN'][$meta]; ?>%</span>
                                </div>
                                <div class="h-2.5 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(236,72,153,0.3)]" style="width: <?= $metrics['KNN'][$meta]; ?>%; background: <?= $metrics['KNN']['color']; ?>;"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="pros-cons grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50">
                                <h4 class="text-sm font-bold tracking-wider text-pink-600 uppercase mb-3 font-mono">✓ Core Advantages</h4>
                                <ul class="text-xs text-slate-600 space-y-2 pl-4 m-0">
                                    <li>Sangat praktis, tangguh, dan sama sekali tidak memerlukan proses training yang berat.</li>
                                    <li>Sangat mudah ditambahkan data sampel baru tanpa merusak fungsi inti.</li>
                                </ul>
                            </div>
                            <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50">
                                <h4 class="text-sm font-bold tracking-wider text-pink-600 uppercase mb-3 font-mono">✗ Model Limitations</h4>
                                <ul class="text-xs text-slate-600 space-y-2 pl-4 m-0">
                                    <li>Peforma komputasi melambat signifikan seiring membengkaknya baris dataset.</li>
                                    <li>Sangat sensitif terhadap anomali perbedaan skala atau satuan fitur data.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-4 flex justify-center group-hover/card:scale-105 transition-transform duration-500">
                        <img src="assets/images/knn.png" class="w-full max-w-[240px] rounded-3xl opacity-90 filter drop-shadow-xl border border-slate-200">
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
const ctx = document.getElementById('multiMetricChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Decision Tree', 'K-Nearest Neighbor', 'Support Vector Machine'],
        datasets: [
            {
                label: 'Accuracy',
                data: [
                    <?= $metrics['DT']['accuracy']; ?>, 
                    <?= $metrics['KNN']['accuracy']; ?>, 
                    <?= $metrics['SVM']['accuracy']; ?>
                ],
                backgroundColor: 'rgba(2, 132, 199, 0.8)', // Sky 600
                borderColor: '#0284c7',
                borderWidth: 1,
                borderRadius: 8
            },
            {
                label: 'Precision',
                data: [
                    <?= $metrics['DT']['precision']; ?>, 
                    <?= $metrics['KNN']['precision']; ?>, 
                    <?= $metrics['SVM']['precision']; ?>
                ],
                backgroundColor: 'rgba(219, 39, 119, 0.8)', // Pink 600
                borderColor: '#db2777',
                borderWidth: 1,
                borderRadius: 8
            },
            {
                label: 'Recall',
                data: [
                    <?= $metrics['DT']['recall']; ?>, 
                    <?= $metrics['KNN']['recall']; ?>, 
                    <?= $metrics['SVM']['recall']; ?>
                ],
                backgroundColor: 'rgba(124, 58, 237, 0.8)', // Violet 600
                borderColor: '#7c3aed',
                borderWidth: 1,
                borderRadius: 8
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    color: '#475569', /* Slate 600 */
                    font: { family: 'Space Mono', size: 11, weight: 'bold' }
                }
            },
            tooltip: {
                backgroundColor: '#ffffff',
                titleColor: '#0f172a', /* Slate 900 */
                bodyColor: '#475569', /* Slate 600 */
                borderColor: 'rgba(0,0,0,0.08)',
                borderWidth: 1,
                padding: 12,
                bodyFont: { family: 'Plus Jakarta Sans' },
                titleFont: { family: 'Plus Jakarta Sans', weight: 'bold' },
                boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1)'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    color: '#64748b', /* Slate 500 */
                    font: { font: 'Space Mono', weight: 'bold' },
                    callback: function(value) { return value + '%'; }
                },
                grid: { color: 'rgba(0, 0, 0, 0.05)' } /* Garis grid yang sangat tipis untuk Light Mode */
            },
            x: {
                ticks: {
                    color: '#475569', /* Slate 600 */
                    font: { weight: 'bold', family: 'Plus Jakarta Sans' }
                },
                grid: { display: false }
            }
        }
    }
});
</script>

<style>
body {
    background-color: #f8fafc !important; /* Menyamakan dengan halaman lain (Slate 50) */
}
@keyframes bounceSlow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}
.animate-bounce-slow {
    animation: bounceSlow 3s ease-in-out infinite;
}
</style>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ once: true });
</script>

<?php include 'components/footer.php'; ?>