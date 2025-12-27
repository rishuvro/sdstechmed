<?php
declare(strict_types=1);

require __DIR__ . '/../app/config/db.php';
$config = require __DIR__ . '/../app/config/config.php';

require __DIR__ . '/../app/helpers/auth.php';
require __DIR__ . '/../app/helpers/upload.php';
require __DIR__ . '/../app/helpers/functions.php';
require __DIR__ . '/../app/helpers/csrf.php';

adminRequireLogin();

$uploadDir = $config['uploads']['categories'] ?? (__DIR__ . '/../public/uploads/categories');

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = $_GET['msg'] ?? '';

function findCategory(PDO $pdo, int $id): ?array {
  $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  return $row ?: null;
}

function slugExists(PDO $pdo, string $slug, int $ignoreId = 0): bool {
  if ($ignoreId > 0) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE slug=? AND id<>?");
    $stmt->execute([$slug, $ignoreId]);
  } else {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE slug=?");
    $stmt->execute([$slug]);
  }
  return (int)$stmt->fetchColumn() > 0;
}

function uniqueSlug(PDO $pdo, string $base, int $ignoreId = 0): string {
  $slug = slugify($base);
  $candidate = $slug;
  $i = 2;
  while (slugExists($pdo, $candidate, $ignoreId)) {
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

$title = 'Manage Categories';

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

  $cat = findCategory($pdo, $id);
  if (!$cat) {
    header("Location: /sdstechmed/admin/categories.php?msg=" . urlencode("Category not found."));
    exit;
  }

  // delete row first (FK safe)
  $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
  $stmt->execute([$id]);

  // then delete image file
  removeImageIfExists($uploadDir, $cat['image'] ?? null);

  header("Location: /sdstechmed/admin/categories.php?msg=" . urlencode("Category deleted."));
  exit;
}

// SAVE (ADD/EDIT)
if (($action === 'add' || $action === 'edit') && $_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_verify($_POST['_csrf'] ?? null)) {
    echo '<div class="card"><p class="muted">CSRF failed. Refresh and try again.</p></div>';
    require __DIR__ . '/partials/admin_footer.php';
    exit;
  }

  $name = trim($_POST['name'] ?? '');
  $slugInput = trim($_POST['slug'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $seo_h1 = trim($_POST['seo_h1'] ?? '');
  $meta_title = trim($_POST['meta_title'] ?? '');
  $meta_description = trim($_POST['meta_description'] ?? '');
  $sort_order = (int)($_POST['sort_order'] ?? 0);
  $featured = !empty($_POST['featured']) ? 1 : 0;

  if ($name === '') {
    echo '<div class="card"><p class="muted">Name is required.</p></div>';
  } else {
    if ($action === 'add') {
      $slug = $slugInput !== '' ? uniqueSlug($pdo, $slugInput) : uniqueSlug($pdo, $name);
      $newImage = uploadImage($_FILES['image'] ?? [], $uploadDir);

      $stmt = $pdo->prepare("
        INSERT INTO categories (name, slug, description, image, seo_h1, meta_title, meta_description, sort_order, featured)
        VALUES (?,?,?,?,?,?,?,?,?)
      ");
      $stmt->execute([
        $name, $slug, $description ?: null, $newImage,
        $seo_h1 ?: null, $meta_title ?: null, $meta_description ?: null,
        $sort_order, $featured
      ]);

      header("Location: /sdstechmed/admin/categories.php?msg=" . urlencode("Category created."));
      exit;
    }

    // edit
    $cat = findCategory($pdo, $id);
    if (!$cat) {
      header("Location: /sdstechmed/admin/categories.php?msg=" . urlencode("Category not found."));
      exit;
    }

    $slug = $slugInput !== '' ? uniqueSlug($pdo, $slugInput, $id) : uniqueSlug($pdo, $name, $id);

    $newImage = uploadImage($_FILES['image'] ?? [], $uploadDir);
    $finalImage = $cat['image'] ?? null;

    if ($newImage) {
      // remove old file
      removeImageIfExists($uploadDir, $finalImage);
      $finalImage = $newImage;
    }

    // optional: if checked, remove image
    if (!empty($_POST['remove_image'])) {
      removeImageIfExists($uploadDir, $finalImage);
      $finalImage = null;
    }

    $stmt = $pdo->prepare("
      UPDATE categories
      SET name=?, slug=?, description=?, image=?, seo_h1=?, meta_title=?, meta_description=?, sort_order=?, featured=?
      WHERE id=?
    ");
    $stmt->execute([
      $name, $slug, $description ?: null, $finalImage,
      $seo_h1 ?: null, $meta_title ?: null, $meta_description ?: null,
      $sort_order, $featured, $id
    ]);

    header("Location: /sdstechmed/admin/categories.php?msg=" . urlencode("Category updated."));
    exit;
  }
}

// FORM (ADD/EDIT)
if ($action === 'add' || ($action === 'edit' && $id > 0)) {
  $isEdit = ($action === 'edit');
  $cat = $isEdit ? findCategory($pdo, $id) : null;

  if ($isEdit && !$cat) {
    echo '<div class="card"><p class="muted">Category not found.</p></div>';
    require __DIR__ . '/partials/admin_footer.php';
    exit;
  }

  $title = $isEdit ? 'Edit Category' : 'Add Category';
  ?>
  <div class="card">
    <h2 style="margin-top:0;"><?= htmlspecialchars($title) ?></h2>
    <p class="muted">Upload an image to show on the homepage category tile.</p>

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">

      <div class="row">
        <div>
          <label>Name *</label>
          <input name="name" required value="<?= htmlspecialchars($cat['name'] ?? '') ?>" placeholder="Hair Removal Machines">
        </div>
        <div>
          <label>Slug (optional)</label>
          <input name="slug" value="<?= htmlspecialchars($cat['slug'] ?? '') ?>" placeholder="hair-removal-machines">
          <div class="muted" style="font-size:12px;margin-top:6px;">Leave empty to auto-generate.</div>
        </div>
      </div>

      <label>SEO H1 (your “China … manufacturer” line)</label>
      <input name="seo_h1" value="<?= htmlspecialchars($cat['seo_h1'] ?? '') ?>" placeholder="China Hair Removal Machines manufacturer">

      <label>Description</label>
      <textarea name="description" rows="3" placeholder="Short intro paragraph"><?= htmlspecialchars($cat['description'] ?? '') ?></textarea>

      <div class="row">
        <div>
          <label>Meta Title</label>
          <input name="meta_title" value="<?= htmlspecialchars($cat['meta_title'] ?? '') ?>" placeholder="Hair Removal Machines | SDS Techmed">
        </div>
        <div>
          <label>Meta Description</label>
          <input name="meta_description" value="<?= htmlspecialchars($cat['meta_description'] ?? '') ?>" placeholder="Short SEO description...">
        </div>
      </div>

      <div class="row">
        <div>
          <label>Sort Order</label>
          <input type="number" name="sort_order" value="<?= htmlspecialchars((string)($cat['sort_order'] ?? 0)) ?>">
        </div>
        <div>
          <label>Featured (show on homepage)</label>
          <select name="featured">
            <?php $f = (int)($cat['featured'] ?? 1); ?>
            <option value="1" <?= $f === 1 ? 'selected' : '' ?>>Yes</option>
            <option value="0" <?= $f === 0 ? 'selected' : '' ?>>No</option>
          </select>
        </div>
      </div>

      <label>Category Image</label>
      <?php if (!empty($cat['image'])): ?>
        <div style="display:flex;gap:12px;align-items:center;margin:8px 0 6px;">
          <img class="thumb" src="/sdstechmed/public/uploads/categories/<?= htmlspecialchars($cat['image']) ?>" alt="">
          <div class="muted" style="font-size:13px;">
            Current: <?= htmlspecialchars($cat['image']) ?><br>
            <label style="margin-top:6px;">
              <input type="checkbox" name="remove_image" value="1"> Remove current image
            </label>
          </div>
        </div>
      <?php endif; ?>
      <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">

      <div style="margin-top:14px;display:flex;gap:10px;align-items:center;">
        <button class="btn btn-primary" type="submit"><?= $isEdit ? 'Update' : 'Create' ?></button>
        <a class="btn" href="/sdstechmed/admin/categories.php">Back</a>
      </div>
    </form>
  </div>
  <?php
  require __DIR__ . '/partials/admin_footer.php';
  exit;
}

// LIST
$rows = $pdo->query("SELECT * FROM categories ORDER BY sort_order ASC, id DESC")->fetchAll();
?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;">
    <div>
      <h2 style="margin:0;">Categories</h2>
      <div class="muted" style="font-size:13px;">These power your homepage tiles and product browsing.</div>
    </div>
    <a class="btn btn-primary" href="/sdstechmed/admin/categories.php?action=add">+ Add Category</a>
  </div>

  <div style="margin-top:12px;overflow:auto;">
    <table>
      <thead>
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Slug</th>
          <th>SEO H1</th>
          <th>Featured</th>
          <th>Sort</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td>
            <?php if (!empty($r['image'])): ?>
              <img class="thumb" src="/sdstechmed/public/uploads/categories/<?= htmlspecialchars($r['image']) ?>" alt="">
            <?php else: ?>
              <div class="muted">No image</div>
            <?php endif; ?>
          </td>
          <td><strong><?= htmlspecialchars($r['name']) ?></strong></td>
          <td class="muted"><?= htmlspecialchars($r['slug']) ?></td>
          <td class="muted"><?= htmlspecialchars($r['seo_h1'] ?? '') ?></td>
          <td><?= ((int)$r['featured'] === 1) ? 'Yes' : 'No' ?></td>
          <td><?= (int)$r['sort_order'] ?></td>
          <td>
            <div class="actions">
              <a class="btn" href="/sdstechmed/admin/categories.php?action=edit&id=<?= (int)$r['id'] ?>">Edit</a>

              <form method="post" action="/sdstechmed/admin/categories.php?action=delete&id=<?= (int)$r['id'] ?>"
                    onsubmit="return confirm('Delete this category? This will also remove its image file.');">
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

<?php
require __DIR__ . '/partials/admin_footer.php';
