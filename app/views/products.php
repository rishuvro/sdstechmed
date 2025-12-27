<?php
// Variables expected: $cats, $basePath
?>
<section class="section">
  <div class="section__head">
    <h2>Products</h2>
    <span class="muted">Browse categories to explore SDS Techmed devices.</span>
  </div>

  <div class="grid grid--4">
    <?php foreach ($cats as $c): ?>
      <a class="card card--category" href="<?= $basePath ?>/category/<?= htmlspecialchars($c['slug']) ?>">
        <div class="thumb thumb--cat">
          <?php if (!empty($c['image'])): ?>
            <img src="<?= $basePath ?>/uploads/categories/<?= htmlspecialchars($c['image']) ?>" alt="<?= htmlspecialchars($c['name']) ?>">
          <?php else: ?>
            <div class="thumb__placeholder">Category</div>
          <?php endif; ?>
        </div>

        <div class="card__title"><?= htmlspecialchars($c['name']) ?></div>
        <div class="card__text"><?= htmlspecialchars($c['seo_h1'] ?? '') ?></div>
        <div class="card__cta">View products →</div>
      </a>
    <?php endforeach; ?>
  </div>
</section>
