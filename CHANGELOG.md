# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## develop

[Full Changelog](https://github.com/netzmacht/release-notifier/compare/0.3.2...0.4.0)

### Added

- Add ability to define a wait interval between each publish action
- Add options to the configuration which are passed to the renderer
- Add twitter publisher

## 0.3.2 - 2018-11-01

[Full Changelog](https://github.com/netzmacht/release-notifier/compare/0.3.1...0.3.2)

### Fixed

- Last modified date was used for last run

## 0.3.1 - 2018-09-19

[Full Changelog](https://github.com/netzmacht/release-notifier/compare/0.3.0...0.3.1)

### Fixed

- Last run information was ignored in publish command

## 0.3.0 - 2018-09-07

[Full Changelog](https://github.com/netzmacht/release-notifier/compare/0.2.2...0.3.0)

### Added

- Add Releases interface

- Add ReleaseIterator to make last run accessible

### Changed

- Change Publisher interface to count the number of successful made publishments.

- Use different history files for each config files stored in the same directory as the config file

- Change namespace from App to Netzmacht\ReleaseNotifier

- Use last modified date of packagist instead of last run of the tool

- Move classes from Packagist namespace to Package

- Rename to release-notifier

## 0.2.2 - 2018-09-06
