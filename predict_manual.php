<?php
session_start();
include 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Fitur prediksi hanya dapat diakses oleh pengguna yang telah login.");
    exit;
}
include 'components/header.php';
?>

<style>
    /* 1. Layout Wrapper */
    .page-wrapper { 
        display: grid; 
        grid-template-columns: 48% 52%; 
        min-height: 100vh; 
        margin-top: 96px; /* Sesuaikan angka ini (coba 100px-150px sampai pas) */
    }

    /* 2. Panel Kiri */
    .left-panel { 
        background: #1a1916; 
        padding: 60px; 
        color: #fff; 
        display: flex; 
        flex-direction: column; 
        justify-content: center; 
    }

    /* 3. Panel Kanan (Form) */
    .right-panel {
        background: #fafaf7; 
        padding: 60px 80px !important; 
        overflow-y: auto;
    }

    /* 4. Elemen Form */
    .field-input { 
        width: 100%; 
        padding: 14px; 
        border: 1px solid #d4d1c4; 
        border-radius: 12px; 
        margin-bottom: 20px; 
        background: #ffffff;
    }
    .btn-continue { 
        width: 100%; 
        padding: 18px; 
        background: #1a1916; 
        color: #fff; 
        border-radius: 12px; 
        font-weight: 700; 
        text-transform: uppercase; 
        border: none; 
        cursor: pointer;
    }

    .custom-navbar {
        z-index: 99999 !important;
    }
    
    /* Responsive */
    @media (max-width: 991px) { 
        .page-wrapper { grid-template-columns: 1fr; margin-top: 100px; } 
    }
</style>

<div class="page-wrapper">
    <aside class="left-panel">
        <h1 style="font-family: 'Instrument Serif', serif; font-size: 56px;">
            Siapa<br><em style="color:#c4b9ff;">kamu</em><br>sebagai gamer?
        </h1>
        <p style="color:rgba(255,255,255,0.6); margin-top:20px; max-width: 400px;">
            Ceritakan sedikit tentang dirimu dan kebiasaan gaming-mu. Data ini membantu kami memberikan analisis yang personal.
        </p>
    </aside>

    <main class="right-panel">
        <form method="POST">
            <h5 class="text-uppercase text-muted mb-4" style="letter-spacing: 2px; font-size: 12px;">Identitas Diri</h5>
            <div class="row">
                <div class="col-md-6">
                    <label>Age</label>
                    <input type="number" name="age" class="field-input" placeholder="Contoh: 20" required>
                </div>
                <div class="col-md-6">
                    <label>Gender</label>
                    <select name="gender" class="field-input">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>

            <h5 class="text-uppercase text-muted mb-4 mt-4" style="letter-spacing: 2px; font-size: 12px;">Kebiasaan Gaming</h5>
            <div class="row">
                <div class="col-md-6">
                    <label>Daily Gaming Hours</label>
                    <input type="number" step="0.1" name="daily_gaming_hours" class="field-input" placeholder="Contoh: 3.5" required>
                </div>
                <div class="col-md-6">
                    <label>Years Gaming</label>
                    <input type="number" name="years_gaming" class="field-input" placeholder="Contoh: 5" required>
                </div>
                <div class="col-md-6">
                    <label>Game Genre</label>
                    <input type="text" name="game_genre" class="field-input" placeholder="Contoh: FPS, RPG, MOBA" required>
                </div>
                <div class="col-md-6">
                    <label>Primary Game</label>
                    <input type="text" name="primary_game" class="field-input" placeholder="Contoh: Valorant" required>
                </div>
                <div class="col-12">
                    <label>Gaming Platform</label>
                    <input type="text" name="gaming_platform" class="field-input" placeholder="Contoh: PC, PlayStation, Mobile" required>
                </div>
            </div>

            <button type="submit" name="next_1" class="btn-continue mt-4">
                Continue to Health Analysis →
            </button>
        </form>
    </main>
</div>

<?php include 'components/footer.php'; ?>