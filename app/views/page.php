<?php
// Variables expected: $page, $basePath
?>
<section class="section">
  <div class="breadcrumb">
    <a href="<?= $basePath ?>/">Home</a>
    <span>›</span>
    <span><?= htmlspecialchars($page['title'] ?? 'Page') ?></span>
  </div>

  <h1 style="color:#fff;margin-top:10px;"><?= htmlspecialchars($page['title'] ?? 'Page') ?></h1>
</section>

<section class="section">
  <div class="card">
    <div class="rich">
      <?= $page['content'] ?? '' ?>
    </div>
  </div>
</section>

<section class="section section--alt">
  <div class="cta-bar">
    <div>
      <h3>Need help choosing a device?</h3>
      <p class="muted">Contact SDS Techmed for quotation, OEM/ODM, warranty, shipping and training.</p>
    </div>
    <a class="btn btn--primary" href="<?= $basePath ?>/contact">Contact Sales</a>
  </div>
</section>
