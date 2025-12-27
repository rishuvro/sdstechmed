<?php
$basePath = $basePath ?? '/sdstechmed/public';
$page = $page ?? ['title' => 'Page', 'content' => ''];
$title = $page['title'] ?? 'Page';
?>

<!-- Page Hero -->
<section class="page-hero page-hero--simple">
  <div class="page-hero__content">
    <div class="breadcrumb">
      <a href="<?= $basePath ?>/">Home</a>
      <span>›</span>
      <span><?= htmlspecialchars($title) ?></span>
    </div>

    <h1><?= htmlspecialchars($title) ?></h1>
    <p class="muted">Professional aesthetic devices, OEM/ODM support, and global delivery.</p>

    <div class="page-hero__actions">
      <a class="btn btn--primary" href="<?= $basePath ?>/contact#form">Get Quote</a>
      <a class="btn btn--ghost" href="<?= $basePath ?>/products">Browse Products</a>
    </div>
  </div>

  <div class="page-hero__badge">
    <div class="badge-card">
      <div class="badge-card__title">SDS Techmed</div>
      <div class="badge-card__text">Quality control • Training support • Warranty • Worldwide shipping</div>
    </div>
  </div>
</section>

<!-- Content -->
<section class="section">
  <div class="page-content">
    <div class="rich page-content__rich">
      <?= $page['content'] ?? '' ?>
    </div>
  </div>
</section>

<!-- Extra blocks (nice for About page) -->
<section class="section section--alt">
  <div class="about-grid">
    <div class="about-card">
      <div class="about-card__icon">🏭</div>
      <div class="about-card__title">Factory Direct</div>
      <div class="about-card__text muted">Manufacturing + strict QC + stable supply chain for global clients.</div>
    </div>

    <div class="about-card">
      <div class="about-card__icon">🧩</div>
      <div class="about-card__title">OEM/ODM Support</div>
      <div class="about-card__text muted">Branding, shell customization, software options and packaging support.</div>
    </div>

    <div class="about-card">
      <div class="about-card__icon">🚚</div>
      <div class="about-card__title">Worldwide Shipping</div>
      <div class="about-card__text muted">Air/sea shipping options with export documents and safe packing.</div>
    </div>

    <div class="about-card">
      <div class="about-card__icon">🎓</div>
      <div class="about-card__title">Training & After-sales</div>
      <div class="about-card__text muted">Video training, manuals, troubleshooting and long-term support.</div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section">
  <div class="cta-bar">
    <div>
      <h3>Need pricing, MOQ, or OEM/ODM?</h3>
      <p class="muted">Contact SDS Techmed for quotation, shipping, warranty and training.</p>
    </div>
    <a class="btn btn--primary" href="<?= $basePath ?>/contact#form">Contact Sales</a>
  </div>
</section>
