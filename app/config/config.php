<?php
declare(strict_types=1);

return [
  'base_url' => 'http://localhost/sdstechmed/public',
  'db' => [
    'host' => '127.0.0.1',
    'name' => 'sdstechmed',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4',
  ],
  'uploads' => [
    'products' => __DIR__ . '/../../public/uploads/products',
    'news'     => __DIR__ . '/../../public/uploads/news',
  ],
];
