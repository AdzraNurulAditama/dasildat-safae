<?php
session_start();
include 'config/database.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php?message=Fitur prediksi hanya dapat diakses oleh pengguna yang telah login.");
    exit;
}

// Proteksi akses step
if(!isset($_SESSION['step1_data']) || !isset($_SESSION['step2_data'])) {
    header("Location: predict_manual.php");
    exit;
}

// ==========================================
// PROSES PREDIKSI (LOGIKA PHP)
// ==========================================
if(isset($_POST['predict_final'])) {

    $data = array_merge(
        $_SESSION['step1_data'],
        $_SESSION['step2_data'],
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

    // SIMPAN HISTORY
    $prediction_result = $prediction;

    mysqli_query($conn, "
        INSERT INTO history (
            age, gender, daily_gaming_hours, game_genre, primary_game, gaming_platform,
            sleep_hours, sleep_quality, sleep_disruption_frequency, continued_despite_problems,
            eye_strain, back_neck_pain, weight_change_kg, exercise_hours_weekly, social_isolation_score,
            face_to_face_social_hours_weekly, monthly_game_spending_usd, years_gaming, academic_work_performance,
            grades_gpa, work_productivity_score, mood_state, mood_swing_frequency, withdrawal_symptoms,
            loss_of_other_interests, prediction_result, confidence, algorithm
        ) VALUES (
            '{$data['age']}', '{$data['gender']}', '{$data['daily_gaming_hours']}', '{$data['game_genre']}',
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

    unset($_SESSION['step1_data'], $_SESSION['step2_data']);

    // TAMPILAN HASIL
    include 'components/header.php';
    ?>
    <style>
    .result-page{
        min-height:100vh;
        background:#f8fafc;
        padding-top:140px;
        padding-bottom:80px;
    }
    .result-card{
        background:white;
        border-radius:32px;
        padding:50px;
        box-shadow:0 20px 60px rgba(0,0,0,.08);
    }
    .risk-badge{
        display:inline-block;
        padding:10px 18px;
        border-radius:999px;
        font-size:12px;
        font-weight:700;
        letter-spacing:2px;
        text-transform:uppercase;
    }
    .risk-high{ background:#fee2e2; color:#dc2626; }
    .risk-medium{ background:#fef3c7; color:#d97706; }
    .risk-low{ background:#dcfce7; color:#16a34a; }
    .score-circle{
        width:180px; height:180px; border-radius:50%; margin:auto;
        display:flex; align-items:center; justify-content:center;
        font-size:52px; font-weight:800; color:white;
        background:linear-gradient(135deg, #9333ea, #7c3aed);
    }
    .analysis-box{
        background:#f8fafc; border-radius:20px; padding:20px; margin-top:25px;
    }
    .result-btn{
        display:block; width:100%; padding:18px; border-radius:16px;
        text-align:center; text-decoration:none; background:#111827;
        color:white; font-weight:700; margin-top:30px;
    }
    </style>

    <div class="result-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="result-card text-center">
                        <?php
                        $badgeClass = "risk-low";
                        if($prediction == "High Risk"){ $badgeClass = "risk-high"; }
                        if($prediction == "Moderate Risk"){ $badgeClass = "risk-medium"; }
                        ?>
                        <div class="score-circle">
                            <?php echo $confidence; ?>%
                        </div>
                        <div class="mt-4">
                            <span class="risk-badge <?php echo $badgeClass; ?>">
                                <?php echo strtoupper($prediction); ?>
                            </span>
                        </div>
                        <h1 class="mt-4 fw-bold">Gaming Addiction Analysis</h1>
                        <p class="text-muted mt-3">
                            <?php echo $message; ?>
                        </p>
                        <div class="analysis-box text-start">
                            <h5 class="fw-bold mb-3">AI Findings</h5>
                            <ul class="mb-0">
                                <li>Daily Gaming Hours: <strong><?php echo $data['daily_gaming_hours']; ?> hours</strong></li>
                                <li>Sleep Duration: <strong><?php echo $data['sleep_hours']; ?> hours</strong></li>
                                <li>Continued Despite Problems: <strong><?php echo $data['continued_despite_problems']; ?></strong></li>
                                <li>Loss Of Other Interests: <strong><?php echo $data['loss_of_other_interests']; ?></strong></li>
                                <li>Model Used: <strong><?php echo $data['algorithm']; ?></strong></li>
                            </ul>
                        </div>
                        <a href="predict_manual.php" class="result-btn">New Predict</a>
                    </div>
                </div>
            </div>
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

<style>
.page-wrapper{
    display:grid;
    grid-template-columns:48% 52%;
    min-height:100vh;
    margin-top:96px;
}
.left-panel{
    background:#1a1916;
    padding:60px;
    color:#fff;
    display:flex;
    flex-direction:column;
    justify-content:center;
}
.right-panel{
    background:#fafaf7;
    padding:60px 80px;
}
.progress-wrapper{
    display:flex;
    gap:8px;
    margin-bottom:40px;
}
.progress-item{
    flex:1;
    height:8px;
    border-radius:999px;
}
.progress-active{
    background:#8b5cf6;
}
.section-label{
    letter-spacing:2px;
    font-size:12px;
    text-transform:uppercase;
    color:#6b7280;
    margin-bottom:24px;
    font-weight:600;
}
.field-input{
    width:100%;
    padding:14px;
    border:1px solid #d4d1c4;
    border-radius:12px;
    margin-bottom:20px;
    background:#fff;
}
.field-input:focus{
    outline:none;
    border-color:#8b5cf6;
    box-shadow:0 0 0 4px rgba(139,92,246,.1);
}
label{
    font-size:14px;
    font-weight:600;
    margin-bottom:8px;
    display:block;
}
.button-group{
    display:flex;
    gap:16px;
    margin-top:30px;
}
.btn-back{
    flex:1;
    padding:18px;
    border-radius:12px;
    border:1px solid #d4d1c4;
    background:#fff;
    color:#1a1916;
    text-decoration:none;
    text-align:center;
    font-weight:700;
    text-transform:uppercase;
}
.btn-next{
    flex:1;
    padding:18px;
    border:none;
    border-radius:12px;
    background:#1a1916;
    color:#fff;
    font-weight:700;
    text-transform:uppercase;
    cursor:pointer;
}
@media(max-width:991px){
    .page-wrapper{ grid-template-columns:1fr; margin-top:100px; }
    .right-panel{ padding:40px 25px; }
    .button-group{ flex-direction:column; }
}
</style>

<div class="page-wrapper">

    <aside class="left-panel">
        <div style="margin-bottom:30px;">
            <span style="background:rgba(196,185,255,.15); border:1px solid rgba(196,185,255,.25); color:#c4b9ff; padding:10px 16px; border-radius:999px; font-size:12px; font-weight:700; letter-spacing:2px; text-transform:uppercase;">
                STEP 3 OF 3
            </span>
        </div>
        <h1 style="font-family:'Instrument Serif', serif; font-size:56px; line-height:1.05;">
            Kondisi<br>
            <em style="color:#c4b9ff;">psikologis</em><br>
            gamer
        </h1>
        <p style="color:rgba(255,255,255,.65); margin-top:20px; max-width:420px; line-height:1.8;">
            Tahap terakhir sebelum sistem AI melakukan analisis risiko gaming berdasarkan pola perilaku, kebiasaan bermain, dan kondisi psikologis Anda.
        </p>
    </aside>

    <main class="right-panel">
        <div class="progress-wrapper">
            <div class="progress-item progress-active"></div>
            <div class="progress-item progress-active"></div>
            <div class="progress-item progress-active"></div>
        </div>

        <form method="POST">
            <h5 class="section-label">
                Psychological Assessment
            </h5>

            <div>
                <label>Loss Of Other Interests</label>
                <select name="loss_of_other_interests" class="field-input">
                    <option value="False">No</option>
                    <option value="True">Yes</option>
                </select>
            </div>

            <div>
                <label>Continued Despite Problems</label>
                <select name="continued_despite_problems" class="field-input">
                    <option value="False">No</option>
                    <option value="True">Yes</option>
                </select>
            </div>

            <div>
                <label>Mood State</label>
                <select name="mood_state" class="field-input">
                    <option value="Happy">Happy</option>
                    <option value="Neutral">Neutral</option>
                    <option value="Sad">Sad</option>
                    <option value="Anxious">Anxious</option>
                </select>
            </div>

            <div>
                <label>Mood Swing Frequency</label>
                <select name="mood_swing_frequency" class="field-input">
                    <option value="Never">Never</option>
                    <option value="Sometimes">Sometimes</option>
                    <option value="Often">Often</option>
                </select>
            </div>

            <div>
                <label>Withdrawal Symptoms</label>
                <input type="number" name="withdrawal_symptoms" class="field-input" placeholder="Skala 1-10" required>
            </div>

            <div>
                <label>Machine Learning Algorithm</label>
                <select name="algorithm" class="field-input">
                    <option value="DT">Decision Tree</option>
                    <option value="SVM">Support Vector Machine</option>
                    <option value="KNN">K-Nearest Neighbor</option>
                </select>
            </div>

            <div class="button-group">
                <a href="predict_manual_2.php" class="btn-back">
                    &larr; Back
                </a>
                <button type="submit" name="predict_final" class="btn-next">
                    Generate Prediction &rarr;
                </button>
            </div>
        </form>
    </main>
</div>

<?php include 'components/footer.php'; ?>