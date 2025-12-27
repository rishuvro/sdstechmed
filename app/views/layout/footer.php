<?php
$basePath = $basePath ?? '/sdstechmed/public';
$settings = $settings ?? [];
$siteName = $settings['company_name'] ?? 'SDS Techmed';
?>
</main>

<footer class="site-footer">
  <div class="site-footer__wrap">
    <div class="site-footer__grid">

      <!-- Company -->
      <div class="site-footer__col">
        <div class="site-footer__brand"><?= htmlspecialchars($siteName) ?></div>
        <p class="site-footer__text">
          Advanced medical aesthetic devices & laser solutions for clinics, salons, and distributors.
        </p>

        <?php if (!empty($settings['address'])): ?>
          <div class="site-footer__meta">
            <div class="site-footer__label">Address</div>
            <div><?= nl2br(htmlspecialchars($settings['address'])) ?></div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Quick Links -->
      <div class="site-footer__col">
        <div class="site-footer__title">Quick Links</div>
        <ul class="site-footer__links">
          <li><a href="<?= $basePath ?>/products">Products</a></li>
          <li><a href="<?= $basePath ?>/about">About</a></li>
          <li><a href="<?= $basePath ?>/news">News</a></li>
          <li><a href="<?= $basePath ?>/contact">Contact</a></li>
        </ul>
      </div>

      <!-- Policies -->
      <div class="site-footer__col">
        <div class="site-footer__title">Policies</div>
        <ul class="site-footer__links">
          <li><a href="<?= $basePath ?>/service-privacy">Service & Privacy</a></li>
          <li><a href="<?= $basePath ?>/privacy-policy">Privacy Policy</a></li>
          <li><a href="<?= $basePath ?>/terms">Terms of Service</a></li>
          <li><a href="<?= $basePath ?>/faq">FAQs</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div class="site-footer__col">
        <div class="site-footer__title">Contact</div>

        <?php if (!empty($settings['email'])): ?>
          <div class="site-footer__meta">
            <div class="site-footer__label">Email</div>
            <a href="mailto:<?= htmlspecialchars($settings['email']) ?>"><?= htmlspecialchars($settings['email']) ?></a>
          </div>
        <?php endif; ?>

        <?php if (!empty($settings['phone'])): ?>
          <div class="site-footer__meta">
            <div class="site-footer__label">Phone</div>
            <a href="tel:<?= htmlspecialchars($settings['phone']) ?>"><?= htmlspecialchars($settings['phone']) ?></a>
          </div>
        <?php endif; ?>

        <?php if (!empty($settings['whatsapp'])): ?>
          <div class="site-footer__meta">
            <div class="site-footer__label">WhatsApp</div>
            <div><?= htmlspecialchars($settings['whatsapp']) ?></div>
          </div>
        <?php endif; ?>

        <a class="site-footer__cta" href="<?= $basePath ?>/contact#form">Get Quotation</a>
      </div>

    </div>

    <div class="site-footer__bottom">
      <div class="site-footer__copy">
        <?= $settings['footer_text'] ?? ('© ' . date('Y') . ' ' . htmlspecialchars($siteName) . '. All rights reserved.') ?>
      </div>
    </div>
  </div>
</footer>

</body>
</html>
