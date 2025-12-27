<?php
declare(strict_types=1);

require __DIR__ . '/../app/config/db.php';
require __DIR__ . '/../app/config/config.php';

require __DIR__ . '/../app/helpers/auth.php';
require __DIR__ . '/../app/helpers/csrf.php';

adminRequireLogin();

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = $_GET['msg'] ?? '';
$title = 'Inquiries';

require __DIR__ . '/partials/admin_header.php';

if ($msg): ?>
  <div class="msg"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<?php
// DELETE inquiry (POST)
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

  $stmt = $pdo->prepare("DELETE FROM inquiries WHERE id=?");
  $stmt->execute([$id]);

  header("Location: /sdstechmed/admin/inquiries.php?msg=" . urlencode("Inquiry deleted."));
  exit;
}

// VIEW SINGLE
if ($action === 'view' && $id > 0) {
  $stmt = $pdo->prepare("SELECT * FROM inquiries WHERE id=?");
  $stmt->execute([$id]);
  $q = $stmt->fetch();

  if (!$q) {
    echo '<div class="card"><p class="muted">Inquiry not found.</p></div>';
    require __DIR__ . '/partials/admin_footer.php';
    exit;
  }
  ?>
  <div class="card">
    <h2 style="margin-top:0;">Inquiry Details</h2>

    <div class="row">
      <div>
        <label>Name</label>
        <input value="<?= htmlspecialchars($q['name']) ?>" disabled>
      </div>
      <div>
        <label>Received</label>
        <input value="<?= htmlspecialchars($q['created_at']) ?>" disabled>
      </div>
    </div>

    <div class="row">
      <div>
        <label>Email</label>
        <input value="<?= htmlspecialchars($q['email'] ?? '') ?>" disabled>
      </div>
      <div>
        <label>WhatsApp</label>
        <input value="<?= htmlspecialchars($q['whatsapp'] ?? '') ?>" disabled>
      </div>
    </div>

    <label>Message</label>
    <textarea rows="8" disabled><?= htmlspecialchars($q['message']) ?></textarea>

    <div style="margin-top:14px;display:flex;gap:10px;align-items:center;">
      <a class="btn" href="/sdstechmed/admin/inquiries.php">Back</a>

      <form method="post" action="/sdstechmed/admin/inquiries.php?action=delete&id=<?= (int)$q['id'] ?>"
            onsubmit="return confirm('Delete this inquiry?');">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
        <button class="btn danger" type="submit">Delete</button>
      </form>
    </div>
  </div>
  <?php
  require __DIR__ . '/partials/admin_footer.php';
  exit;
}

// LIST + Search
$search = trim($_GET['q'] ?? '');
$params = [];
$sql = "SELECT * FROM inquiries";

if ($search !== '') {
  $sql .= " WHERE name LIKE ? OR email LIKE ? OR whatsapp LIKE ? OR message LIKE ?";
  $params = ["%$search%","%$search%","%$search%","%$search%"];
}

$sql .= " ORDER BY id DESC LIMIT 200";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>

<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
    <div>
      <h2 style="margin:0;">Inquiries</h2>
      <div class="muted" style="font-size:13px;">Messages submitted from Contact form.</div>
    </div>
  </div>

  <form method="get" style="margin-top:12px;display:grid;grid-template-columns:1fr auto auto;gap:10px;align-items:end;">
    <div>
      <label>Search</label>
      <input name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search name/email/whatsapp/message...">
    </div>
    <div>
      <button class="btn" type="submit">Search</button>
      <a class="btn" href="/sdstechmed/admin/inquiries.php">Reset</a>
    </div>
  </form>

  <div style="margin-top:12px;overflow:auto;">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>WhatsApp</th>
          <th>Received</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td class="muted"><?= (int)$r['id'] ?></td>
          <td><strong><?= htmlspecialchars($r['name']) ?></strong></td>
          <td class="muted"><?= htmlspecialchars($r['email'] ?? '') ?></td>
          <td class="muted"><?= htmlspecialchars($r['whatsapp'] ?? '') ?></td>
          <td class="muted"><?= htmlspecialchars($r['created_at']) ?></td>
          <td>
            <div class="actions">
              <a class="btn" href="/sdstechmed/admin/inquiries.php?action=view&id=<?= (int)$r['id'] ?>">View</a>
              <form method="post" action="/sdstechmed/admin/inquiries.php?action=delete&id=<?= (int)$r['id'] ?>"
                    onsubmit="return confirm('Delete this inquiry?');">
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
