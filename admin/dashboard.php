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

$total_users = isset($conn) ? mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM users")
)['total'] : 0;

$total_history = isset($conn) ? mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM history")
)['total'] : 0;

$accuracy = "85.04";

include '../components/header.php';
?>

<style>
.admin-page{
    min-height:100vh;
    background:#f8fafc;
    padding-top:130px;
    padding-bottom:50px;
}

.admin-title{
    font-size:42px;
    font-weight:800;
    margin-bottom:10px;
}

.admin-subtitle{
    color:#64748b;
    margin-bottom:40px;
}

.stat-card{
    background:white;
    border-radius:24px;
    padding:30px;
    box-shadow:0 10px 30px rgba(0,0,0,.05);
    height:100%;
    transition:.3s;
}

.stat-card:hover{
    transform:translateY(-5px);
}

.stat-number{
    font-size:38px;
    font-weight:800;
    color:#7c3aed;
}

.stat-label{
    color:#64748b;
    margin-top:10px;
}

.menu-card{
    background:white;
    border-radius:24px;
    padding:30px;
    text-decoration:none;
    color:#111827;
    display:block;
    height:100%;
    box-shadow:0 10px 30px rgba(0,0,0,.05);
    transition:.3s;
}

.menu-card:hover{
    transform:translateY(-5px);
    color:#7c3aed;
}

.menu-icon{
    font-size:42px;
    margin-bottom:15px;
}

.menu-title{
    font-size:20px;
    font-weight:700;
}

.menu-desc{
    color:#64748b;
    margin-top:10px;
}
</style>

<div class="admin-page">

<div class="container">

    <h1 class="admin-title">
        Admin Dashboard
    </h1>

    <p class="admin-subtitle">
        Kelola pengguna, data history, dan model machine learning.
    </p>

    <div class="row g-4 mb-5">

            <div class="col-md-6">            <div class="stat-card">
                <div class="stat-number">
                    <?= $total_users ?>
                </div>
                <div class="stat-label">
                    Total Users
                </div>
            </div>
        </div>

            <div class="col-md-6">            <div class="stat-card">
                <div class="stat-number">
                    <?= $total_history ?>
                </div>
                <div class="stat-label">
                    Total History
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4">

        <div class="col-md-4">

            <a href="users.php" class="menu-card">

                <div class="menu-icon">
                    👥
                </div>

                <div class="menu-title">
                    Kelola User
                </div>

                <div class="menu-desc">
                    Melihat dan mengelola akun pengguna.
                </div>

            </a>

        </div>

        <div class="col-md-4">

            <a href="download_history.php" class="menu-card">

                <div class="menu-icon">
                    📥
                </div>

                <div class="menu-title">
                    Download History
                </div>

                <div class="menu-desc">
                    Export data history sebagai bahan retraining.
                </div>

            </a>

        </div>

        <div class="col-md-4">

            <a href="update_model.php" class="menu-card">

                <div class="menu-icon">
                    🚀
                </div>

                <div class="menu-title">
                    Update Model
                </div>

                <div class="menu-desc">
                    Retraining model menggunakan data terbaru.
                </div>

            </a>

        </div>

    </div>

</div>

</div>

<?php include '../components/footer.php'; ?>

