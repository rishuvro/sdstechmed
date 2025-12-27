<?php
$basePath = $basePath ?? '/sdstechmed/public';
$settings = $settings ?? [];
$sent = isset($_GET['sent']) && $_GET['sent'] === '1';
?>

<section class="cmini">
  <div class="cmini__wrap">
    <div class="breadcrumb">
      <a href="<?= $basePath ?>/">Home</a>
      <span>›</span>
      <span>Contact</span>
    </div>

    <div class="cmini__head">
      <div>
        <h1>Contact Sales</h1>
        <p class="muted">Get quotation, OEM/ODM, shipping & support — we reply fast.</p>
      </div>

      <div class="cmini__quick">
        <?php if (!empty($settings['email'])): ?>
          <a class="qbtn" href="mailto:<?= htmlspecialchars($settings['email']) ?>">
            ✉️ Email
          </a>
        <?php endif; ?>

        <?php if (!empty($settings['phone'])): ?>
          <a class="qbtn" href="tel:<?= htmlspecialchars($settings['phone']) ?>">
            📞 Call
          </a>
        <?php endif; ?>

        <?php if (!empty($settings['whatsapp'])): ?>
          <!-- Replace with wa.me link later if you want -->
          <span class="qbtn qbtn--wa">
            💬 WhatsApp: <?= htmlspecialchars($settings['whatsapp']) ?>
          </span>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($sent): ?>
      <div class="cmini__alert">
        <div class="cmini__tick">✅</div>
        <div>
          <div class="cmini__alertTitle">Message sent successfully</div>
          <div class="muted">We’ll contact you shortly. For urgent orders, WhatsApp is fastest.</div>
        </div>
      </div>
    <?php endif; ?>

    <div class="cmini__grid">
      <!-- Form (main focus) -->
      <div class="cmini__formCard" id="form">
        <div class="cmini__formTop">
          <div>
            <h2 style="margin:0;">Send an Enquiry</h2>
            <div class="muted" style="margin-top:4px;">Product + quantity + country = fastest quotation.</div>
          </div>
          <div class="cmini__badge">Fast Reply</div>
        </div>

        <form method="post" action="<?= $basePath ?>/contact" class="cmini__form">
          <div class="cmini__row">
            <div>
              <label>Your Name *</label>
              <input name="name" required placeholder="Your full name">
            </div>
            <div>
              <label>Email</label>
              <input name="email" type="email" placeholder="your@email.com">
            </div>
          </div>

          <div class="cmini__row">
            <div>
              <label>WhatsApp</label>
              <input name="whatsapp" placeholder="+880...">
            </div>
            <div>
              <label>Country</label>
              <input name="country" placeholder="Bangladesh / China / UAE...">
            </div>
          </div>

          <label>Message *</label>
          <textarea name="message" rows="6" required
            placeholder="Example: Hair Removal Machine, Qty 10, Ship to Dubai, need warranty & training."></textarea>

          <div class="cmini__actions">
            <button class="btn btn--primary" type="submit">Submit Enquiry</button>
            <a class="btn btn--ghost" href="<?= $basePath ?>/products">Browse Products</a>
          </div>
        </form>
      </div>

      <!-- Minimal info (small, not heavy) -->
      <aside class="cmini__side">
        <div class="cmini__infoCard">
          <div class="cmini__infoTitle">Company</div>
          <div class="cmini__infoValue"><?= htmlspecialchars($settings['company_name'] ?? 'SDS Techmed') ?></div>

          <?php if (!empty($settings['address'])): ?>
            <div class="muted" style="margin-top:10px;font-size:13px;">Address</div>
            <div style="margin-top:6px;"><?= nl2br(htmlspecialchars($settings['address'])) ?></div>
          <?php endif; ?>
        </div>

        <div class="cmini__infoCard cmini__infoCard--soft">
          <div class="cmini__infoTitle">What you’ll receive</div>
          <ul class="cmini__list">
            <li> Price + MOQ</li>
            <li> Shipping options</li>
            <li> OEM/ODM details</li>
            <li> Warranty & support</li>
          </ul>
        </div>
      </aside>
    </div>
  </div>
</section>
