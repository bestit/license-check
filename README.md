# best it license check

[![Build Status](https://travis-ci.org/bestit/license-check.svg?branch=main)](https://travis-ci.org/bestit/license-check) [![Build Status](https://scrutinizer-ci.com/g/bestit/license-check/badges/build.png?b=main)](https://scrutinizer-ci.com/g/bestit/license-check/build-status/main) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bestit/license-check/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/bestit/license-check/?branch=main) [![Code Coverage](https://scrutinizer-ci.com/g/bestit/license-check/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/bestit/license-check/?branch=main)

This tool can be used to check the licenses of the used packages.

## Installation

Our package can be installed with composer with the following command:
```bash
composer require best-it/license-check --dev
```

## Usage

Create one or more YAML configuration files ([license-check.yml](license-check.yml)) like this:
```yaml
allowed-licenses:
  - MIT

allowed-packages:
  composer:
    - /best-it\/license-check/
    - /best-it\/.*/
  node:
    - /test\/test/
```

The allowed packages must be defined as a regular expression.

If multiple files are passed as an argument they will be merged to a single configuration.

Execute the following command to get a report which includes the information that everything is compatible or that
some dependencies are not compatible with your configuration. In case of problems the error code is 1.
```bash
./vendor/bin/license-check 
```

## Development / Contributing

See [CONTRIBUTING.md](./CONTRIBUTING.md).
