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
        $items = scandir($dir);
        $entries = [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            $url = $prefix . preg_replace('/^\d+_?/', '', $item);
            $url = str_replace("\\", '/', $url); // Für Linux-Kompatibilität
            if (is_dir($path)) {
                $entries[] = [
                    'type' => 'dir',
                    'name' =>  preg_replace('/^\d+_?/', '', $item),
                    'url' => $url . '/',
                    'children' => $this->scanDir($path, $url . '/'),
                    'raw' => $item
                ];
            } elseif (strtolower(pathinfo($item, PATHINFO_EXTENSION)) === 'md') {
                $url = substr($url, 0, -strlen('.md')); //.'.html'; // .md durch .html ersetzen
                $entries[] = [
                    'type' => 'file',
                    'name' => preg_replace('/^\d+_?/', '', pathinfo($item, PATHINFO_FILENAME)),
                    'url' => $url,
                    'raw' => $item
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


    public function getPrevUrl(string $mdFile): string
    {
        $urls = array_keys($this->flatMap);
        $index = array_search($mdFile, $urls);
        if ($index !== false && $index > 0) {
            return '?doc='.$urls[$index - 1];
        }
        return '';
    }

    public function getNextUrl(string $mdFile): string
    {
        $urls = array_keys($this->flatMap);
        $index = array_search($mdFile, $urls);
        if ($index !== false && $index < count($urls) - 1) {
            return '?doc='.$urls[$index + 1];
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
