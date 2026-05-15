<?php
// Template für einen einzelnen Knoten im Seitenbaum
$node = $data['node'];
$level = $data['level'] ?? 0;
$parentId = $data['parentId'] ?? 'tree';
$index = $data['index'] ?? 0;
$classes = $node['type'] === 'dir'
    ? 'list-group-item tree-folder fw-bold py-1'
    : 'list-group-item fw-normal py-1' . (!empty($node['active']) ? ' active' : '');
$id = $parentId . '-' . $index . '-' . $level;

use kmucms\Dokudoku\HtmlBasics;

?>
<li class="<?= $classes ?>">
    <?php if ($node['type'] === 'dir'): ?>
        <?php
        $collapseId = 'collapse-' . md5($id);
        $isOpen = !empty($node['open']);
        ?>
        <a href="#<?= $collapseId ?>" class="text-decoration-none" data-bs-toggle="collapse" role="button" aria-expanded="<?= $isOpen ? 'true' : 'false' ?>" aria-controls="<?= $collapseId ?>">
            <i class="bi bi-folder<?= $isOpen ? '-open' : '' ?>"></i> <?= htmlspecialchars($node['name']) ?>
        </a>
        <div class="collapse<?= $isOpen ? ' show' : '' ?>" id="<?= $collapseId ?>">
            <?php if (!empty($node['children'])): ?>
                <ul class="list-group ps-<?= ($level + 1) * 2 ?>">
                    <?php foreach ($node['children'] as $i => $child):
                        $childData = ['node' => $child, 'level' => $level + 1, 'parentId' => $id, 'index' => $i];
                        echo HtmlBasics::getView('tree_node', $childData);
                    endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php elseif ($node['type'] === 'file'): ?>
        <a href="?doc=<?= rawurlencode($node['url']) ?>" class="text-decoration-none d-block w-100 h-100" style="color:inherit;">
            <?= htmlspecialchars($node['name']) ?>
        </a>
    <?php endif; ?>
</li>

