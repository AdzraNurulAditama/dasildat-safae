```php
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

if(isset($_POST['update_model'])){

    $output = shell_exec(
        'python ../retrain.py 2>&1'
    );

    $message = $output;
}

$total_history = 0;
if (isset($conn)) {
    $total_history = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT COUNT(*) as total FROM history"
        )
    )['total'];
} else {
    $message = "Database connection not established.";
}

include '../components/header.php';
?>

<style>
.admin-page{
    min-height:100vh;
    padding-top:130px;
    padding-bottom:50px;
    background:#f8fafc;
}

.card-admin{
    background:white;
    border-radius:24px;
    padding:35px;
    box-shadow:0 10px 30px rgba(0,0,0,.05);
}

.stat-box{
    background:#f8fafc;
    border-radius:18px;
    padding:20px;
    text-align:center;
}

.stat-number{
    font-size:32px;
    font-weight:800;
    color:#7c3aed;
}

.btn-update{
    border:none;
    padding:15px 30px;
    border-radius:16px;
    background:linear-gradient(
        135deg,
        #7c3aed,
        #ec4899
    );
    color:white;
    font-weight:700;
}

.output-box{
    margin-top:25px;
    background:#111827;
    color:#22c55e;
    padding:20px;
    border-radius:16px;
    font-family:monospace;
    white-space:pre-wrap;
}
</style>

<div class="admin-page">

<div class="container">

    <h1 class="mb-4">
        🚀 Update Model
    </h1>

    <div class="card-admin">

        <div class="row mb-4">

            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-number">
                        <?= $total_history ?>
                    </div>
                    <div>
                        Total History
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-number">
                        1000+
                    </div>
                    <div>
                        Dataset Awal
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-number">
                        <?= 1000 + $total_history ?>
                    </div>
                    <div>
                        Total Data Training
                    </div>
                </div>
            </div>

        </div>

        <form method="POST">

            <button
                type="submit"
                name="update_model"
                class="btn-update">
                Update Model Sekarang
            </button>

        </form>

        <?php if(!empty($message)): ?>

            <div class="output-box">
<?= htmlspecialchars($message) ?>
            </div>

        <?php endif; ?>

    </div>

</div>

</div>

<?php include '../components/footer.php'; ?>

