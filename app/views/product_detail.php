<?php
$basePath = $basePath ?? '/sdstechmed/public';

$img = !empty($product['main_image'])
  ? ($basePath . '/uploads/products/' . $product['main_image'])
  : '';

$catUrl = $basePath . '/category/' . ($product['category_slug'] ?? '');
?>

<!-- Product Hero -->
<section class="pd-hero">
  <div class="pd-hero__media">
    <div class="pd-card">
      <div class="pd-card__img">
        <?php if ($img): ?>
          <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($product['name'] ?? '') ?>">
        <?php else: ?>
          <div class="thumb__placeholder">Product Image</div>
        <?php endif; ?>
      </div>

      <div class="pd-card__strip">
        <div class="pd-chip">Factory Direct</div>
        <div class="pd-chip">OEM/ODM</div>
        <div class="pd-chip">Warranty</div>
        <div class="pd-chip">Training</div>
      </div>
    </div>
  </div>

  <div class="pd-hero__info">
    <div class="breadcrumb">
      <a href="<?= $basePath ?>/">Home</a>
      <span>›</span>
      <a href="<?= $basePath ?>/products">Products</a>
      <span>›</span>
      <a href="<?= htmlspecialchars($catUrl) ?>">
        <?= htmlspecialchars($product['category_name'] ?? 'Category') ?>
      </a>
    </div>

    <h1 class="pd-title"><?= htmlspecialchars($product['name'] ?? '') ?></h1>
    <p class="muted pd-sub"><?= htmlspecialchars($product['short_description'] ?? '') ?></p>

    <div class="pd-actions">
      <a class="btn btn--primary" href="<?= $basePath ?>/contact#form">Get Quotation</a>
      <a class="btn btn--ghost" href="<?= $basePath ?>/contact">Enquiry Now</a>
    </div>

    <!-- Quick info box -->
    <div class="pd-infoBox">
      <div class="pd-infoBox__row">
        <span class="pd-infoBox__k">Category</span>
        <span class="pd-infoBox__v"><?= htmlspecialchars($product['category_name'] ?? '-') ?></span>
      </div>
      <div class="pd-infoBox__row">
        <span class="pd-infoBox__k">Shipping</span>
        <span class="pd-infoBox__v">Worldwide (Air/Sea)</span>
      </div>
      <div class="pd-infoBox__row">
        <span class="pd-infoBox__k">Support</span>
        <span class="pd-infoBox__v">Video training + manual</span>
      </div>
    </div>
  </div>
</section>

<!-- Specs + Overview -->
<section class="section pd-body">
  <div class="pd-grid">
    <div class="pd-left">
      <div class="pd-panel">
        <h2 class="pd-h2">Overview</h2>
        <div class="rich pd-rich">
          <?= $product['description'] ?? '' ?>
        </div>
      </div>
    </div>

    <aside class="pd-right">
      <?php if (!empty($product['specs'])): ?>
        <div class="pd-panel pd-panel--sticky">
          <h2 class="pd-h2">Specifications</h2>

          <?php if (isJsonArray($product['specs'])): ?>
            <?php $rows = json_decode($product['specs'], true); ?>
            <table class="table pd-table">
              <tbody>
                <?php foreach ($rows as $r): ?>
                  <tr>
                    <th><?= htmlspecialchars($r['key'] ?? '') ?></th>
                    <td><?= htmlspecialchars($r['value'] ?? '') ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div class="rich pd-rich"><?= $product['specs'] ?></div>
          <?php endif; ?>

          <div class="pd-ctaCard">
            <div class="pd-ctaCard__t">Need OEM/ODM or Bulk Pricing?</div>
            <div class="pd-ctaCard__d muted">
              Talk to SDS Techmed for customization, training, warranty and shipping.
            </div>
            <a class="btn btn--primary" href="<?= $basePath ?>/contact#form">Contact Sales</a>
          </div>
        </div>
      <?php else: ?>
        <div class="pd-panel pd-panel--sticky">
          <h2 class="pd-h2">Get a Quote</h2>
          <p class="muted">Ask our team for specs, pricing, training and shipping options.</p>
          <a class="btn btn--primary" href="<?= $basePath ?>/contact#form">Contact Sales</a>
        </div>
      <?php endif; ?>
    </aside>
  </div>
</section>

<!-- Bottom CTA -->
<section class="section section--alt">
  <div class="cta-bar">
    <div>
      <h3>Need OEM/ODM or Bulk Pricing?</h3>
      <p class="muted">Talk to SDS Techmed sales for customization, training, warranty and shipping.</p>
    </div>
    <a class="btn btn--primary" href="<?= $basePath ?>/contact#form">Contact Sales</a>
  </div>
</section>
