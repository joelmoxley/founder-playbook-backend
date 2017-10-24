# Founder Playbook

This repository contains the code for the Founder Playbook website.

The site is built in PHP using [Pico CMS](http://picocms.org/).

The layout templates and asset files are located in `themes/vbtk`.

The main plugin (used for search functionality, fuzzy URL matching, etc.) is
`plugins/VBTKPlugin.php`.

Cloning the content repo and generating the content markdown files is done
inside `clone.php` and `serialize.php`.

To install:

`cd themes/vbtk`
`npm install`
`gulp`
`php clone.php`