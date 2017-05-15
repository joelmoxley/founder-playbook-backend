<?php
require('./config/config.php');

if (file_exists('content') && !file_exists('content/.git')) {
  exec('rm -rf content');
}

if (file_exists('content')) {
  exec('cd content && git add --all && git reset --hard HEAD && git pull > /dev/null 2>/dev/null && git checkout ' . $config['git']['branch'] . ' && php ../serialize.php');
} else {
  exec('git clone ' . $config['git']['url'] . '.git content > /dev/null 2>/dev/null && git checkout ' . $config['git']['branch'] . ' && php serialize.php &');
}
?>