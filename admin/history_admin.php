<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
$conn = $conn ?? $connection ?? $db ?? $mysqli ?? null;

if (!$conn) {
    die('Database connection not established.');
}

// Logika Hapus Semua Data
if(isset($_POST['delete_all'])) {
    mysqli_query($conn, "TRUNCATE TABLE history");
    header("Location: history_admin.php?status=deleted");
    exit;
}

$query = "
SELECT 
    h.*, 
    u.username 
FROM history h
LEFT JOIN users u ON h.user_id = u.id
ORDER BY h.created_at DESC
";

$result = mysqli_query($conn, $query);

include '../components/header_admin.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
body {
    background-color: #f4f7fb;
    background-image: radial-gradient(at 0% 0%, rgba(14, 165, 233, 0.05) 0px, transparent 50%), radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.05) 0px, transparent 50%);
    background-attachment: fixed;
    color: #1e293b;
}
.admin-page-wrapper { min-height: calc(100vh - 80px); padding-top: 100px; padding-bottom: 80px; }
@keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
.animate-fade-up { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

.page-title { font-size: 36px; font-weight: 800; letter-spacing: -0.5px; color: #0f172a; }
.btn-export { background: #ffffff; color: #0f172a; border: 1px solid rgba(0,0,0,0.1); padding: 10px 20px; border-radius: 12px; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(0,0,0,0.02); text-decoration: none; }
.btn-export:hover { background: #0ea5e9; color: #ffffff; border-color: #0ea5e9; transform: translateY(-2px); }

.glass-table-card { background: #ffffff; border-radius: 24px; border: 1px solid rgba(255,255,255,0.8); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.04); overflow: hidden; }
.table-custom thead th { background: rgba(248, 250, 252, 0.8) !important; color: #64748b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; padding: 18px 24px; }
.table-custom tbody td { padding: 16px 24px; vertical-align: middle; border-bottom: 1px solid rgba(0,0,0,0.03); }
.status-badge { padding: 8px 16px; border-radius: 999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: inline-flex; align-items: center; gap: 6px; }
.status-high { background: rgba(239, 68, 68, 0.1); color: #dc2626; }
.status-moderate { background: rgba(245, 158, 11, 0.1); color: #d97706; }
.status-low { background: rgba(16, 185, 129, 0.1); color: #059669; }
.algo-badge { background: rgba(139, 92, 246, 0.1); color: #7c3aed; padding: 6px 14px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; }
.btn-detail { background: transparent; color: #0ea5e9; border: 1px solid rgba(14, 165, 233, 0.3); padding: 6px 16px; border-radius: 999px; font-size: 0.85rem; font-weight: 600; }
.btn-detail:hover { background: #0ea5e9; color: #ffffff; }
</style>

<div class="admin-page-wrapper">
<div class="container">

    <?php if(isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4 animate-fade-up" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Seluruh data history telah berhasil dihapus.
        </div>
    <?php endif; ?>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3 animate-fade-up">
        <div>
            <h2 class="page-title mb-1">Prediction History</h2>
            <p class="text-muted mb-0">Pantau semua riwayat prediksi dan diagnosis algoritma.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="export_csv.php" class="btn-export"><i class="bi bi-cloud-arrow-down-fill"></i> Export</a>
            <form method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SELURUH riwayat prediksi? Tindakan ini tidak dapat dibatalkan.');">
                <button type="submit" name="delete_all" class="btn-export" style="color: #dc2626; border-color: rgba(220, 38, 38, 0.2);">
                    <i class="bi bi-trash-fill"></i> Hapus Semua
                </button>
            </form>
        </div>
    </div>

    <div class="glass-table-card animate-fade-up" style="animation-delay: 0.1s;">
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th>User Info</th>
                        <th>Date & Time</th>
                        <th>Risk Result</th>
                        <th>Algorithm</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <?php 
                                $res = $row['prediction_result'];
                                $badgeClass = ($res == 'High Risk') ? 'status-high' : (($res == 'Moderate Risk') ? 'status-moderate' : 'status-low');
                            ?>
                            <tr>
                                <td>
                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($row['username'] ?? 'Unknown') ?></h6>
                                    <small class="text-muted">ID: #<?= htmlspecialchars($row['user_id']) ?></small>
                                </td>
                                <td><?= date("d M Y, H:i", strtotime($row['created_at'])) ?> WIB</td>
                                <td><span class="status-badge <?= $badgeClass ?>"><?= htmlspecialchars($res) ?></span></td>
                                <td><span class="algo-badge"><?= htmlspecialchars($row['algorithm']) ?></span></td>
                                <td class="text-end"><a href="history_detail.php?id=<?= urlencode($row['id']) ?>" class="btn-detail">Detail</a></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada data history.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include '../components/footer_admin.php'; ?>