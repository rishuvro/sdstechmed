<?php
declare(strict_types=1);

function isJsonArray(string $s): bool {
  $s = trim($s);
  if ($s === '') return false;
  json_decode($s, true);
  return json_last_error() === JSON_ERROR_NONE && is_array(json_decode($s, true));
}
