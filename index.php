<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
include 'config/database.php';

/* =========================================================================
   DYNAMIC DATASET ANALYTICS PARSER (Live Reading langsung dari file CSV)
   ========================================================================= */
$csv_file_path = 'Gaming and Mental Health.csv';

$total_records = 0;
$total_features = 0;
$high_severe_count = 0;
$total_spending = 0;
$total_gaming_hours = 0;
$genre_distribution = [];

if (file_exists($csv_file_path)) {
    if (($handle = fopen($csv_file_path, "r")) !== FALSE) {
        $headers = fgetcsv($handle, 1000, ",");
        if ($headers !== FALSE) {
            $total_features = count($headers); 
        }
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $total_records++;
            $total_gaming_hours += (float)($data[3] ?? 0);
            
            $genre = trim($data[4] ?? 'Unknown');
            if(!empty($genre)) {
                $genre_distribution[$genre] = ($genre_distribution[$genre] ?? 0) + 1;
            }
            
            $total_spending += (float)($data[24] ?? 0);
            
            $risk_label = strtolower(trim($data[26] ?? ''));
            if ($risk_label === 'high' || $risk_label === 'severe') {
                $high_severe_count++;
            }
        }
        fclose($handle);
    }
}

if($total_records === 0) {
    $total_records = 1000;
    $total_features = 27;
    $risk_percentage = 34.5;
    $avg_spending = 105.2;
    $avg_gaming_hours = 6.4;
    $top_genres = ['FPS', 'MOBA', 'RPG'];
} else {
    $risk_percentage = round(($high_severe_count / $total_records) * 100, 1);
    $avg_spending = round(($total_spending / $total_records), 2);
    $avg_gaming_hours = round(($total_gaming_hours / $total_records), 1);
    arsort($genre_distribution);
    $top_genres = array_slice(array_keys($genre_distribution), 0, 3);
}

include 'components/header.php'; 
?>

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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

<div id="tailwind-scope" class="text-slate-800 antialiased selection:bg-purple-200 selection:text-purple-900" style="font-family: 'Plus Jakarta Sans', sans-serif; display: block; clear: both;">
    
    <section class="relative min-h-[95vh] flex items-center pt-[14rem] pb-24 overflow-hidden" style="display: flex; align-items: center;">
        <div class="max-w-7xl mx-auto px-6 w-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
                
                <div class="lg:col-span-7 space-y-6" data-aos="fade-right" data-aos-duration="1000">
                    <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-purple-100 border border-purple-200 text-xs font-bold tracking-widest text-purple-700 uppercase font-mono shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-purple-500 shadow-[0_0_8px_#a855f7] animate-pulse"></span>
                        ⚡ Neural Network Engine v2.4
                    </div>

                    <h1 class="text-4xl md:text-6xl font-black tracking-tight leading-[1.2] text-slate-900 m-0 font-extrabold" 
                        style="font-size: clamp(2.5rem, 5vw, 4.2rem) !important; font-weight: 900 !important; line-height: 1.2 !important; color: #0f172a !important; letter-spacing: -0.04em !important; margin: 0 !important;">
                        Mendekode Ketimpangan & <br>
                        <span style="background: linear-gradient(135deg, #0284c7 0%, #9333ea 50%, #db2777 100%) !important; -webkit-background-clip: text !important; -webkit-text-fill-color: transparent !important; display: inline-block !important;">
                            Risiko Kecanduan Game
                        </span>
                    </h1>

                    <p class="text-slate-600 text-base md:text-lg max-w-xl leading-relaxed m-0">
                        Platform analitik pintar untuk mendeteksi kecanduan game dan memantau kesehatan mental kamu. Lewat kalkulasi algoritma modern, semua pola kebiasaan dan kualitas tidur kamu bisa dianalisis secara real-time!
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="p-5 rounded-2xl border border-slate-200 bg-white/60 space-y-2 hover:border-purple-300 hover:shadow-md transition-all duration-300" style="backdrop-filter: blur(8px);">
                            <div class="text-xl">📊 <span class="text-xs font-mono text-purple-600 font-bold ml-1">[ CROSS_MATRIX ]</span></div>
                            <h4 class="text-sm font-bold text-slate-900 m-0">Analisis Multivariat</h4>
                            <p class="text-xs text-slate-500 leading-relaxed m-0">Memeriksa hubungan antara durasi main game, jam tidur, sampai pengaruhnya ke performa kerja atau kuliah kamu.</p>
                        </div>
                        <div class="p-5 rounded-2xl border border-slate-200 bg-white/60 space-y-2 hover:border-pink-300 hover:shadow-md transition-all duration-300" style="backdrop-filter: blur(8px);">
                            <div class="text-xl">🧬 <span class="text-xs font-mono text-pink-600 font-bold ml-1">[ BIO_VECTORS ]</span></div>
                            <h4 class="text-sm font-bold text-slate-900 m-0">Arsitektur Perilaku</h4>
                            <p class="text-xs text-slate-500 leading-relaxed m-0">Memproses <?= $total_features; ?> variabel penting, termasuk gejala kecemasan (*withdrawal*) dan hilangnya minat pada hobi lain.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-2 flex-wrap">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="predict_manual.php" class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-gradient-to-r from-sky-400 via-purple-500 to-pink-500 text-white font-black text-sm tracking-wider uppercase shadow-lg shadow-purple-500/30 hover:translate-y-[-4px] active:scale-[0.98] transition-all duration-300 text-decoration-none group" style="border: none !important; color: #ffffff !important;">
                                🚀 Coba Prediksi
                            </a>
                        <?php else: ?>
                            <a href="register.php" class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-gradient-to-r from-sky-400 via-purple-500 to-pink-500 text-white font-black text-sm tracking-wider uppercase shadow-lg shadow-purple-500/30 hover:translate-y-[-4px] active:scale-[0.98] transition-all duration-300 text-decoration-none group" style="border: none !important; color: #ffffff !important;">
                                🔐 Buat Akun Baru
                            </a>
                        <?php endif; ?>

                        <a href="predict_csv.php" class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-white border border-slate-200 hover:border-purple-300 text-purple-700 font-bold text-sm tracking-wider uppercase hover:bg-purple-50 hover:translate-y-[-4px] active:scale-[0.98] transition-all duration-300 text-decoration-none" style="color: #7e22ce !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                            📁 Upload File Excel atau CSV
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 w-full" data-aos="fade-left" data-aos-duration="1000">
                    <div class="border border-slate-200 rounded-[32px] overflow-hidden shadow-2xl shadow-slate-200/50 transition-all duration-500 hover:border-purple-300 group/panel" style="background-color: rgba(255, 255, 255, 0.85); backdrop-filter: blur(24px);">
                        
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between" style="background-color: rgba(248, 250, 252, 0.8);">
                            <div class="flex gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#ff5f56]"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-[#ffbd2e]"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-[#27c93f]"></span>
                            </div>
                            <span class="font-mono text-[10px] tracking-widest text-slate-400 font-bold">⚙️ SYSTEM_MATRICES_MONITOR.LOG</span>
                        </div>

                        <div class="p-6 md:p-8 space-y-6">
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="relative h-28 rounded-2xl overflow-hidden group/img shadow-sm border border-slate-200">
                                    <img src="assets/images/Nintendo.jpg" class="w-full h-full object-cover group-hover/img:scale-105 transition-transform duration-500 opacity-80" alt="Gaming Analytics">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                                    <div class="absolute bottom-3 left-4">
                                        <small class="text-[9px] uppercase tracking-wider text-sky-300 font-mono block">💠 Jalur Aktif</small>
                                        <p class="text-xs font-bold text-white m-0">Somatic Stream</p>
                                    </div>
                                </div>
                                <div class="relative h-28 rounded-2xl overflow-hidden group/img shadow-sm border border-slate-200">
                                    <img src="assets/images/mental-health.webp" class="w-full h-full object-cover group-hover/img:scale-105 transition-transform duration-500 opacity-80" alt="Mental Health Analytics">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                                    <div class="absolute bottom-3 left-4">
                                        <small class="text-[9px] uppercase tracking-wider text-pink-300 font-mono block">🗲 Log Valid</small>
                                        <p class="text-xs font-bold text-white m-0">Psychometrics</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <div class="flex justify-between text-xs font-mono tracking-wide text-slate-500">
                                        <span>💎 DECISION TREE CLASSIFIER ACCURACY</span>
                                        <span class="text-sky-600 font-bold">98.0%</span>
                                    </div>
                                    <div class="h-2 rounded-full bg-slate-100 overflow-hidden"><div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-sky-400 shadow-[0_0_12px_rgba(56,189,248,0.2)]" style="width: 98.2%;"></div></div>
                                </div>
                                <div class="space-y-1.5">
                                    <div class="flex justify-between text-xs font-mono tracking-wide text-slate-500">
                                        <span>🧬 SUPPORT VECTOR MACHINE (SVM) CLASSIFIER</span>
                                        <span class="text-indigo-600 font-bold">98.0%</span>
                                    </div>
                                    <div class="h-2 rounded-full bg-slate-100 overflow-hidden"><div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-indigo-400 shadow-[0_0_12px_rgba(129,140,248,0.2)]" style="width: 98%;"></div></div>
                                </div>
                                <div class="space-y-1.5">
                                    <div class="flex justify-between text-xs font-mono tracking-wide text-slate-500">
                                        <span>⚠️ K-Nearest Neighbors</span>
                                        <span class="text-indigo-600 font-bold">86.0%</span>
                                    </div>
                                    <div class="h-2 rounded-full bg-slate-100 overflow-hidden"><div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-indigo-400 shadow-[0_0_12px_rgba(129,140,248,0.2)]" style="width: 86%;"></div></div>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl border border-purple-100 bg-purple-50 flex items-center justify-between gap-4 shadow-sm">
                                <div class="space-y-1">
                                    <span class="font-mono text-[9px] font-bold text-purple-600 tracking-widest uppercase block">👑 Genre Paling Banyak Di Dataset</span>
                                    <h4 class="text-xs font-bold text-slate-900 mb-1"><?= implode(', ', $top_genres); ?></h4>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="text-[10px] font-mono block text-slate-500">⏳ Rata-rata Main</span>
                                    <span class="text-xs font-bold text-amber-600 font-mono"><?= $avg_gaming_hours; ?> jam/hari</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="models-overview" class="py-24 border-t border-slate-200 relative block bg-white/50">
        <div class="max-w-7xl mx-auto px-6">
            
            <div class="text-center mb-16 space-y-3" data-aos="fade-up">
                <span class="inline-block px-3 py-1 rounded-full bg-purple-50 border border-purple-100 text-[10px] font-bold tracking-widest text-purple-600 font-mono uppercase">🔮 Inteligensia Database</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 m-0 tracking-tight">Statistik Riwayat Real-Time</h2>
                <p class="text-slate-600 text-sm md:text-base max-w-xl mx-auto leading-relaxed">Hasil ekstraksi otomatis dari seluruh data mentah berkas dataset yang berhasil di-load oleh sistem.</p>
            </div>

            <div class="bg-white border border-slate-200 rounded-[2rem] p-10 md:p-14 mb-16 shadow-lg shadow-slate-200/50" data-aos="fade-up" data-aos-delay="50">
                <div class="mb-10">
                    <span class="inline-block px-4 py-2 rounded-full bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold uppercase tracking-widest mb-4">
                        📚 Tentang Dataset
                    </span>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-6">Penjelasan Dataset Utama</h2>
                    <p class="text-slate-600 leading-relaxed text-lg max-w-5xl m-0">
                        Dataset <b class="text-slate-900">Gaming and Mental Health</b> ini bersumber dari hasil pengumpulan data responden dengan total 1.000 rekaman (records). Dataset ini digunakan untuk menggambarkan hubungan antara kebiasaan bermain game dan kondisi kesehatan mental. Berbagai variabel yang berkaitan dengan aktivitas bermain game, pola hidup, serta kondisi psikologis dianalisis secara bersamaan sehingga dapat memberikan gambaran yang lebih menyeluruh mengenai tingkat risiko kecanduan bermain game. Untuk memudahkan analisis, variabel-variabel tersebut dikelompokkan ke dalam beberapa kategori utama, yaitu:
                
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-indigo-300 transition-colors">
                    <div class="text-3xl mb-3">🎮</div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Klaster Aktivitas Gaming</h3>
                    <p class="text-sm text-slate-600 leading-relaxed m-0">
                        Berisi data yang menggambarkan kebiasaan bermain game responden, seperti durasi bermain per hari, genre game yang dimainkan, platform yang digunakan, lama pengalaman bermain, serta rata-rata pengeluaran untuk game setiap bulan.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-sky-300 transition-colors">
                    <div class="text-3xl mb-3">🩺</div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Klaster Kondisi Fisik</h3>
                    <p class="text-sm text-slate-600 leading-relaxed m-0">
                        Mencakup berbagai indikator kesehatan fisik yang dapat dipengaruhi oleh aktivitas bermain game, seperti kualitas tidur, gangguan tidur, kelelahan mata, nyeri pada leher atau punggung, serta perubahan berat badan.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-pink-300 transition-colors">
                    <div class="text-3xl mb-3">🧠</div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Klaster Psikologis</h3>
                    <p class="text-sm text-slate-600 leading-relaxed m-0">
                        Berisi variabel yang berkaitan dengan kondisi mental dan perilaku responden, seperti suasana hati, tingkat stres, kecenderungan bermain secara berlebihan, serta berkurangnya minat terhadap aktivitas lain di luar game.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-amber-300 transition-colors">
                    <div class="text-3xl mb-3">👥</div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Klaster Sosial dan Produktivitas</h3>
                    <p class="text-sm text-slate-600 leading-relaxed m-0">
                        Menggambarkan dampak aktivitas bermain game terhadap kehidupan sehari-hari, termasuk interaksi sosial, produktivitas, performa akademik atau pekerjaan, serta keseimbangan waktu antara bermain dan aktivitas lainnya.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-300 transition-colors lg:col-span-2">
                    <div class="text-3xl mb-3">🎯</div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Klaster Target Prediksi</h3>
                    <p class="text-sm text-slate-600 leading-relaxed m-0">
                        Klaster ini berisi variabel target <b>gaming_addiction_risk_level</b> yang terdiri dari kategori Low, Moderate, High, dan Severe. Variabel ini digunakan sebagai label yang akan diprediksi oleh model machine learning berdasarkan pola dari seluruh fitur yang tersedia pada dataset.
                    </p>
                </div>

            </div>
                        </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-24">
                
                <div class="group/stat border border-slate-200 hover:border-purple-300 rounded-3xl p-6 md:p-8 transition-all duration-300 shadow-md hover:shadow-xl flex flex-col justify-between bg-white" data-aos="fade-up" data-aos-delay="100">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-center text-xs group-hover/stat:border-purple-200 transition-colors shadow-sm font-mono text-purple-600">📜</div>
                        <div class="space-y-1">
                            <h1 class="text-4xl font-black bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent m-0 leading-none font-extrabold font-mono"><?= number_format($total_records); ?></h1>
                            <h3 class="text-sm font-bold text-slate-900 m-0">Total Baris Data</h3>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed m-0">Jumlah data gamer yang tercatat secara valid di dalam file dataset CSV ini.</p>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-slate-100 mt-6 font-mono text-[10px]">
                        <span class="text-slate-400">Format Berkas</span>
                        <span class="text-purple-600 font-bold">Standard .CSV</span>
                    </div>
                </div>

                <div class="group/stat border border-slate-200 hover:border-purple-300 rounded-3xl p-6 md:p-8 transition-all duration-300 shadow-md hover:shadow-xl flex flex-col justify-between bg-white" data-aos="fade-up" data-aos-delay="150">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-center text-xs group-hover/stat:border-purple-200 transition-colors shadow-sm font-mono text-purple-600">⛓️</div>
                        <div class="space-y-1">
                            <h1 class="text-4xl font-black bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent m-0 leading-none font-extrabold font-mono"><?= $total_features; ?></h1>
                            <h3 class="text-sm font-bold text-slate-900 m-0">Fitur Terlatih</h3>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed m-0">Jumlah total kolom indikator kesehatan dan kebiasaan yang dibaca untuk melakukan pelatihan.</p>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-slate-100 mt-6 font-mono text-[10px]">
                        <span class="text-slate-400">Status Fitur</span>
                        <span class="text-emerald-600 font-bold">Fully Optimized</span>
                    </div>
                </div>

                <div class="group/stat border border-slate-200 hover:border-purple-300 rounded-3xl p-6 md:p-8 transition-all duration-300 shadow-md hover:shadow-xl flex flex-col justify-between bg-white" data-aos="fade-up" data-aos-delay="200">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-center text-xs group-hover/stat:border-purple-200 transition-colors shadow-sm font-mono text-purple-600">🎯</div>
                        <div class="space-y-1">
                            <h1 class="text-4xl font-black bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent m-0 leading-none font-extrabold font-mono">98%</h1>
                            <h3 class="text-sm font-bold text-slate-900 m-0">Akurasi Tertinggi</h3>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed m-0">Skor akurasi tertinggi yang berhasil dicapai oleh model klasifikasi sistem.</p>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-slate-100 mt-6 font-mono text-[10px]">
                        <span class="text-slate-400">Bobot Akurasi</span>
                        <span class="text-sky-600 font-bold">Excellent</span>
                    </div>
                </div>

                <div class="group/stat border border-slate-200 hover:border-purple-300 rounded-3xl p-6 md:p-8 transition-all duration-300 shadow-md hover:shadow-xl flex flex-col justify-between bg-white" data-aos="fade-up" data-aos-delay="250">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-center text-xs group-hover/stat:border-purple-200 transition-colors shadow-sm font-mono text-purple-600">💵</div>
                        <div class="space-y-1">
                            <h1 class="text-4xl font-black bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent m-0 leading-none font-extrabold font-mono">$<?= number_format($avg_spending, 2); ?></h1>
                            <h3 class="text-sm font-bold text-slate-900 m-0">Rata-rata Top-Up</h3>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed m-0">Rata-rata pengeluaran bulanan para responden game buat beli item.</p>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-slate-100 mt-6 font-mono text-[10px]">
                        <span class="text-slate-400">Mata Uang</span>
                        <span class="text-amber-600 font-bold">USD (United States)</span>
                    </div>
                </div>

            </div>

            <div class="text-center mb-16" data-aos="fade-up">
                <span class="inline-block px-5 py-2 rounded-full bg-pink-100 border border-pink-200 text-pink-700 text-xs font-bold uppercase tracking-widest mb-4">
                    ⚙️ Struktur Atribut Dataset
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-6">Penjelasan Tiap Fitur (<?= $total_features; ?> Kolom)</h2>
                <p class="text-slate-600 max-w-2xl mx-auto leading-relaxed text-lg">
                    Penjelasan lengkap dari berbagai variabel yang dikelompokkan ke dalam beberapa kategori untuk membantu model machine learning mengenali pola dan memprediksi tingkat risiko kecanduan game.                </p>
            </div>

            <?php
            $features = [                ["icon" => "🎂", "name" => "age", "desc" => "Usia responden (dalam satuan tahun). Digunakan untuk melihat rentang demografi mana yang paling rentan terhadap kecanduan."],
                ["icon" => "🚻", "name" => "gender", "desc" => "Jenis kelamin responden (Male/Female). Membantu memetakan apakah ada perbedaan pola adiksi atau preferensi genre."],
                ["icon" => "⏳", "name" => "daily_gaming_hours", "desc" => "Kuantitas waktu bermain per hari. Ini adalah fitur utama klaster gaming yang paling memiliki korelasi kuat dengan tingkat kecanduan."],
                ["icon" => "🎮", "name" => "game_genre", "desc" => "Kategori game yang paling sering dimainkan (FPS, MOBA, RPG, dll) untuk melihat jenis stimulus psikologis yang diberikan."],
                ["icon" => "🔥", "name" => "primary_game", "desc" => "Judul spesifik dari game utama yang mendominasi waktu bermain responden (contoh: Dota 2, CS:GO, Apex Legends)."],
                ["icon" => "💻", "name" => "gaming_platform", "desc" => "Media atau perangkat keras utama yang digunakan untuk bermain (PC, Konsol, atau Mobile)."],
                ["icon" => "😴", "name" => "sleep_hours", "desc" => "Kuantitas waktu tidur harian responden (dalam jam). Memiliki korelasi negatif yang kuat dengan tingginya jam bermain."],
                ["icon" => "🛌", "name" => "sleep_quality", "desc" => "Metrik tingkat kualitas tidur secara subjektif dari responden (contoh: Very Poor, Poor, Fair, Good)."],
                ["icon" => "⚠️", "name" => "sleep_disruption_frequency", "desc" => "Seberapa sering jam tidur responden secara aktif terganggu akibat aktivitas gaming (misal: begadang untuk push rank)."],
                ["icon" => "📚", "name" => "academic_work_performance", "desc" => "Penilaian subjektif terhadap performa dan fungsi sosial responden di sekolah atau tempat kerja."],
                ["icon" => "🎓", "name" => "grades_gpa", "desc" => "Skor IPK atau nilai rata-rata akademik (skala 4.0) khusus untuk metrik sosio-ekonomi responden berstatus pelajar."],
                ["icon" => "📈", "name" => "work_productivity_score", "desc" => "Metrik numerik keras berupa skor penilaian produktivitas khusus untuk koresponden yang berstatus pekerja/profesional."],
                ["icon" => "😊", "name" => "mood_state", "desc" => "Kondisi emosi atau mood yang dominan dialami akhir-akhir ini (Anxious, Irritable, Withdrawn, Angry, dll)."],
                ["icon" => "🎭", "name" => "mood_swing_frequency", "desc" => "Seberapa sering (frekuensi) responden mengalami fluktuasi atau perubahan suasana hati secara mendadak."],
                ["icon" => "🧠", "name" => "withdrawal_symptoms", "desc" => "Boolean (True/False): Apakah responden merasakan gejala putus zat/psikologis (marah, cemas, gelisah) saat tidak bermain game."],
                ["icon" => "🚫", "name" => "loss_of_other_interests", "desc" => "Boolean (True/False): Penanda awal adiksi psikologis di mana hal-hal atau hobi di dunia nyata tidak lagi menarik baginya."],
                ["icon" => "🔄", "name" => "continued_despite_problems", "desc" => "Boolean (True/False): Indikator psikologis tertinggi—bermain impulsif meski tahu kebiasaan tersebut memicu/merusak masalah hidupnya."],
                ["icon" => "👀", "name" => "eye_strain", "desc" => "Boolean (True/False): Keluhan fisiologis berupa mata lelah atau tegang akibat terlalu lama menatap layar monitor/ponsel."],
                ["icon" => "🪑", "name" => "back_neck_pain", "desc" => "Boolean (True/False): Keluhan fisiologis berupa nyeri punggung atau leher karena postur statis selama sesi bermain yang panjang."],
                ["icon" => "⚖️", "name" => "weight_change_kg", "desc" => "Besaran perubahan berat badan (naik/turun dalam Kg) yang diakibatkan oleh gaya hidup kurang gerak (sedentary lifestyle)."],
                ["icon" => "🏃", "name" => "exercise_hours_weekly", "desc" => "Jumlah jam yang disisihkan responden untuk melakukan aktivitas fisik atau olahraga penyeimbang dalam satu minggu."],
                ["icon" => "🌑", "name" => "social_isolation_score", "desc" => "Rasio/skor tingkat isolasi kuantitatif yang menunjukkan seberapa jauh responden menarik diri dari pergaulan luar."],
                ["icon" => "👥", "name" => "face_to_face_social_hours_weekly", "desc" => "Total durasi waktu yang secara aktual dihabiskan koresponden untuk berinteraksi sosial secara langsung (tatap muka) per minggu."],
                ["icon" => "💸", "name" => "monthly_game_spending_usd", "desc" => "Indikator sunk cost fallacy. Total pengeluaran uang bulanan (dalam USD) untuk keperluan game (top-up, battle pass, item)."],
                ["icon" => "📅", "name" => "years_gaming", "desc" => "Lama durasi atau rekam jejak lamanya responden terpapar kebiasaan bermain game secara kronologis (dalam tahun)."],
                ["icon" => "🚨", "name" => "gaming_addiction_risk_level", "desc" => "Atribut Target Prediksi (Low, Moderate, High, Severe). Label final tempat seluruh analisis algoritma klasifikasi bermuara."]
            ];
            ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5" data-aos="fade-up" data-aos-delay="100">
                <?php foreach($features as $feature): ?>
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 md:p-6 flex items-start gap-4 hover:border-purple-400 hover:shadow-md transition-all duration-300">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-xl md:text-2xl flex-shrink-0 shadow-sm">
                            <?= $feature['icon']; ?>
                        </div>
                        <div>
                            <h3 class="text-base md:text-lg font-bold text-slate-900 mb-1 font-mono flex items-center gap-2 m-0">
                                <?= $feature['name']; ?>
                            </h3>
                            <p class="text-slate-500 leading-relaxed text-xs md:text-sm m-0 mt-1">
                                <?= $feature['desc']; ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

</div>

<style>
body {
    background: #f8fafc !important; /* Tailwind slate-50 */
}

#tailwind-scope::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image:
        linear-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0, 0, 0, 0.03) 1px, transparent 1px);
    background-size: 60px 60px;
    mask-image: radial-gradient(circle at center, black 40%, transparent 90%);
    pointer-events: none;
    z-index: 0;
}

@keyframes shimmer {
    100% { transform: skewX(-12deg) translateX(100%); }
}
.group-hover\:animate-shimmer {
    animation: shimmer 1.5s ease-in-out infinite;
}
@keyframes bounceSlow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-6px); }
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