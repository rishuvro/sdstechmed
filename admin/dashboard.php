<?php
declare(strict_types=1);

require __DIR__ . '/../app/config/db.php';
require __DIR__ . '/../app/helpers/auth.php';
adminRequireLogin();

$title = 'Dashboard';

// KPIs
$kpi = [];
$kpi['categories'] = (int)$pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$kpi['products']   = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$kpi['featured_products'] = (int)$pdo->query("SELECT COUNT(*) FROM products WHERE featured=1")->fetchColumn();
$kpi['active_products']   = (int)$pdo->query("SELECT COUNT(*) FROM products WHERE status='active'")->fetchColumn();
$kpi['news']       = (int)$pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
$kpi['inquiries']  = (int)$pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();

// Recent products
$recentProducts = $pdo->query("
  SELECT p.id, p.name, p.slug, p.status, p.featured, p.main_image, c.name AS category_name
  FROM products p
  JOIN categories c ON c.id=p.category_id
  ORDER BY p.id DESC
  LIMIT 6
")->fetchAll();

// Recent news
$recentNews = $pdo->query("
  SELECT id, title, slug, published_at
  FROM news
  ORDER BY published_at DESC, id DESC
  LIMIT 5
")->fetchAll();

// Recent inquiries
$recentInquiries = $pdo->query("
  SELECT id, name, email, whatsapp, created_at
  FROM inquiries
  ORDER BY id DESC
  LIMIT 6
")->fetchAll();

// Chart data: inquiries last 14 days
// (MySQL: DATE(created_at) grouping)
$days = 14;
$chartRows = $pdo->query("
  SELECT DATE(created_at) AS d, COUNT(*) AS c
  FROM inquiries
  WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL {$days} DAY)
  GROUP BY DATE(created_at)
  ORDER BY d ASC
")->fetchAll();

$countsByDate = [];
foreach ($chartRows as $r) {
  $countsByDate[$r['d']] = (int)$r['c'];
}

// Build last N days labels
$labels = [];
$values = [];
$dt = new DateTime('today');
$dt->modify("-{$days} day");
for ($i=0; $i<=$days; $i++) {
  $d = $dt->format('Y-m-d');
  $labels[] = $d;
  $values[] = $countsByDate[$d] ?? 0;
  $dt->modify("+1 day");
}

require __DIR__ . '/partials/admin_header.php';
?>

<div class="card" style="padding:16px;">
  <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;">
    <div>
      <h2 style="margin:0;">Welcome, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></h2>
      <div class="muted" style="margin-top:6px;">
        Manage SDS Techmed website content, products, inquiries, and settings.
      </div>
    </div>

    <div class="actions">
      <a class="btn btn-primary" href="/sdstechmed/admin/products.php?action=add">+ Add Product</a>
      <a class="btn" href="/sdstechmed/admin/categories.php?action=add">+ Add Category</a>
      <a class="btn" href="/sdstechmed/admin/news.php?action=add">+ Add News</a>
    </div>
  </div>
</div>

<!-- KPI Cards -->
<div class="row" style="margin-top:14px;">
  <div class="card">
    <div class="muted" style="font-size:12px;">Categories</div>
    <div style="font-size:28px;font-weight:900;"><?= $kpi['categories'] ?></div>
    <div class="muted" style="font-size:13px;">Homepage tiles & browsing</div>
  </div>

  <div class="card">
    <div class="muted" style="font-size:12px;">Products</div>
    <div style="font-size:28px;font-weight:900;"><?= $kpi['products'] ?></div>
    <div class="muted" style="font-size:13px;"><?= $kpi['active_products'] ?> active</div>
  </div>

  <div class="card">
    <div class="muted" style="font-size:12px;">Featured Products</div>
    <div style="font-size:28px;font-weight:900;"><?= $kpi['featured_products'] ?></div>
    <div class="muted" style="font-size:13px;">Shown on homepage</div>
  </div>

  <div class="card">
    <div class="muted" style="font-size:12px;">News Posts</div>
    <div style="font-size:28px;font-weight:900;"><?= $kpi['news'] ?></div>
    <div class="muted" style="font-size:13px;">Latest updates</div>
  </div>

  <div class="card">
    <div class="muted" style="font-size:12px;">Inquiries</div>
    <div style="font-size:28px;font-weight:900;"><?= $kpi['inquiries'] ?></div>
    <div class="muted" style="font-size:13px;">From contact form</div>
  </div>
</div>

<!-- Chart + Recent Activity -->
<div class="row" style="margin-top:14px;">
  <div class="card" style="grid-column:span 1;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;">
      <div>
        <h3 style="margin:0;">Inquiries (Last 14 Days)</h3>
        <div class="muted" style="font-size:13px;">Daily message volume</div>
      </div>
      <a class="btn" href="/sdstechmed/admin/inquiries.php">View all</a>
    </div>

    <div style="margin-top:12px;">
      <canvas id="inqChart" width="900" height="240" style="width:100%;height:240px;border-radius:14px;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.03);"></canvas>
    </div>
  </div>

  <div class="card">
    <h3 style="margin-top:0;">Recent Inquiries</h3>
    <?php if (empty($recentInquiries)): ?>
      <div class="muted">No inquiries yet.</div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Customer</th>
            <th>Contact</th>
            <th>Time</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($recentInquiries as $q): ?>
          <tr>
            <td>
              <strong>
                <a href="/sdstechmed/admin/inquiries.php?action=view&id=<?= (int)$q['id'] ?>">
                  <?= htmlspecialchars($q['name']) ?>
                </a>
              </strong>
            </td>
            <td class="muted" style="font-size:13px;">
              <?= htmlspecialchars($q['email'] ?? '') ?><br>
              <?= htmlspecialchars($q['whatsapp'] ?? '') ?>
            </td>
            <td class="muted" style="font-size:13px;">
              <?= htmlspecialchars($q['created_at']) ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<div class="row" style="margin-top:14px;">
  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;">
      <h3 style="margin:0;">Latest Products</h3>
      <a class="btn" href="/sdstechmed/admin/products.php">Manage</a>
    </div>

    <?php if (empty($recentProducts)): ?>
      <div class="muted" style="margin-top:8px;">No products yet.</div>
    <?php else: ?>
      <table style="margin-top:10px;">
        <thead>
          <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Status</th>
            <th>Featured</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($recentProducts as $p): ?>
          <tr>
            <td>
              <strong><?= htmlspecialchars($p['name']) ?></strong><br>
              <a class="muted" style="font-size:13px;" target="_blank" href="/sdstechmed/public/product/<?= htmlspecialchars($p['slug']) ?>">
                View on site →
              </a>
            </td>
            <td class="muted"><?= htmlspecialchars($p['category_name']) ?></td>
            <td><?= htmlspecialchars($p['status']) ?></td>
            <td><?= ((int)$p['featured'] === 1) ? 'Yes' : 'No' ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;">
      <h3 style="margin:0;">Latest News</h3>
      <a class="btn" href="/sdstechmed/admin/news.php">Manage</a>
    </div>

    <?php if (empty($recentNews)): ?>
      <div class="muted" style="margin-top:8px;">No news posts yet.</div>
    <?php else: ?>
      <div style="margin-top:10px;display:grid;gap:10px;">
        <?php foreach ($recentNews as $n): ?>
          <div class="card" style="padding:12px;">
            <strong><?= htmlspecialchars($n['title']) ?></strong>
            <div class="muted" style="font-size:13px;margin-top:4px;">
              <?= htmlspecialchars($n['published_at'] ?? '') ?>
            </div>
            <div style="margin-top:8px;display:flex;gap:10px;flex-wrap:wrap;">
              <a class="btn" href="/sdstechmed/admin/news.php?action=edit&id=<?= (int)$n['id'] ?>">Edit</a>
              <a class="btn" target="_blank" href="/sdstechmed/public/news/<?= htmlspecialchars($n['slug']) ?>">View</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
(() => {
  const labels = <?= json_encode($labels) ?>;
  const values = <?= json_encode($values) ?>;

  const canvas = document.getElementById('inqChart');
  const ctx = canvas.getContext('2d');

  // Resize for HiDPI
  const dpr = window.devicePixelRatio || 1;
  const rect = canvas.getBoundingClientRect();
  canvas.width = Math.floor(rect.width * dpr);
  canvas.height = Math.floor(rect.height * dpr);
  ctx.scale(dpr, dpr);

  const W = rect.width;
  const H = rect.height;

  const pad = {l:38, r:14, t:16, b:28};
  const innerW = W - pad.l - pad.r;
  const innerH = H - pad.t - pad.b;

  const maxV = Math.max(3, ...values);
  const xStep = innerW / Math.max(1, (values.length - 1));

  // background grid
  ctx.clearRect(0,0,W,H);
  ctx.globalAlpha = 1;

  // grid lines
  ctx.strokeStyle = 'rgba(255,255,255,.10)';
  ctx.lineWidth = 1;

  for (let i=0;i<=4;i++){
    const y = pad.t + (innerH * i/4);
    ctx.beginPath();
    ctx.moveTo(pad.l, y);
    ctx.lineTo(W - pad.r, y);
    ctx.stroke();
  }

  // axis labels (y)
  ctx.fillStyle = 'rgba(255,255,255,.65)';
  ctx.font = '12px system-ui, Arial';
  for (let i=0;i<=4;i++){
    const v = Math.round(maxV * (1 - i/4));
    const y = pad.t + (innerH * i/4) + 4;
    ctx.fillText(String(v), 10, y);
  }

  // line path
  const pts = values.map((v, i) => {
    const x = pad.l + xStep * i;
    const y = pad.t + innerH - (v/maxV) * innerH;
    return {x,y,v};
  });

  // line
  ctx.strokeStyle = 'rgba(255,255,255,.92)';
  ctx.lineWidth = 2;
  ctx.beginPath();
  pts.forEach((p, i) => {
    if (i === 0) ctx.moveTo(p.x, p.y);
    else ctx.lineTo(p.x, p.y);
  });
  ctx.stroke();

  // points
  ctx.fillStyle = 'rgba(255,255,255,.92)';
  pts.forEach(p => {
    ctx.beginPath();
    ctx.arc(p.x, p.y, 3, 0, Math.PI*2);
    ctx.fill();
  });

  // x labels (show every ~3 days)
  ctx.fillStyle = 'rgba(255,255,255,.55)';
  const stepLabel = 3;
  labels.forEach((lab, i) => {
    if (i % stepLabel !== 0 && i !== labels.length-1) return;
    const x = pad.l + xStep * i;
    const t = lab.slice(5); // MM-DD
    ctx.fillText(t, x - 14, H - 10);
  });
})();
</script>

<?php require __DIR__ . '/partials/admin_footer.php'; ?>
