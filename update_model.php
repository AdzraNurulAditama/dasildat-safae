<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: index.php");
    exit;
}

include 'config/database.php';
include 'components/header.php';

$message = "";
$success = false;

$dataset_count = 1000;

$history_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM history");
$history_data = mysqli_fetch_assoc($history_query);
$history_count = $history_data['total'];

$total_data = $dataset_count + $history_count;

if(isset($_POST['update_model'])){

    $output = shell_exec("python retrain.py 2>&1");

    $message = $output;
    $success = true;
}
?>

<style>
.page-update{
    min-height:100vh;
    background:#f8fafc;
    padding-top:140px;
    padding-bottom:60px;
}

.hero-card{
    background:white;
    border-radius:30px;
    padding:50px;
    border:1px solid #e2e8f0;
    box-shadow:0 20px 40px rgba(0,0,0,.05);
}

.hero-title{
    font-size:48px;
    font-weight:800;
    color:#0f172a;
    margin-bottom:15px;
}

.hero-desc{
    color:#64748b;
    font-size:16px;
}

.stat-card{
    background:white;
    border-radius:24px;
    padding:30px;
    text-align:center;
    border:1px solid #e2e8f0;
    box-shadow:0 10px 25px rgba(0,0,0,.04);
    height:100%;
}

.stat-number{
    font-size:42px;
    font-weight:800;
    background:linear-gradient(135deg,#0284c7,#9333ea,#db2777);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.stat-label{
    margin-top:10px;
    color:#64748b;
    font-weight:600;
}

.update-card{
    background:white;
    border-radius:30px;
    padding:50px;
    border:1px solid #e2e8f0;
    box-shadow:0 20px 40px rgba(0,0,0,.05);
}

.btn-update{
    border:none;
    padding:18px 40px;
    border-radius:18px;
    color:white;
    font-weight:700;
    font-size:15px;
    background:linear-gradient(
        135deg,
        #0284c7,
        #9333ea,
        #db2777
    );
    transition:.3s;
}

.btn-update:hover{
    transform:translateY(-3px);
}

.output-box{
    margin-top:25px;
    background:#0f172a;
    color:#e2e8f0;
    padding:25px;
    border-radius:20px;
    overflow:auto;
    max-height:400px;
}

.output-box pre{
    margin:0;
    white-space:pre-wrap;
}

.success-alert{
    background:#ecfdf5;
    border:1px solid #10b981;
    color:#065f46;
    padding:18px;
    border-radius:16px;
    margin-top:25px;
    font-weight:600;
}
</style>

<div class="page-update">

    <div class="container">

        <div class="hero-card mb-4">

            <h1 class="hero-title">
                🚀 Update Machine Learning Model
            </h1>

            <p class="hero-desc mb-0">
                Retrain model menggunakan dataset awal dan data history pengguna terbaru.
                Sistem akan menggabungkan seluruh data yang tersedia lalu melatih ulang model Decision Tree.
            </p>

        </div>

        <div class="row g-4 mb-4">

            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number">
                        <?= $dataset_count ?>
                    </div>
                    <div class="stat-label">
                        Dataset Awal
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number">
                        <?= $history_count ?>
                    </div>
                    <div class="stat-label">
                        Data History
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number">
                        <?= $total_data ?>
                    </div>
                    <div class="stat-label">
                        Total Training Data
                    </div>
                </div>
            </div>

        </div>

        <div class="update-card">

            <h3 class="fw-bold mb-3">
                Retraining Model
            </h3>

            <p class="text-muted">
                Klik tombol di bawah untuk memperbarui model menggunakan dataset terbaru.
            </p>

            <form method="POST">

                <button
                    type="submit"
                    name="update_model"
                    class="btn-update">

                    🚀 Update Model

                </button>

            </form>

            <?php if($success): ?>

                <div class="success-alert">
                    ✅ Model berhasil diperbarui menggunakan data history terbaru.
                </div>

                <div class="output-box">
                    <pre><?= htmlspecialchars($message) ?></pre>
                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php include 'components/footer.php'; ?>
```
