<?php
declare(strict_types=1);

function uploadImage(array $file, string $targetDir, array $allowedExt = ['jpg','jpeg','png','webp']): ?string {
  if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) return null;

  $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  if (!in_array($ext, $allowedExt, true)) return null;

  if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

  $newName = bin2hex(random_bytes(16)) . '.' . $ext;
  $dest = rtrim($targetDir, '/') . '/' . $newName;

  if (!move_uploaded_file($file['tmp_name'], $dest)) return null;

  return $newName; // store filename in DB
}
