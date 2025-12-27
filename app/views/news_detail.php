<section class="section">
  <div class="breadcrumb">
    <a href="<?= $basePath ?>/">Home</a>
    <span>›</span>
    <a href="<?= $basePath ?>/news">News</a>
    <span>›</span>
    <span><?= htmlspecialchars($post['title']) ?></span>
  </div>

  <h1 style="color:#fff;margin-top:10px;"><?= htmlspecialchars($post['title']) ?></h1>

  <?php if (!empty($post['published_at'])): ?>
    <div class="muted" style="margin:6px 0 14px;">
      Published: <?= htmlspecialchars(date('M d, Y H:i', strtotime($post['published_at']))) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($post['cover_image'])): ?>
    <div class="card" style="padding:12px;">
      <img src="<?= $basePath ?>/uploads/news/<?= htmlspecialchars($post['cover_image']) ?>"
           alt=""
           style="width:100%;border-radius:14px;border:1px solid rgba(255,255,255,.12);">
    </div>
  <?php endif; ?>
</section>

<section class="section">
  <div class="rich">
    <?= $post['content'] ?>
  </div>
</section>

<section class="section section--alt">
  <div class="cta-bar">
    <div>
      <h3>Want quotation or OEM/ODM?</h3>
      <p class="muted">Contact SDS Techmed for pricing, shipping, warranty and training.</p>
    </div>
    <a class="btn btn--primary" href="<?= $basePath ?>/contact">Contact Sales</a>
  </div>
</section>
