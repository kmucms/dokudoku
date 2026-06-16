<?php /* @var array $data */ 
/** @var \kmucms\Dokudoku\Links $links */
$links = $data['links'];
?>
<h2>Suchergebnisse für <mark><?= kmucms\Dokudoku\HtmlBasics::escapeHtml($data['search']) ?></mark></h2>
<?php if (!isset($data['results'])): ?>
  <div class="alert alert-danger">Fehler: Keine Suchdaten übergeben!</div>
<?php elseif (empty($data['results'])): ?>
  <div class="alert alert-warning">Keine Treffer gefunden.</div>
<?php else: ?>
  <ul class="list-group mb-4">
    <?php foreach ($data['results'] as $doc): ?>
      <li class="list-group-item">
          <a href="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($links->getDoc($doc))?>" class="text-decoration-none">
          <?= kmucms\Dokudoku\HtmlBasics::escapeHtml($doc) ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
