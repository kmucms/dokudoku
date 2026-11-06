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
      'search' => $search,
    ];
    return '?' . http_build_query($this->addEnv($par));
  }

  public function getDoc(string $document): string {
    $par = [
      'doc' => $document,
    ];
    return '?' . http_build_query($this->addEnv($par));
  }

  public function getHelp(): string {
    return '?help=1';
  }

  private function addEnv(array $par): array {
    if ($this->env != '') {
      $par['env'] = $this->env;
    }
    return $par;
  }
}
