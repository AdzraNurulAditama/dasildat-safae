<?php
session_start();

require_once __DIR__ . '/../config/database.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}

/* =========================
HAPUS USER
========================= */

if(isset($_GET['delete'])){

    $id = (int) $_GET['delete'];

    // Mencegah admin menghapus akunnya sendiri
    if($id != $_SESSION['user_id']){

        mysqli_query(
            $conn,
            "DELETE FROM users
             WHERE id='$id'"
        );

    }

    header("Location: users.php");
    exit;
}

$result = mysqli_query(
    $conn,
    "SELECT * FROM users
     ORDER BY id DESC"
);

include '../components/header.php';
?>

<style>
.users-page{
    min-height:100vh;
    padding-top:130px;
    padding-bottom:50px;
    background:#f8fafc;
}

.users-card{
    background:white;
    border-radius:24px;
    padding:30px;
    box-shadow:0 10px 30px rgba(0,0,0,.05);
}

.table th{
    font-weight:700;
}

.badge-admin{
    background:#7c3aed;
    color:white;
    padding:8px 14px;
    border-radius:999px;
}

.badge-user{
    background:#e5e7eb;
    color:#111827;
    padding:8px 14px;
    border-radius:999px;
}

.btn-action{
    border:none;
    border-radius:12px;
    padding:8px 14px;
    text-decoration:none;
    margin-right:5px;
}
</style>

<div class="users-page">

<div class="container">

    <h1 class="mb-4">
        👥 Kelola User
    </h1>

    <div class="users-card">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                <?php while($user = mysqli_fetch_assoc($result)): ?>

                    <tr>

                        <td>
                            <?= $user['id'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($user['username']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($user['email']) ?>
                        </td>

                        <td>

                            <?php if($user['role'] == 'admin'): ?>

                                <span class="badge-admin">
                                    Admin
                                </span>

                            <?php else: ?>

                                <span class="badge-user">
                                    User
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>
                            <?= $user['created_at'] ?>
                        </td>

                        <td>

                            <?php if($user['id'] != $_SESSION['user_id']): ?>

                                <a
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Hapus user ini?')"
                                href="?delete=<?= $user['id'] ?>">
                                    Hapus
                                </a>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</div>

<?php include '../components/footer.php'; ?>