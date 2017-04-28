<?php
if (file_exists('content') && !file_exists('content/.git')) {
  exec('rm -rf content');
}

if (file_exists('content')) {
  exec('cd content && git pull > /dev/null 2>/dev/null &');
} else {
  exec('git clone https://rchipka@bitbucket.org/vibethink-dev/founders-playbook-content.git content > /dev/null 2>/dev/null &');
}
?>