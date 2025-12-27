<section class="hero">
  <div class="hero__content">
    <h1><?= htmlspecialchars($settings['hero_title'] ?? 'SDS Techmed') ?></h1>
    <p><?= htmlspecialchars($settings['hero_subtitle'] ?? '') ?></p>
    <div class="hero__actions">
      <a class="btn btn--primary" href="<?= htmlspecialchars($settings['hero_button_url'] ?? '/sdstechmed/public/products') ?>">
        <?= htmlspecialchars($settings['hero_button_text'] ?? 'View Products') ?>
      </a>
      <a class="btn btn--ghost" href="/sdstechmed/public/contact">Contact Sales</a>
    </div>
  </div>

  <div class="hero__media">
    <?php if (!empty($settings['hero_image'])): ?>
  <img src="<?= $basePath ?>/uploads/<?= htmlspecialchars($settings['hero_image']) ?>" alt="Hero">
<?php else: ?>
  <div class="hero__placeholder">Hero Image</div>
<?php endif; ?>

  </div>
</section>

<section class="section">
  <div class="section__head">
    <h2>Popular Categories</h2>
    <a class="link" href="/sdstechmed/public/products">View all</a>
  </div>

  <div class="grid grid--4 cat-grid">
  <?php foreach ($cats as $c): ?>
    <a class="cat-tile" href="<?= $basePath ?>/category/<?= htmlspecialchars($c['slug']) ?>">
      <div class="cat-tile__head">
        <?= htmlspecialchars($c['name']) ?>
      </div>

      <div class="cat-tile__img">
        <?php if (!empty($c['image'])): ?>
          <img src="<?= $basePath ?>/uploads/categories/<?= htmlspecialchars($c['image']) ?>"
               alt="<?= htmlspecialchars($c['name']) ?>">
        <?php else: ?>
          <div class="cat-tile__placeholder">Category Image</div>
        <?php endif; ?>
      </div>
    </a>
  <?php endforeach; ?>
</div>

</section>


<section class="section section--alt">
  <div class="section__head">
    <h2>Featured Products</h2>
  </div>

  <div class="grid grid--4">
    <?php foreach ($featured as $p): ?>
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
        <div class="card__cta">View details →</div>
      </a>
      
    <?php endforeach; ?>
  </div>
</section>


<section class="section">
  <div class="section__head">
    <h2>Latest News</h2>
    <a class="link" href="/sdstechmed/public/news">View more</a>
  </div>

  <div class="grid grid--3">
    <?php foreach ($latestNews as $n): ?>
      <a class="card card--news" href="/sdstechmed/public/news/<?= htmlspecialchars($n['slug']) ?>">
        <div class="thumb">
          <?php if (!empty($n['cover_image'])): ?>
            <img src="/sdstechmed/public/uploads/news/<?= htmlspecialchars($n['cover_image']) ?>" alt="">
          <?php else: ?>
            <div class="thumb__placeholder">News</div>
          <?php endif; ?>
        </div>
        <div class="card__title"><?= htmlspecialchars($n['title']) ?></div>
        <div class="card__text"><?= htmlspecialchars($n['excerpt'] ?? '') ?></div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

