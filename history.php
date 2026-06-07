<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
include 'config/database.php';

// 1. Cek Login
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

// 2. Logika Hapus History (Aman: Hanya hapus milik user sendiri)
if(isset($_GET['delete']) && $_GET['delete'] == 'true'){
    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "DELETE FROM history WHERE user_id = '$user_id'");
    header("Location: history.php");
    exit;
}

// 3. Query Data
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM history WHERE user_id = '$user_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);

include 'components/header.php';
?>

<!-- Google Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&family=Instrument+Serif&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    /* 1. Page Background (Sama dengan Index) */
    body { 
        background: linear-gradient(135deg, #e0f2fe 0%, #f0fdf4 50%, #fdf4ff 100%);
        color: #334155; 
        background-attachment: fixed;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .history-page-wrapper {
        min-height: calc(100vh - 80px);
        padding-top: 140px;
        padding-bottom: 80px;
    }

    /* 2. Header Style */
    .history-header { text-align: center; margin-bottom: 50px; }
    .history-title {
        font-family: 'Instrument Serif', serif;
        font-size: 64px;
        font-weight: 800;
        margin-bottom: 15px;
        color: #0f172a;
    }
    .history-title em { color: #8b5cf6; font-style: italic; }
    .history-subtitle { color: #64748b; font-size: 1.1rem; }

    /* 3. Stats Grid (Zen Bento Cards) */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
    .stat-card-zen {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        padding: 24px;
        border: 1px solid rgba(255, 255, 255, 0.8);
        text-align: center;
        transition: transform 0.3s ease;
    }
    .stat-card-zen:hover { transform: translateY(-5px); }
    .stat-value { font-size: 32px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
    .stat-label { color: #64748b; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; }

    /* 4. Main History Card */
    .history-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        border-radius: 32px;
        padding: 40px;
        border: 1px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 15px 40px rgba(0,0,0,0.05);
    }

    /* 5. Table Custom */
    .table-custom { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-custom thead th { color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; padding: 20px; border-bottom: 1px solid rgba(0,0,0,0.05); }
    .table-custom tbody td { padding: 20px; border-bottom: 1px dashed rgba(0,0,0,0.05); }
    
    .badge-risk {
        padding: 6px 14px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .badge-high { background: rgba(239, 68, 68, 0.1); color: #dc2626; }
    .badge-moderate { background: rgba(245, 158, 11, 0.1); color: #d97706; }
    .badge-low { background: rgba(16, 185, 129, 0.1); color: #059669; }

    .btn-detail-pill {
        background: #0f172a; color: #fff; padding: 8px 20px; border-radius: 12px;
        font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: 0.3s;
    }
    .btn-detail-pill:hover { background: #334155; color: white; }
    .btn-clear {
        color: #ef4444; background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.1);
        font-weight: 600; padding: 10px 20px; border-radius: 14px; text-decoration: none; transition: 0.3s;
    }
    .btn-clear:hover { background: #ef4444; color: white; }

    @media(max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<?php
$total_predictions = mysqli_num_rows($result);
$high_count = 0; $moderate_count = 0; $low_count = 0;
while($temp = mysqli_fetch_assoc($result)){
    $pred = strtolower($temp['prediction_result'] ?? '');
    if(str_contains($pred, 'high')) $high_count++;
    elseif(str_contains($pred, 'moderate')) $moderate_count++;
    else $low_count++;
}
mysqli_data_seek($result, 0);
?>

<div class="history-page-wrapper">
<div class="container">

    <div class="history-header">
        <h1 class="history-title">Prediction <em>History</em></h1>
        <p class="history-subtitle">Tinjau kembali hasil diagnosis kecanduan game kamu.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card-zen"><div class="stat-value"><?= $total_predictions ?></div><div class="stat-label">Predictions</div></div>
        <div class="stat-card-zen"><div class="stat-value text-danger"><?= $high_count ?></div><div class="stat-label">High Risk</div></div>
        <div class="stat-card-zen"><div class="stat-value text-warning"><?= $moderate_count ?></div><div class="stat-label">Moderate</div></div>
        <div class="stat-card-zen"><div class="stat-value text-success"><?= $low_count ?></div><div class="stat-label">Low Risk</div></div>
    </div>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr><th>#</th><th>Age/Gender</th><th>Gaming Hours</th><th>Sleep</th><th>Risk Result</th><th>Action</th></tr>
                </thead>
                <tbody>
                <?php if(mysqli_num_rows($result) > 0): 
                    $no = 1;
                    while($row = mysqli_fetch_assoc($result)):
                        $prediction = $row['prediction_result'] ?? 'Low Risk';
                        $badge = (str_contains(strtolower($prediction), 'high')) ? 'badge-high' : ((str_contains(strtolower($prediction), 'moderate')) ? 'badge-moderate' : 'badge-low');
                ?>
                    <tr>
                        <td class="fw-bold text-muted">#<?= str_pad($no++, 2, '0', STR_PAD_LEFT); ?></td>
                        <td><?= $row['age']; ?> / <?= $row['gender']; ?></td>
                        <td class="fw-bold"><?= $row['daily_gaming_hours']; ?> hrs</td>
                        <td class="fw-bold"><?= $row['sleep_hours']; ?> hrs</td>
                        <td><span class="badge-risk <?= $badge ?>"><?= $prediction ?></span></td>
                        <td><a href="detail_history.php?id=<?= $row['id']; ?>" class="btn-detail-pill">View Detail</a></td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<?php include 'components/footer.php'; ?>