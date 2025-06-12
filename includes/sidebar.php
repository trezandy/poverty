<?php
// Mendapatkan path relatif untuk include CSS dan JS
$root_path = './';
$current_path = $_SERVER['PHP_SELF'];
$depth = substr_count($current_path, '/') - 1;
if ($depth > 1) {
    $root_path = str_repeat('../', $depth - 1);
}
?>
<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <a href="<?php echo $root_path; ?>dashboard.php" class="sidebar-brand">
            <i class="fas fa-home"></i>
            <span>Sistem Klasifikasi SVM</span>
        </a>
    </div>
    <ul class="sidebar-menu">
        <li class="sidebar-item">
            <a href="<?php echo $root_path; ?>dashboard.php" class="sidebar-link">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <?php /*
        <li class="sidebar-item">
            <a href="<?php echo $root_path; ?>penduduk/penduduk.php" class="sidebar-link">
                <i class="fas fa-users"></i>
                <span>Data Penduduk</span>
            </a>
        </li>
        */ ?>
        <?php /*
        <li class="sidebar-item">
            <a href="<?php echo $root_path; ?>training/training.php" class="sidebar-link">
                <i class="fas fa-database"></i>
                <span>Data Training</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="<?php echo $root_path; ?>testing/testing.php" class="sidebar-link">
                <i class="fas fa-vial"></i>
                <span>Data Testing</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="<?php echo $root_path; ?>confusion_matrix/confusion_matrix.php" class="sidebar-link">
                <i class="fas fa-chart-pie"></i>
                <span>Confusion Matrix</span>
            </a>
        </li>
        */ ?>
        <li class="sidebar-item">
            <a href="<?php echo $root_path; ?>klasifikasi/klasifikasi.php" class="sidebar-link">
                <i class="fas fa-chart-bar"></i>
                <span>Klasifikasi</span>
            </a>
        </li>
    </ul>
    <div style="padding: 1rem 0; display: flex; justify-content: center;">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
    <div class="sidebar-footer">
        <a href="<?php echo $root_path; ?>logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</div>


<!-- Sidebar Styles -->
<style>
    :root {
        --sidebar-bg: #343a40;
        --sidebar-hover: #4a5056;
        --sidebar-active: #5a5c69;
        --sidebar-text: #e9ecef;
        --sidebar-text-muted: #adb5bd;
        --sidebar-border: rgba(233, 236, 239, 0.1);
        --sidebar-icon: #858796;
        --sidebar-width: 240px;
        --sidebar-collapsed-width: 60px;
        --header-height: 50px;
        --shadow-color: rgba(33, 37, 41, 0.15);
    }

    body {
        min-height: 100vh;
        position: relative;
        margin: 0;
        padding: 0;
    }

    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: var(--sidebar-width);
        background: linear-gradient(180deg, var(--sidebar-bg) 0%, #2b3035 100%);
        color: var(--sidebar-text);
        transition: all 0.3s ease;
        z-index: 1000;
        box-shadow: 2px 0 10px var(--shadow-color);
    }

    .sidebar-header {
        height: var(--header-height);
        display: flex;
        align-items: center;
        padding: 0 1rem;
        border-bottom: 1px solid var(--sidebar-border);
        background: rgba(0, 0, 0, 0.1);
    }

    .sidebar-brand {
        color: var(--sidebar-text);
        text-decoration: none;
        font-size: 1rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        flex-grow: 1;
        opacity: 0.95;
        transition: opacity 0.2s ease;
    }

    .sidebar-brand:hover {
        opacity: 1;
        color: var(--sidebar-text);
    }

    .sidebar-brand i {
        margin-right: 0.5rem;
        font-size: 1.1rem;
        color: var(--sidebar-icon);
    }

    .sidebar-toggle {
        background: none;
        border: none;
        color: var(--sidebar-text-muted);
        font-size: 0.9rem;
        cursor: pointer;
        padding: 0.25rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 6px;
    }

    .sidebar-toggle:hover {
        color: var(--sidebar-text);
        background: var(--sidebar-hover);
    }

    .sidebar-menu {
        padding: 0.75rem 0;
        list-style: none;
        margin: 0;
    }

    .sidebar-item {
        margin: 0.375rem 0;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 0.625rem 1rem;
        color: var(--sidebar-text-muted);
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
        overflow: hidden;
        font-size: 0.9rem;
        border-radius: 6px;
        margin: 0 0.5rem;
        font-weight: 500;
    }

    .sidebar-link:hover {
        color: var(--sidebar-text);
        background: var(--sidebar-hover);
    }

    .sidebar-link.active {
        color: var(--sidebar-text);
        background: var(--sidebar-active);
        border-left: none;
        font-weight: 600;
    }

    .sidebar-link i {
        font-size: 1rem;
        margin-right: 0.875rem;
        width: 1.25rem;
        text-align: center;
        color: var(--sidebar-icon);
        transition: color 0.2s ease;
    }

    .sidebar-link:hover i,
    .sidebar-link.active i {
        color: var(--sidebar-text);
    }

    .sidebar.sidebar-collapsed {
        width: var(--sidebar-collapsed-width);
    }

    .sidebar.sidebar-collapsed .sidebar-link span {
        display: none;
    }

    .sidebar.sidebar-collapsed .sidebar-link {
        padding: 0.625rem;
        justify-content: center;
        margin: 0.375rem 0.375rem;
    }

    .sidebar.sidebar-collapsed .sidebar-link i {
        margin-right: 0;
        font-size: 1.1rem;
    }

    .sidebar.sidebar-collapsed .sidebar-brand span {
        display: none;
    }

    .sidebar.sidebar-collapsed .sidebar-brand i {
        margin-right: 0;
        font-size: 1.25rem;
    }

    /* Main Content Adjustment */
    .main-content {
        margin-left: var(--sidebar-width);
        transition: margin-left 0.3s ease;
    }

    body.sidebar-collapsed .main-content {
        margin-left: var(--sidebar-collapsed-width);
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.sidebar-collapsed {
            transform: translateX(0);
            width: var(--sidebar-width);
        }

        .main-content {
            margin-left: 0;
        }

        .sidebar-collapsed+.main-content {
            margin-left: 0;
        }
    }

    /* Logout Button Styles */
    .sidebar-footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        padding: 1rem;
        border-top: 1px solid var(--sidebar-border);
        background: rgba(0, 0, 0, 0.1);
    }

    .logout-btn {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        color: #ff6b6b;
        background: rgba(255, 107, 107, 0.1);
        text-decoration: none;
        transition: all 0.3s ease;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .logout-btn i {
        font-size: 1.1rem;
        margin-right: 0.875rem;
        width: 1.25rem;
        text-align: center;
    }

    .logout-btn:hover {
        background: rgba(255, 107, 107, 0.2);
        color: #ff8787;
        transform: translateY(-1px);
    }

    .sidebar.sidebar-collapsed .logout-btn {
        padding: 0.75rem;
        justify-content: center;
    }

    .sidebar.sidebar-collapsed .logout-btn span {
        display: none;
    }

    .sidebar.sidebar-collapsed .logout-btn i {
        margin-right: 0;
        font-size: 1.25rem;
    }

    @media (max-width: 768px) {
        .sidebar-footer {
            position: relative;
            margin-top: auto;
        }

        .sidebar.sidebar-collapsed .logout-btn {
            padding: 0.75rem 1rem;
            justify-content: flex-start;
        }

        .sidebar.sidebar-collapsed .logout-btn span {
            display: inline;
        }

        .sidebar.sidebar-collapsed .logout-btn i {
            margin-right: 0.875rem;
            font-size: 1.1rem;
        }
    }
</style>

<!-- Sidebar Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const sidebarLinks = document.querySelectorAll('.sidebar-link');

        sidebarLinks.forEach(link => {
            if (currentPath.includes(link.getAttribute('href'))) {
                link.classList.add('active');
            }
        });

        // Toggle sidebar
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-collapsed');

            if (sidebar.classList.contains('sidebar-collapsed')) {
                sidebarToggle.innerHTML = '<i class="fas fa-chevron-right"></i>';
                document.body.classList.add('sidebar-collapsed');
            } else {
                sidebarToggle.innerHTML = '<i class="fas fa-chevron-left"></i>';
                document.body.classList.remove('sidebar-collapsed');
            }
        });
    });
</script>