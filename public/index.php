<?php
declare(strict_types=1);

require __DIR__ . '/../app/config/db.php';
require __DIR__ . '/../app/helpers/functions.php';

$config = require __DIR__ . '/../app/config/config.php';

/**
 * Base path (important)
 * If your project is: http://localhost/sdstechmed/public
 * then base_path should be: /sdstechmed/public
 */
$basePath = '/sdstechmed/public';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$uri = str_replace($basePath, '', $uri);
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

/**
 * Default SEO (can be overridden per-page)
 */
$settings = getSettings($pdo);
$seo = [
  'title' => $settings['company_name'] ?? 'SDS Techmed',
  'description' => $settings['footer_text'] ?? '',
];

if ($uri === '/') {
  // Home: 4 featured categories + featured products + latest news + category product blocks
  $cats = $pdo->query("SELECT * FROM categories WHERE featured=1 ORDER BY sort_order ASC")->fetchAll();

  $featured = $pdo->query("
    SELECT * FROM products
    WHERE featured=1 AND status='active'
    ORDER BY id DESC
    LIMIT 8
  ")->fetchAll();

  $latestNews = $pdo->query("
    SELECT * FROM news
    ORDER BY published_at DESC, id DESC
    LIMIT 3
  ")->fetchAll();

  // NEW: Products-by-category blocks for home
  $homeCategoryProducts = [];
  foreach ($cats as $c) {
    $stmt = $pdo->prepare("
      SELECT * FROM products
      WHERE category_id=? AND status='active'
      ORDER BY id DESC
      LIMIT 4
    ");
    $stmt->execute([$c['id']]);
    $homeCategoryProducts[$c['slug']] = $stmt->fetchAll();
  }

  $seo['title'] = ($settings['hero_title'] ?? $seo['title']) . ' | ' . ($settings['company_name'] ?? 'SDS Techmed');
  $seo['description'] = $settings['hero_subtitle'] ?? $seo['description'];

  view('home', compact('settings','cats','featured','latestNews','homeCategoryProducts','seo','basePath'));
  exit;
}

if ($uri === '/products') {
  $cats = $pdo->query("SELECT * FROM categories ORDER BY sort_order ASC, id DESC")->fetchAll();

  $seo['title'] = 'Products | ' . ($settings['company_name'] ?? 'SDS Techmed');
  $seo['description'] = 'Browse product categories and devices offered by SDS Techmed.';

  view('products', compact('settings','cats','seo','basePath'));
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

  // SEO from category meta if available
  $seo['title'] = ($category['meta_title'] ?: $category['name']) . ' | ' . ($settings['company_name'] ?? 'SDS Techmed');
  $seo['description'] = $category['meta_description'] ?: ($category['description'] ?? '');

  view('category', compact('settings','category','products','seo','basePath'));
  exit;
}

if (preg_match('#^/product/([a-z0-9\-]+)$#i', $uri, $m)) {
  $slug = $m[1];

  $stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name, c.slug AS category_slug
    FROM products p
    JOIN categories c ON c.id=p.category_id
    WHERE p.slug=? AND p.status='active'
  ");
  $stmt->execute([$slug]);
  $product = $stmt->fetch();
  if (!$product) { http_response_code(404); exit('Not found'); }

  // SEO from product meta if available
  $seo['title'] = ($product['meta_title'] ?: $product['name']) . ' | ' . ($settings['company_name'] ?? 'SDS Techmed');
  $seo['description'] = $product['meta_description'] ?: ($product['short_description'] ?? '');

  view('product_detail', compact('settings','product','seo','basePath'));
  exit;
}

if ($uri === '/news') {
  $news = $pdo->query("SELECT * FROM news ORDER BY published_at DESC, id DESC")->fetchAll();

  $seo['title'] = 'News | ' . ($settings['company_name'] ?? 'SDS Techmed');
  $seo['description'] = 'Latest updates and announcements from SDS Techmed.';

  view('news', compact('settings','news','seo','basePath'));
  exit;
}

if (preg_match('#^/news/([a-z0-9\-]+)$#i', $uri, $m)) {
  $slug = $m[1];

  $stmt = $pdo->prepare("SELECT * FROM news WHERE slug=?");
  $stmt->execute([$slug]);
  $post = $stmt->fetch();
  if (!$post) { http_response_code(404); exit('Not found'); }

  $seo['title'] = $post['title'] . ' | ' . ($settings['company_name'] ?? 'SDS Techmed');
  $seo['description'] = $post['excerpt'] ?? '';

  view('news_detail', compact('settings','post','seo','basePath'));
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
      header("Location: {$basePath}/contact?sent=1");
      exit;
    }
  }

  $seo['title'] = 'Contact | ' . ($settings['company_name'] ?? 'SDS Techmed');
  $seo['description'] = 'Contact SDS Techmed for quotation, OEM/ODM, warranty, shipping and support.';

  view('contact', compact('settings','seo','basePath'));
  exit;
}

if (preg_match('#^/(about|faq|privacy-policy|terms|service-privacy)$#', $uri, $m)) {
  $slug = $m[1];

  $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug=?");
  $stmt->execute([$slug]);
  $page = $stmt->fetch();
  if (!$page) { http_response_code(404); exit('Not found'); }

  $seo['title'] = ($page['title'] ?? ucfirst($slug)) . ' | ' . ($settings['company_name'] ?? 'SDS Techmed');
  $seo['description'] = strip_tags($page['content'] ?? '');

  view('page', compact('settings','page','seo','basePath'));
  exit;
}

http_response_code(404);
echo "404 Not Found";
