Packagist release publisher
===========================

This tool allows to publish releases published on packagist to different targets. 
At the moment only tapatalk is supported.

Requirements
------------

 - PHP 7.1


Install
-------

 1. Create a project `composer create-project netzmacht/packagist-release-publisher release-publisher`
 2. Copy `config/config.php.dist` to `config/config.php` and adjust the config
    The rendering is done by a renderer which uses callbacks.
 3. Call `php bin/release-publisher`

Options
-------

By default the tool checks for releases made today. You can define a custom option by using the since option. Valid 
values are any string which can be parsed by `DateTime`.

```bash

php bin/release-publisher -since="3 days ago" -v

```
