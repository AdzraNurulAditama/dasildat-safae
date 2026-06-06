<?php

include 'config/database.php';

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$error = "";

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($query) > 0){
        $user = mysqli_fetch_assoc($query);
        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if($user['role'] == 'admin'){
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }

            exit;
        }else{
            $error = "Kombinasi password yang Anda masukkan tidak sesuai.";
        }
    }else{
        $error = "Alamat email tersebut belum terdaftar di sistem kami.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Gateway |  MindTrack Gaming</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
</head>

<style>
/* CSS Reset Global Pengunci Background Sinkron Proyek SAFAE (Light Mode) */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    background-color: #f8fafc !important; /* Warna dasar slate-50 */
}

body {
    background: 
        radial-gradient(circle at top left, rgba(168,85,247,0.10), transparent 30%),
        radial-gradient(circle at bottom right, rgba(236,72,153,0.08), transparent 30%),
        linear-gradient(180deg, #f8fafc 0%, #f1f5f9 45%, #e2e8f0 100%) !important;
    overflow-x: hidden;
}

/* Kustomisasi Grid Partikel Background Gumpalan Cahaya Neon (Soft/Pastel) */
body::before {
    content: '';
    position: fixed;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(168,85,247,0.08), transparent 70%);
    top: -250px;
    right: -150px;
    z-index: 0;
    pointer-events: none;
}

body::after {
    content: '';
    position: fixed;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(236,72,153,0.08), transparent 70%);
    bottom: -220px;
    left: -120px;
    z-index: 0;
    pointer-events: none;
}

@keyframes shimmer {
    100% { transform: skewX(-12deg) translateX(100%); }
}

.group-hover\:animate-shimmer {
    animation: shimmer 1.5s ease-in-out infinite;
}

/* Kustomisasi State untuk Input Toggle Password */
.password-toggle-btn {
    background: transparent;
    border: none;
    color: #64748b;
    transition: color 0.3s ease;
}
.password-toggle-btn:hover {
    color: #ec4899;
}
</style>

<body>

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

<div id="tailwind-scope" class="text-slate-800 antialiased relative z-10">
    <div class="max-w-[1240px] mx-auto min-h-screen flex items-center justify-center px-6 py-12">
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <div class="lg:col-span-7 space-y-6" data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-purple-100 border border-purple-200 text-xs font-bold tracking-widest text-purple-700 uppercase font-mono shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-purple-500 shadow-[0_0_10px_#a855f7] animate-pulse"></span>
                    Secure Access Gateway
                </div>

                <h1 class="text-4xl md:text-6xl font-black tracking-tight leading-none text-slate-900 m-0 font-extrabold">
                    Welcome Back To <br>
                    <span class="bg-gradient-to-r from-purple-600 via-pink-500 to-amber-500 bg-clip-text text-transparent"> MindTrack Gaming Portal</span>
                </h1>

                <p class="text-slate-600 text-base md:text-lg max-w-xl leading-relaxed m-0">
                    Masuk menggunakan akun terverifikasi untuk kembali memantau klasterisasi data analitik, riwayat statistik, serta menjalankan pengujian model machine learning.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                    <div class="p-5 rounded-2xl border border-slate-200 bg-white shadow-sm flex items-start gap-4 hover:border-purple-300 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                        <span class="text-2xl p-3 rounded-xl bg-purple-100 text-purple-600 font-sans shadow-sm">🎮</span>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 mb-1">Instant Analytics</h4>
                            <p class="text-xs text-slate-500 leading-relaxed m-0">Kalkulasi manual instan dengan bobot indikator perilaku mutakhir.</p>
                        </div>
                    </div>
                    <div class="p-5 rounded-2xl border border-slate-200 bg-white shadow-sm flex items-start gap-4 hover:border-pink-300 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                        <span class="text-2xl p-3 rounded-xl bg-pink-100 text-pink-600 font-sans shadow-sm">📊</span>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 mb-1">Batch Pipeline</h4>
                            <p class="text-xs text-slate-500 leading-relaxed m-0">Unggah ribuan baris sampel CSV langsung ke dalam *neural processing engine*.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 w-full" data-aos="fade-left" data-aos-duration="1000">
              <div class="border border-white rounded-[36px] p-6 md:p-10 shadow-2xl shadow-slate-200/60 relative overflow-hidden flex flex-col min-h-[550px]" style="background-color: rgba(255, 255, 255, 0.7); backdrop-filter: blur(24px);">

                    <div class="mb-8">
                        <h2 class="text-2xl md:text-3xl font-black tracking-tight text-slate-900 mb-2 font-extrabold">Login Account</h2>
                        <p class="text-xs md:text-sm text-slate-500 m-0">Otentikasi kredensial Anda untuk membuka enkripsi dasbor.</p>
                    </div>

                    <?php if($error): ?>
                        <div class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 text-red-600 text-xs font-semibold flex items-center gap-2.5 animate-pulse">
                            <span>⚠️</span>
                            <span><?= $error; ?></span>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-5">
                        <div class="space-y-1.5 focus-container">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-slate-700 block">Alamat Email</label>
                            <input type="email" name="email" class="w-full bg-white border border-slate-300 rounded-xl px-4 py-3.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-pink-500 focus:ring-4 focus:ring-pink-500/10 transition-all duration-300 shadow-sm" placeholder="nama@email.com" required>
                        </div>

                        <div class="space-y-1.5 focus-container">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-slate-700 block">Kata Sandi</label>
                            <div class="relative w-full flex items-center">
                                <input type="password" name="password" id="password-field" class="w-full bg-white border border-slate-300 rounded-xl px-4 py-3.5 pr-12 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-pink-500 focus:ring-4 focus:ring-pink-500/10 transition-all duration-300 shadow-sm" placeholder="••••••••" required>
                                <button type="button" id="toggle-password" class="absolute right-4 password-toggle-btn z-30 cursor-pointer text-sm font-bold font-mono">SHOW</button>
                            </div>
                        </div>

                        <button type="submit" name="login" class="w-full relative group overflow-hidden h-14 rounded-xl bg-gradient-to-r from-purple-600 via-pink-500 to-amber-500 text-white font-black text-xs tracking-widest uppercase shadow-lg shadow-pink-500/20 transition-all duration-300 transform active:scale-[0.99] hover:translate-y-[-2px] border-none cursor-pointer mt-4">
                            <span class="absolute inset-0 w-full h-full bg-white/20 transform -skew-x-12 -translate-x-full group-hover:animate-shimmer"></span>
                            Sign In
                        </button>
                    </form>

                    <div class="text-center mt-auto pt-6 border-t border-slate-200 text-xs text-slate-500">
                        Belum terdaftar di jaringan? 
                        <a href="register.php" class="text-pink-600 font-bold hover:text-pink-700 text-decoration-none ml-1 transition-colors">Buat Akun Baru</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ once: true });

  // Sistem Interaktivitas Fokus Neon Glow (Disesuaikan untuk Light Mode)
  document.querySelectorAll('.focus-container input').forEach(input => {
      input.addEventListener('focus', () => {
          const label = input.closest('.focus-container').querySelector('label');
          if (label) label.classList.replace('text-slate-700', 'text-pink-600');
      });
      input.addEventListener('blur', () => {
          const label = input.closest('.focus-container').querySelector('label');
          if (label) label.classList.replace('text-pink-600', 'text-slate-700');
      });
  });

  // Advance Feature: Password Visibility Toggle Handler
  const passwordField = document.getElementById('password-field');
  const togglePasswordBtn = document.getElementById('toggle-password');

  if (togglePasswordBtn && passwordField) {
      togglePasswordBtn.addEventListener('click', () => {
          const isPassword = passwordField.type === 'password';
          passwordField.type = isPassword ? 'text' : 'password';
          togglePasswordBtn.textContent = isPassword ? 'HIDE' : 'SHOW';
      });
  }
</script>

</body>
</html>