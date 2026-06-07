<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}

include __DIR__ . '/../config/database.php';

if (!isset($conn)) {
    die('Database connection not established.');
}

// Security: Cast to int to prevent basic SQL Injection
$id = (int)$_GET['id'];

$query = mysqli_query(
    $conn,
    "SELECT h.*, u.username 
     FROM history h
     LEFT JOIN users u ON h.user_id = u.id
     WHERE h.id = $id"
);

$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan, kembalikan ke halaman sebelumnya
if (!$data) {
    header("Location: history_admin.php");
    exit;
}

include '../components/header_admin.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
/* 1. Global Background (Menyesuaikan dengan Dashboard) */
body {
    background-color: #f4f7fb;
    background-image: 
        radial-gradient(at 0% 0%, rgba(14, 165, 233, 0.05) 0px, transparent 50%),
        radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.05) 0px, transparent 50%);
    background-attachment: fixed;
    color: #1e293b;
}

.admin-page-wrapper {
    min-height: calc(100vh - 80px);
    padding-top: 100px;
    padding-bottom: 80px;
}

/* 2. Animasi Masuk */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-up { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

/* 3. Tombol Back & Header */
.btn-back-custom {
    background: #ffffff;
    color: #0f172a;
    border: 1px solid rgba(0,0,0,0.05);
    width: 44px; height: 44px;
    border-radius: 50%;
    display: flex; justify-content: center; align-items: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
    transition: all 0.3s ease;
    text-decoration: none;
}
.btn-back-custom:hover {
    background: #0ea5e9;
    color: #ffffff;
    transform: translateX(-3px);
    box-shadow: 0 8px 15px rgba(14, 165, 233, 0.2);
}

.page-title {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: -0.5px;
    color: #0f172a;
}

/* 4. Detail Cards */
.detail-card {
    background: #ffffff;
    border-radius: 24px;
    border: 1px solid rgba(255,255,255,0.8);
    box-shadow: 0 10px 40px -10px rgba(0,0,0,0.03);
    height: 100%;
    overflow: hidden;
}

.detail-header {
    background: rgba(248, 250, 252, 0.5);
    padding: 20px 24px;
    border-bottom: 1px solid rgba(0,0,0,0.03);
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 12px;
}

.detail-header-icon {
    width: 32px; height: 32px;
    background: rgba(14, 165, 233, 0.1);
    color: #0ea5e9;
    border-radius: 8px;
    display: flex; justify-content: center; align-items: center;
    font-size: 1.1rem;
}

/* 5. Custom List Group */
.custom-list-item {
    padding: 16px 24px;
    border-bottom: 1px dashed rgba(0,0,0,0.06);
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background-color 0.2s ease;
}
.custom-list-item:last-child {
    border-bottom: none;
}
.custom-list-item:hover {
    background-color: rgba(14, 165, 233, 0.01);
}
.list-label {
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}
.list-value {
    color: #0f172a;
    font-weight: 600;
    text-align: right;
}
</style>

<div class="admin-page-wrapper">
<div class="container">

    <div class="d-flex align-items-center mb-4 animate-fade-up">
        <a href="history_admin.php" class="btn-back-custom me-3">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-1" style="background: rgba(14,165,233,0.1); color: #0ea5e9; font-size: 0.75rem; font-weight: 700; letter-spacing: 1px;">
                <i class="bi bi-hash"></i> RECORD <?= htmlspecialchars($data['id']) ?>
            </div>
            <h2 class="page-title mb-0">Prediction Detail</h2>
        </div>
    </div>

    <?php
    // Logika Warna Dinamis berdasarkan Prediksi
    $res = $data['prediction_result'];
    $themeColor = "#10b981"; // Hijau
    $themeBg = "rgba(16, 185, 129, 0.1)";
    $iconClass = "bi-shield-check";

    if($res == 'High Risk') {
        $themeColor = "#ef4444"; // Merah
        $themeBg = "rgba(239, 68, 68, 0.1)";
        $iconClass = "bi-exclamation-triangle-fill";
    } elseif($res == 'Moderate Risk') {
        $themeColor = "#f59e0b"; // Oranye
        $themeBg = "rgba(245, 158, 11, 0.1)";
        $iconClass = "bi-shield-exclamation";
    }
    ?>

    <div class="card shadow-sm border-0 rounded-4 mb-4 animate-fade-up" style="background: <?= $themeBg ?>; border-left: 6px solid <?= $themeColor ?> !important; animation-delay: 0.1s;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0 border-end" style="border-color: rgba(0,0,0,0.05) !important;">
                    <span class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Prediction Result</span>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <i class="bi <?= $iconClass ?> fs-4" style="color: <?= $themeColor ?>;"></i>
                        <h3 class="fw-bold mb-0" style="color: <?= $themeColor ?>;"><?= htmlspecialchars($res) ?></h3>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0 border-end" style="border-color: rgba(0,0,0,0.05) !important;">
                    <span class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Confidence Score</span>
                    <h3 class="fw-bold text-dark mb-0 mt-1"><?= htmlspecialchars($data['confidence']) ?><span style="font-size: 1.2rem; color: #64748b;">%</span></h3>
                </div>
                <div class="col-md-4">
                    <span class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Engine Algorithm</span>
                    <h4 class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-cpu text-secondary me-2"></i><?= htmlspecialchars($data['algorithm']) ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 animate-fade-up" style="animation-delay: 0.2s;">
        
        <div class="col-md-6">
            <div class="detail-card">
                <div class="detail-header">
                    <div class="detail-header-icon"><i class="bi bi-person-badge"></i></div>
                    User Information
                </div>
                <div class="card-body p-0">
                    <div class="custom-list-item">
                        <span class="list-label"><i class="bi bi-person text-secondary"></i> Name</span>
                        <span class="list-value"><?= htmlspecialchars($data['username'] ?? 'Unknown') ?></span>
                    </div>
                    <div class="custom-list-item">
                        <span class="list-label"><i class="bi bi-calendar3 text-secondary"></i> Assessment Date</span>
                        <span class="list-value"><?= date("d M Y, H:i", strtotime($data['created_at'])) ?></span>
                    </div>
                    <div class="custom-list-item">
                        <span class="list-label"><i class="bi bi-person-vcard text-secondary"></i> Age / Gender</span>
                        <span class="list-value"><?= htmlspecialchars($data['age']) ?> yrs <span class="text-muted mx-1">|</span> <?= htmlspecialchars($data['gender']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-card">
                <div class="detail-header">
                    <div class="detail-header-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;"><i class="bi bi-controller"></i></div>
                    Gaming Profile
                </div>
                <div class="card-body p-0">
                    <div class="custom-list-item">
                        <span class="list-label">Primary Game</span>
                        <span class="list-value"><?= htmlspecialchars($data['primary_game']) ?></span>
                    </div>
                    <div class="custom-list-item">
                        <span class="list-label">Genre / Platform</span>
                        <span class="list-value"><?= htmlspecialchars($data['game_genre']) ?> <span class="text-muted mx-1">|</span> <?= htmlspecialchars($data['gaming_platform']) ?></span>
                    </div>
                    <div class="custom-list-item">
                        <span class="list-label">Daily Hours / Years Active</span>
                        <span class="list-value"><?= htmlspecialchars($data['daily_gaming_hours']) ?> hrs <span class="text-muted mx-1">|</span> <?= htmlspecialchars($data['years_gaming']) ?> yrs</span>
                    </div>
                    <div class="custom-list-item">
                        <span class="list-label">Monthly Spending</span>
                        <span class="list-value text-danger fw-bold">$<?= htmlspecialchars($data['monthly_game_spending_usd']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-card">
                <div class="detail-header">
                    <div class="detail-header-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;"><i class="bi bi-briefcase"></i></div>
                    Academic & Work
                </div>
                <div class="card-body p-0">
                    <div class="custom-list-item">
                        <span class="list-label">Performance</span>
                        <span class="list-value"><?= htmlspecialchars($data['academic_work_performance']) ?></span>
                    </div>
                    <div class="custom-list-item">
                        <span class="list-label">GPA</span>
                        <span class="list-value"><?= htmlspecialchars($data['grades_gpa']) ?></span>
                    </div>
                    <div class="custom-list-item">
                        <span class="list-label">Productivity Score</span>
                        <span class="list-value"><?= htmlspecialchars($data['work_productivity_score']) ?> <span class="text-muted fw-normal">/10</span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-card">
                <div class="detail-header">
                    <div class="detail-header-icon" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;"><i class="bi bi-moon-stars"></i></div>
                    Mental Health & Sleep
                </div>
                <div class="card-body p-0">
                    <div class="custom-list-item">
                        <span class="list-label">Mood State / Swings</span>
                        <span class="list-value"><?= htmlspecialchars($data['mood_state']) ?> <span class="text-muted fw-normal">(<?= htmlspecialchars($data['mood_swing_frequency']) ?>)</span></span>
                    </div>
                    <div class="custom-list-item">
                        <span class="list-label">Withdrawal Symptoms</span>
                        <span class="list-value"><?= htmlspecialchars($data['withdrawal_symptoms']) ?></span>
                    </div>
                    <div class="custom-list-item">
                        <span class="list-label">Sleep Quality / Hours</span>
                        <span class="list-value"><?= htmlspecialchars($data['sleep_quality']) ?> <span class="text-muted fw-normal">(<?= htmlspecialchars($data['sleep_hours']) ?> hrs)</span></span>
                    </div>
                    <div class="custom-list-item">
                        <span class="list-label">Sleep Disruption</span>
                        <span class="list-value"><?= htmlspecialchars($data['sleep_disruption_frequency']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="detail-card">
                <div class="detail-header">
                    <div class="detail-header-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i class="bi bi-activity"></i></div>
                    Physical & Social Impact
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-6 border-end-md" style="border-color: rgba(0,0,0,0.05) !important;">
                            <div class="custom-list-item">
                                <span class="list-label">Continued Despite Problems</span>
                                <span class="list-value"><?= htmlspecialchars($data['continued_despite_problems']) ?></span>
                            </div>
                            <div class="custom-list-item">
                                <span class="list-label">Loss of Other Interests</span>
                                <span class="list-value"><?= htmlspecialchars($data['loss_of_other_interests']) ?></span>
                            </div>
                            <div class="custom-list-item border-bottom-0">
                                <span class="list-label">Social Isolation Score</span>
                                <span class="list-value"><?= htmlspecialchars($data['social_isolation_score']) ?> <span class="text-muted fw-normal">/10</span></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-list-item">
                                <span class="list-label">Eye Strain</span>
                                <span class="list-value"><?= htmlspecialchars($data['eye_strain']) ?></span>
                            </div>
                            <div class="custom-list-item">
                                <span class="list-label">Back/Neck Pain</span>
                                <span class="list-value"><?= htmlspecialchars($data['back_neck_pain']) ?></span>
                            </div>
                            <div class="custom-list-item border-bottom-0">
                                <span class="list-label">Weight Change / Exercise</span>
                                <span class="list-value"><?= htmlspecialchars($data['weight_change_kg']) ?> kg <span class="text-muted mx-1">|</span> <?= htmlspecialchars($data['exercise_hours_weekly']) ?> hrs</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
</div>

<?php include '../components/footer_admin.php'; ?>