<?php
// Variables expected: $settings, $basePath
$sent = isset($_GET['sent']) && $_GET['sent'] === '1';
?>
<section class="section">
  <div class="section__head">
    <h2>Contact Us</h2>
    <span class="muted">Quotation, OEM/ODM, shipping, warranty & support</span>
  </div>

  <?php if ($sent): ?>
    <div class="card" style="border-color:rgba(34,197,94,.35);">
      <strong>Thank you!</strong>
      <div class="muted">Your message has been received. We will contact you shortly.</div>
    </div>
  <?php endif; ?>

  <div class="grid" style="grid-template-columns:1fr 1fr;gap:14px;">
    <!-- Contact Info -->
    <div class="card">
      <h3 style="margin-top:0;">Company Info</h3>

      <div class="muted" style="margin-bottom:8px;">Company</div>
      <div style="font-weight:800;margin-bottom:12px;">
        <?= htmlspecialchars($settings['company_name'] ?? 'SDS Techmed') ?>
      </div>

      <?php if (!empty($settings['email'])): ?>
        <div class="muted">Email</div>
        <div style="margin-bottom:10px;">
          <a class="link" href="mailto:<?= htmlspecialchars($settings['email']) ?>">
            <?= htmlspecialchars($settings['email']) ?>
          </a>
        </div>
      <?php endif; ?>

      <?php if (!empty($settings['phone'])): ?>
        <div class="muted">Phone</div>
        <div style="margin-bottom:10px;">
          <a class="link" href="tel:<?= htmlspecialchars($settings['phone']) ?>">
            <?= htmlspecialchars($settings['phone']) ?>
          </a>
        </div>
      <?php endif; ?>

      <?php if (!empty($settings['whatsapp'])): ?>
        <div class="muted">WhatsApp</div>
        <div style="margin-bottom:10px;">
          <?= htmlspecialchars($settings['whatsapp']) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($settings['address'])): ?>
        <div class="muted">Address</div>
        <div><?= nl2br(htmlspecialchars($settings['address'])) ?></div>
      <?php endif; ?>
    </div>

    <!-- Contact Form -->
    <div class="card" id="form">
      <h3 style="margin-top:0;">Send an Enquiry</h3>

      <form method="post" action="<?= $basePath ?>/contact">
        <label>Your Name *</label>
        <input name="name" required placeholder="Your full name">

        <label>Email</label>
        <input name="email" type="email" placeholder="your@email.com">

        <label>WhatsApp</label>
        <input name="whatsapp" placeholder="+880...">

        <label>Message *</label>
        <textarea name="message" rows="6" required placeholder="Tell us which product you need, quantity, country, etc."></textarea>

        <div style="margin-top:12px;display:flex;gap:10px;flex-wrap:wrap;">
          <button class="btn btn--primary" type="submit">Submit</button>
          <a class="btn btn--ghost" href="<?= $basePath ?>/products">Browse Products</a>
        </div>
      </form>
    </div>
  </div>
</section>
