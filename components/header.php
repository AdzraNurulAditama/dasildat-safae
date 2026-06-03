<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MindTrack Gaming | Gaming Mental Health</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
body { 
    background-color: #f8fafc; 
    margin: 0; 
    padding: 0; 
    padding-top: 100px; /* Memberi ruang agar konten utama tidak tertutup navbar fixed */
}

.custom-navbar {
    position: fixed; 
    top: 24px; 
    left: 50%; 
    transform: translateX(-50%);
    width: calc(100% - 48px); 
    max-width: 1240px; 
    z-index: 9999;
    padding: 14px 28px; 
    border-radius: 24px;
    background: rgba(255, 255, 255, 0.85) !important;
    backdrop-filter: blur(20px); 
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.04);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.navbar-brand {
    font-family: 'Space Mono', monospace !important;
    font-size: 20px !important; 
    font-weight: 700 !important;
    background: linear-gradient(135deg, #0284c7, #9333ea, #db2777) !important;
    -webkit-background-clip: text !important; 
    -webkit-text-fill-color: transparent !important;
}

.nav-link {
    padding: 10px 16px !important; 
    border-radius: 12px;
    color: #64748b !important; 
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13px !important; 
    font-weight: 600 !important;
    transition: all 0.2s ease;
}

.nav-link:hover, .nav-link.active {
    color: #0f172a !important; 
    background: rgba(147, 51, 234, 0.05);
}

/* Dropdown Styling */
.dropdown-menu { 
    background: rgba(255, 255, 255, 0.95) !important; 
    border-radius: 16px !important; 
    padding: 8px !important; 
    border: 1px solid #e2e8f0; 
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

.dropdown-item { 
    font-size: 13px !important; 
    font-weight: 600 !important; 
    color: #64748b !important; 
    border-radius: 8px !important; 
    padding: 8px 16px !important;
}

.dropdown-item:hover { 
    background: #f1f5f9 !important; 
    color: #0f172a !important; 
}

/* Auth Buttons */
.nav-btn {
    display: inline-block;
    padding: 12px 26px !important;
    border-radius: 16px !important;
    background: linear-gradient(135deg,#9333ea,#db2777) !important;
    color: white !important;
    text-decoration: none !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    font-family: 'Space Mono', monospace;
    transition: all 0.2s ease;
    text-align: center;
}

.nav-btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

.nav-btn-secondary {
    display: inline-block;
    padding: 12px 26px !important;
    border-radius: 16px !important;
    background: transparent;
    color: #334155 !important;
    text-decoration: none !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    font-family: 'Space Mono', monospace;
    transition: all 0.2s ease;
    text-align: center;
}

.nav-btn-secondary:hover {
    background: #e2e8f0;
    color: #0f172a !important;
    transform: translateY(-1px);
}

/* Logout Button (Satu rumpun dengan nav-btn-secondary) */
.logout-btn {
    display: inline-block;
    padding: 12px 26px !important;
    border-radius: 16px !important;
    background: rgba(239, 68, 68, 0.08);
    color: #ef4444 !important;
    text-decoration: none !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    font-family: 'Space Mono', monospace;
    transition: all 0.2s ease;
    text-align: center;
    border: 1px solid rgba(239, 68, 68, 0.15);
}

.logout-btn:hover {
    background: #ef4444 !important;
    color: white !important;
    transform: translateY(-1px);
}

.username-display {
    display: flex; 
    align-items: center; 
    gap: 8px; 
    padding: 10px 16px;
    border-radius: 14px; 
    background: #f1f5f9; 
    color: #334155;
    font-size: 13px; 
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Perbaikan Jarak Responsif untuk HP */
@media (max-width: 991.98px) {
    .custom-navbar {
        top: 12px;
        width: calc(100% - 24px);
        padding: 12px 20px;
    }
    .navbar-collapse {
        margin-top: 16px;
        max-height: 75vh;
        overflow-y: auto;
    }
    .nav-item {
        width: 100%;
        margin-bottom: 8px;
    }
    .ms-lg-2, .ms-lg-3 {
        margin-left: 0 !important;
        margin-top: 4px;
    }
    .username-display, .nav-btn, .nav-btn-secondary, .logout-btn {
        display: flex;
        justify-content: center;
        width: 100%;
    }
}
</style>
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<body>

<nav class="navbar navbar-expand-lg custom-navbar" id="safae-navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">MindTrack Gaming</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
                </li>

                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'update_model.php') ? 'active' : ''; ?>" href="update_model.php">Update Model</a>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'models.php') ? 'active' : ''; ?>" href="models.php">Models</a>
                </li>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= (in_array($current_page, ['predict_manual.php', 'predict_csv.php'])) ? 'active' : ''; ?>" 
                           href="#" role="button" data-bs-toggle="dropdown">Predict</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="predict_manual.php">Manual Input</a></li>
                            <li><a class="dropdown-item" href="predict_csv.php">Upload</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page == 'history.php') ? 'active' : ''; ?>" href="history.php">History</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <div class="username-display"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['username']); ?></div>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="<?= (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../logout.php' : 'logout.php'; ?>" class="logout-btn">
                            Logout
                        </a>                   
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-3">
                        <a href="login.php" class="nav-btn-secondary">LOGIN</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="register.php" class="nav-btn">REGISTER</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>