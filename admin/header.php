<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['admin_name'])) {
    header("Location: index.php");
    exit();
}
$current_url = $_SERVER['PHP_SELF'];
$is_sub = (strpos($current_url, '/request/') !== false);
$style_base = $is_sub ? "../../style_admin/" : "style_admin/";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Command Center</title>
    <!-- Linked using the new style_admin folder -->
    <link rel="stylesheet" href="<?php echo $style_base; ?>style.css">
    <link rel="stylesheet" href="<?php echo $style_base; ?>request-style.css">
</head>
<body class="onyx-theme">
    <div class="app-layout">
        <?php include $is_sub ? '../../sidebar.php' : './sidebar.php'; ?>
        <main class="main-content">
