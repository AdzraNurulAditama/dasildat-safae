<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Fitur prediksi hanya dapat diakses oleh pengguna yang telah login.");
    exit;
}

if(isset($_POST['next_1'])) {
    
    // Simpan ke session
    $_SESSION['prediction_data']['age'] = $_POST['age'];
    $_SESSION['prediction_data']['gender'] = $_POST['gender'];
    $_SESSION['prediction_data']['daily_gaming_hours'] = $_POST['daily_gaming_hours'];
    $_SESSION['prediction_data']['years_gaming'] = $_POST['years_gaming'];
    $_SESSION['prediction_data']['game_genre'] = $_POST['game_genre'];
    $_SESSION['prediction_data']['primary_game'] = $_POST['primary_game'];
    $_SESSION['prediction_data']['gaming_platform'] = $_POST['gaming_platform'];

    // Cek apakah ada spasi/teks bocor yang merusak fungsi Header
    if (headers_sent($file, $line)) {
        die("<h3>ERROR REDIRECT:</h3>Redirect gagal karena ada output tidak sengaja di file <b>$file</b> pada baris <b>$line</b>. <br><br><b>Solusi:</b> Buka file tersebut dan pastikan tidak ada spasi atau baris kosong di luar tag &lt;?php ... ?&gt;");
    } else {
        // Jika aman, lakukan redirect
        header("Location: predict_manual_2.php");
        exit;
    }
}

include 'components/header.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    /* 1. Serene Gradient Background */
    body { 
        background: linear-gradient(135deg, #e0f2fe 0%, #f0fdf4 50%, #fdf4ff 100%);
        color: #334155; 
        background-attachment: fixed;
    }
    
    /* 2. Main Wrapper Centering */
    .zen-wrapper { 
        min-height: calc(100vh - 96px); 
        margin-top: 96px;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
    }

    /* 3. Glassmorphism Card Container */
    .glass-card {
        width: 100%;
        max-width: 1150px;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 32px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        display: grid;
        grid-template-columns: 1.1fr 1.3fr;
        overflow: hidden;
    }

    /* 4. Left Panel (Typography, Image & Vibe) */
    .glass-left {
        padding: 50px;
        background: linear-gradient(180deg, rgba(255,255,255,0.5) 0%, rgba(255,255,255,0.1) 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        border-right: 1px solid rgba(255,255,255,0.5);
    }

    .zen-title {
        font-family: 'Instrument Serif', Georgia, serif;
        font-size: 54px;
        line-height: 1.1;
        color: #0f172a;
        margin-bottom: 20px;
    }

    .zen-title em {
        color: #0ea5e9;
        font-style: italic;
    }

    .zen-subtitle {
        color: #64748b;
        font-size: 1.05rem;
        line-height: 1.6;
        font-weight: 400;
        margin-bottom: 30px;
    }

    /* Styling Gambar Ilustrasi Gaming */
    .gaming-illustration {
        width: 100%;
        height: 240px;
        object-fit: cover;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(14, 165, 233, 0.15);
        border: 4px solid rgba(255, 255, 255, 0.6);
        transition: transform 0.4s ease;
    }

    .gaming-illustration:hover {
        transform: translateY(-5px) scale(1.02);
    }

    /* 5. Right Panel (Form Layout) */
    .glass-right {
        padding: 50px 60px;
        background: rgba(255, 255, 255, 0.6);
    }

    .form-section-title {
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #0ea5e9;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section-title i {
        font-size: 1.2rem;
        background: rgba(14, 165, 233, 0.1);
        padding: 8px;
        border-radius: 10px;
    }

    .form-section-title::after {
        content: '';
        flex: 1;
        height: 2px;
        background: rgba(14, 165, 233, 0.15);
        border-radius: 2px;
    }

    .form-label-zen {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
    }

    .form-label-zen i {
        color: #94a3b8;
    }

    /* Input Fields */
    .input-zen {
        width: 100%;
        padding: 14px 18px;
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid transparent;
        border-radius: 14px;
        color: #1e293b;
        font-size: 1rem;
        margin-bottom: 24px;
        transition: all 0.3s ease;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }

    .input-zen::placeholder { color: #94a3b8; }

    .input-zen:focus {
        outline: none;
        background: #ffffff;
        border-color: #38bdf8;
        box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
    }

    select.input-zen {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
    }

    /* Button */
    .btn-zen {
        width: 100%;
        padding: 18px;
        background: #0f172a; 
        color: #ffffff;
        border: none;
        border-radius: 14px;
        font-weight: 600;
        font-size: 1rem;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }

    .btn-zen:hover {
        background: #0ea5e9;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(14, 165, 233, 0.25);
    }

    .custom-navbar { z-index: 99999 !important; }

    /* Responsive */
    @media (max-width: 991px) {
        .zen-wrapper { margin-top: 80px; padding: 20px; }
        .glass-card { grid-template-columns: 1fr; border-radius: 24px; }
        .glass-left { padding: 40px 30px; text-align: center; border-right: none; border-bottom: 1px solid rgba(255,255,255,0.5); }
        .zen-title { font-size: 42px; }
        .gaming-illustration { height: 200px; }
        .glass-right { padding: 40px 30px; }
        .form-section-title::after { display: none; }
        .form-label-zen { justify-content: flex-start; }
    }
</style>

<div class="zen-wrapper">
    <div class="glass-card">
        
        <aside class="glass-left">
            <h1 class="zen-title">
                Mengenal<br>
                <em>profil</em> gaming<br>
                dirimu.
            </h1>
            <p class="zen-subtitle">
                Kesehatan mental bermula dari kesadaran diri. Beritahu kami sedikit tentang kebiasaan bermainmu untuk mendapatkan analisis yang tepat.
            </p>
            
            <img src="https://images.unsplash.com/photo-1593305841991-05c297ba4575?q=80&w=800&auto=format&fit=crop" 
                 alt="Gaming Controller Aesthetics" 
                 class="gaming-illustration mt-2">
        </aside>

        <main class="glass-right">
            <form method="POST">
                
                <div class="form-section-title">
                    <i class="bi bi-person-vcard"></i> Identitas Diri
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label-zen"><i class="bi bi-calendar3"></i> Age</label>
                        <input type="number" name="age" class="input-zen" placeholder="Contoh: 20" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-zen"><i class="bi bi-gender-ambiguous"></i> Gender</label>
                        <select name="gender" class="input-zen" required>
                            <option value="" disabled selected>Pilih Gender...</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="form-section-title mt-3">
                    <i class="bi bi-controller"></i> Kebiasaan Bermain
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label-zen"><i class="bi bi-clock-history"></i> Daily Gaming Hours</label>
                        <input type="number" step="0.1" name="daily_gaming_hours" class="input-zen" placeholder="Contoh: 3.5" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-zen"><i class="bi bi-calendar-check"></i> Years Gaming</label>
                        <input type="number" name="years_gaming" class="input-zen" placeholder="Contoh: 5" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-zen"><i class="bi bi-joystick"></i> Game Genre</label>
                        <input type="text" name="game_genre" class="input-zen" placeholder="Contoh: FPS, RPG" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-zen"><i class="bi bi-trophy"></i> Primary Game</label>
                        <input type="text" name="primary_game" class="input-zen" placeholder="Contoh: Valorant" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label-zen"><i class="bi bi-pc-display"></i> Gaming Platform</label>
                        <input type="text" name="gaming_platform" class="input-zen" placeholder="Contoh: PC, Mobile" required>
                    </div>
                </div>

                <button type="submit" name="next_1" class="btn-zen mt-3">
                    Lanjut ke Analisis Kesehatan
                    <i class="bi bi-arrow-right-circle fs-5"></i>
                </button>
            </form>
        </main>

    </div>
</div>

<?php include 'components/footer.php'; ?>