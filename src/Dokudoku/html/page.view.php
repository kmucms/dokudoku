<?php

/** @var \kmucms\Dokudoku\Links $links */
$links = $data['links'];
?>

<!DOCTYPE html>
<html lang="<?=$data['data']['lang']??'en'?>" >
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DokuDoku</title>
    <?php foreach ($data['data']['css'] as $url): ?>
      <link href="<?= $url ?>" rel="stylesheet">
    <?php endforeach; ?>
    <style>
      body{
        background: linear-gradient(135deg, #232526 0%, #414345 40%, #0f2027 100%, #2c5364 120%);
      }
      em,b {
        color: #f00;
      }
      h1, h2, h3, h4, h5, h6 {
        margin-top: 2em;
        margin-bottom: 0.5em;
        color: #009;
        border-bottom: 2px solid #dde;
      }
      main{
        background-color: #fff;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top ">
      <div class="container-fluid">
        <?php if (isset($data['data']['brandName'])): ?>
          <?php if (isset($data['data']['brandIcon'])): ?>
            <img src="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($data['data']['brandIcon']) ?>" />
          <?php endif; ?>
            <a class="navbar-brand fw-bold" href="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($data['data']['brandUrl']) ?>"><?= kmucms\Dokudoku\HtmlBasics::escapeHtml($data['data']['brandName']) ?></a>
        <?php endif; ?>
        <a class="navbar-brand fw-bold" href="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($data['data']['urlPrefix']) ?>"><i class="bi bi-journal-bookmark"></i> </a>
        <div class="d-flex ms-auto gap-2">
          <?php foreach($data['data']['mdDocsPaths'] as $item):?>
            <?php if ($item['name'] != 'dokudokuhelp'): ?>
              <a class="btn btn-secondary" href="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($links->getEnvHome($item['name']))?>"><i class="bi bi-bookmark"></i></a>
            <?php endif; ?>
          <?php endforeach; ?>  
          <?php if ($data['data']['showHelp']): ?>
            <a class="btn btn-secondary" href="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($links->getHelp())?>"><i class="bi bi-patch-question-fill"></i></a>
          <?php endif; ?>
          <button id="menuSwitch" class="btn btn-secondary" title="Seitenbaum anzeigen" data-bs-toggle="offcanvas"
                  data-bs-target="#seitenbaumOffcanvas"><i class="bi bi-diagram-3"></i></button>
          <button id="tocBtn" class="btn btn-secondary" title="Inhaltsverzeichnis" data-bs-toggle="offcanvas"
                  data-bs-target="#tocOffcanvas"><i class="bi bi-list-columns"></i></button>
        </div>
      </div>
    </nav>
    <div class="container-fluid">
      <div class="row">
        <main class="col-lg-6 mx-auto py-4 min-vh-100" id="mainContent">
          <div class="content-card">
            <?php if ($data['type'] === 'home'): ?>
              <div class="tree-card">
                <?= \kmucms\Dokudoku\HtmlBasics::getView('tree', $data) ?>
              </div>
            <?php elseif ($data['type'] === 'search'): ?>
              <form class="d-flex mb-3" method="get" action="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($links->getSearch())?>">
                <div class="input-group">
                  <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                  <input class="form-control search-input" type="search" name="search" placeholder="..." aria-label=""
                         value="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($data['search'] ?? '') ?>">
                  <input type="hidden" value="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($data['env'])?>" name="env"/>
                </div>
                <button class="btn btn-outline-success ms-2" type="submit"><i class="bi bi-arrow-right-circle"></i></button>
              </form>
              <?php if (!empty($data['results'])): ?>
                <div class="search-results mb-3">
                  <ul class="list-group">
                    <?php foreach ($data['results'] as $result): ?>
                      <li class="list-group-item">
                          <a href="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($links->getDoc($result)) ?>" class="text-decoration-none">
                          <?= kmucms\Dokudoku\HtmlBasics::escapeHtml($result) ?>
                        </a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php else: ?>
                <div class="alert alert-warning"><i class="bi bi-emoji-tear-fill"></i></div>
              <?php endif; ?>
              <?= $data['contentHtml'] ?? '' ?>
            <?php else: ?>
              <?= $data['contentHtml'] ?? '' ?>
              <div class="d-flex justify-content-between mt-4">
                <?php if (!empty($data['prevDoc'])): ?>
                  <a href="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($links->getDoc($data['prevDoc'])) ?>" class="btn btn-outline-primary px-4">&laquo; <?= kmucms\Dokudoku\HtmlBasics::escapeHtml($data['prevDoc']) ?></a>
                <?php else: ?>
                  <span></span>
                <?php endif; ?>
                <?php if (!empty($data['nextDoc'])): ?>
                  <a href="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($links->getDoc($data['nextDoc'])); ?>" class="btn btn-outline-primary px-4"><?= kmucms\Dokudoku\HtmlBasics::escapeHtml($data['nextDoc']) ?> &raquo;</a>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </main>

      </div>
    </div>
    <!-- Seitenbaum Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="seitenbaumOffcanvas" aria-labelledby="seitenbaumLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="seitenbaumLabel"><i class="bi bi-diagram-3"></i> <!--Seitenbaum --></h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Schließen"></button>
      </div>
      <div class="offcanvas-body">
        <form class="d-flex mb-3" method="get" action="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($links->getSearch())?>">
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input class="form-control search-input" type="search" name="search" placeholder="..." aria-label=""
                   value="">
            <input type="hidden" value="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($data['env'])?>" name="env"/>
          </div>
          <button class="btn btn-outline-success ms-2" type="submit"><i class="bi bi-arrow-right-circle"></i></button>
        </form>
        <div class="tree-card">
          <?= \kmucms\Dokudoku\HtmlBasics::getView('tree', $data) ?>
        </div>
      </div>
    </div>
    <!-- Inhaltsverzeichnis Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="tocOffcanvas" aria-labelledby="tocLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="tocLabel"><i class="bi bi-list-columns"></i> <!-- Inhaltsverzeichnis --></h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Schließen"></button>
      </div>
      <div class="offcanvas-body">
        <div class="toc-card">
          <ul><?= $data['tocHtml'] ?? ''; ?></ul>
        </div>
      </div>
    </div>
    <?php foreach ($data['data']['js'] as $url): ?>
    <script src="<?= kmucms\Dokudoku\HtmlBasics::escapeAttribute($url) ?>"></script>
    <?php endforeach; ?>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        mermaid.init(undefined, document.querySelectorAll('.mermaid'));
        Prism.highlightAll();
      });
    </script>




  </body>
</html>

