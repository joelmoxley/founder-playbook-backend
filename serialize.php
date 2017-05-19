<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$output = './content.txt';
$filesArray = [];

function getFiles($dir, &$length, $pathStr) {
  $files = scandir($dir);
  $farray = [];

  sort($files);

  if (!is_dir($dir)) {
    return $farray;
  }

  foreach ($files as $key => $value){
    $path = realpath($dir . DIRECTORY_SEPARATOR . $value);

    if (is_dir($path)) {
    } else if (!is_link($path) && strpos($value, '.') !== 0 && $value !== 'index.md') {
      $mdvalue = $value . '.md';
      $mdpath = $dir . '/md/' . $mdvalue;
      $mdexists = file_exists($mdpath);
      $slug = slugify($value);

      $length++;

      if ($mdexists) {
        // $path = $mdpath;
        // $value = $mdvalue;
      }

      array_push($farray, [
        'oldPath' => preg_replace('/^.+?\/content\//', '', $path),
        'path' => getcwd() . '/content/' . $pathStr . '/' . ($mdexists ? 'md/' . $mdvalue : $value),
        'name' => preg_replace('/^[0-9]+[^A-Za-z]+/', '', preg_replace('/(\.[a-z0-9]{1,6})+/i', '', $value)),
        'slug' => $slug,
        'md' => $mdexists,
        'content' => ($mdexists ? trim(preg_replace('/\{[^a-z]*(raw|search)-?content\:.*/si', '', file_get_contents($mdpath))) : ''),
        'url' => '/' . $pathStr . '/' . $slug
      ]);

      if (strpos($value, '.webloc') !== false) {
        preg_match('/<string>(https?:[^\<]+)/i', file_get_contents($path), $matches);
        $farray[sizeof($farray) - 1]['url'] = $matches[1];
      }

      global $filesArray;
      array_push($filesArray, $farray[sizeof($farray) - 1]);
    }
  }

  return $farray;
}

function getSections($dir, &$length, $pathStr) {
  $files = scandir($dir);
  $sections = [];

  foreach ($files as $key => $value){
    $path = realpath($dir . DIRECTORY_SEPARATOR . $value);

    if (!is_dir($path)) {
    } else if (strpos($value, '.') !== 0) {
      $slength = 0;
      $slug = slugify($value);

      $sections[$value] = [
        'name' => $value,
        'slug' => $slug,
        'files' => getFiles($path, $slength, $pathStr . '/' . $value),
        'length' => $slength
      ];

      $length += sizeof($sections[$value]['files']);
    }
  }

  if (sizeof($sections) < 1) {
    $slength = 0;

    $sections['index'] = [
      'name' => '',
      'files' => getFiles($dir, $slength, $pathStr),
      'length' => $slength
    ];

    $length += $slength;
  }

  return $sections;
}

function getPlays($dir, &$length, $pathStr) {
  $files = scandir($dir);
  $plays = [];

  foreach ($files as $key => $value){
    $path = realpath($dir . DIRECTORY_SEPARATOR . $value);

    if (!is_dir($path)) {
    } else if ($value != "." && $value != "..") {
      $plength = 0;
      $slug = slugify($value);

      $plays[$value] = [
        'name' => $value,
        'slug' => $slug,
        'hasIndex' => file_exists($path . '/index.md'),
        'hasFooter' => file_exists($path . '/footer.md'),
        'path' => $pathStr . '/' . $slug,
        'oldPath' => preg_replace('/^.+?\/content\//', '', $path),
        'sections' => getSections($path, $plength, $pathStr . '/' . $slug),
        'length' => $plength
      ];

      $length += $plength;

      $link = $dir . '/' .$plays[$value]['slug'];

      $index = $path . '/index.md';
      if (!file_exists($index)) {
        file_put_contents($index, "---\ntemplate: play\n---\n");
      } else {
        file_put_contents($index, preg_replace("/\n---\n/", "\ntemplate: play\n---\n", preg_replace("/\ntemplate:[^\n]+/", '', file_get_contents($index))));
      }

      if (!file_exists($link)) {
        rename($path, $link);
      }
    }
  }

  return $plays;
}

function getPlaybooks($dir) {
  $files = scandir($dir);
  $playbooks = [];

  file_put_contents($dir . DIRECTORY_SEPARATOR . 'tree.md', "---\ntemplate: tree\n---\n");

  foreach ($files as $key => $value) {
    $path = realpath($dir . DIRECTORY_SEPARATOR . $value);

    if (!is_dir($path)) {
    } else if ($value !== 'img' && strpos($value, '.') !== 0) {
      echo $value."\n";
      $length = 0;
      $name = trim(preg_replace('/^[^\.]+\./', '', $value));
      $slug = slugify($name);

      $playbooks[$value] = [
        'name' => $name,
        'slug' => $slug,
        'plays' => getPlays($path, $length, $slug),
        'length' => $length
      ];

      file_put_contents($path . '/index.md', "---\ntemplate: playbook\n---\n");

      rename($path, $dir . '/' .$slug);
    }
  }

  return $playbooks;
}

function slugify($string) {
  return preg_replace('/^[0-9]+\-/', '',
          preg_replace('/-{2,}/', '-',
          preg_replace('/-+$/', '',
            preg_replace('/[^a-z0-9\-\.\/]+/', '-',
              strtolower($string)))));
}


$object = [
  'playbooks' => getPlaybooks('./content'),
  'files' => $filesArray
];

print_r($object);


file_put_contents($output, serialize($object));
?>