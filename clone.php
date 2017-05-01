<?php
// $gitURL = 'https://rchipka@bitbucket.org/vibethink-dev/founders-playbook-content.git';
$gitURL = 'https://bitbucket.org/vibethink-dev/fp-content-test';

if (file_exists('content') && !file_exists('content/.git')) {
  exec('rm -rf content');
}

if (file_exists('content')) {
  exec('cd content && git reset --hard HEAD && git pull > /dev/null 2>/dev/null &');
} else {
  exec('git clone ' . $gitURL . ' content > /dev/null 2>/dev/null &');
}
?>