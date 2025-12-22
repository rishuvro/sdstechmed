<?php
require __DIR__ . '/../app/config/db.php';

$name = "SDS Admin";
$email = "admin@sdstechmed.com";
$pass = "1234"; // change after first login

$hash = password_hash($pass, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO admins (name,email,password_hash) VALUES (?,?,?)");
$stmt->execute([$name, $email, $hash]);

echo "Admin created. Email: $email Password: $pass";
