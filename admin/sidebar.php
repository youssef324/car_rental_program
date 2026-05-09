<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$current_url = $_SERVER['PHP_SELF'];
$admin_pos = strpos($current_url, '/admin/');

if ($admin_pos !== false) {
    $admin_web_root = substr($current_url, 0, $admin_pos + 7);
    $project_web_root = substr($current_url, 0, $admin_pos + 1);
} else {
    $admin_web_root = "/admin/";
    $project_web_root = "/";
}

$full_admin = $protocol . "://" . $host . $admin_web_root;
$full_project = $protocol . "://" . $host . $project_web_root;
?>

<div class="sidebar">
    <nav class="nav">
        <a href="<?php echo $full_admin; ?>index.php" title="Dashboard">
            <img src="<?php echo $full_project; ?>photos/account.png" alt="Dashboard" style="min-width: 45px;" />
        </a>
        <a href="<?php echo $full_admin; ?>request/cars/index.php" title="Manage Cars">
            <img src="<?php echo $full_project; ?>photos/car.png" alt="Cars" style="min-width: 45px;" />
        </a>
        <a href="<?php echo $full_admin; ?>request/reservations/index.php" title="Bookings">
            <img src="<?php echo $full_project; ?>photos/mail.png" alt="Bookings" style="min-width: 45px;" />
        </a>
        <a href="<?php echo $full_admin; ?>request/customers/index.php" title="Manage Users">
            <img src="<?php echo $full_project; ?>photos/about.png" alt="Users" style="min-width: 45px;" />
        </a>
        <a href="<?php echo $full_admin; ?>logout.php" title="Log Out">
            <img src="<?php echo $full_project; ?>photos/logout.png" alt="Log Out" style="min-width: 45px;" />
        </a>
    </nav>
</div>
