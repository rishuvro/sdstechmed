<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

/**
 * Clear session data
 */
$_SESSION = [];

/**
 * Delete the session cookie (if used)
 */
if (ini_get('session.use_cookies')) {
  $params = session_get_cookie_params();
  setcookie(
    session_name(),
    '',
    time() - 42000,
    $params['path'],
    $params['domain'],
    $params['secure'],
    $params['httponly']
  );
}

/**
 * Destroy and regenerate
 */
session_destroy();
session_regenerate_id(true);

header("Location: /sdstechmed/admin/login.php");
exit;
