# DokuDoku

An folder with markdown documents (*.md) is turned to a web-page.
Folders plus Filename represents the path.
A navigation tree is generated.

I wrote a small lib for documenting software, so there are
some special enhantments (code highlighting and graph drawing).

## Installation

get the lib:

```console
composer require kmucms/dokudoku
```

create index.php and put following code:

```php 
require_once __DIR__ . '/vendor/autoload.php';
$d = new \kmucms\Dokudoku\DokuDoku(); // create new instance
$d->setMdDocsPath(__DIR__ . '/docsmd/'); // *important* provide path with md-files
$d->go();
``` 

run webserver 

```console
php -S localhost:8000 
```

and browser
```console
http://localhost:8000
```

## Used Libraries

There are libraries (mermaidjs, prismjs, bootstrap) which are loaded from
cdn.jsdelivr.net, so you have to be online or you can install it locally.

```php 
$d = new \kmucms\Dokudoku\DokuDoku();
$d->setConfiguration('css', ['/your/path.css']);
$d->setConfiguration('js', ['/your/path.js']);
$d->go();
``` 

## Usage

There is an internal help page.

```console
http://localhost:8000?doc=dokudoku_edit_help
```
