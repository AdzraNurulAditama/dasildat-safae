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

include '../components/header_admin.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
/* 1. Global Background */
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
.animate-fade-up {
    animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* 3. Typography */
.page-title {
    font-size: 36px;
    font-weight: 800;
    letter-spacing: -0.5px;
    color: #0f172a;
}

/* 4. Glass Card Container */
.glass-table-card {
    background: #ffffff;
    border-radius: 24px;
    border: 1px solid rgba(255,255,255,0.8);
    box-shadow: 0 10px 40px -10px rgba(0,0,0,0.04);
    overflow: hidden;
}

/* 5. Custom Table Styling */
.table-custom { margin-bottom: 0; }
.table-custom thead th {
    background: rgba(248, 250, 252, 0.8) !important;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 18px 24px;
}
.table-custom tbody td {
    padding: 16px 24px;
    vertical-align: middle;
    border-bottom: 1px solid rgba(0,0,0,0.03);
    color: #334155;
    transition: background-color 0.2s ease;
}
.table-custom tbody tr:hover td {
    background-color: rgba(139, 92, 246, 0.02);
}
.table-custom tbody tr:last-child td { border-bottom: none; }

/* 6. User Avatar Initial */
.avatar-initial {
    width: 40px; height: 40px;
    border-radius: 50%;
    display: flex; justify-content: center; align-items: center;
    font-weight: 700;
    background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(139, 92, 246, 0.1));
    color: #8b5cf6;
    margin-right: 12px;
}

/* 7. Badges */
.badge-role {
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.role-admin {
    background: rgba(139, 92, 246, 0.1);
    color: #7c3aed;
    border: 1px solid rgba(139, 92, 246, 0.2);
}
.role-user {
    background: rgba(100, 116, 139, 0.1);
    color: #475569;
    border: 1px solid rgba(100, 116, 139, 0.2);
}

/* 8. Action Button (Delete) */
.btn-delete {
    color: #94a3b8;
    background: transparent;
    border: 1px solid transparent;
    width: 36px; height: 36px;
    border-radius: 10px;
    display: inline-flex; justify-content: center; align-items: center;
    transition: all 0.2s ease;
}
.btn-delete:hover {
    color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.2);
}
</style>

<div class="admin-page-wrapper">
<div class="container">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3 animate-fade-up">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(139,92,246,0.1); color: #8b5cf6; font-size: 0.8rem; font-weight: 700; letter-spacing: 1px;">
                <i class="bi bi-people"></i> USER MANAGER
            </div>
            <h2 class="page-title mb-1">Daftar Pengguna</h2>
            <p class="text-muted mb-0">Kelola akses, pantau pendaftaran, atau hapus akun pengguna sistem.</p>
        </div>
    </div>

    <div class="glass-table-card animate-fade-up" style="animation-delay: 0.1s;">
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th>User Profile</th>
                        <th>Email Address</th>
                        <th>Access Role</th>
                        <th>Date Registered</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while($user = mysqli_fetch_assoc($result)): ?>
                            <?php 
                                $username = htmlspecialchars($user['username']);
                                $initial = strtoupper(substr($username, 0, 1)); 
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-initial">
                                            <?= $initial ?>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark"><?= $username ?></h6>
                                            <small class="text-muted fw-medium">ID: #<?= $user['id'] ?></small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="text-secondary fw-medium">
                                        <?= htmlspecialchars($user['email']) ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if($user['role'] == 'admin'): ?>
                                        <span class="badge-role role-admin">
                                            <i class="bi bi-shield-lock-fill"></i> Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-role role-user">
                                            <i class="bi bi-person-fill"></i> User
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="d-block text-dark fw-bold">
                                        <?= date("d M Y", strtotime($user['created_at'])) ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <?php if($user['id'] != $_SESSION['user_id']): ?>
                                        <a href="?delete=<?= $user['id'] ?>" 
                                           class="btn-delete" 
                                           title="Hapus Pengguna"
                                           onclick="return confirm('Peringatan: Anda yakin ingin menghapus pengguna <?= $username ?> secara permanen? Data yang dihapus tidak bisa dikembalikan.')">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: 0.8rem; font-style: italic;">(You)</span>
                                    <?php endif; ?>
                                </td>

                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4 text-muted">
                                    <i class="bi bi-people fs-1 mb-3 d-block opacity-50"></i>
                                    <h6 class="fw-bold text-dark mb-1">Belum ada pengguna</h6>
                                    <p class="mb-0 text-sm">Sistem saat ini belum memiliki pengguna terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

<?php include '../components/footer_admin.php'; ?>