<?php
$basePath = $basePath ?? '/sdstechmed/public';

$title = $category['seo_h1'] ?: $category['name'];
$desc  = $category['description'] ?? '';
$count = is_array($products) ? count($products) : 0;
?>

<!-- Category Hero -->
<section class="cat-hero">
  <div class="cat-hero__content">
    <div class="breadcrumb">
      <a href="<?= $basePath ?>/">Home</a>
      <span>›</span>
      <a href="<?= $basePath ?>/products">Products</a>
      <span>›</span>
      <span><?= htmlspecialchars($category['name']) ?></span>
    </div>

    <h1><?= htmlspecialchars($title) ?></h1>
    <?php if ($desc): ?>
      <p class="muted"><?= htmlspecialchars($desc) ?></p>
    <?php else: ?>
      <p class="muted">Explore our professional devices and models in this category.</p>
    <?php endif; ?>

    <div class="cat-hero__meta">
      <div class="meta-pill">
        <div class="meta-pill__top"><?= (int)$count ?></div>
        <div class="meta-pill__bot">Products</div>
      </div>
      <div class="meta-pill">
        <div class="meta-pill__top">OEM/ODM</div>
        <div class="meta-pill__bot">Supported</div>
      </div>
      <div class="meta-pill">
        <div class="meta-pill__top">Fast</div>
        <div class="meta-pill__bot">Delivery</div>
      </div>
    </div>

    <div class="cat-hero__actions">
      <a class="btn btn--primary" href="<?= $basePath ?>/contact#form">Get Quote</a>
      <a class="btn btn--ghost" href="<?= $basePath ?>/contact">Talk to Sales</a>
    </div>
  </div>

  <div class="cat-hero__media">
    <?php if (!empty($category['image'])): ?>
      <img src="<?= $basePath ?>/uploads/categories/<?= htmlspecialchars($category['image']) ?>" alt="">
    <?php else: ?>
      <div class="cat-hero__placeholder">Category Image</div>
    <?php endif; ?>
  </div>
</section>

<!-- Product Grid -->
<section class="section">
  <div class="section__head">
    <h2>Available Models</h2>
    <span class="muted">Click any product to view specifications and enquiry options.</span>
  </div>

  <?php if (empty($products)): ?>
    <div class="card" style="padding:18px;">
      <p class="muted" style="margin:0;">No products added in this category yet.</p>
    </div>
  <?php else: ?>
    <div class="grid grid--4 product-grid">
      <?php foreach ($products as $p): ?>
        <a class="p-card" href="<?= $basePath ?>/product/<?= htmlspecialchars($p['slug']) ?>">
          <div class="p-card__img">
            <?php if (!empty($p['main_image'])): ?>
              <img src="<?= $basePath ?>/uploads/products/<?= htmlspecialchars($p['main_image']) ?>" alt="">
            <?php else: ?>
              <div class="thumb__placeholder">Image</div>
            <?php endif; ?>

            <?php if (!empty($p['featured']) && (int)$p['featured'] === 1): ?>
              <div class="p-card__badge">Featured</div>
            <?php endif; ?>
          </div>

          <div class="p-card__body">
            <div class="p-card__title"><?= htmlspecialchars($p['name']) ?></div>
            <div class="p-card__text"><?= htmlspecialchars($p['short_description'] ?? '') ?></div>

            <div class="p-card__foot">
              <span class="p-card__cta">View details →</span>
              <span class="p-card__mini">Enquiry</span>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
