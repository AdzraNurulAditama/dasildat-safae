<?php
session_start(); // Cukup dipanggil 1 kali saja

// Cek login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php?message=Fitur prediksi hanya dapat diakses oleh pengguna yang telah login.");
    exit;
}

// Cek apakah data dari Step 1 sudah ada (Ganti step1_data jadi prediction_data)
if(!isset($_SESSION['prediction_data'])) {
    header("Location: predict_manual.php");
    exit;
}

// Tangkap data dari Step 2 jika tombol Submit ditekan
if(isset($_POST['next_2'])) {
    // Simpan data step 2 gabung ke session yang sama
    $_SESSION['prediction_data']['sleep_hours'] = $_POST['sleep_hours'];
    $_SESSION['prediction_data']['sleep_quality'] = $_POST['sleep_quality'];
    $_SESSION['prediction_data']['sleep_disruption_frequency'] = $_POST['sleep_disruption_frequency'];
    $_SESSION['prediction_data']['academic_work_performance'] = $_POST['academic_work_performance'];
    $_SESSION['prediction_data']['grades_gpa'] = $_POST['grades_gpa'];
    $_SESSION['prediction_data']['work_productivity_score'] = $_POST['work_productivity_score'];
    $_SESSION['prediction_data']['monthly_game_spending_usd'] = $_POST['monthly_game_spending_usd'];
    $_SESSION['prediction_data']['exercise_hours_weekly'] = $_POST['exercise_hours_weekly'];
    $_SESSION['prediction_data']['eye_strain'] = $_POST['eye_strain'];
    $_SESSION['prediction_data']['back_neck_pain'] = $_POST['back_neck_pain'];
    $_SESSION['prediction_data']['weight_change_kg'] = $_POST['weight_change_kg'];
    $_SESSION['prediction_data']['social_isolation_score'] = $_POST['social_isolation_score'];
    $_SESSION['prediction_data']['face_to_face_social_hours_weekly'] = $_POST['face_to_face_social_hours_weekly'];

    // Lanjut ke halaman 3 (Ganti nama filenya sesuai dengan file step 3 kamu)
    header("Location: predict_manual_3.php");
    exit;
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
    
    /* 2. Main Wrapper - FIX: Ubah align-items ke flex-start agar bisa di-scroll natural */
    .zen-wrapper { 
        min-height: calc(100vh - 96px); 
        margin-top: 96px;
        display: flex;
        justify-content: center;
        align-items: flex-start; /* Diubah dari center ke flex-start */
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
        /* Hapus overflow: hidden agar isi tidak terpotong */
    }

    /* 4. Left Panel (Typography, Image & Vibe) */
    .glass-left {
        padding: 50px;
        background: linear-gradient(180deg, rgba(255,255,255,0.5) 0%, rgba(255,255,255,0.1) 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-start; /* Agar text tetap di atas mengikuti scroll */
        border-right: 1px solid rgba(255,255,255,0.5);
        border-top-left-radius: 32px;
        border-bottom-left-radius: 32px;
        position: sticky; /* Membuat panel kiri menempel saat kanan di-scroll */
        top: 110px;
        height: fit-content;
    }

    .step-badge {
        display: inline-block;
        background: rgba(14, 165, 233, 0.15);
        color: #0ea5e9;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 24px;
        width: fit-content;
        border: 1px solid rgba(14, 165, 233, 0.3);
    }

    .zen-title {
        font-family: 'Instrument Serif', Georgia, serif;
        font-size: 50px;
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

    /* Styling Gambar Ilustrasi */
    .lifestyle-illustration {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(14, 165, 233, 0.15);
        border: 4px solid rgba(255, 255, 255, 0.6);
        transition: transform 0.4s ease;
    }

    .lifestyle-illustration:hover {
        transform: translateY(-5px) scale(1.02);
    }

    /* 5. Right Panel (Form Layout) */
    .glass-right {
        padding: 50px 60px;
        background: rgba(255, 255, 255, 0.6);
        border-top-right-radius: 32px;
        border-bottom-right-radius: 32px;
    }

    /* Progress Dots Tracker */
    .progress-tracker {
        display: flex;
        gap: 8px;
        margin-bottom: 30px;
        justify-content: center;
    }
    .tracker-dot {
        height: 6px;
        flex: 1;
        border-radius: 999px;
        background: rgba(14, 165, 233, 0.2);
    }
    .tracker-dot.active {
        background: #0ea5e9;
    }
    .tracker-dot.completed {
        background: #38bdf8;
    }

    .form-section-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #0ea5e9;
        margin-bottom: 20px;
        margin-top: 30px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-section-title:first-of-type { margin-top: 0; }

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
    .form-label-zen i { color: #94a3b8; }

    /* Input Fields */
    .input-zen {
        width: 100%;
        padding: 14px 18px;
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid transparent;
        border-radius: 14px;
        color: #1e293b;
        font-size: 1rem;
        margin-bottom: 20px;
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

    /* Buttons */
    .button-group-zen {
        display: flex;
        gap: 16px;
        margin-top: 30px;
    }

    .btn-back-zen {
        flex: 1;
        padding: 16px;
        background: rgba(255, 255, 255, 0.6);
        color: #475569;
        border: 2px solid #cbd5e1;
        border-radius: 14px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }
    .btn-back-zen:hover {
        background: #ffffff;
        border-color: #94a3b8;
        color: #1e293b;
    }

    .btn-next-zen {
        flex: 1.5;
        padding: 16px;
        background: #0f172a; 
        color: #ffffff;
        border: none;
        border-radius: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }
    .btn-next-zen:hover {
        background: #0ea5e9;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(14, 165, 233, 0.25);
    }

    .custom-navbar { z-index: 99999 !important; }

    /* Responsive */
    @media (max-width: 991px) {
        .zen-wrapper { margin-top: 80px; padding: 20px; }
        .glass-card { grid-template-columns: 1fr; border-radius: 24px; }
        .glass-left { padding: 40px 30px; position: relative; top: 0; border-right: none; border-radius: 24px 24px 0 0; }
        .zen-title { font-size: 42px; }
        .lifestyle-illustration { height: 180px; }
        .glass-right { padding: 40px 30px; border-radius: 0 0 24px 24px; }
        .form-section-title::after { display: none; }
        .button-group-zen { flex-direction: column; }
    }
</style>

<div class="zen-wrapper">
    <div class="glass-card">
        
        <aside class="glass-left">
            <div class="step-badge">Step 2 of 3</div>
            
            <h1 class="zen-title">
                Kondisi<br>
                <em>fisik</em><br>
                & gaya hidup
            </h1>
            <p class="zen-subtitle">
                Informasi ini membantu sistem memahami dampak aktivitas bermain game terhadap kualitas tidur, aktivitas sosial, dan kebugaran tubuhmu.
            </p>
            
            <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?q=80&w=800&auto=format&fit=crop" 
                 alt="Healthy Lifestyle Aesthetics" 
                 class="lifestyle-illustration">
        </aside>

        <main class="glass-right">
            
            <div class="progress-tracker">
                <div class="tracker-dot completed"></div> <div class="tracker-dot active"></div>    <div class="tracker-dot"></div>           </div>

            <form method="POST">

                <div class="form-section-title">
                    <i class="bi bi-moon-stars"></i> Tidur & Istirahat
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label-zen"><i class="bi bi-clock-history"></i> Sleep Hours</label>
                        <input type="number" step="0.01" name="sleep_hours" class="input-zen" placeholder="Contoh: 7.5" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-zen"><i class="bi bi-star-half"></i> Sleep Quality</label>
                        <select name="sleep_quality" class="input-zen" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="Very Poor">Very Poor</option>
                            <option value="Poor">Poor</option>
                            <option value="Fair">Fair</option>
                            <option value="Good">Good</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-zen"><i class="bi bi-bell-slash"></i> Disruption</label>
                        <select name="sleep_disruption_frequency" class="input-zen" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="Never">Never</option>
                            <option value="Sometimes">Sometimes</option>
                            <option value="Frequently">Frequently</option>
                        </select>
                    </div>
                </div>

                <div class="form-section-title">
                    <i class="bi bi-briefcase"></i> Akademik & Produktivitas
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label-zen"><i class="bi bi-award"></i> Performance</label>
                        <select name="academic_work_performance" class="input-zen" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="Excellent">Excellent</option>
                            <option value="Average">Average</option>
                            <option value="Poor">Poor</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-zen"><i class="bi bi-book"></i> Grades GPA</label>
                        <input type="number" step="0.01" name="grades_gpa" class="input-zen" placeholder="Contoh: 3.85" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-zen"><i class="bi bi-graph-up-arrow"></i> Prod. Score</label>
                        <input type="number" name="work_productivity_score" class="input-zen" placeholder="Skala 1-10" required>
                    </div>
                </div>

                <div class="form-section-title">
                    <i class="bi bi-heart-pulse"></i> Fisik & Finansial
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label-zen"><i class="bi bi-bicycle"></i> Exercise Hours Weekly</label>
                        <input type="number" step="0.1" name="exercise_hours_weekly" class="input-zen" placeholder="Contoh: 3" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-zen"><i class="bi bi-wallet2"></i> Monthly Game Spend ($)</label>
                        <input type="number" step="0.01" name="monthly_game_spending_usd" class="input-zen" placeholder="Contoh: 25.50" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-zen"><i class="bi bi-eye"></i> Eye Strain</label>
                        <select name="eye_strain" class="input-zen" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="True">Yes</option>
                            <option value="False">No</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-zen"><i class="bi bi-person-arms-up"></i> Neck/Back Pain</label>
                        <select name="back_neck_pain" class="input-zen" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="True">Yes</option>
                            <option value="False">No</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-zen"><i class="bi bi-speedometer2"></i> Weight Change</label>
                        <input type="number" step="0.1" name="weight_change_kg" class="input-zen" placeholder="Contoh: 0 (kg)" required>
                    </div>
                </div>

                <div class="form-section-title">
                    <i class="bi bi-people"></i> Kehidupan Sosial
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label-zen"><i class="bi bi-person-x"></i> Social Isolation Score</label>
                        <input type="number" name="social_isolation_score" class="input-zen" placeholder="Skala 1-10" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-zen"><i class="bi bi-cup-hot"></i> Face-to-Face Social Hours</label>
                        <input type="number" step="0.1" name="face_to_face_social_hours_weekly" class="input-zen" placeholder="Contoh: 5 (jam/mgg)" required>
                    </div>
                </div>

                <div class="button-group-zen">
                    <a href="predict_manual.php" class="btn-back-zen">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" name="next_2" class="btn-next-zen">
                        Lanjut Analisis Mental <i class="bi bi-arrow-right-circle fs-5"></i>
                    </button>
                </div>

            </form>
        </main>

    </div>
</div>

<?php include 'components/footer.php'; ?>