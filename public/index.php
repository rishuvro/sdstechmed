<?php
declare(strict_types=1);

require __DIR__ . '/../app/config/db.php';
require __DIR__ . '/../app/helpers/functions.php';

$config = require __DIR__ . '/../app/config/config.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$uri = str_replace('/sdstechmed/public', '', $uri); // adjust if your folder name differs
$uri = rtrim($uri, '/') ?: '/';

function view(string $file, array $data = []): void {
  extract($data);
  require __DIR__ . '/../app/views/layout/header.php';
  require __DIR__ . '/../app/views/' . $file . '.php';
  require __DIR__ . '/../app/views/layout/footer.php';
}

function getSettings(PDO $pdo): array {
  $stmt = $pdo->query("SELECT * FROM settings WHERE id=1");
  return $stmt->fetch() ?: [];
}

$settings = getSettings($pdo);

if ($uri === '/') {
  // Example home data
  $cats = $pdo->query("SELECT * FROM categories WHERE featured=1 ORDER BY sort_order ASC")->fetchAll();
  $featured = $pdo->query("SELECT * FROM products WHERE featured=1 AND status='active' ORDER BY id DESC LIMIT 8")->fetchAll();
  $latestNews = $pdo->query("SELECT * FROM news ORDER BY published_at DESC, id DESC LIMIT 3")->fetchAll();
  view('home', compact('settings','cats','featured','latestNews'));
  exit;
}



if ($uri === '/products') {
  $cats = $pdo->query("SELECT * FROM categories ORDER BY sort_order ASC, id DESC")->fetchAll();
  view('products', compact('settings','cats'));
  exit;
}

if (preg_match('#^/category/([a-z0-9\-]+)$#i', $uri, $m)) {
  $slug = $m[1];
  $stmt = $pdo->prepare("SELECT * FROM categories WHERE slug=?");
  $stmt->execute([$slug]);
  $category = $stmt->fetch();
  if (!$category) { http_response_code(404); exit('Not found'); }

  $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id=? AND status='active' ORDER BY id DESC");
  $stmt->execute([$category['id']]);
  $products = $stmt->fetchAll();

  view('category', compact('settings','category','products'));
  exit;
}

if (preg_match('#^/product/([a-z0-9\-]+)$#i', $uri, $m)) {
  $slug = $m[1];
  $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name, c.slug AS category_slug
                         FROM products p JOIN categories c ON c.id=p.category_id
                         WHERE p.slug=? AND p.status='active'");
  $stmt->execute([$slug]);
  $product = $stmt->fetch();
  if (!$product) { http_response_code(404); exit('Not found'); }

  view('product_detail', compact('settings','product'));
  exit;
}

if ($uri === '/news') {
  $news = $pdo->query("SELECT * FROM news ORDER BY published_at DESC, id DESC")->fetchAll();
  view('news', compact('settings','news'));
  exit;
}

if (preg_match('#^/news/([a-z0-9\-]+)$#i', $uri, $m)) {
  $slug = $m[1];
  $stmt = $pdo->prepare("SELECT * FROM news WHERE slug=?");
  $stmt->execute([$slug]);
  $post = $stmt->fetch();
  if (!$post) { http_response_code(404); exit('Not found'); }

  view('news_detail', compact('settings','post'));
  exit;
}

if ($uri === '/contact') {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $message) {
      $stmt = $pdo->prepare("INSERT INTO inquiries (name,email,whatsapp,message) VALUES (?,?,?,?)");
      $stmt->execute([$name, $email ?: null, $whatsapp ?: null, $message]);
      header("Location: /sdstechmed/public/contact?sent=1");
      exit;
    }
  }
  view('contact', compact('settings'));
  exit;
}

if (preg_match('#^/(about|faq|privacy-policy|terms|service-privacy)$#', $uri, $m)) {
  $slug = $m[1];
  $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug=?");
  $stmt->execute([$slug]);
  $page = $stmt->fetch();
  if (!$page) { http_response_code(404); exit('Not found'); }
  view('page', compact('settings','page'));
  exit;
}

http_response_code(404);
echo "404 Not Found";
