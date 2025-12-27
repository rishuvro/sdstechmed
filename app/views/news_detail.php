<?php
$basePath = $basePath ?? '/sdstechmed/public';
?>

<!-- News Detail Hero -->
<section class="news-detail-hero">
  <div class="news-detail-hero__content">
    <div class="breadcrumb">
      <a href="<?= $basePath ?>/">Home</a>
      <span>›</span>
      <a href="<?= $basePath ?>/news">News</a>
      <span>›</span>
      <span><?= htmlspecialchars($post['title'] ?? '') ?></span>
    </div>

    <h1 class="news-detail-hero__title"><?= htmlspecialchars($post['title'] ?? '') ?></h1>

    <?php if (!empty($post['published_at'])): ?>
      <div class="news-detail-hero__meta">
        <span class="meta-chip">Published</span>
        <span class="muted">
          <?= htmlspecialchars(date('M d, Y H:i', strtotime($post['published_at']))) ?>
        </span>
      </div>
    <?php endif; ?>

    <div class="news-detail-hero__actions">
      <a class="btn btn--primary" href="<?= $basePath ?>/contact#form">Get Quote</a>
      <a class="btn btn--ghost" href="<?= $basePath ?>/products">Browse Products</a>
    </div>
  </div>

  <div class="news-detail-hero__media">
    <?php if (!empty($post['cover_image'])): ?>
      <img src="<?= $basePath ?>/uploads/news/<?= htmlspecialchars($post['cover_image']) ?>" alt="">
    <?php else: ?>
      <div class="news-detail-hero__placeholder">Cover Image</div>
    <?php endif; ?>
  </div>
</section>

<!-- Content -->
<section class="section">
  <div class="news-article">
    <div class="rich news-article__rich">
      <?= $post['content'] ?? '' ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section section--alt">
  <div class="cta-bar">
    <div>
      <h3>Want quotation or OEM/ODM?</h3>
      <p class="muted">Contact SDS Techmed for pricing, shipping, warranty and training.</p>
    </div>
    <a class="btn btn--primary" href="<?= $basePath ?>/contact#form">Contact Sales</a>
  </div>
</section>
