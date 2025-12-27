<?php
declare(strict_types=1);

require __DIR__ . '/../app/config/db.php';
$config = require __DIR__ . '/../app/config/config.php';

require __DIR__ . '/../app/helpers/auth.php';
require __DIR__ . '/../app/helpers/upload.php';
require __DIR__ . '/../app/helpers/functions.php';
require __DIR__ . '/../app/helpers/csrf.php';

adminRequireLogin();

$uploadDir = $config['uploads']['news'] ?? (__DIR__ . '/../public/uploads/news');

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = $_GET['msg'] ?? '';

function findNews(PDO $pdo, int $id): ?array {
  $stmt = $pdo->prepare("SELECT * FROM news WHERE id=?");
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  return $row ?: null;
}

function slugExistsNews(PDO $pdo, string $slug, int $ignoreId = 0): bool {
  if ($ignoreId > 0) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM news WHERE slug=? AND id<>?");
    $stmt->execute([$slug, $ignoreId]);
  } else {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM news WHERE slug=?");
    $stmt->execute([$slug]);
  }
  return (int)$stmt->fetchColumn() > 0;
}

function uniqueNewsSlug(PDO $pdo, string $base, int $ignoreId = 0): string {
  $slug = slugify($base);
  $candidate = $slug;
  $i = 2;
  while (slugExistsNews($pdo, $candidate, $ignoreId)) {
    $candidate = $slug . '-' . $i;
    $i++;
  }
  return $candidate;
}

function removeImageIfExists(string $dir, ?string $filename): void {
  if (!$filename) return;
  $path = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $filename;
  if (is_file($path)) @unlink($path);
}

$title = 'Manage News';
require __DIR__ . '/partials/admin_header.php';

if ($msg): ?>
  <div class="msg"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<?php
// DELETE (POST)
if ($action === 'delete' && $id > 0) {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<div class="card"><p class="muted">Invalid request.</p></div>';
    require __DIR__ . '/partials/admin_footer.php';
    exit;
  }
  if (!csrf_verify($_POST['_csrf'] ?? null)) {
    echo '<div class="card"><p class="muted">CSRF failed. Refresh and try again.</p></div>';
    require __DIR__ . '/partials/admin_footer.php';
    exit;
  }

  $post = findNews($pdo, $id);
  if (!$post) {
    header("Location: /sdstechmed/admin/news.php?msg=" . urlencode("Post not found."));
    exit;
  }

  $stmt = $pdo->prepare("DELETE FROM news WHERE id=?");
  $stmt->execute([$id]);

  removeImageIfExists($uploadDir, $post['cover_image'] ?? null);

  header("Location: /sdstechmed/admin/news.php?msg=" . urlencode("Post deleted."));
  exit;
}

// SAVE (ADD/EDIT)
if (($action === 'add' || $action === 'edit') && $_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_verify($_POST['_csrf'] ?? null)) {
    echo '<div class="card"><p class="muted">CSRF failed. Refresh and try again.</p></div>';
    require __DIR__ . '/partials/admin_footer.php';
    exit;
  }

  $titleInput = trim($_POST['title'] ?? '');
  $slugInput = trim($_POST['slug'] ?? '');
  $excerpt = trim($_POST['excerpt'] ?? '');
  $content = trim($_POST['content'] ?? '');
  $published_at = trim($_POST['published_at'] ?? '');

  if ($titleInput === '' || $content === '') {
    echo '<div class="card"><p class="muted">Title and Content are required.</p></div>';
  } else {

    // Normalize published_at: allow empty; otherwise keep as YYYY-MM-DD HH:MM:SS
    $pub = null;
    if ($published_at !== '') {
      // Accept "YYYY-MM-DDTHH:MM" from datetime-local input
      $published_at = str_replace('T', ' ', $published_at);
      if (strlen($published_at) === 16) $published_at .= ':00';
      $pub = $published_at;
    }

    if ($action === 'add') {
      $slug = $slugInput !== '' ? uniqueNewsSlug($pdo, $slugInput) : uniqueNewsSlug($pdo, $titleInput);
      $newImage = uploadImage($_FILES['cover_image'] ?? [], $uploadDir);

      $stmt = $pdo->prepare("
        INSERT INTO news (title, slug, excerpt, content, cover_image, published_at)
        VALUES (?,?,?,?,?,?)
      ");
      $stmt->execute([
        $titleInput,
        $slug,
        $excerpt ?: null,
        $content,
        $newImage,
        $pub
      ]);

      header("Location: /sdstechmed/admin/news.php?msg=" . urlencode("News post created."));
      exit;
    }

    // edit
    $post = findNews($pdo, $id);
    if (!$post) {
      header("Location: /sdstechmed/admin/news.php?msg=" . urlencode("Post not found."));
      exit;
    }

    $slug = $slugInput !== '' ? uniqueNewsSlug($pdo, $slugInput, $id) : uniqueNewsSlug($pdo, $titleInput, $id);

    $newImage = uploadImage($_FILES['cover_image'] ?? [], $uploadDir);
    $finalImage = $post['cover_image'] ?? null;

    if ($newImage) {
      removeImageIfExists($uploadDir, $finalImage);
      $finalImage = $newImage;
    }

    if (!empty($_POST['remove_image'])) {
      removeImageIfExists($uploadDir, $finalImage);
      $finalImage = null;
    }

    $stmt = $pdo->prepare("
      UPDATE news
      SET title=?, slug=?, excerpt=?, content=?, cover_image=?, published_at=?
      WHERE id=?
    ");
    $stmt->execute([
      $titleInput,
      $slug,
      $excerpt ?: null,
      $content,
      $finalImage,
      $pub,
      $id
    ]);

    header("Location: /sdstechmed/admin/news.php?msg=" . urlencode("News post updated."));
    exit;
  }
}

// FORM
if ($action === 'add' || ($action === 'edit' && $id > 0)) {
  $isEdit = ($action === 'edit');
  $post = $isEdit ? findNews($pdo, $id) : null;

  if ($isEdit && !$post) {
    echo '<div class="card"><p class="muted">Post not found.</p></div>';
    require __DIR__ . '/partials/admin_footer.php';
    exit;
  }

  $pageTitle = $isEdit ? 'Edit News Post' : 'Add News Post';

  // Convert stored datetime to datetime-local format for input
  $pubVal = '';
  if (!empty($post['published_at'])) {
    $pubVal = str_replace(' ', 'T', substr((string)$post['published_at'], 0, 16));
  }
  ?>
  <div class="card">
    <h2 style="margin-top:0;"><?= htmlspecialchars($pageTitle) ?></h2>
    <p class="muted">Tip: use cover image + short excerpt. Content can be HTML.</p>

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">

      <label>Title *</label>
      <input name="title" required value="<?= htmlspecialchars($post['title'] ?? '') ?>" placeholder="e.g., New Model Launch / Exhibition / Company Update">

      <label>Slug (optional)</label>
      <input name="slug" value="<?= htmlspecialchars($post['slug'] ?? '') ?>" placeholder="auto-generated if empty">

      <label>Excerpt (for list cards)</label>
      <textarea name="excerpt" rows="3" placeholder="Short summary..."><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>

      <label>Published Date/Time</label>
      <input type="datetime-local" name="published_at" value="<?= htmlspecialchars($pubVal) ?>">

      <label>Content *</label>
      <textarea name="content" rows="10" required placeholder="<p>Write details here...</p>"><?= htmlspecialchars($post['content'] ?? '') ?></textarea>

      <label>Cover Image</label>
      <?php if (!empty($post['cover_image'])): ?>
        <div style="display:flex;gap:12px;align-items:center;margin:8px 0 6px;">
          <img class="thumb" src="/sdstechmed/public/uploads/news/<?= htmlspecialchars($post['cover_image']) ?>" alt="">
          <div class="muted" style="font-size:13px;">
            Current: <?= htmlspecialchars($post['cover_image']) ?><br>
            <label style="margin-top:6px;">
              <input type="checkbox" name="remove_image" value="1"> Remove current image
            </label>
          </div>
        </div>
      <?php endif; ?>
      <input type="file" name="cover_image" accept=".jpg,.jpeg,.png,.webp">

      <div style="margin-top:14px;display:flex;gap:10px;align-items:center;">
        <button class="btn btn-primary" type="submit"><?= $isEdit ? 'Update' : 'Create' ?></button>
        <a class="btn" href="/sdstechmed/admin/news.php">Back</a>
      </div>
    </form>
  </div>
  <?php
  require __DIR__ . '/partials/admin_footer.php';
  exit;
}

// LIST + Search
$search = trim($_GET['q'] ?? '');
if ($search !== '') {
  $stmt = $pdo->prepare("SELECT * FROM news WHERE title LIKE ? OR slug LIKE ? ORDER BY published_at DESC, id DESC");
  $stmt->execute(["%$search%", "%$search%"]);
  $rows = $stmt->fetchAll();
} else {
  $rows = $pdo->query("SELECT * FROM news ORDER BY published_at DESC, id DESC")->fetchAll();
}
?>

<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
    <div>
      <h2 style="margin:0;">News</h2>
      <div class="muted" style="font-size:13px;">Manage updates and announcements shown on the public site.</div>
    </div>
    <div class="actions">
      <a class="btn btn-primary" href="/sdstechmed/admin/news.php?action=add">+ Add News</a>
    </div>
  </div>

  <form method="get" style="margin-top:12px;display:grid;grid-template-columns:1fr auto auto;gap:10px;align-items:end;">
    <div>
      <label>Search</label>
      <input name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search title or slug...">
    </div>
    <div>
      <button class="btn" type="submit">Search</button>
      <a class="btn" href="/sdstechmed/admin/news.php">Reset</a>
    </div>
  </form>

  <div style="margin-top:12px;overflow:auto;">
    <table>
      <thead>
        <tr>
          <th>Cover</th>
          <th>Title</th>
          <th>Published</th>
          <th>Slug</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td>
            <?php if (!empty($r['cover_image'])): ?>
              <img class="thumb" src="/sdstechmed/public/uploads/news/<?= htmlspecialchars($r['cover_image']) ?>" alt="">
            <?php else: ?>
              <div class="muted">No image</div>
            <?php endif; ?>
          </td>
          <td><strong><?= htmlspecialchars($r['title']) ?></strong><br>
            <span class="muted" style="font-size:13px;"><?= htmlspecialchars($r['excerpt'] ?? '') ?></span>
          </td>
          <td class="muted">
            <?= htmlspecialchars($r['published_at'] ?? '') ?>
          </td>
          <td class="muted"><?= htmlspecialchars($r['slug']) ?></td>
          <td>
            <div class="actions">
              <a class="btn" href="/sdstechmed/admin/news.php?action=edit&id=<?= (int)$r['id'] ?>">Edit</a>
              <a class="btn" href="/sdstechmed/public/news/<?= htmlspecialchars($r['slug']) ?>" target="_blank">View</a>

              <form method="post"
                    action="/sdstechmed/admin/news.php?action=delete&id=<?= (int)$r['id'] ?>"
                    onsubmit="return confirm('Delete this post? This will remove its cover image too.');">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                <button class="btn danger" type="submit">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/partials/admin_footer.php'; ?>
