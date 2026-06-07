<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
include 'config/database.php';

if(!isset($_GET['id'])){
    header("Location: history.php");
    exit;
}

$id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

// Query dengan pengecekan user_id agar user tidak bisa melihat detail history orang lain
$query = mysqli_query($conn, "SELECT * FROM history WHERE id = $id AND user_id = '$user_id'");
$data = mysqli_fetch_assoc($query);

if(!$data){
    header("Location: history.php");
    exit;
}

include 'components/header.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body { background: linear-gradient(135deg, #e0f2fe 0%, #f0fdf4 50%, #fdf4ff 100%); background-attachment: fixed; }
    .detail-page { padding-top: 120px; padding-bottom: 80px; }
    
    /* Result Card Premium */
    .result-card {
        background: linear-gradient(135deg, #0ea5e9, #8b5cf6);
        color: white; border-radius: 32px; padding: 40px; margin-bottom: 30px;
        box-shadow: 0 20px 40px rgba(14, 165, 233, 0.2);
    }
    
    /* Section Cards */
    .section-card {
        background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px);
        border-radius: 28px; padding: 30px; margin-bottom: 25px;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }
    .section-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
    
    .detail-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
    .label { color: #64748b; font-weight: 600; font-size: 0.9rem; width: 50%; }
    .value { font-weight: 700; color: #1e293b; }
    
    .btn-back { border-radius: 12px; font-weight: 600; padding: 10px 20px; margin-bottom: 20px; }
</style>

<div class="container detail-page">

    <a href="history.php" class="btn btn-light btn-back shadow-sm">
        <i class="bi bi-arrow-left"></i> Kembali ke History
    </a>

    <!-- Result Banner -->
    <div class="result-card">
        <div style="font-size: 0.9rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 1px;">Prediction Result</div>
        <div class="display-4 fw-bold mb-3"><?= htmlspecialchars($data['prediction_result']); ?></div>
        <div class="d-flex gap-4">
            <div><i class="bi bi-shield-check"></i> Confidence: <strong><?= htmlspecialchars($data['confidence'] ?? '-'); ?>%</strong></div>
            <div><i class="bi bi-calendar3"></i> <?= date('d F Y, H:i', strtotime($data['created_at'])); ?></div>
        </div>
    </div>

    <div class="row">
        <!-- Gaming & Profile -->
        <div class="col-lg-6">
            <div class="section-card">
                <div class="section-title"><i class="bi bi-controller"></i> Gaming Profile</div>
                <table class="detail-table">
                    <tr><td class="label">Gender</td><td class="value"><?= htmlspecialchars($data['gender']); ?></td></tr>
                    <tr><td class="label">Daily Hours</td><td class="value"><?= htmlspecialchars($data['daily_gaming_hours']); ?> Hours</td></tr>
                    <tr><td class="label">Game Genre</td><td class="value"><?= htmlspecialchars($data['game_genre']); ?></td></tr>
                    <tr><td class="label">Primary Game</td><td class="value"><?= htmlspecialchars($data['primary_game']); ?></td></tr>
                    <tr><td class="label">Platform</td><td class="value"><?= htmlspecialchars($data['gaming_platform']); ?></td></tr>
                    <tr><td class="label">Years Active</td><td class="value"><?= htmlspecialchars($data['years_gaming']); ?> Years</td></tr>
                    <tr><td class="label">Monthly Spend</td><td class="value">$<?= htmlspecialchars($data['monthly_game_spending_usd']); ?></td></tr>
                </table>
            </div>
        </div>

        <!-- Health & Sleep -->
        <div class="col-lg-6">
            <div class="section-card">
                <div class="section-title"><i class="bi bi-moon-stars"></i> Sleep & Physical Health</div>
                <table class="detail-table">
                    <tr><td class="label">Sleep Hours</td><td class="value"><?= htmlspecialchars($data['sleep_hours']); ?></td></tr>
                    <tr><td class="label">Quality</td><td class="value"><?= htmlspecialchars($data['sleep_quality']); ?></td></tr>
                    <tr><td class="label">Disruption</td><td class="value"><?= htmlspecialchars($data['sleep_disruption_frequency']); ?></td></tr>
                    <tr><td class="label">Eye Strain</td><td class="value"><?= htmlspecialchars($data['eye_strain']); ?></td></tr>
                    <tr><td class="label">Back/Neck Pain</td><td class="value"><?= htmlspecialchars($data['back_neck_pain']); ?></td></tr>
                    <tr><td class="label">Weight Change</td><td class="value"><?= htmlspecialchars($data['weight_change_kg']); ?> Kg</td></tr>
                    <tr><td class="label">Weekly Exercise</td><td class="value"><?= htmlspecialchars($data['exercise_hours_weekly']); ?> Hours</td></tr>
                </table>
            </div>
        </div>

        <!-- Academic & Mental -->
        <div class="col-lg-6">
            <div class="section-card">
                <div class="section-title"><i class="bi bi-book"></i> Academic & Productivity</div>
                <table class="detail-table">
                    <tr><td class="label">Performance</td><td class="value"><?= htmlspecialchars($data['academic_work_performance']); ?></td></tr>
                    <tr><td class="label">GPA</td><td class="value"><?= htmlspecialchars($data['grades_gpa']); ?></td></tr>
                    <tr><td class="label">Productivity</td><td class="value"><?= htmlspecialchars($data['work_productivity_score']); ?>/10</td></tr>
                </table>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="section-card">
                <div class="section-title"><i class="bi bi-brain"></i> Mental & Social Life</div>
                <table class="detail-table">
                    <tr><td class="label">Mood State</td><td class="value"><?= htmlspecialchars($data['mood_state']); ?></td></tr>
                    <tr><td class="label">Mood Swing</td><td class="value"><?= htmlspecialchars($data['mood_swing_frequency']); ?></td></tr>
                    <tr><td class="label">Withdrawal</td><td class="value"><?= htmlspecialchars($data['withdrawal_symptoms']); ?></td></tr>
                    <tr><td class="label">Continued w/ Problems</td><td class="value"><?= htmlspecialchars($data['continued_despite_problems']); ?></td></tr>
                    <tr><td class="label">Loss of Interests</td><td class="value"><?= htmlspecialchars($data['loss_of_other_interests']); ?></td></tr>
                    <tr><td class="label">Social Isolation</td><td class="value"><?= htmlspecialchars($data['social_isolation_score']); ?>/10</td></tr>
                    <tr><td class="label">Face-to-Face Social</td><td class="value"><?= htmlspecialchars($data['face_to_face_social_hours_weekly']); ?> hrs</td></tr>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include 'components/footer.php'; ?>