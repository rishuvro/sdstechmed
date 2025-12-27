<?php
declare(strict_types=1);

require __DIR__ . '/../app/config/db.php';

require __DIR__ . '/../app/helpers/auth.php';
require __DIR__ . '/../app/helpers/csrf.php';

adminRequireLogin();

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = $_GET['msg'] ?? '';
$title = 'Pages';

function findPage(PDO $pdo, int $id): ?array {
  $stmt = $pdo->prepare("SELECT * FROM pages WHERE id=?");
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  return $row ?: null;
}

require __DIR__ . '/partials/admin_header.php';

if ($msg): ?>
  <div class="msg"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<?php
// SAVE EDIT
if ($action === 'edit' && $id > 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_verify($_POST['_csrf'] ?? null)) {
    echo '<div class="card"><p class="muted">CSRF failed. Refresh and try again.</p></div>';
    require __DIR__ . '/partials/admin_footer.php';
    exit;
  }

  $titleInput = trim($_POST['title'] ?? '');
  $content = trim($_POST['content'] ?? '');

  if ($titleInput === '' || $content === '') {
    echo '<div class="card"><p class="muted">Title and content are required.</p></div>';
  } else {
    $stmt = $pdo->prepare("UPDATE pages SET title=?, content=? WHERE id=?");
    $stmt->execute([$titleInput, $content, $id]);
    header("Location: /sdstechmed/admin/pages.php?msg=" . urlencode("Page updated."));
    exit;
  }
}

// EDIT FORM
if ($action === 'edit' && $id > 0) {
  $page = findPage($pdo, $id);
  if (!$page) {
    echo '<div class="card"><p class="muted">Page not found.</p></div>';
    require __DIR__ . '/partials/admin_footer.php';
    exit;
  }
  ?>
  <div class="card">
    <h2 style="margin-top:0;">Edit Page</h2>
    <div class="muted" style="font-size:13px;margin-bottom:10px;">
      Slug: <strong><?= htmlspecialchars($page['slug']) ?></strong>
    </div>

    <form method="post">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">

      <label>Title *</label>
      <input name="title" required value="<?= htmlspecialchars($page['title']) ?>">

      <label>Content (HTML allowed) *</label>
      <textarea name="content" rows="14" required placeholder="<h3>Section</h3><p>Text...</p>"><?= htmlspecialchars($page['content']) ?></textarea>

      <div style="margin-top:14px;display:flex;gap:10px;align-items:center;">
        <button class="btn btn-primary" type="submit">Save</button>
        <a class="btn" href="/sdstechmed/admin/pages.php">Back</a>
      </div>
    </form>
  </div>
  <?php
  require __DIR__ . '/partials/admin_footer.php';
  exit;
}

// LIST
$rows = $pdo->query("SELECT * FROM pages ORDER BY slug ASC")->fetchAll();
?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;">
    <div>
      <h2 style="margin:0;">Pages</h2>
      <div class="muted" style="font-size:13px;">Edit About, FAQ, Privacy, Terms, Service & Privacy, Home content blocks.</div>
    </div>
  </div>

  <div style="margin-top:12px;overflow:auto;">
    <table>
      <thead>
        <tr>
          <th>Slug</th>
          <th>Title</th>
          <th>Updated</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td class="muted"><?= htmlspecialchars($r['slug']) ?></td>
          <td><strong><?= htmlspecialchars($r['title']) ?></strong></td>
          <td class="muted"><?= htmlspecialchars($r['updated_at']) ?></td>
          <td>
            <div class="actions">
              <a class="btn" href="/sdstechmed/admin/pages.php?action=edit&id=<?= (int)$r['id'] ?>">Edit</a>
              <a class="btn" href="/sdstechmed/public/<?= htmlspecialchars($r['slug']) ?>" target="_blank">View</a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/partials/admin_footer.php'; ?>
