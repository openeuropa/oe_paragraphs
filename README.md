OpenEuropa Paragraphs
=====================

[![Build Status](https://travis-ci.org/openeuropa/oe_paragraphs.svg?branch=master)](https://travis-ci.org/openeuropa/oe_paragraphs)

This module integrates the
[ECL](https://github.com/ec-europa/europa-component-library) - the component
library of the European Commission - with the
[Paragraphs](https://www.drupal.org/project/paragraphs) module.

It provides a number of Paragraph types that are based on a select number of
ECL components. These are intended to be used on landing pages of various sites
of the European Commission.


## Development setup

You can build a test site by running the following steps.

* Install all the composer dependencies:

```
$ composer install
```

* Customize build settings by copying `runner.yml.dist` to `runner.yml` and
changing relevant values.

* Setup test site by running:

```
$ ./vendor/bin/run drupal:site-setup
```

This will symlink the theme in the proper directory within the test site and
perform token substitution in test configuration files such as `behat.yml.dist`.

* Install test site by running:

```
$ ./vendor/bin/run drupal:site-install
```

Your test site will be available at `./build`.

### Using Docker Compose

Alternatively you can build a test site using Docker and Docker-compose with the
provided configuration.

Requirements:

- [Docker](https://www.docker.com/get-docker)
- [Docker-compose](https://docs.docker.com/compose/)

Run:

```
$ docker-compose up -d
```

Then:

```
$ docker-compose exec -u web web composer install
$ docker-compose exec -u web web ./vendor/bin/run drupal:site-setup
$ docker-compose exec -u web web ./vendor/bin/run drupal:site-install
```

Your test site will be available at
[http://localhost:8080/build](http://localhost:8080/build).

To run the Behat test:

```
$ docker-compose exec -u web web ./vendor/bin/behat
```
