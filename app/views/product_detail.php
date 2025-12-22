<section class="product">
  <div class="product__media">
    <div class="product__image">
      <?php if (!empty($product['main_image'])): ?>
        <img src="/sdstechmed/public/uploads/products/<?= htmlspecialchars($product['main_image']) ?>" alt="">
      <?php else: ?>
        <div class="thumb__placeholder">Product Image</div>
      <?php endif; ?>
    </div>
  </div>

  <div class="product__info">
    <div class="breadcrumb">
      <a href="/sdstechmed/public/products">Products</a>
      <span>›</span>
      <a href="/sdstechmed/public/category/<?= htmlspecialchars($product['category_slug']) ?>">
        <?= htmlspecialchars($product['category_name']) ?>
      </a>
    </div>

    <h1><?= htmlspecialchars($product['name']) ?></h1>
    <p class="muted"><?= htmlspecialchars($product['short_description'] ?? '') ?></p>

    <div class="actions">
      <a class="btn btn--primary" href="/sdstechmed/public/contact">Enquiry Now</a>
      <a class="btn btn--ghost" href="/sdstechmed/public/contact#form">Get Quotation</a>
    </div>

    <?php if (!empty($product['specs'])): ?>
      <div class="specs">
        <h3>Specifications</h3>

        <?php if (isJsonArray($product['specs'])): ?>
          <?php $rows = json_decode($product['specs'], true); ?>
          <table class="table">
            <tbody>
              <?php foreach ($rows as $r): ?>
                <tr>
                  <th><?= htmlspecialchars($r['key'] ?? '') ?></th>
                  <td><?= htmlspecialchars($r['value'] ?? '') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <!-- fallback: allow HTML specs if you stored it that way -->
          <div class="rich"><?= $product['specs'] ?></div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="section">
  <h2>Overview</h2>
  <div class="rich">
    <?= $product['description'] ?? '' ?>
  </div>
</section>

<section class="section section--alt">
  <div class="cta-bar">
    <div>
      <h3>Need OEM/ODM or Bulk Pricing?</h3>
      <p class="muted">Talk to SDS Techmed sales for customization, training, warranty and shipping.</p>
    </div>
    <a class="btn btn--primary" href="/sdstechmed/public/contact">Contact Sales</a>
  </div>
</section>
