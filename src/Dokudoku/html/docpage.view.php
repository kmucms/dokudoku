<?php /* @var array $data */ ?>
<?php if (!empty($data['tocArray'])): ?>
  <div class="toc-card">
    <div class="toc"><ul>
      <?php foreach ($data['tocArray'] as $entry): ?>
        <li style="margin-left:<?= (int)(($entry['level']-1)*20) ?>px">
          <a href="#<?= htmlspecialchars($entry['anchor']) ?>"><?= htmlspecialchars($entry['text']) ?></a>
        </li>
      <?php endforeach; ?>
    </ul></div>
  </div>
<?php endif; ?>
<div class="doc-content">
  <?= $data['contentHtml'] ?>
</div>
