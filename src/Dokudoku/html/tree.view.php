<?php /* @var array $data */ ?>
<?php

use kmucms\Dokudoku\HtmlBasics;

$tree = $data['treeArray'] ?? [];
if (!empty($tree)) {
    echo '<ul class="list-group ps-0">';
    foreach ($tree as $i => $node) {
        $nodeData = ['node' => $node, 'level' => 0, 'parentId' => 'tree', 'index' => $i];
        echo HtmlBasics::getView('tree_node', $nodeData);
    }
    echo '</ul>';
}
?>
