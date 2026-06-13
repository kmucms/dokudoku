<?php

namespace kmucms\Dokudoku;


class Links {
  private string $env = '';  
    
  public function __construct($env) {
      $this->env = $env;
  }
  
    
  public function getEnvHome(string $env=''): string {
      if(empty($env)){
          return '?';
      }
      return '?env='.urlencode($env);
  }
  
  public function getSearch(string $search=''): string {
      return $this->getEnvHome($this->env).'&search='.urlencode($search);
  }
  
  public function getDoc(string $document): string {
      return $this->getEnvHome($this->env).'&doc='.rawurlencode($document);
  }
  
  public function getHelp(): string {
      return '?help=1';
  }
  
}
