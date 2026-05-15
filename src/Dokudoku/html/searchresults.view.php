<?php /* @var array $data */ ?>
<h2>Suchergebnisse für <mark><?= htmlspecialchars($data['search']) ?></mark></h2>
<?php if (!isset($data['results'])): ?>
  <div class="alert alert-danger">Fehler: Keine Suchdaten übergeben!</div>
<?php elseif (empty($data['results'])): ?>
  <div class="alert alert-warning">Keine Treffer gefunden.</div>
<?php else: ?>
  <ul class="list-group mb-4">
    <?php foreach ($data['results'] as $url): ?>
      <li class="list-group-item">
        <a href="?doc=<?= rawurlencode($url) ?>&search=<?= urlencode($data['search']) ?>" class="text-decoration-none">
          <?= htmlspecialchars($url) ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
