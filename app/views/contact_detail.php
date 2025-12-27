<?php
// Placeholder: not used by current router.
// You can route to it later if you want /contact-detail or /thank-you.
?>
<section class="section">
  <h2>Contact Details</h2>
  <p class="muted">This is a placeholder page. You can use it for a Thank You page or additional contact information.</p>

  <div class="card">
    <h3 style="margin-top:0;">SDS Techmed</h3>
    <p class="muted">Email: <?= htmlspecialchars($settings['email'] ?? '') ?></p>
    <p class="muted">Phone: <?= htmlspecialchars($settings['phone'] ?? '') ?></p>
    <p class="muted">WhatsApp: <?= htmlspecialchars($settings['whatsapp'] ?? '') ?></p>
    <p class="muted"><?= nl2br(htmlspecialchars($settings['address'] ?? '')) ?></p>
  </div>
</section>
