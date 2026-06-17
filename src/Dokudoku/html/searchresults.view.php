<?php /* @var array $data */ 
/** @var \kmucms\Dokudoku\Links $links */
$links = $data['links'];
?>
<h2><i class="bi bi-search"></i> <mark><?= kmucms\Dokudoku\HtmlBasics::escapeHtml($data['search']) ?></mark></h2>
<?php if (empty($data['results'])): ?>
  <div class="alert alert-warning"><i class="bi bi-emoji-tear-fill"></i></div>
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
