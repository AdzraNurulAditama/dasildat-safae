<?php

include 'config/database.php';

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$error = "";
$success = "";

if(isset($_POST['register'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    /* CHECK EMAIL */
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check) > 0){
        $error = "Alamat email tersebut sudah terdaftar di dalam sistem kami.";
    }elseif($password != $confirm){
        $error = "Konfirmasi kata sandi tidak cocok dengan input pertama.";
    }else{
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hash')";

        if(mysqli_query($conn, $query)){
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $_SESSION['username'] = $username;

            header("Location: index.php");
            exit;
        }else{
            $error = "Gagal memproses pendaftaran. Terjadi kesalahan pada database.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | MindTrack Gaming</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
</head>

<style>
/* FIX NGAMBANG UTAMA: 
  Memaksa HTML dan Body mengunci tinggi minimum secara mutlak dan 
  memastikan linear-gradient mengalir tanpa patah di bawah container viewport.
*/
html {
    height: 100% !important;
}

body {
    min-height: 100% !important;
    display: flex !important;
    flex-direction: column !important;
    margin: 0;
    padding: 0;
    background: #f8fafc !important; /* Slate 50 background */
    background-image: 
        radial-gradient(circle at top left, rgba(147,51,234,0.04), transparent 35%),
        radial-gradient(circle at bottom right, rgba(219,39,119,0.04), transparent 35%),
        linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%) !important;
    background-attachment: fixed !important; /* Mengunci gradasi agar tidak putus saat scroll */
    overflow-x: hidden;
}

/* Penyelaras posisi bola cahaya aura cyber (Light Mode Subtle Glow) */
body::before {
    content: '';
    position: fixed;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(147,51,234,0.06), transparent 70%);
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
    background: radial-gradient(circle, rgba(219,39,119,0.06), transparent 70%);
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

.password-toggle-btn {
    background: transparent;
    border: none;
    color: #94a3b8; /* Slate 400 */
    transition: color 0.3s ease;
}
.password-toggle-btn:hover {
    color: #db2777; /* Pink 600 */
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

<div id="tailwind-scope" class="text-slate-900 antialiased relative z-10 flex-grow flex items-center justify-center w-full pt-[14rem] pb-24 font-[Plus Jakarta Sans]">
    <div class="max-w-[1240px] w-full mx-auto px-6">
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <div class="lg:col-span-7 space-y-6" data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-purple-100 border border-purple-200 text-xs font-bold tracking-widest text-purple-700 uppercase font-mono shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-purple-600 shadow-[0_0_8px_#9333ea] animate-pulse"></span>
                    MACHINE LEARNING PLATFORM
                </div>

                <h1 class="text-4xl md:text-6xl font-black tracking-tight leading-none text-slate-900 m-0 font-extrabold">
                    Join The Future of <br>
                    <span class="bg-gradient-to-r from-purple-600 via-pink-600 to-amber-500 bg-clip-text text-transparent">Gaming Analytics</span>
                </h1>

                <p class="text-slate-600 text-base md:text-lg max-w-xl leading-relaxed m-0">
                    Bangun akun untuk mengakses sistem prediksi Gaming Addiction Risk Level berbasis Artificial Intelligence dan visual analytics modern.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                    <div class="p-5 rounded-2xl border border-slate-200 bg-white/80 flex items-start gap-4 hover:border-purple-300 transition-all duration-300 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                        <span class="text-2xl p-3 rounded-xl bg-purple-50 text-purple-600 font-sans border border-purple-100">🎮</span>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 mb-1">Gaming Analytics</h4>
                            <p class="text-xs text-slate-500 leading-relaxed m-0">Analisis perilaku gaming mendalam menggunakan kecerdasan komputasi terpadu.</p>
                        </div>
                    </div>
                    <div class="p-5 rounded-2xl border border-slate-200 bg-white/80 flex items-start gap-4 hover:border-pink-300 transition-all duration-300 shadow-sm" data-aos="fade-up" data-aos-delay="300">
                        <span class="text-2xl p-3 rounded-xl bg-pink-50 text-pink-600 font-sans border border-pink-100">🧠</span>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 mb-1">Mental Health Detection</h4>
                            <p class="text-xs text-slate-500 leading-relaxed m-0">Prediksi risiko gangguan pola istirahat serta isolasi lingkungan secara otomatis.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 w-full" data-aos="fade-left" data-aos-duration="1000">
                <div class="border border-slate-200 rounded-[36px] p-6 md:p-10 shadow-2xl shadow-slate-200/50 relative overflow-hidden" style="background-color: rgba(255, 255, 255, 0.85); backdrop-filter: blur(24px);">
                    
                    <div class="mb-6">
                        <h2 class="text-2xl md:text-3xl font-black tracking-tight text-slate-900 mb-2 font-extrabold">Create Account</h2>
                        <p class="text-xs md:text-sm text-slate-500 m-0">Daftarkan profil baru untuk membuka seluruh fungsionalitas AI.</p>
                    </div>

                    <?php if($error): ?>
                        <div class="mb-4 p-4 rounded-xl border border-red-200 bg-red-50 text-red-600 text-xs font-semibold flex items-center gap-2.5 shadow-sm">
                            <span>⚠️</span>
                            <span><?= $error; ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if($success): ?>
                        <div class="mb-4 p-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-600 text-xs font-semibold flex items-center gap-2.5 shadow-sm">
                            <span>✅</span>
                            <span><?= $success; ?></span>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-4">
                        <div class="space-y-1 focus-container">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-purple-700 block transition-colors">Username</label>
                            <input type="text" name="username" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-pink-500 focus:bg-white transition-all duration-300" placeholder="Contoh: adzranurul" required>
                        </div>

                        <div class="space-y-1 focus-container">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-purple-700 block transition-colors">Alamat Email</label>
                            <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-pink-500 focus:bg-white transition-all duration-300" placeholder="nama@email.com" required>
                        </div>

                        <div class="space-y-1 focus-container">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-purple-700 block transition-colors">Kata Sandi</label>
                            <div class="relative w-full flex items-center">
                                <input type="password" name="password" id="password-field" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 pr-12 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-pink-500 focus:bg-white transition-all duration-300" placeholder="Buat sandi rumit" required>
                                <button type="button" id="toggle-password" class="absolute right-4 password-toggle-btn z-30 cursor-pointer text-[10px] font-bold font-mono">SHOW</button>
                            </div>
                            <div class="pt-1.5 flex gap-1 items-center">
                                <div class="h-1 flex-grow rounded-full bg-slate-200 overflow-hidden"><div id="strength-bar" class="h-full w-0 transition-all duration-500 rounded-full"></div></div>
                                <span id="strength-text" class="text-[9px] font-bold font-mono tracking-wider text-slate-400 uppercase ml-1">None</span>
                            </div>
                        </div>

                        <div class="space-y-1 focus-container">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-purple-700 block transition-colors">Konfirmasi Kata Sandi</label>
                            <div class="relative w-full flex items-center">
                                <input type="password" name="confirm_password" id="confirm-field" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 pr-12 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-pink-500 focus:bg-white transition-all duration-300" placeholder="Ulangi sandi di atas" required>
                                <button type="button" id="toggle-confirm" class="absolute right-4 password-toggle-btn z-30 cursor-pointer text-[10px] font-bold font-mono">SHOW</button>
                            </div>
                        </div>

                        <button type="submit" name="register" class="w-full relative group overflow-hidden h-14 rounded-xl bg-gradient-to-r from-purple-600 via-pink-600 to-amber-500 text-white font-black text-xs tracking-widest uppercase shadow-md hover:shadow-lg shadow-pink-500/20 transition-all duration-300 transform active:scale-[0.99] hover:-translate-y-1 border-none cursor-pointer mt-4">
                            <span class="absolute inset-0 w-full h-full bg-white/20 transform -skew-x-12 -translate-x-full group-hover:animate-shimmer"></span>
                            Create Account
                        </button>
                    </form>

                    <div class="text-center mt-6 pt-4 border-t border-slate-100 text-xs text-slate-500">
                        Sudah terintegrasi di sistem? 
                        <a href="login.php" class="text-pink-600 font-bold hover:text-pink-700 text-decoration-none ml-1 transition-colors">Sign In</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ once: true });

  // Neon Focus Input Ring Animation Updates (Adapted for Light Mode)
  document.querySelectorAll('.focus-container input').forEach(input => {
      input.addEventListener('focus', () => {
          const label = input.closest('.focus-container').querySelector('label');
          if (label) {
              label.classList.remove('text-purple-700');
              label.classList.add('text-pink-600');
          }
          input.classList.add('ring-4', 'ring-pink-500/10');
      });
      input.addEventListener('blur', () => {
          const label = input.closest('.focus-container').querySelector('label');
          if (label) {
              label.classList.remove('text-pink-600');
              label.classList.add('text-purple-700');
          }
          input.classList.remove('ring-4', 'ring-pink-500/10');
      });
  });

  // Dual-Field Password Visibility Toggle Utilities
  const makeToggleable = (fieldId, btnId) => {
      const field = document.getElementById(fieldId);
      const btn = document.getElementById(btnId);
      if(field && btn) {
          btn.addEventListener('click', () => {
              const isPass = field.type === 'password';
              field.type = isPass ? 'text' : 'password';
              btn.textContent = isPass ? 'HIDE' : 'SHOW';
          });
      }
  };
  makeToggleable('password-field', 'toggle-password');
  makeToggleable('confirm-field', 'toggle-confirm');

  // Real-time Cyber Password Strength Meter Logic
  const passField = document.getElementById('password-field');
  const strengthBar = document.getElementById('strength-bar');
  const strengthText = document.getElementById('strength-text');

  if(passField && strengthBar && strengthText) {
      passField.addEventListener('input', () => {
          const val = passField.value;
          let score = 0;
          
          if(val.length >= 6) score++;
          if(val.length >= 10) score++;
          if(/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
          if(/[^A-Za-z0-9]/.test(val)) score++;

          if(val.length === 0) {
              strengthBar.style.width = '0%';
              strengthText.textContent = 'None';
              strengthText.className = 'text-[9px] font-bold font-mono tracking-wider text-slate-400 uppercase ml-1';
          } else if(score <= 1) {
              strengthBar.style.width = '33%';
              strengthBar.style.backgroundColor = '#ef4444'; // Red 500
              strengthText.textContent = 'Weak';
              strengthText.className = 'text-[9px] font-bold font-mono tracking-wider text-red-500 uppercase ml-1';
          } else if(score <= 3) {
              strengthBar.style.width = '66%';
              strengthBar.style.backgroundColor = '#eab308'; // Yellow 500
              strengthText.textContent = 'Medium';
              strengthText.className = 'text-[9px] font-bold font-mono tracking-wider text-amber-500 uppercase ml-1';
          } else {
              strengthBar.style.width = '100%';
              strengthBar.style.backgroundColor = '#10b981'; // Emerald 500
              strengthText.textContent = 'Strong';
              strengthText.className = 'text-[9px] font-bold font-mono tracking-wider text-emerald-500 uppercase ml-1';
          }
      });
  }
</script>

</body>
</html>