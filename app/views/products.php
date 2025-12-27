<?php
// Variables expected: $cats, $basePath
$basePath = $basePath ?? '/sdstechmed/public';
?>

<!-- Products Page Hero -->
<section class="page-hero">
  <div class="page-hero__content">
    <h1>Products</h1>
    <p class="muted">Browse categories to explore SDS Techmed devices.</p>

    <div class="page-hero__actions">
      <a class="btn btn--primary" href="<?= $basePath ?>/contact#form">Get Quote</a>
      <a class="btn btn--ghost" href="<?= $basePath ?>/contact">Talk to Sales</a>
    </div>
  </div>

  <div class="page-hero__badge">
    <div class="badge-card">
      <div class="badge-card__title">Factory Direct</div>
      <div class="badge-card__text">OEM/ODM supported • Fast delivery • Global shipping</div>
    </div>
  </div>
</section>

<!-- Categories Grid -->
<section class="section">
  <div class="section__head">
    <h2>Explore Categories</h2>
    <span class="muted">Select a category to view available machines and models.</span>
  </div>

  <div class="grid grid--4 cat-grid">
    <?php foreach ($cats as $c): ?>
      <a class="cat-tile cat-tile--page" href="<?= $basePath ?>/category/<?= htmlspecialchars($c['slug']) ?>">
        <div class="cat-tile__head"><?= htmlspecialchars($c['name']) ?></div>

        <div class="cat-tile__img">
          <?php if (!empty($c['image'])): ?>
            <img src="<?= $basePath ?>/uploads/categories/<?= htmlspecialchars($c['image']) ?>"
                 alt="<?= htmlspecialchars($c['name']) ?>">
          <?php else: ?>
            <div class="cat-tile__placeholder">Category Image</div>
          <?php endif; ?>
        </div>

        <div class="cat-tile__foot">
          <span class="cat-tile__sub">
            <?= htmlspecialchars($c['seo_h1'] ?? 'Professional devices & solutions') ?>
          </span>
          <span class="cat-tile__go">View products →</span>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>
