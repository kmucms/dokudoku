<?php

namespace kmucms\Dokudoku;

class DocPageTree
{
    private $mdDocsPath;
    private $urlPrefix;
    private $tree = [];
    private $flatMap = [];

    public function __construct(string $mdDocsPath, string $urlPrefix)
    {
        $this->mdDocsPath = rtrim($mdDocsPath, '/\\');
        $this->urlPrefix = $urlPrefix;
        $this->tree = $this->scanDir($this->mdDocsPath, '');
    }

    private function scanDir(string $dir, string $prefix): array
    {
        $items = new \FilesystemIterator($dir, \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::KEY_AS_PATHNAME);
        $entries = [];
        foreach ($items as $item) {
            /* @var /SplFileInfo $item */
            $fileName = $item->getFilename();
            $path = $item->getPathname();
            $url = $prefix . preg_replace('/^\d+_?/', '', $fileName);
            $url = str_replace("\\", '/', $url);   
            if ($item->isDir()) {
                $entries[] = [
                    'type' => 'dir',
                    'name' =>  preg_replace('/^\d+_?/', '', $fileName),
                    'url' => $url . '/',
                    'children' => $this->scanDir($path, $url . '/'),
                    'raw' => $fileName
                ];
            } elseif (strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) === 'md') {
                $url = substr($url, 0, -strlen('.md')); //.'.html'; // .md replace by .html
                $entries[] = [
                    'type' => 'file',
                    'name' => preg_replace('/^\d+_?/', '', pathinfo($fileName, PATHINFO_FILENAME)),
                    'url' => $url,
                    'raw' => $fileName
                ];
                $this->flatMap[$url] = $path;
            }
        }
        // Sortiere nach Präfixnummer (falls vorhanden), sonst alphabetisch
        usort($entries, function($a, $b) {
            $aRaw = $a['raw'];
            $bRaw = $b['raw'];
            $numA = null; $numB = null;
            if (preg_match('/^(\d+)/', $aRaw, $mA)) $numA = (int)$mA[1];
            if (preg_match('/^(\d+)/', $bRaw, $mB)) $numB = (int)$mB[1];
            if ($numA !== null && $numB !== null && $numA !== $numB) return $numA - $numB;
            if ($numA !== null && $numB === null) return -1;
            if ($numA === null && $numB !== null) return 1;
            return strnatcasecmp($aRaw, $bRaw);
        });
        return $entries;
    }

    /**
     * @param string $mdFile comes from $_GET['doc']
     * @return array
     */
    public function getTree(string $mdFile): array
    {
        // Markiere im Baum den aktuellen Pfad
        $mark = function(array &$nodes, $target) use (&$mark) {
            foreach ($nodes as &$node) {
                if ($node['type'] === 'file' && $node['url'] === $target) {
                    $node['active'] = true;
                    return true;
                }
                if ($node['type'] === 'dir' && !empty($node['children'])) {
                    if ($mark($node['children'], $target)) {
                        $node['open'] = true;
                        return true;
                    }
                }
            }
            return false;
        };
        $tree = $this->tree;
        $mark($tree, $mdFile);

        return $tree;
    }

    public function getFlatMap(): array
    {
        return $this->flatMap;
    }
    
    public function getPrevDoc(string $mdFile): string
    {
        $urls = array_keys($this->flatMap);
        $index = array_search($mdFile, $urls);
        if ($index !== false && $index > 0) {
            return $urls[$index - 1];
        }
        return '';
    }

    public function getNextDoc(string $mdFile): string
    {
        $urls = array_keys($this->flatMap);
        $index = array_search($mdFile, $urls);
        if ($index !== false && $index < count($urls) - 1) {
            return $urls[$index + 1];
        }
        return '';
    }
    
    /**
     * @param string $search
     * @return array
     */
    public function getSearchResultArray(string $search): array
    {
        $flatMap = $this->flatMap;
        $results = [];
        foreach ($flatMap as $url => $file) {
            $content = @file_get_contents($file);
            if ($content !== false && stripos($content, $search) !== false) {
                $results[] = $url;
            }
        }
        return $results;
    }
}
