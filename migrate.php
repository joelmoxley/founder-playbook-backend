<?php
$baseDir = './content/';
$parent = '/../';

foreach (listDir($baseDir) as $path) {
    $pathRel = preg_replace('/^.?\/.+?\/content\//', '', $path);
    $file = pathinfo($path);
    $depth = substr_count($pathRel, '/');

    if (preg_match('/^(do|pdf|ppt|rtf|txt|webloc|xls)[a-z]*/', $file['extension'])) {
        // appendFile(
        //     $path . $parent . 'index.md',
        //     '* [' . $file['filename'] . '](' . slugify($file['basename']) . ')\n');
    } else if (is_dir($path)) {
        if (preg_match('/^[0-9]+[\s\-]*/', $file['filename'])) {
            $mdPath = $path;
            $order = preg_replace('/[^0-9]+/', '', $file['filename']);
            $meta = 'title: ' . preg_replace('/^[0-9]+[\s\-]+/', '' ,$file['filename']) . "\n";

            if ($depth === 0) {
                $meta .= 'playbook: ' . $order;
            } else {
                $mdPath .= $parent;

                if ($depth === 1) {
                    $meta .= 'play: ' . $order;
                } else if ($depth === 2) {
                    $meta .= 'section: ' . $order;
                }
            }

            appendMeta($mdPath . slugify($file['filename']) . '.md', $meta);
        }
    } else {

    }
}

function slugify($string) {
    return preg_replace('/[^a-z0-9\.\/]+/', '-', strtolower($string));
}

function appendFile($file, $content) {
    echo $file . "\n" . $content . "\n";
    // file_put_contents($file, $content, FILE_APPEND);
}

function appendMeta($file, $meta) {
    $data = file_get_contents($file);

    if (hasMeta($data)) {
        $data = substr($data, 4);
    }

    $data = "---\n" . $meta . "\n" . $data;

    echo "$file \n $data\n";
    // file_put_contents($file, $data);
}

function hasMeta($data) {
    return (substr($data, 0, 3) === '---');
}

function listDir($dir, &$results = array()){
    $files = scandir($dir);

    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path)) {
            $results[] = $path;
        } else if(substr($value, 0, 1) != '.') {
            listDir($path, $results);
            $results[] = $path;
        }
    }

    return $results;
}
?>