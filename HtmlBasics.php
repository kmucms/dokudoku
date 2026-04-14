<?php

namespace kmucms\Dokudoku;

class HtmlBasics
{

  public static function getView(string $viewName, array $data = []): string{
    ob_start();
    require __DIR__ . '/html/' . $viewName . '.view.php';
    return ob_get_clean();
  }


}