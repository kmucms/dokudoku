<?php

namespace kmucms\Dokudoku;

class DokuDoku {

  private array $data = [
    'mdDocsPath' => '',
    'urlPrefix' => '/',
    'brandName' => '',
    'brandUrl' => '',
    'brandIcon' => '',
    'seoLinks' => false,
    'showHelp' => true,
    'css' => [
      "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css",
      "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism.min.css",
    ],
    'js' => [
      "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js",
      "https://cdn.jsdelivr.net/npm/mermaid@11.12.2/dist/mermaid.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-core.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-clike.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-markup.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-markup-templating.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-bash.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-c.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-cpp.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-csharp.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-css.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-docker.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-git.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-go.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-java.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-javascript.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-json.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-markdown.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-php.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-python.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-sql.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-typescript.min.js",
      "https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-yaml.min.js",
    ],
  ];

  public function setMdDocsPath(string $val): void {
    $this->data['mdDocsPath'] = $val;
  }

  public function setUrlPrefix(string $val): void {
    $this->data['urlPrefix'] = $val;
  }

  public function setBrand(string $name, string $brandUrl, string $iconUrl = ''): void {
    $this->data['brandName'] = $name;
    $this->data['brandUrl'] = $brandUrl;
    $this->data['brandIcon'] = $iconUrl;
  }

  public function setDoSeoLinks(bool $val): void {
    $this->data['seoLinks'] = $val;
  }

  public function go(): void {

    $mdDocsPath = $this->data['mdDocsPath'] ?? $_SERVER['DOCUMENT_ROOT'] . '/docs/';
    $urlPrefix = $this->data['urlPrefix'] ?? '/';

    $doc = $_GET['doc'] ?? '';
    $search = $_GET['search'] ?? '';

    $data['data'] = $this->data;

    if ($search !== '') {
      $pt = new \kmucms\Dokudoku\DocPageTree($mdDocsPath, $urlPrefix);
      $data['type'] = 'search';
      $data['treeArray'] = $pt->getTree($doc);
      $data['results'] = $pt->getSearchResultArray($search);
      $data['tocHtml'] = '';
      $data['prevUrl'] = '';
      $data['nextUrl'] = '';
      $data['search'] = $search;
      echo \kmucms\Dokudoku\HtmlBasics::getView('page', $data);
    } elseif ($doc !== '') {
      $pt = new \kmucms\Dokudoku\DocPageTree($mdDocsPath, $urlPrefix);
      $pv = new \kmucms\Dokudoku\DocPageView($mdDocsPath, $urlPrefix);
      $flatMap = $pt->getFlatMap();
      $flatMap['dokudoku_edit_help'] = __DIR__ . '/md/textfeatures.md';
      $data['type'] = 'doc';
      $data['tocHtml'] = $pv->getTocHtml($doc, $flatMap);
      $data['treeArray'] = $pt->getTree($doc);
      $data['contentHtml'] = $pv->getDocHtml($doc, $flatMap);
      $data['prevUrl'] = $pt->getPrevUrl($doc);
      $data['nextUrl'] = $pt->getNextUrl($doc);
      echo \kmucms\Dokudoku\HtmlBasics::getView('page', $data);
    } else {
      $pt = new \kmucms\Dokudoku\DocPageTree($mdDocsPath, $urlPrefix);
      $data['type'] = 'home';
      $data['treeArray'] = $pt->getTree('');
      echo \kmucms\Dokudoku\HtmlBasics::getView('page', $data);
    }
  }
}
