Release notifier
================

This tool allows to notify about releases published on packagist to a broader audience. 

There are multiple channels to share the release information planned. At the moment only tapatalk is supported.

Requirements
------------

 - PHP 7.1


Install
-------

### Global installation 

 1. Make sure that the composer global bin directory is part of your `PATH` environment variable.
 2. Install the tool with `composer global require netzmacht/release-notifier` 
 
### Local installation

Alternatively you can install it in any directory locally

 1. Install the tool with `composer require netzmacht/release-notifier`
 

Usage
-----

The following description assumes you have installed the tool globally. If you have an local installation you have to
adjust the executable path.

 1. Create a configuration file in your current directory 
    ```bash
    release-notifier create-config config.php
    ```
 2. Edit the configuration file with your publishers and packages.
 3. Check if any new releases where made (since last run). If config is used the first time, it's checked against today.
    ```bash
    release-notifier check -v
    ```
 4. Let the tool publish your releases
    ```bash
    release-notifier publish -v
    ``` 
 5. Setup an cron job to automate the task
 
The tool used the *symfony/console*. You might use the `list` command or `--help` option to get more information about
the provides commands.


Migrate from netzmacht/packagist-release-publisher
--------------------------------------------------

The tool was previously named "netzmacht/packagist-release-publisher". To migrate 

 1. Replace namespaces beginning with "App\" with "Netzmacht\ReleaseNotifier\" in your config file.
 2. Be aware that last run information has changed and are not migrated.
