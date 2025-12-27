<section class="section">
  <div class="section__head">
    <h2>News</h2>
  </div>

  <div class="grid grid--3">
    <?php foreach ($news as $n): ?>
      <a class="card card--news" href="<?= $basePath ?>/news/<?= htmlspecialchars($n['slug']) ?>">
        <div class="thumb">
          <?php if (!empty($n['cover_image'])): ?>
            <img src="<?= $basePath ?>/uploads/news/<?= htmlspecialchars($n['cover_image']) ?>" alt="">
          <?php else: ?>
            <div class="thumb__placeholder">News</div>
          <?php endif; ?>
        </div>

        <div class="card__title"><?= htmlspecialchars($n['title']) ?></div>

        <?php if (!empty($n['published_at'])): ?>
          <div class="muted" style="font-size:13px;margin-bottom:6px;">
            <?= htmlspecialchars(date('M d, Y', strtotime($n['published_at']))) ?>
          </div>
        <?php endif; ?>

        <div class="card__text"><?= htmlspecialchars($n['excerpt'] ?? '') ?></div>
        <div class="card__cta">Read more →</div>
      </a>
    <?php endforeach; ?>
  </div>
</section>
