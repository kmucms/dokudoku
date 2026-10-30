<?php

namespace kmucms\Dokudoku;

class Links {

  private string $env = '';

  public function __construct($env) {
    $this->env = $env;
  }

  public function getEnvHome(string $env = ''): string {
    if (empty($env)) {
      return '?';
    }
    $par = [
      'env' => $env,
    ];
    return '?' . http_build_query($par);
  }

  public function getSearch(string $search = ''): string {
    $par = [
      'env' => $this->env,
      'search' => $search,
    ];
    return '?' . http_build_query($par);
  }

  public function getDoc(string $document): string {
    $par = [
      'env' => $this->env,
      'doc' => $document,
    ];
    return '?' . http_build_query($par);
  }

  public function getHelp(): string {
    return '?help=1';
  }
}
