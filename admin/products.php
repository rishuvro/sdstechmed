<?php
declare(strict_types=1);

require __DIR__ . '/../app/config/db.php';
$config = require __DIR__ . '/../app/config/config.php';

require __DIR__ . '/../app/helpers/auth.php';
require __DIR__ . '/../app/helpers/upload.php';
require __DIR__ . '/../app/helpers/functions.php';
require __DIR__ . '/../app/helpers/csrf.php';

adminRequireLogin();

$uploadDir = $config['uploads']['products'] ?? (__DIR__ . '/../public/uploads/products');

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = $_GET['msg'] ?? '';

function findProduct(PDO $pdo, int $id): ?array {
  $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  return $row ?: null;
}

function slugExistsProduct(PDO $pdo, string $slug, int $ignoreId = 0): bool {
  if ($ignoreId > 0) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE slug=? AND id<>?");
    $stmt->execute([$slug, $ignoreId]);
  } else {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE slug=?");
    $stmt->execute([$slug]);
  }
  return (int)$stmt->fetchColumn() > 0;
}

function uniqueProductSlug(PDO $pdo, string $base, int $ignoreId = 0): string {
  $slug = slugify($base);
  $candidate = $slug;
  $i = 2;
  while (slugExistsProduct($pdo, $candidate, $ignoreId)) {
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

function categoriesAll(PDO $pdo): array {
  return $pdo->query("SELECT id, name FROM categories ORDER BY sort_order ASC, id DESC")->fetchAll();
}

$title = 'Manage Products';
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

  $p = findProduct($pdo, $id);
  if (!$p) {
    header("Location: /sdstechmed/admin/products.php?msg=" . urlencode("Product not found."));
    exit;
  }

  $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
  $stmt->execute([$id]);

  removeImageIfExists($uploadDir, $p['main_image'] ?? null);

  header("Location: /sdstechmed/admin/products.php?msg=" . urlencode("Product deleted."));
  exit;
}

// SAVE (ADD/EDIT)
if (($action === 'add' || $action === 'edit') && $_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_verify($_POST['_csrf'] ?? null)) {
    echo '<div class="card"><p class="muted">CSRF failed. Refresh and try again.</p></div>';
    require __DIR__ . '/partials/admin_footer.php';
    exit;
  }

  $category_id = (int)($_POST['category_id'] ?? 0);
  $name = trim($_POST['name'] ?? '');
  $slugInput = trim($_POST['slug'] ?? '');
  $short_description = trim($_POST['short_description'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $specs = trim($_POST['specs'] ?? '');
  $featured = !empty($_POST['featured']) ? 1 : 0;
  $status = ($_POST['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active';
  $meta_title = trim($_POST['meta_title'] ?? '');
  $meta_description = trim($_POST['meta_description'] ?? '');

  if ($category_id <= 0 || $name === '') {
    echo '<div class="card"><p class="muted">Category and Name are required.</p></div>';
  } else {

    // Validate specs JSON if looks like JSON
    if ($specs !== '' && str_starts_with(trim($specs), '[')) {
      json_decode($specs, true);
      if (json_last_error() !== JSON_ERROR_NONE) {
        echo '<div class="card"><p class="muted">Specs JSON is invalid. Please fix it.</p></div>';
        require __DIR__ . '/partials/admin_footer.php';
        exit;
      }
    }

    if ($action === 'add') {
      $slug = $slugInput !== '' ? uniqueProductSlug($pdo, $slugInput) : uniqueProductSlug($pdo, $name);
      $newImage = uploadImage($_FILES['main_image'] ?? [], $uploadDir);

      $stmt = $pdo->prepare("
        INSERT INTO products
        (category_id, name, slug, short_description, description, specs, featured, main_image, status, meta_title, meta_description)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)
      ");
      $stmt->execute([
        $category_id, $name, $slug,
        $short_description ?: null,
        $description ?: null,
        $specs ?: null,
        $featured,
        $newImage,
        $status,
        $meta_title ?: null,
        $meta_description ?: null
      ]);

      header("Location: /sdstechmed/admin/products.php?msg=" . urlencode("Product created."));
      exit;
    }

    // edit
    $p = findProduct($pdo, $id);
    if (!$p) {
      header("Location: /sdstechmed/admin/products.php?msg=" . urlencode("Product not found."));
      exit;
    }

    $slug = $slugInput !== '' ? uniqueProductSlug($pdo, $slugInput, $id) : uniqueProductSlug($pdo, $name, $id);

    $newImage = uploadImage($_FILES['main_image'] ?? [], $uploadDir);
    $finalImage = $p['main_image'] ?? null;

    if ($newImage) {
      removeImageIfExists($uploadDir, $finalImage);
      $finalImage = $newImage;
    }

    if (!empty($_POST['remove_image'])) {
      removeImageIfExists($uploadDir, $finalImage);
      $finalImage = null;
    }

    $stmt = $pdo->prepare("
      UPDATE products
      SET category_id=?, name=?, slug=?, short_description=?, description=?, specs=?,
          featured=?, main_image=?, status=?, meta_title=?, meta_description=?
      WHERE id=?
    ");
    $stmt->execute([
      $category_id, $name, $slug,
      $short_description ?: null,
      $description ?: null,
      $specs ?: null,
      $featured,
      $finalImage,
      $status,
      $meta_title ?: null,
      $meta_description ?: null,
      $id
    ]);

    header("Location: /sdstechmed/admin/products.php?msg=" . urlencode("Product updated."));
    exit;
  }
}

// FORM (ADD/EDIT)
if ($action === 'add' || ($action === 'edit' && $id > 0)) {
  $isEdit = ($action === 'edit');
  $p = $isEdit ? findProduct($pdo, $id) : null;

  if ($isEdit && !$p) {
    echo '<div class="card"><p class="muted">Product not found.</p></div>';
    require __DIR__ . '/partials/admin_footer.php';
    exit;
  }

  $cats = categoriesAll($pdo);
  $pageTitle = $isEdit ? 'Edit Product' : 'Add Product';
  ?>
  <div class="card">
    <h2 style="margin-top:0;"><?= htmlspecialchars($pageTitle) ?></h2>
    <p class="muted">Specs should be JSON array like: <code>[{"key":"Power","value":"1200W"}]</code></p>

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">

      <label>Category *</label>
      <select name="category_id" required>
        <option value="">Select Category</option>
        <?php foreach ($cats as $c): ?>
          <?php $selected = (int)($p['category_id'] ?? 0) === (int)$c['id'] ? 'selected' : ''; ?>
          <option value="<?= (int)$c['id'] ?>" <?= $selected ?>><?= htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
      </select>

      <div class="row">
        <div>
          <label>Name *</label>
          <input name="name" required value="<?= htmlspecialchars($p['name'] ?? '') ?>" placeholder="e.g., 808nm Diode Laser Hair Removal Machine">
        </div>
        <div>
          <label>Slug (optional)</label>
          <input name="slug" value="<?= htmlspecialchars($p['slug'] ?? '') ?>" placeholder="auto-generated if empty">
        </div>
      </div>

      <label>Short Description (for cards)</label>
      <textarea name="short_description" rows="2" placeholder="One sentence summary..."><?= htmlspecialchars($p['short_description'] ?? '') ?></textarea>

      <label>Description (HTML allowed)</label>
      <textarea name="description" rows="6" placeholder="<p>Overview...</p>"><?= htmlspecialchars($p['description'] ?? '') ?></textarea>

      <label>Specs (JSON preferred)</label>
      <textarea name="specs" rows="6" placeholder='[{"key":"Laser Type","value":"Diode 808nm"},{"key":"Power","value":"1200W"}]'><?= htmlspecialchars($p['specs'] ?? '') ?></textarea>

      <div class="row">
        <div>
          <label>Featured (show on homepage)</label>
          <?php $feat = (int)($p['featured'] ?? 0); ?>
          <select name="featured">
            <option value="1" <?= $feat === 1 ? 'selected' : '' ?>>Yes</option>
            <option value="0" <?= $feat === 0 ? 'selected' : '' ?>>No</option>
          </select>
        </div>
        <div>
          <label>Status</label>
          <?php $st = $p['status'] ?? 'active'; ?>
          <select name="status">
            <option value="active" <?= $st === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $st === 'inactive' ? 'selected' : '' ?>>Inactive</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div>
          <label>Meta Title</label>
          <input name="meta_title" value="<?= htmlspecialchars($p['meta_title'] ?? '') ?>" placeholder="Product Name | SDS Techmed">
        </div>
        <div>
          <label>Meta Description</label>
          <input name="meta_description" value="<?= htmlspecialchars($p['meta_description'] ?? '') ?>" placeholder="Short SEO description...">
        </div>
      </div>

      <label>Main Image</label>
      <?php if (!empty($p['main_image'])): ?>
        <div style="display:flex;gap:12px;align-items:center;margin:8px 0 6px;">
          <img class="thumb" src="/sdstechmed/public/uploads/products/<?= htmlspecialchars($p['main_image']) ?>" alt="">
          <div class="muted" style="font-size:13px;">
            Current: <?= htmlspecialchars($p['main_image']) ?><br>
            <label style="margin-top:6px;">
              <input type="checkbox" name="remove_image" value="1"> Remove current image
            </label>
          </div>
        </div>
      <?php endif; ?>
      <input type="file" name="main_image" accept=".jpg,.jpeg,.png,.webp">

      <div style="margin-top:14px;display:flex;gap:10px;align-items:center;">
        <button class="btn btn-primary" type="submit"><?= $isEdit ? 'Update' : 'Create' ?></button>
        <a class="btn" href="/sdstechmed/admin/products.php">Back</a>
      </div>
    </form>
  </div>
  <?php
  require __DIR__ . '/partials/admin_footer.php';
  exit;
}

// LIST + Filters
$cats = categoriesAll($pdo);
$catFilter = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$search = trim($_GET['q'] ?? '');

$where = [];
$params = [];

if ($catFilter > 0) {
  $where[] = "p.category_id=?";
  $params[] = $catFilter;
}
if ($search !== '') {
  $where[] = "(p.name LIKE ? OR p.slug LIKE ?)";
  $params[] = "%$search%";
  $params[] = "%$search%";
}

$sql = "SELECT p.*, c.name AS category_name
        FROM products p
        JOIN categories c ON c.id=p.category_id";

if ($where) $sql .= " WHERE " . implode(" AND ", $where);

$sql .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>

<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
    <div>
      <h2 style="margin:0;">Products</h2>
      <div class="muted" style="font-size:13px;">Add products under your 4 categories. Mark featured for homepage.</div>
    </div>
    <div class="actions">
      <a class="btn btn-primary" href="/sdstechmed/admin/products.php?action=add">+ Add Product</a>
    </div>
  </div>

  <form method="get" style="margin-top:12px;display:grid;grid-template-columns:1fr 220px auto;gap:10px;align-items:end;">
    <div>
      <label>Search</label>
      <input name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search name or slug...">
    </div>
    <div>
      <label>Category</label>
      <select name="category_id">
        <option value="0">All Categories</option>
        <?php foreach ($cats as $c): ?>
          <option value="<?= (int)$c['id'] ?>" <?= $catFilter === (int)$c['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <button class="btn" type="submit">Filter</button>
      <a class="btn" href="/sdstechmed/admin/products.php">Reset</a>
    </div>
  </form>

  <div style="margin-top:12px;overflow:auto;">
    <table>
      <thead>
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Category</th>
          <th>Featured</th>
          <th>Status</th>
          <th>Slug</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td>
            <?php if (!empty($r['main_image'])): ?>
              <img class="thumb" src="/sdstechmed/public/uploads/products/<?= htmlspecialchars($r['main_image']) ?>" alt="">
            <?php else: ?>
              <div class="muted">No image</div>
            <?php endif; ?>
          </td>
          <td><strong><?= htmlspecialchars($r['name']) ?></strong></td>
          <td class="muted"><?= htmlspecialchars($r['category_name']) ?></td>
          <td><?= ((int)$r['featured'] === 1) ? 'Yes' : 'No' ?></td>
          <td><?= htmlspecialchars($r['status']) ?></td>
          <td class="muted"><?= htmlspecialchars($r['slug']) ?></td>
          <td>
            <div class="actions">
              <a class="btn" href="/sdstechmed/admin/products.php?action=edit&id=<?= (int)$r['id'] ?>">Edit</a>
              <a class="btn" href="/sdstechmed/public/product/<?= htmlspecialchars($r['slug']) ?>" target="_blank">View</a>

              <form method="post"
                    action="/sdstechmed/admin/products.php?action=delete&id=<?= (int)$r['id'] ?>"
                    onsubmit="return confirm('Delete this product? This will also remove its image file.');">
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
