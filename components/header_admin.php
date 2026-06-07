<!DOCTYPE html>
<html lang="en">
<head>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
    /* Styling Navbar Premium */
    .custom-navbar {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 16px 0;
        z-index: 9999;
        transition: all 0.3s ease;
    }

    /* Brand Logo Text */
    .custom-navbar .navbar-brand {
        color: #0f172a;
        font-size: 1.25rem;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .custom-navbar .navbar-brand i {
        color: #0ea5e9;
        font-size: 1.4rem;
    }

    .brand-highlight {
        color: #0ea5e9;
    }

    /* Navigation Links */
    .custom-navbar .nav-link {
        color: #64748b;
        font-weight: 600;
        font-size: 0.95rem;
        padding: 8px 16px !important;
        border-radius: 12px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .custom-navbar .nav-link:hover, 
    .custom-navbar .nav-link.active {
        background: rgba(14, 165, 233, 0.1);
        color: #0ea5e9;
    }

    /* Logout Button */
    .btn-logout {
        color: #ef4444;
        background: rgba(239, 68, 68, 0.05);
        border: 1px solid rgba(239, 68, 68, 0.2);
        font-weight: 600;
        padding: 8px 24px;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-logout:hover {
        background: #ef4444;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
    }

    /* Toggler untuk versi Mobile */
    .navbar-toggler {
        border: none;
        box-shadow: none !important;
    }
    .navbar-toggler:focus {
        outline: none;
    }
    </style>

</head>

<body>

<nav class="navbar navbar-expand-lg custom-navbar fixed-top shadow-sm">
    <div class="container">

        <a class="navbar-brand fw-bold" href="../admin/dashboard.php">
            <i class="bi bi-layers-fill"></i> 
            MindTrack <span class="brand-highlight">Admin</span>
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#adminNavbar">
            <i class="bi bi-list fs-1 text-dark"></i>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <div class="navbar-nav ms-auto align-items-lg-center gap-1 mt-3 mt-lg-0">

                <a class="nav-link" href="../admin/dashboard.php">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>

                <a class="nav-link" href="../admin/users.php">
                    <i class="bi bi-people"></i> Users
                </a>

                <a class="nav-link" href="../admin/history_admin.php">
                    <i class="bi bi-clock-history"></i> History
                </a>

                <a class="nav-link" href="../admin/update_model.php">
                    <i class="bi bi-cpu"></i> Retraining
                </a>

                <span class="mx-3 text-secondary opacity-25 d-none d-lg-block">
                    |
                </span>

                <a class="btn btn-logout mt-2 mt-lg-0" href="../logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>

            </div>
        </div>

    </div>
</nav>

<div style="padding-top: 80px;"></div>

</body>
</html>