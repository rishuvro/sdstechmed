<?php
$basePath = $basePath ?? '/sdstechmed/public';
$news = $news ?? []; // safety
?>

<!-- News Hero -->
<section class="news-hero">
  <div class="news-hero__content">
    <div class="breadcrumb">
      <a href="<?= $basePath ?>/">Home</a>
      <span>›</span>
      <span>News</span>
    </div>

    <h1>News & Updates</h1>
    <p class="muted">Latest updates, product launches, and industry insights from SDS Techmed.</p>

    <div class="news-hero__actions">
      <a class="btn btn--primary" href="<?= $basePath ?>/contact#form">Get Quote</a>
      <a class="btn btn--ghost" href="<?= $basePath ?>/products">Browse Products</a>
    </div>
  </div>

  <div class="news-hero__badge">
    <div class="badge-card">
      <div class="badge-card__title">Stay Updated</div>
      <div class="badge-card__text">Follow our newest launches, training updates, and OEM/ODM offers.</div>
    </div>
  </div>
</section>

<!-- News Grid -->
<section class="section">
  <div class="section__head">
    <h2>Latest Articles</h2>
    <span class="muted">Click to read full details.</span>
  </div>

  <?php if (empty($news)): ?>
    <div class="card" style="padding:18px;">
      <p class="muted" style="margin:0;">No news posts yet.</p>
    </div>
  <?php else: ?>
    <div class="grid grid--3 news-grid">
      <?php foreach ($news as $n): ?>
        <a class="n-card" href="<?= $basePath ?>/news/<?= htmlspecialchars($n['slug'] ?? '') ?>">
          <div class="n-card__img">
            <?php if (!empty($n['cover_image'])): ?>
              <img src="<?= $basePath ?>/uploads/news/<?= htmlspecialchars($n['cover_image']) ?>" alt="">
            <?php else: ?>
              <div class="thumb__placeholder">News</div>
            <?php endif; ?>

            <?php if (!empty($n['published_at'])): ?>
              <div class="n-card__date">
                <?= htmlspecialchars(date('M d, Y', strtotime($n['published_at']))) ?>
              </div>
            <?php endif; ?>
          </div>

          <div class="n-card__body">
            <div class="n-card__title"><?= htmlspecialchars($n['title'] ?? '') ?></div>
            <div class="n-card__text"><?= htmlspecialchars($n['excerpt'] ?? '') ?></div>
            <div class="n-card__cta">Read article →</div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
