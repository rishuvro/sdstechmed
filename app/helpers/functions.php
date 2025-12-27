<?php
declare(strict_types=1);

function isJsonArray(string $s): bool {
  $s = trim($s);
  if ($s === '') return false;
  json_decode($s, true);
  return json_last_error() === JSON_ERROR_NONE && is_array(json_decode($s, true));
}

function slugify(string $text): string {
  $text = trim($text);
  $text = strtolower($text);
  $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
  $text = trim($text ?? '', '-');
  return $text !== '' ? $text : 'n-a';
}
