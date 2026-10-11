<?php

// Turn on all error reporting
error_reporting(E_ALL);

// Display errors on the screen (set to '0' in production)
ini_set('display_errors', '1');

// Display startup errors as well
ini_set('display_startup_errors', '1');

require_once __DIR__ . '/vendor/autoload.php';
$d = new \kmucms\Dokudoku\DokuDoku(); // create new instance
$d->setMdDocsPath(__DIR__ . '/docsmd/'); // *important* provide path with md-files
$d->setMdDocsPathEnv(__DIR__ . '/docsmd2/', 'alt');
$d->setShowHelp(true);
$d->setFooterHtml('');
$d->go();
