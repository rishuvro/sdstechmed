<?php
declare(strict_types=1);

function adminRequireLogin(): void {
  session_start();
  if (empty($_SESSION['admin_id'])) {
    header("Location: /sdstechmed/admin/login.php");
    exit;
  }
}
