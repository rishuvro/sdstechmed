<?php
declare(strict_types=1);

require __DIR__ . '/../app/config/db.php';
$config = require __DIR__ . '/../app/config/config.php';

require __DIR__ . '/../app/helpers/auth.php';
require __DIR__ . '/../app/helpers/upload.php';
require __DIR__ . '/../app/helpers/csrf.php';

adminRequireLogin();

$uploadRoot = __DIR__ . '/../public/uploads'; // for hero image (existing)
$brandUploadRoot = __DIR__ . '/../public/uploads/brand'; // for header logo (new)

if (!is_dir($uploadRoot)) {
  @mkdir($uploadRoot, 0775, true);
}
if (!is_dir($brandUploadRoot)) {
  @mkdir($brandUploadRoot, 0775, true);
}

$msg = $_GET['msg'] ?? '';
$title = 'Settings';

function getSettingsRow(PDO $pdo): array {
  $stmt = $pdo->query("SELECT * FROM settings WHERE id=1");
  return $stmt->fetch() ?: [];
}

function removeFileIfExists(string $dir, ?string $filename): void {
  if (!$filename) return;
  $path = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $filename;
  if (is_file($path)) @unlink($path);
}

$settings = getSettingsRow($pdo);

require __DIR__ . '/partials/admin_header.php';

if ($msg): ?>
  <div class="msg"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_verify($_POST['_csrf'] ?? null)) {
    echo '<div class="card"><p class="muted">CSRF failed. Refresh and try again.</p></div>';
    require __DIR__ . '/partials/admin_footer.php';
    exit;
  }

  // Company fields (existing)
  $company_name = trim($_POST['company_name'] ?? 'SDS Techmed');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $whatsapp = trim($_POST['whatsapp'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $footer_text = trim($_POST['footer_text'] ?? '');

  // Hero fields (existing)
  $hero_title = trim($_POST['hero_title'] ?? '');
  $hero_subtitle = trim($_POST['hero_subtitle'] ?? '');
  $hero_button_text = trim($_POST['hero_button_text'] ?? '');
  $hero_button_url = trim($_POST['hero_button_url'] ?? '');

  // Upload hero image to public/uploads/
  $newHero = uploadImage($_FILES['hero_image'] ?? [], $uploadRoot);
  $finalHero = $settings['hero_image'] ?? null;

  if ($newHero) {
    removeFileIfExists($uploadRoot, $finalHero);
    $finalHero = $newHero;
  }
  if (!empty($_POST['remove_hero_image'])) {
    removeFileIfExists($uploadRoot, $finalHero);
    $finalHero = null;
  }

  // -----------------------------
  // HEADER SETTINGS (NEW)
  // -----------------------------
  $topbar_enabled  = !empty($_POST['topbar_enabled']) ? 1 : 0;
  $header_tagline  = trim($_POST['header_tagline'] ?? '');
  $header_cta_text = trim($_POST['header_cta_text'] ?? '');
  $header_cta_url  = trim($_POST['header_cta_url'] ?? '');
  $header_style    = trim($_POST['header_style'] ?? 'v1');

  // Upload header logo to public/uploads/brand/
  $newLogo = uploadImage($_FILES['header_logo'] ?? [], $brandUploadRoot);
  $finalLogo = $settings['header_logo'] ?? null;

  if ($newLogo) {
    removeFileIfExists($brandUploadRoot, $finalLogo);
    $finalLogo = $newLogo;
  }

  if (!empty($_POST['remove_header_logo'])) {
    removeFileIfExists($brandUploadRoot, $finalLogo);
    $finalLogo = null;
  }

  // Save all settings
  $stmt = $pdo->prepare("
    UPDATE settings
    SET company_name=?, email=?, phone=?, whatsapp=?, address=?, footer_text=?,
        hero_title=?, hero_subtitle=?, hero_button_text=?, hero_button_url=?, hero_image=?,
        topbar_enabled=?, header_tagline=?, header_cta_text=?, header_cta_url=?, header_logo=?, header_style=?
    WHERE id=1
  ");

  $stmt->execute([
    $company_name,
    $email ?: null,
    $phone ?: null,
    $whatsapp ?: null,
    $address ?: null,
    $footer_text ?: null,

    $hero_title ?: null,
    $hero_subtitle ?: null,
    $hero_button_text ?: null,
    $hero_button_url ?: null,
    $finalHero,

    $topbar_enabled,
    $header_tagline ?: null,
    $header_cta_text ?: null,
    $header_cta_url ?: null,
    $finalLogo,
    $header_style ?: 'v1',
  ]);

  header("Location: /sdstechmed/admin/settings.php?msg=" . urlencode("Settings updated."));
  exit;
}

$settings = getSettingsRow($pdo);
?>

<div class="card">
  <h2 style="margin-top:0;">Site Settings</h2>
  <p class="muted">Update company details, header style, footer text, and homepage hero section.</p>

  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">

    <h3>Company</h3>

    <div class="row">
      <div>
        <label>Company Name</label>
        <input name="company_name" value="<?= htmlspecialchars($settings['company_name'] ?? '') ?>">
      </div>
      <div>
        <label>Email</label>
        <input name="email" value="<?= htmlspecialchars($settings['email'] ?? '') ?>">
      </div>
    </div>

    <div class="row">
      <div>
        <label>Phone</label>
        <input name="phone" value="<?= htmlspecialchars($settings['phone'] ?? '') ?>">
      </div>
      <div>
        <label>WhatsApp</label>
        <input name="whatsapp" value="<?= htmlspecialchars($settings['whatsapp'] ?? '') ?>">
      </div>
    </div>

    <label>Address</label>
    <textarea name="address" rows="3"><?= htmlspecialchars($settings['address'] ?? '') ?></textarea>

    <label>Footer Text</label>
    <textarea name="footer_text" rows="2"><?= htmlspecialchars($settings['footer_text'] ?? '') ?></textarea>

    <!-- HEADER SETTINGS (NEW UI) -->
    <h3 style="margin-top:16px;">Header Settings</h3>

    <label style="display:flex;gap:10px;align-items:center;">
      <input type="checkbox" name="topbar_enabled" value="1" <?= !empty($settings['topbar_enabled']) ? 'checked' : '' ?>>
      Enable top bar (email/phone line)
    </label>

    <label>Header Tagline (small text under company name)</label>
    <input name="header_tagline"
           value="<?= htmlspecialchars($settings['header_tagline'] ?? '') ?>"
           placeholder="Laser • IPL • Body Shaping">

    <div class="row">
      <div>
        <label>Header CTA Button Text</label>
        <input name="header_cta_text"
               value="<?= htmlspecialchars($settings['header_cta_text'] ?? '') ?>"
               placeholder="Get Quote">
      </div>
      <div>
        <label>Header CTA Button URL</label>
        <input name="header_cta_url"
               value="<?= htmlspecialchars($settings['header_cta_url'] ?? '') ?>"
               placeholder="/sdstechmed/public/contact#form">
      </div>
    </div>

    <label>Header Style</label>
    <select name="header_style">
      <option value="v1" <?= (($settings['header_style'] ?? 'v1') === 'v1') ? 'selected' : '' ?>>V1 (Colorful Premium)</option>
      <option value="v2" <?= (($settings['header_style'] ?? '') === 'v2') ? 'selected' : '' ?>>V2 (Clean Minimal)</option>
    </select>

    <label>Header Logo</label>
    <?php if (!empty($settings['header_logo'])): ?>
      <div style="display:flex;gap:12px;align-items:center;margin:8px 0 6px;">
        <img class="thumb" style="width:140px;height:70px;object-fit:contain;"
             src="/sdstechmed/public/uploads/brand/<?= htmlspecialchars($settings['header_logo']) ?>" alt="">
        <div class="muted" style="font-size:13px;">
          Current: <?= htmlspecialchars($settings['header_logo']) ?><br>
          <label style="margin-top:6px;display:inline-block;">
            <input type="checkbox" name="remove_header_logo" value="1"> Remove current logo
          </label>
        </div>
      </div>
    <?php endif; ?>
    <input type="file" name="header_logo" accept=".jpg,.jpeg,.png,.webp">

    <!-- HERO (existing) -->
    <h3 style="margin-top:16px;">Homepage Hero</h3>

    <label>Hero Title</label>
    <input name="hero_title" value="<?= htmlspecialchars($settings['hero_title'] ?? '') ?>" placeholder="Medical Aesthetic Devices & Laser Solutions">

    <label>Hero Subtitle</label>
    <textarea name="hero_subtitle" rows="3" placeholder="Short summary..."><?= htmlspecialchars($settings['hero_subtitle'] ?? '') ?></textarea>

    <div class="row">
      <div>
        <label>Hero Button Text</label>
        <input name="hero_button_text" value="<?= htmlspecialchars($settings['hero_button_text'] ?? '') ?>" placeholder="Explore Products">
      </div>
      <div>
        <label>Hero Button URL</label>
        <input name="hero_button_url" value="<?= htmlspecialchars($settings['hero_button_url'] ?? '') ?>" placeholder="/sdstechmed/public/products">
      </div>
    </div>

    <label>Hero Image</label>
    <?php if (!empty($settings['hero_image'])): ?>
      <div style="display:flex;gap:12px;align-items:center;margin:8px 0 6px;">
        <img class="thumb" src="/sdstechmed/public/uploads/<?= htmlspecialchars($settings['hero_image']) ?>" alt="">
        <div class="muted" style="font-size:13px;">
          Current: <?= htmlspecialchars($settings['hero_image']) ?><br>
          <label style="margin-top:6px;display:inline-block;">
            <input type="checkbox" name="remove_hero_image" value="1"> Remove current hero image
          </label>
        </div>
      </div>
    <?php endif; ?>
    <input type="file" name="hero_image" accept=".jpg,.jpeg,.png,.webp">

    <div style="margin-top:14px;display:flex;gap:10px;align-items:center;">
      <button class="btn btn-primary" type="submit">Save Settings</button>
    </div>
  </form>
</div>

<?php require __DIR__ . '/partials/admin_footer.php'; ?>
