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
 3. Call `php bin/release-publisher list-releases -v` to get all tracked packages
 4. Call `php bin/release-publisher publish-notes -v` to publish the release notes.

Options
-------

By default the tool checks for releases made since the last time it published packages or today for the first run. 
You can define a custom option by using the since option. Valid values are any string which can be parsed by `DateTime`.

```bash

php bin/release-publisher list-releases --since="3 days ago" -v
php bin/release-publisher publish-notes --since="3 days ago" -v

```

*Note* If the last run time is not ignored and the last run time is newer than the since option, the last run time is 
preferred.

By default the tool stores the time when it's run the last time. You can define a custom option by ignore the last run
time. 

```bash

php bin/release-publisher list-releases --ignore-last-run -v
php bin/release-publisher publish-notes --ignore-last-run -v
php bin/release-publisher list-releases --since="3 days ago" --ignore-last-run -v
php bin/release-publisher publish-notes --since="3 days ago" --ignore-last-run -v

```
