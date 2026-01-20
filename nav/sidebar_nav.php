<?php
    // This detects the current filename (e.g., dashboard.php)
    $current_page = basename($_SERVER['PHP_SELF']);

    $app_name = "SELECT setting_value FROM system_settings WHERE setting_key = 'app_name'";
    $result = $conn->query($app_name);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $app_name_value = $row['setting_value'];
    } else {
        $app_name_value = "MVentory"; // Default value if not set in DB
    }

    $words = explode(" ", $app_name_value);

    if (count($words) > 1) {
        // Wrap the first word in text-info
        $first_part = '<span class="text-info">' . htmlspecialchars($words[0]) . '</span>';
        
        // Remove the first word from the array
        array_shift($words);
        
        // Join the remaining words with NO SPACE
        $second_part = htmlspecialchars(implode("", $words)); 
        
        $display_name = $first_part . $second_part;
    } else {
        $display_name = htmlspecialchars($app_name_value);
    }

    $app_logo = "SELECT setting_value FROM system_settings WHERE setting_key = 'app_logo'";
    $result_logo = $conn->query($app_logo);
    if ($result_logo && $result_logo->num_rows > 0) {
        $row_logo = $result_logo->fetch_assoc();
        $app_logo_value = $row_logo['setting_value'];
    } else {
        $app_logo_value = "default_logo.png"; // Default logo if not set in DB
    }
?>

<style>
    /* Logout button color fix */
    .dropdown-menu-stark .logout-item {
        color: #dc3545 !important;
    }

    .dropdown-menu-stark .logout-item:hover,
    .dropdown-menu-stark .logout-item:focus {
        color: #dc3545 !important;
        background-color: rgba(220, 53, 69, 0.15);
    }
</style>

<link rel="stylesheet" href="../src/style/navbar_style.css">

<div class="sidebar-stark" id="sidebar">

    <div class="sidebar-header">
        <img src="../src/image/logo/<?php echo $app_logo_value; ?>" width="40" alt="Logo">
        <h3 class="ms-3 fw-bold text-white logo-text"><?php echo $display_name; ?></h3>
    </div>

    <ul class="nav flex-column mt-2">
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
                <i class="fa-solid fa-gauge-high"></i>
                <span class="ms-3 link-text">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'inventory.php') ? 'active' : ''; ?>" href="inventory.php">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span class="ms-3 link-text">Inventory</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'allocation.php') ? 'active' : ''; ?>" href="allocation.php">
                <i class="fa-solid fa-layer-group"></i>
                <span class="ms-3 link-text">Allocation</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-danger <?php echo ($current_page == 'damage.php') ? 'active-danger' : ''; ?>" href="damage.php">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span class="ms-3 link-text">Damage</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'accounts.php') ? 'active' : ''; ?>" href="accounts.php">
                <i class="fa-solid fa-users"></i>
                <span class="ms-3 link-text">Accounts</span>
            </a>
        </li>

        <li class="nav-item dropend">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fa-solid fa-clipboard-list"></i>
                <span class="ms-3 link-text">Logs</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-stark shadow-lg">
                <li><a class="dropdown-item" href="branch_logs.php">Branch Logs</a></li>
                <li><a class="dropdown-item" href="head_office_logs.php">Head Office Logs</a></li>
                <li><a class="dropdown-item" href="security_logs.php">Security Logs</a></li>
                <li><a class="dropdown-item" href="system_logs.php">System Logs</a></li>
                <li><hr class="text-light"></li>
                <li><a class="dropdown-item" href="full_logs.php">Full System Logs</a></li>
            </ul>
        </li>
    </ul>

    <div class="sidebar-footer dropup">
        <a class="nav-link profile-btn dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fa-solid fa-user"></i>
            <span class="link-text ms-2"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-stark w-100">
            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
            <li><a class="dropdown-item" href="settings.php">Settings</a></li>
            <li><hr class="dropdown-divider border-secondary"></li>
            <li>
                <a class="dropdown-item fw-bold logout-item" href="logout.php">
                    Sign Out
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- <nav class="navbar navbar-stark fixed-top">
    <div class="container-fluid">
        <button id="sidebarToggle">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</nav> -->
<!-- 
<script>
document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const toggle = document.getElementById("sidebarToggle");

    toggle.addEventListener("click", () => {
        // Toggle Sidebar state
        sidebar.classList.toggle("collapsed");

        // Close any open dropdowns instantly when collapsing/expanding
        document.querySelectorAll(".dropdown-menu.show")
            .forEach(d => d.classList.remove("show"));
    });
});
</script> -->