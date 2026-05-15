<?php
require_once __DIR__ . '/vendor/autoload.php';
$d = new \kmucms\Dokudoku\DokuDoku(); // create new instance
$d->setMdDocsPath(__DIR__ . '/docsmd/'); // *important* provide path with md-files
$d->go();
