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

require_once __DIR__ . '/../config/database.php';

$message = "";
$min_data = 50;

// Ambil total history
$total_history = 0;
if (isset($conn)) {
    $total_history = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT COUNT(*) as total FROM history")
    )['total'];
}

$is_eligible = $total_history >= $min_data;

// Proses Retraining
if(isset($_POST['update_model'])){
    if (!$is_eligible) {
        $message = "Error: Data history belum mencukupi (Minimal $min_data data).";
    } else {
        $output = shell_exec('python ../retrain.py 2>&1');
        $message = $output;
    }
}

include '../components/header_admin.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
body {
    background-color: #f4f7fb;
    background-image: 
        radial-gradient(at 0% 0%, rgba(14, 165, 233, 0.05) 0px, transparent 50%),
        radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.05) 0px, transparent 50%);
    background-attachment: fixed;
    color: #1e293b;
}

.admin-page-wrapper { min-height: calc(100vh - 80px); padding-top: 100px; padding-bottom: 80px; }
.animate-fade-up { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
@keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

.glass-card { background: #ffffff; border-radius: 24px; border: 1px solid rgba(255,255,255,0.8); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.04); padding: 40px; }
.stat-box { background: #ffffff; border-radius: 20px; padding: 24px; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 4px 15px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 16px; }
.stat-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; justify-content: center; align-items: center; font-size: 24px; }
.icon-blue { background: rgba(14, 165, 233, 0.1); color: #0ea5e9; }
.icon-purple { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
.icon-green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.stat-info h3 { font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 2px; }
.stat-info p { font-size: 0.85rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0; }

.btn-update-premium { background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%); color: #ffffff; border: none; padding: 16px 32px; border-radius: 16px; font-weight: 700; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s ease; box-shadow: 0 10px 20px rgba(14, 165, 233, 0.25); cursor: pointer; }
.btn-update-premium:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(14, 165, 233, 0.35); }
.btn-update-premium:disabled { background: #94a3b8 !important; cursor: not-allowed; opacity: 0.7; }

.terminal-window { background: #0f172a; border-radius: 16px; overflow: hidden; margin-top: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
.terminal-header { background: #1e293b; padding: 12px 20px; display: flex; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05); }
.terminal-body { padding: 24px; color: #34d399; font-family: 'Courier New', monospace; font-size: 0.9rem; line-height: 1.6; white-space: pre-wrap; max-height: 400px; overflow-y: auto; }
@keyframes spin { 100% { transform: rotate(360deg); } }
.spin { display: inline-block; animation: spin 1s linear infinite; }
</style>

<div class="admin-page-wrapper">
<div class="container">
    <div class="d-flex flex-column mb-4 animate-fade-up">
        <h2 class="fw-bold">Update Model</h2>
        <p class="text-muted">Latih ulang model dengan data history terbaru.</p>
    </div>

    <div class="glass-card animate-fade-up" style="animation-delay: 0.1s;">
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-icon icon-blue"><i class="bi bi-cloud-plus-fill"></i></div>
                    <div class="stat-info"><h3><?= number_format($total_history) ?></h3><p>New Data (History)</p></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-icon icon-purple"><i class="bi bi-hdd-stack-fill"></i></div>
                    <div class="stat-info"><h3>1,000+</h3><p>Base Dataset</p></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box" style="background: linear-gradient(135deg, rgba(16,185,129,0.05), transparent);">
                    <div class="stat-icon icon-green"><i class="bi bi-database-fill-check"></i></div>
                    <div class="stat-info">
                        <h3 class="text-success"><?= number_format(1000 + $total_history) ?></h3>
                        <p class="text-success">Total Training Data</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <?php if (!$is_eligible): ?>
                <div class="alert alert-warning d-inline-block px-4 py-2 mb-4">
                    <i class="bi bi-exclamation-triangle-fill"></i> Data history saat ini: <strong><?= $total_history ?>/<?= $min_data ?></strong>. 
                    Retraining baru bisa dilakukan setelah mencapai minimal 50 data.
                </div>
            <?php endif; ?>

            <form method="POST">
                <button type="submit" name="update_model" class="btn-update-premium" <?= !$is_eligible ? 'disabled' : '' ?> onclick="this.innerHTML='<i class=\'bi bi-arrow-repeat spin\'></i> Memproses...';">
                    <i class="bi bi-rocket-takeoff"></i> <?= $is_eligible ? 'Update Model Sekarang' : 'Data Belum Mencukupi' ?>
                </button>
            </form>
        </div>

        <?php if(!empty($message)): ?>
            <div class="terminal-window">
                <div class="terminal-header">
                    <div class="terminal-dots"><div class="dot red"></div><div class="dot yellow"></div><div class="dot green"></div></div>
                    <div class="terminal-title">python_compiler ~ retrain.py</div>
                </div>
                <div class="terminal-body"><?= htmlspecialchars($message) ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>

<?php include '../components/footer_admin.php'; ?>