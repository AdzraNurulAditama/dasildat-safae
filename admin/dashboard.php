<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");   
    exit;
}

include '../config/database.php';

// Mengambil total users
$total_users = isset($conn) ? mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM users")
)['total'] : 0;

// Mengambil total history
$total_history = isset($conn) ? mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM history")
)['total'] : 0;

// Mengambil last updated dari tabel history berdasarkan created_at terbaru
$last_update_data = isset($conn) ? mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT MAX(created_at) as last_date FROM history")
) : null;
$last_update = ($last_update_data && $last_update_data['last_date']) ? date('d M Y', strtotime($last_update_data['last_date'])) : 'N/A';

include '../components/header_admin.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
/* CSS tetap sama seperti sebelumnya */
body { background-color: #f4f7fb; background-image: radial-gradient(at 0% 0%, rgba(14, 165, 233, 0.05) 0px, transparent 50%), radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.05) 0px, transparent 50%); background-attachment: fixed; color: #1e293b; }
.admin-page { min-height: calc(100vh - 80px); padding-top: 50px; padding-bottom: 80px; }
@keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
.animate-1 { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.1s forwards; opacity: 0; }
.animate-2 { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards; opacity: 0; }
.animate-3 { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.3s forwards; opacity: 0; }
.date-badge { display: inline-flex; align-items: center; gap: 8px; background: #ffffff; color: #64748b; padding: 8px 16px; border-radius: 999px; font-size: 13px; font-weight: 600; box-shadow: 0 2px 10px rgba(0,0,0,0.02); margin-bottom: 20px; }
.date-badge i { color: #0ea5e9; }
.admin-title { font-size: 44px; font-weight: 800; letter-spacing: -1px; color: #0f172a; margin-bottom: 8px; }
.admin-subtitle { color: #64748b; font-size: 1.1rem; }
.stat-card { background: #ffffff; border-radius: 24px; padding: 32px; height: 100%; position: relative; overflow: hidden; border: 1px solid rgba(255,255,255,0.8); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.04); display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.3s ease, box-shadow 0.3s ease; }
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px -10px rgba(14, 165, 233, 0.08); }
.stat-card::after { content: ''; position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; border-radius: 50%; opacity: 0.1; z-index: 0; }
.bg-blob-blue::after { background: #0ea5e9; }
.bg-blob-purple::after { background: #8b5cf6; }
.bg-blob-green::after { background: #10b981; }
.stat-header { display: flex; justify-content: space-between; align-items: center; z-index: 1; }
.stat-label { color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }
.stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; }
.icon-blue { background: rgba(14, 165, 233, 0.1); color: #0ea5e9; }
.icon-purple { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
.icon-green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.stat-number { font-size: 46px; font-weight: 800; z-index: 1; margin-top: 20px; background: linear-gradient(135deg, #0f172a 0%, #334155 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.menu-card { background: #ffffff; border-radius: 24px; padding: 35px 30px; height: 100%; text-decoration: none; display: block; position: relative; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 10px 30px -10px rgba(0,0,0,0.03); transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
.menu-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px -10px rgba(14, 165, 233, 0.12); border-color: rgba(14, 165, 233, 0.2); }
.menu-icon-large { font-size: 36px; color: #0f172a; margin-bottom: 24px; transition: color 0.3s ease; }
.menu-card:hover .menu-icon-large { color: #0ea5e9; }
.menu-title { font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 10px; }
.menu-desc { color: #64748b; font-size: 0.95rem; line-height: 1.6; margin-bottom: 24px; }
.menu-arrow { display: inline-flex; align-items: center; gap: 8px; font-size: 0.9rem; font-weight: 700; color: #0ea5e9; }
.menu-card:hover .menu-arrow i { transform: translateX(6px); }
</style>

<div class="admin-page">
<div class="container">

    <div class="row align-items-center mb-5 animate-1">
        <div class="col-lg-8">
            <div class="date-badge">
                <i class="bi bi-calendar3"></i> 
                <?= date('l, d F Y') ?>
            </div>
            <h1 class="admin-title">Welcome back, Admin.</h1>
            <p class="admin-subtitle">Berikut adalah ringkasan sistem Machine Learning dan aktivitas pengguna hari ini.</p>
        </div>
    </div>

    <div class="row g-4 mb-5 animate-2">
        <div class="col-md-4">
            <div class="stat-card bg-blob-purple">
                <div class="stat-header">
                    <span class="stat-label">Total Users</span>
                    <div class="stat-icon icon-purple"><i class="bi bi-people-fill"></i></div>
                </div>
                <div class="stat-number"><?= number_format($total_users) ?></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card bg-blob-blue">
                <div class="stat-header">
                    <span class="stat-label">Predictions</span>
                    <div class="stat-icon icon-blue"><i class="bi bi-clock-history"></i></div>
                </div>
                <div class="stat-number"><?= number_format($total_history) ?></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card bg-blob-green">
                <div class="stat-header">
                    <span class="stat-label">Last Updated</span>
                    <div class="stat-icon icon-green">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
                <div class="stat-number" style="font-size: 28px; margin-top: 30px;">
                    <?= $last_update ?>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-3 mb-4 animate-3">
        <h5 class="fw-bold mb-0 text-dark">Manajemen Sistem</h5>
        <div style="height: 1px; flex: 1; background: rgba(0,0,0,0.05);"></div>
    </div>

    <div class="row g-4 animate-3">
        <div class="col-md-4">
            <a href="users.php" class="menu-card">
                <div class="menu-icon-large"><i class="bi bi-person-bounding-box"></i></div>
                <div class="menu-title">Kelola User</div>
                <div class="menu-desc">Melihat daftar, mengedit detail, dan mengatur akses seluruh akun pengguna sistem.</div>
                <div class="menu-arrow mt-auto">Open Manager <i class="bi bi-arrow-right"></i></div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="history_admin.php" class="menu-card">
                <div class="menu-icon-large"><i class="bi bi-database-check"></i></div>
                <div class="menu-title">Data History</div>
                <div class="menu-desc">Memantau riwayat input prediksi dan hasil analisis psikologis dari seluruh pengguna.</div>
                <div class="menu-arrow mt-auto">View Logs <i class="bi bi-arrow-right"></i></div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="update_model.php" class="menu-card">
                <div class="menu-icon-large"><i class="bi bi-cpu"></i></div>
                <div class="menu-title">Retrain AI Model</div>
                <div class="menu-desc">Melatih ulang dan memperbarui parameter model Machine Learning dengan data terbaru.</div>
                <div class="menu-arrow mt-auto">System Config <i class="bi bi-arrow-right"></i></div>
            </a>
        </div>
    </div>

</div>
</div>

<?php include '../components/footer_admin.php'; ?>