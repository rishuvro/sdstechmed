<section class="section">
  <h1><?= htmlspecialchars($category['seo_h1'] ?: $category['name']) ?></h1>
  <p class="muted"><?= htmlspecialchars($category['description'] ?? '') ?></p>
</section>

<section class="section">
  <div class="grid grid--4">
    <?php foreach ($products as $p): ?>
      <a class="card card--product" href="/sdstechmed/public/product/<?= htmlspecialchars($p['slug']) ?>">
        <div class="thumb">
          <?php if (!empty($p['main_image'])): ?>
            <img src="/sdstechmed/public/uploads/products/<?= htmlspecialchars($p['main_image']) ?>" alt="">
          <?php else: ?>
            <div class="thumb__placeholder">Image</div>
          <?php endif; ?>
        </div>
        <div class="card__title"><?= htmlspecialchars($p['name']) ?></div>
        <div class="card__text"><?= htmlspecialchars($p['short_description'] ?? '') ?></div>
        <div class="card__cta">More →</div>
      </a>
    <?php endforeach; ?>
  </div>
</section>
