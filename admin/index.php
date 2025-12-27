<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

if (!empty($_SESSION['admin_logged_in'])) {
  header("Location: /sdstechmed/admin/dashboard.php");
  exit;
}

header("Location: /sdstechmed/admin/login.php");
exit;
