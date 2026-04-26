<?php

namespace kmucms\Dokudoku;

use Parsedown;

class DocPageView {

  private $mdDocsPath;

  public function __construct(string $mdDocsPath) {
    $this->mdDocsPath = rtrim($mdDocsPath, '/\\');
  }

  public function getDocHtml(string $mdFile, array $flatMap = []): string {
    if (empty($mdFile)) {
      return HtmlBasics::getView('welcome');
    }
    $filePath = isset($flatMap[$mdFile]) ? $flatMap[$mdFile] : ($this->mdDocsPath . DIRECTORY_SEPARATOR . $mdFile);
    if (!is_file($filePath)) {
      return HtmlBasics::getView('notfound');
    }
    $parsedown = new Parsedown();
    $md = file_get_contents($filePath);
    $md = $this->addMainHeader($md, pathinfo($mdFile, PATHINFO_FILENAME));
    $md = $this->addHeaderNumbersToMd($md);
    $contentHtml = $parsedown->text($md);
    $contentHtml = $this->addHeaderAnchors($contentHtml);
    $contentHtml = $this->prepareForMermaidGraph($contentHtml);
    return HtmlBasics::getView('docpage', [
        'tocArray' => $this->getSectionHeaders($md),
        'contentHtml' => $contentHtml
    ]);
  }

  private function prepareForMermaidGraph($html, $newClass = 'mermaid') {
    if (empty($html))
      return '';
    $dom = new \DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML(
      '<?xml encoding="utf-8" \? >' .
      $html,
      LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
    );
    libxml_clear_errors();
    $pres = $dom->getElementsByTagName('pre');

    for ($i = $pres->length - 1; $i >= 0; $i--) {
      $pre = $pres->item($i);
      $code = $pre->getElementsByTagName('code')->item(0);
      if ($code && in_array('language-mermaid', explode(' ', $code->getAttribute('class')))) {
        // Neues Div-Element erstellen
        $div = $dom->createElement('div');
        $div->setAttribute('class', $newClass);
        while ($code->childNodes->length > 0) {
          $div->appendChild($code->childNodes->item(0));
        }
        $pre->parentNode->replaceChild($div, $pre);
      }
    }
    $result = $dom->saveHTML();
    return str_replace('<?xml encoding="utf-8" ?>', '', $result);
  }

  public function getTocHtml(string $mdFile, array $flatMap = []): string {
    $filePath = isset($flatMap[$mdFile]) ? $flatMap[$mdFile] : ($this->mdDocsPath . DIRECTORY_SEPARATOR . $mdFile);
    $md = file_get_contents($filePath);
    $md = $this->addMainHeader($md, pathinfo($mdFile, PATHINFO_FILENAME));
    $md = $this->addHeaderNumbersToMd($md);
    return HtmlBasics::getView('docpage', [
        'tocArray' => $this->getSectionHeaders($md),
        'contentHtml' => ''
    ]);
  }

  private function addHeaderNumbersToMd(string $md): string {
    $lines = explode("\n", $md);
    $num = [0, 0, 0, 0, 0, 0];
    foreach ($lines as $i => $line) {
      if (preg_match('/^(#+) (.*)/', $line, $m)) {
        $level = strlen($m[1]);
        if ($level === 1)
          $hasMainHeading = true;
        for ($j = $level; $j < 6; $j++)
          $num[$j] = 0;
        $num[$level - 1]++;
        $prefix = '';
        for ($j = 0; $j < $level; $j++) {
          if ($num[$j] > 0)
            $prefix .= $num[$j] . '.';
        }
        $headingText = $prefix . ' ' . $m[2];
        $anchor = 'section_' . str_replace('.', '_', $prefix);
        $lines[$i] = $m[1] . ' ' . $headingText;
      }
    }
    return implode("\n", $lines);
  }

  /**
   * Fügt eine Hauptüberschrift (H1) mit dem Dateinamen ein, falls keine vorhanden ist.
   * @param string $md Markdown-Text
   * @param string $filename Dateiname (ohne Pfad)
   * @return string Markdown-Text mit H1
   */
  private function addMainHeader(string $md, string $filename): string {
    $lines = explode("\n", $md);
    $hasMainHeading = false;
    foreach ($lines as $line) {
      if (preg_match('/^#\s+.+/', $line)) {
        $hasMainHeading = true;
        break;
      }
    }
    if (!$hasMainHeading) {
      array_unshift($lines, '# ' . $filename);
    }
    return implode("\n", $lines);
  }

  /**
   * Extrahiert alle Überschriften (H1-H6) aus einem Markdown-String.
   * Gibt ein Array mit ['level' => int, 'text' => string, 'line' => int] für jede Überschrift zurück.
   * @param string $mdString
   * @return array
   */
  private function getSectionHeaders(string $mdString): array {
    $lines = explode("\n", $mdString);
    $headers = [];
    foreach ($lines as $i => $line) {
      if (preg_match('/^(#{1,6})\s+([\d,\.]*)\s+(.+)/', $line, $m)) {
        $headers[] = [
          'level' => strlen($m[1]),
          'text' => trim($m[3]),
          'anchor' => 'section_' . str_replace('.', '_', trim($m[2], '.')),
        ];
      }
    }
    return $headers;
  }

  /**
   * Fügt Anker-IDs zu allen Überschriften (H1-H6) im HTML hinzu.
   * @param string $html HTML-String
   * @return string HTML mit Anker-IDs
   */
  private function addHeaderAnchors(string $html): string {
    // Ersetzt <h1>, <h2>, ... durch <hX id="section_...">
    return preg_replace_callback(
      '/<h([1-6])>([\d\.]+)?\s*(.*?)<\/h\1>/i',
      function ($m) {
        $level = $m[1];
        $num = isset($m[2]) ? trim($m[2]) : '';
        $text = trim($m[3]);
        $anchor = 'section_' . str_replace('.', '_', trim($num, '.'));
        if ($num === '')
          $anchor = 'section_' . strtolower(preg_replace('/[^a-z0-9]+/i', '_', $text));
        return '<h' . $level . ' id="' . $anchor . '">' . ($num ? $num . ' ' : '') . $text . '</h' . $level . '>';
      },
      $html
    );
  }
}
