<?php
$basePath = $basePath ?? '/sdstechmed/public';
$settings = $settings ?? [];
$siteName = $settings['company_name'] ?? 'SDS Techmed';

/* Floating CTA helpers */
$whatsappRaw = trim((string)($settings['whatsapp'] ?? ''));
$waDigits = preg_replace('/\D+/', '', $whatsappRaw);
$waLink = $waDigits ? ('https://wa.me/' . $waDigits) : '';
$quoteUrl = $basePath . '/contact#form';
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
            <?php if ($waLink): ?>
              <a target="_blank" rel="noopener" href="<?= htmlspecialchars($waLink) ?>">
                <?= htmlspecialchars($settings['whatsapp']) ?>
              </a>
            <?php else: ?>
              <div><?= htmlspecialchars($settings['whatsapp']) ?></div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <a class="site-footer__cta" href="<?= $quoteUrl ?>">Get Quotation</a>
      </div>

    </div>

    <div class="site-footer__bottom">
      <div class="site-footer__copy">
        <?= $settings['footer_text'] ?? ('© ' . date('Y') . ' ' . htmlspecialchars($siteName) . '. All rights reserved.') ?>
      </div>
    </div>
  </div>
</footer>

<?php if ($waLink || $quoteUrl): ?>
  <div class="float-cta" aria-label="Quick contact">
    <?php if ($waLink): ?>
      <a class="float-cta__btn float-cta__btn--wa"
         href="<?= htmlspecialchars($waLink) ?>"
         target="_blank" rel="noopener"
         aria-label="Chat on WhatsApp">
        <span class="float-cta__icon" aria-hidden="true">
          <!-- WhatsApp SVG (real logo) -->
          <svg viewBox="0 0 32 32" width="18" height="18" xmlns="http://www.w3.org/2000/svg">
            <path fill="currentColor" d="M19.11 17.41c-.27-.14-1.58-.78-1.82-.87-.24-.09-.42-.14-.6.14-.18.27-.69.87-.84 1.05-.15.18-.31.2-.58.07-.27-.14-1.13-.42-2.15-1.34-.79-.7-1.33-1.57-1.49-1.84-.16-.27-.02-.41.12-.55.13-.13.27-.31.4-.46.13-.15.18-.27.27-.45.09-.18.04-.34-.02-.48-.07-.14-.6-1.45-.82-1.98-.22-.53-.44-.46-.6-.47-.15-.01-.34-.01-.52-.01s-.48.07-.73.34c-.25.27-.96.94-.96 2.29 0 1.35.99 2.66 1.13 2.84.14.18 1.95 2.98 4.72 4.18.66.28 1.18.45 1.58.57.66.21 1.26.18 1.73.11.53-.08 1.58-.64 1.8-1.26.22-.62.22-1.15.15-1.26-.07-.11-.24-.18-.51-.32z"/>
            <path fill="currentColor" d="M26.73 5.27C23.96 2.5 20.26 1 16.33 1 8.48 1 2.1 7.38 2.1 15.23c0 2.51.66 4.97 1.91 7.14L2 31l8.84-1.9a14.15 14.15 0 0 0 5.49 1.11h.01c7.85 0 14.23-6.38 14.23-14.23 0-3.93-1.5-7.63-4.27-10.71zM16.34 27.8h-.01c-1.85 0-3.66-.5-5.25-1.44l-.38-.22-5.24 1.12 1.12-5.11-.25-.39a11.55 11.55 0 0 1-1.78-6.15C4.55 9.09 9.81 3.83 16.33 3.83c3.16 0 6.13 1.23 8.37 3.47a11.76 11.76 0 0 1 3.46 8.35c0 6.52-5.3 11.75-11.82 12.15z"/>
          </svg>
        </span>
        <span class="float-cta__text">WhatsApp</span>
      </a>
    <?php endif; ?>

    <a class="float-cta__btn float-cta__btn--quote"
       href="<?= htmlspecialchars($quoteUrl) ?>"
       aria-label="Get quotation">
      <span class="float-cta__icon" aria-hidden="true">⚡</span>
      <span class="float-cta__text">Quote</span>
    </a>
  </div>
<?php endif; ?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
<script src="<?= $basePath ?>/assets/js/animations.js?v=1"></script>
</body>
</html>
