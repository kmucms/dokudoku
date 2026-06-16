<?php

namespace kmucms\Dokudoku;

class HtmlBasics
{

  public static function getView(string $viewName, array $data = []): string{
    ob_start();
    require __DIR__ . '/html/' . $viewName . '.view.php';
    return ob_get_clean();
  }

  public static function escapeAttribute(string $s):string{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
  }

  public static function escapeHtml(string $s): string{
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
  }
  
}