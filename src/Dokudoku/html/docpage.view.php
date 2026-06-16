<?php /* @var array $data */ ?>
<?php if (!empty($data['tocArray'])): ?>
  <div class="toc-card">
    <div class="toc"><ul>
      <?php foreach ($data['tocArray'] as $entry): ?>
        <li style="margin-left:<?= (int)(($entry['level']-1)*20) ?>px">
            <a href="#<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($entry['anchor']) ?>"><?= kmucms\Dokudoku\HtmlBasics::escapeHtml($entry['text']) ?></a>
        </li>
      <?php endforeach; ?>
    </ul></div>
  </div>
<?php endif; ?>
<div class="doc-content">
  <?= $data['contentHtml'] ?>
</div>
