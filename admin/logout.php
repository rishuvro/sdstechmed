<?php
session_start();
session_destroy();
header("Location: /sdstechmed/admin/login.php");
exit;
