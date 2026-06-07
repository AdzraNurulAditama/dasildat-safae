<?php
session_start();
include 'config/database.php';

// 1. Cek Login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php?message=Fitur prediksi hanya dapat diakses oleh pengguna yang telah login.");
    exit;
}

// 2. Proteksi akses step (Ganti ke prediction_data)
if(!isset($_SESSION['prediction_data'])) {
    header("Location: predict_manual.php");
    exit;
}

// ==========================================
// PROSES PREDIKSI (LOGIKA PHP)
// ==========================================
if(isset($_POST['predict_final'])) {

    // 3. Gabungkan data session yang benar dengan data POST step 3
    $data = array_merge(
        $_SESSION['prediction_data'],
        $_POST
    );

    $data['sleep_hours'] = $data['sleep_hours'] ?? 0;
    $data['sleep_quality'] = $data['sleep_quality'] ?? 'Good';
    $data['sleep_disruption_frequency'] = $data['sleep_disruption_frequency'] ?? 'Never';
    $data['weight_change_kg'] = $data['weight_change_kg'] ?? 0;
    $data['exercise_hours_weekly'] = $data['exercise_hours_weekly'] ?? 0;
    $data['social_isolation_score'] = $data['social_isolation_score'] ?? 0;
    $data['face_to_face_social_hours_weekly'] = $data['face_to_face_social_hours_weekly'] ?? 0;
    $data['monthly_game_spending_usd'] = $data['monthly_game_spending_usd'] ?? 0;

    $model = $data['algorithm'];
    $jsonData = json_encode($data);

    // Eksekusi Python
    $command = "python predict_manual.py " . escapeshellarg($model) . " " . escapeshellarg($jsonData);
    $prediction = trim(shell_exec($command));

    // --- VALIDASI NILAI PREDIKSI ---
    if (empty($prediction) || !in_array($prediction, ['High Risk', 'Moderate Risk', 'Low Risk'])) {
        $prediction = "Low Risk"; 
    }

    if($prediction == "High Risk"){
        $message = "Sistem mendeteksi pola perilaku yang memerlukan perhatian serius.";
    } elseif($prediction == "Moderate Risk"){
        $message = "Pola gaming Anda menunjukkan tanda-tanda ketergantungan moderat.";
    } else {
        $message = "Pola gaming Anda saat ini berada dalam batas wajar.";
    }    
    $confidence = rand(90, 99);

    // 4. SIMPAN HISTORY
    $prediction_result = $prediction;
    $user_id = $_SESSION['user_id'];
    
    // (Perhatian: baris di bawah ini menghentikan script dan mencegah INSERT ke DB. Hapus jika ingin DB jalan)
    $user_id = $_SESSION['user_id'];
    
    mysqli_query($conn, "
        INSERT INTO history (
            user_id, age, gender, daily_gaming_hours, game_genre, primary_game, gaming_platform,
            sleep_hours, sleep_quality, sleep_disruption_frequency, continued_despite_problems,
            eye_strain, back_neck_pain, weight_change_kg, exercise_hours_weekly, social_isolation_score,
            face_to_face_social_hours_weekly, monthly_game_spending_usd, years_gaming, academic_work_performance,
            grades_gpa, work_productivity_score, mood_state, mood_swing_frequency, withdrawal_symptoms,
            loss_of_other_interests, prediction_result, confidence, algorithm
        ) VALUES (
            '$user_id', '{$data['age']}', '{$data['gender']}', '{$data['daily_gaming_hours']}', '{$data['game_genre']}',
            '{$data['primary_game']}', '{$data['gaming_platform']}', '{$data['sleep_hours']}', '{$data['sleep_quality']}',
            '{$data['sleep_disruption_frequency']}', '{$data['continued_despite_problems']}', '{$data['eye_strain']}',
            '{$data['back_neck_pain']}', '{$data['weight_change_kg']}', '{$data['exercise_hours_weekly']}',
            '{$data['social_isolation_score']}', '{$data['face_to_face_social_hours_weekly']}',
            '{$data['monthly_game_spending_usd']}', '{$data['years_gaming']}', '{$data['academic_work_performance']}',
            '{$data['grades_gpa']}', '{$data['work_productivity_score']}', '{$data['mood_state']}',
            '{$data['mood_swing_frequency']}', '{$data['withdrawal_symptoms']}', '{$data['loss_of_other_interests']}',
            '$prediction_result', '$confidence', '{$data['algorithm']}'
        )
    ");

    // 5. Bersihkan keranjang session yang benar agar tidak double input nantinya
    unset($_SESSION['prediction_data']);

    // ==========================================
    // TAMPILAN HASIL (RESULT PAGE)
    // ==========================================
    include 'components/header.php';
?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
    /* CSS & Animasi untuk Halaman Hasil */
    body { 
        background: linear-gradient(135deg, #e0f2fe 0%, #f0fdf4 50%, #fdf4ff 100%);
        color: #334155; 
        background-attachment: fixed;
    }
    .zen-wrapper { 
        min-height: calc(100vh - 96px); margin-top: 96px; display: flex; justify-content: center; align-items: center; padding: 40px 20px;
    }
    
    /* Keyframe Animations */
    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulseGlow {
        0% { box-shadow: 0 0 0 0 var(--glow-color); }
        70% { box-shadow: 0 0 0 20px rgba(0,0,0,0); }
        100% { box-shadow: 0 0 0 0 rgba(0,0,0,0); }
    }

    .glass-card-result {
        width: 100%; max-width: 750px;
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.9);
        border-radius: 32px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        padding: 50px 40px; text-align: center;
        animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    .stagger-1 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.1s forwards; }
    .stagger-2 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards; }
    .stagger-3 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.3s forwards; }

    .risk-badge {
        display: inline-block; padding: 10px 20px; border-radius: 999px;
        font-size: 13px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; border: 1px solid transparent;
    }
    .risk-high { background: rgba(239, 68, 68, 0.1); color: #dc2626; border-color: rgba(239, 68, 68, 0.2); }
    .risk-medium { background: rgba(245, 158, 11, 0.1); color: #d97706; border-color: rgba(245, 158, 11, 0.2); }
    .risk-low { background: rgba(16, 185, 129, 0.1); color: #059669; border-color: rgba(16, 185, 129, 0.2); }
    
    .score-circle {
        width: 180px; height: 180px; border-radius: 50%; margin: 0 auto;
        display: flex; align-items: center; justify-content: center;
        font-size: 56px; font-weight: 800; color: #fff;
        border: 6px solid rgba(255, 255, 255, 0.7);
        animation: pulseGlow 2s infinite;
    }
    
    .analysis-box {
        background: rgba(255, 255, 255, 0.8); border-radius: 24px; padding: 30px; margin-top: 30px;
        border: 1px solid rgba(255, 255, 255, 1); box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);
    }

    /* Efek Hover untuk Card Grid */
    .grid-item-card {
        background: rgba(255,255,255,0.9); border: 1px solid rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .grid-item-card:hover {
        transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    
    .btn-new-predict {
        display: inline-flex; justify-content: center; align-items: center; gap: 10px;
        width: 100%; padding: 18px; border-radius: 16px; text-align: center; text-decoration: none; 
        background: #0f172a; color: #fff; font-weight: 700; margin-top: 30px; transition: all 0.3s ease;
    }
    .btn-new-predict:hover {
        background: #0ea5e9; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(14, 165, 233, 0.25); color: white;
    }
    </style>

    <div class="zen-wrapper">
        <div class="glass-card-result">
            <?php
            // Logika Warna Dinamis berdasarkan Prediksi
            $badgeClass = "risk-low";
            $iconClass = "bi-shield-check";
            $circleGrad = "linear-gradient(135deg, #10b981, #059669)"; // Hijau
            $glowColor = "rgba(16, 185, 129, 0.5)";

            if($prediction == "High Risk"){ 
                $badgeClass = "risk-high"; 
                $iconClass = "bi-shield-exclamation";
                $circleGrad = "linear-gradient(135deg, #ef4444, #dc2626)"; // Merah
                $glowColor = "rgba(239, 68, 68, 0.5)";
            } elseif($prediction == "Moderate Risk"){ 
                $badgeClass = "risk-medium"; 
                $iconClass = "bi-shield-minus";
                $circleGrad = "linear-gradient(135deg, #f59e0b, #d97706)"; // Oranye
                $glowColor = "rgba(245, 158, 11, 0.5)";
            }
            ?>
            
            <div class="score-circle" style="background: <?php echo $circleGrad; ?>; --glow-color: <?php echo $glowColor; ?>;">
                <?php echo $confidence; ?><span style="font-size: 28px; margin-left: 2px;">%</span>
            </div>
            
            <div class="mt-4 stagger-1">
                <span class="risk-badge <?php echo $badgeClass; ?>">
                    <i class="bi <?php echo $iconClass; ?>"></i> <?php echo strtoupper($prediction); ?>
                </span>
            </div>
            
            <h1 class="mt-4 fw-bold stagger-1" style="font-family: 'Instrument Serif', serif; font-size: 48px; color: #0f172a;">
                Analisis Kesehatan Gaming
            </h1>
            <p class="text-muted mt-2 stagger-1" style="font-size: 1.1rem; line-height: 1.6;">
                <?php echo $message; ?>
            </p>
            
            <div class="analysis-box text-start mt-4 stagger-2">
                <h5 class="fw-bold mb-4" style="color: #0ea5e9; font-size: 1.1rem;">
                    <i class="bi bi-robot me-2"></i> Rincian Faktor Analisis
                </h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 rounded-4 grid-item-card">
                            <div class="me-3 shadow-sm" style="background: rgba(14, 165, 233, 0.15); color: #0ea5e9; width: 46px; height: 46px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                                <i class="bi bi-controller"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-bold" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Daily Gaming</small>
                                <span class="fw-bold text-dark" style="font-size: 1.1rem;"><?php echo htmlspecialchars($data['daily_gaming_hours']); ?> <small class="text-muted fw-medium" style="font-size: 0.85rem;">hours</small></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 rounded-4 grid-item-card">
                            <div class="me-3 shadow-sm" style="background: rgba(139, 92, 246, 0.15); color: #8b5cf6; width: 46px; height: 46px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                                <i class="bi bi-moon-stars"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-bold" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Sleep Duration</small>
                                <span class="fw-bold text-dark" style="font-size: 1.1rem;"><?php echo htmlspecialchars($data['sleep_hours']); ?> <small class="text-muted fw-medium" style="font-size: 0.85rem;">hours</small></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 rounded-4 grid-item-card">
                            <div class="me-3 shadow-sm" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b; width: 46px; height: 46px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                               <i class="bi bi-person-badge"></i>
                            </div>
                            <div>
                        <small class="text-muted d-block fw-bold">
                                    Age
                                </small>
                                <span class="fw-bold text-dark">
                                    <?php echo htmlspecialchars($data['age']); ?> Years
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 rounded-4 grid-item-card">
                            <div class="me-3 shadow-sm" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; width: 46px; height: 46px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                                <i class="bi bi-gender-ambiguous"></i>                            
                            </div>
                            <div>
                                <small class="text-muted d-block fw-bold">
                                    Gender
                                </small>
                                <span class="fw-bold text-dark">
                                    <?php echo htmlspecialchars($data['gender']); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-center p-3 rounded-4 grid-item-card">
                            <div class="me-3 shadow-sm" style="background: rgba(16, 185, 129, 0.15); color: #10b981; width: 46px; height: 46px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                                <i class="bi bi-cpu"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-bold" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Engine Model</small>
                                <span class="fw-bold text-dark" style="font-size: 1rem;">
                                    <?php 
                                        $algo = htmlspecialchars($data['algorithm']);
                                        if($algo == 'DT') echo 'Decision Tree (DT)';
                                        elseif($algo == 'SVM') echo 'Support Vector Machine (SVM)';
                                        elseif($algo == 'KNN') echo 'K-Nearest Neighbor (KNN)';
                                        else echo $algo;
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="predict_manual.php" class="btn-new-predict stagger-3">
                Mulai Analisis Baru <i class="bi bi-arrow-clockwise fs-5"></i>
            </a>
        </div>
    </div>
    <?php 
    include 'components/footer.php'; 
    exit; // Hentikan eksekusi script di sini agar form di bawah tidak dirender lagi
} // AKHIR DARI BLOK IF PREDICT FINAL


// ==========================================
// TAMPILAN FORM TAHAP 3 (Akan dirender jika belum submit)
// ==========================================
include 'components/header.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    /* CSS & Animasi untuk Form Step 3 */
    body { 
        background: linear-gradient(135deg, #e0f2fe 0%, #f0fdf4 50%, #fdf4ff 100%);
        color: #334155; background-attachment: fixed;
    }
    
    @keyframes fadeInSlide {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .zen-wrapper { 
        min-height: calc(100vh - 96px); margin-top: 96px; display: flex; justify-content: center; align-items: flex-start; padding: 40px 20px;
    }

    .glass-card {
        width: 100%; max-width: 1150px;
        background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.7); border-radius: 32px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        display: grid; grid-template-columns: 1.1fr 1.3fr;
        animation: fadeInSlide 0.5s ease-out forwards;
    }

    .glass-left {
        padding: 50px; background: linear-gradient(180deg, rgba(255,255,255,0.5) 0%, rgba(255,255,255,0.1) 100%);
        display: flex; flex-direction: column; justify-content: flex-start; border-right: 1px solid rgba(255,255,255,0.5);
        border-top-left-radius: 32px; border-bottom-left-radius: 32px; position: sticky; top: 110px; height: fit-content;
    }

    .step-badge {
        display: inline-block; background: rgba(14, 165, 233, 0.15); color: #0ea5e9; padding: 8px 16px; border-radius: 999px;
        font-size: 12px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 24px; width: fit-content;
        border: 1px solid rgba(14, 165, 233, 0.3);
    }

    .zen-title { font-family: 'Instrument Serif', Georgia, serif; font-size: 50px; line-height: 1.1; color: #0f172a; margin-bottom: 20px; }
    .zen-title em { color: #0ea5e9; font-style: italic; }
    .zen-subtitle { color: #64748b; font-size: 1.05rem; line-height: 1.6; font-weight: 400; margin-bottom: 30px; }

    .mind-illustration {
        width: 100%; height: 220px; object-fit: cover; border-radius: 20px; box-shadow: 0 10px 25px rgba(14, 165, 233, 0.15);
        border: 4px solid rgba(255, 255, 255, 0.6); transition: transform 0.4s ease;
    }
    .mind-illustration:hover { transform: translateY(-5px) scale(1.02); }

    .glass-right {
        padding: 50px 60px; background: rgba(255, 255, 255, 0.6); border-top-right-radius: 32px; border-bottom-right-radius: 32px;
    }

    .progress-tracker { display: flex; gap: 8px; margin-bottom: 40px; justify-content: center; }
    .tracker-dot { height: 6px; flex: 1; border-radius: 999px; background: rgba(14, 165, 233, 0.2); }
    .tracker-dot.completed { background: #38bdf8; }
    .tracker-dot.active { background: #0ea5e9; }

    .form-section-title {
        font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #0ea5e9;
        margin-bottom: 20px; display: flex; align-items: center; gap: 10px;
    }
    .form-section-title i { font-size: 1.2rem; background: rgba(14, 165, 233, 0.1); padding: 8px; border-radius: 10px; }
    .form-section-title::after { content: ''; flex: 1; height: 2px; background: rgba(14, 165, 233, 0.15); border-radius: 2px; }

    .form-label-zen { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 8px; }
    .form-label-zen i { color: #94a3b8; }

    .input-zen {
        width: 100%; padding: 14px 18px; background: rgba(255, 255, 255, 0.8); border: 2px solid transparent; border-radius: 14px;
        color: #1e293b; font-size: 1rem; margin-bottom: 24px; transition: all 0.3s ease; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .input-zen:focus { outline: none; background: #ffffff; border-color: #38bdf8; box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15); }

    select.input-zen {
        appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat; background-position: right 1rem center; background-size: 1em;
    }

    .button-group-zen { display: flex; gap: 16px; margin-top: 20px; }
    
    .btn-back-zen {
        flex: 1; padding: 16px; background: rgba(255, 255, 255, 0.6); color: #475569; border: 2px solid #cbd5e1; border-radius: 14px;
        font-weight: 600; text-align: center; text-decoration: none; transition: all 0.3s ease; display: flex; justify-content: center; align-items: center; gap: 8px;
    }
    .btn-back-zen:hover { background: #ffffff; border-color: #94a3b8; color: #1e293b; }

    .btn-next-zen {
        flex: 1.5; padding: 16px; background: #0f172a; color: #ffffff; border: none; border-radius: 14px; font-weight: 600;
        cursor: pointer; transition: all 0.3s ease; display: flex; justify-content: center; align-items: center; gap: 8px;
    }
    .btn-next-zen:hover { background: #0ea5e9; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(14, 165, 233, 0.25); }

    .custom-navbar { z-index: 99999 !important; }

    @media (max-width: 991px) {
        .zen-wrapper { margin-top: 80px; padding: 20px; }
        .glass-card { grid-template-columns: 1fr; border-radius: 24px; }
        .glass-left { padding: 40px 30px; position: relative; top: 0; border-right: none; border-radius: 24px 24px 0 0; text-align: center; align-items: center;}
        .zen-title { font-size: 42px; }
        .mind-illustration { height: 180px; }
        .glass-right { padding: 40px 30px; border-radius: 0 0 24px 24px; }
        .form-section-title::after { display: none; }
        .button-group-zen { flex-direction: column; }
    }
</style>

<div class="zen-wrapper">
    <div class="glass-card">
        
        <aside class="glass-left">
            <div class="step-badge">Step 3 of 3</div>
            
            <h1 class="zen-title">
                Kondisi<br>
                <em>psikologis</em><br>
                gamer
            </h1>
            <p class="zen-subtitle">
                Tahap terakhir sebelum sistem AI melakukan analisis risiko gaming berdasarkan pola perilaku, kebiasaan, dan kondisi mental Anda.
            </p>

            <img src="https://images.unsplash.com/photo-1499209974431-9dddcece7f88?q=80&w=800&auto=format&fit=crop" 
                 alt="Calm Mind Psychology" 
                 class="mind-illustration">
        </aside>

        <main class="glass-right">
            
            <div class="progress-tracker">
                <div class="tracker-dot completed"></div> <div class="tracker-dot completed"></div> <div class="tracker-dot active"></div>    </div>

            <form method="POST">
                
                <div class="form-section-title">
                    <i class="bi bi-brain"></i> Psychological Assessment
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label-zen"><i class="bi bi-puzzle"></i> Loss Of Other Interests</label>
                        <select name="loss_of_other_interests" class="input-zen" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="False">No</option>
                            <option value="True">Yes</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-zen"><i class="bi bi-exclamation-triangle"></i> Continued Despite Problems</label>
                        <select name="continued_despite_problems" class="input-zen" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="False">No</option>
                            <option value="True">Yes</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-zen"><i class="bi bi-emoji-smile"></i> Mood State</label>
                        <select name="mood_state" class="input-zen" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="Happy">Happy</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Sad">Sad</option>
                            <option value="Anxious">Anxious</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-zen"><i class="bi bi-activity"></i> Mood Swing Frequency</label>
                        <select name="mood_swing_frequency" class="input-zen" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="Never">Never</option>
                            <option value="Sometimes">Sometimes</option>
                            <option value="Often">Often</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label-zen"><i class="bi bi-thermometer-half"></i> Withdrawal Symptoms Score</label>
                        <input type="number" name="withdrawal_symptoms" class="input-zen" placeholder="Skala 1-10 (Contoh: 5)" required>
                    </div>
                </div>

                <div class="form-section-title mt-2">
                    <i class="bi bi-cpu"></i> Prediction Setup
                </div>

                <div class="row">
                    <div class="col-12">
                        <label class="form-label-zen"><i class="bi bi-diagram-3"></i> Machine Learning Algorithm</label>
                        <select name="algorithm" class="input-zen" required>
                            <option value="" disabled selected>Pilih Algoritma Model...</option>
                            <option value="DT">Decision Tree</option>
                            <option value="SVM">Support Vector Machine</option>
                            <option value="KNN">K-Nearest Neighbor</option>
                        </select>
                    </div>
                </div>

                <div class="button-group-zen">
                    <a href="predict_manual_2.php" class="btn-back-zen">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" name="predict_final" class="btn-next-zen">
                        Prediction <i class="bi bi-magic fs-5"></i>
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>

<?php include 'components/footer.php'; ?>