OpenEuropa Paragraphs
=====================

[![Build Status](https://drone.fpfis.eu/api/badges/openeuropa/oe_paragraphs/status.svg?branch=master)](https://drone.fpfis.eu/openeuropa/oe_paragraphs)
[![Packagist](https://img.shields.io/packagist/v/openeuropa/oe_paragraphs.svg)](https://packagist.org/packages/openeuropa/oe_paragraphs)

This module provides a number of Paragraph types that are based on a select number of components integrating [ECL](https://github.com/ec-europa/europa-component-library),
the component library of the European Commission.
These are intended to be used on landing pages of various sites of the European Commission.

**Table of contents:**

- [Installation](#installation)
- [Development setup](#development-setup)
- [Contributing](#contributing)
- [Versioning](#versioning)

## Installation

The recommended way of installing the OpenEuropa Authentication module is via [Composer][1].

```bash
composer require openeuropa/oe_paragraphs
```

### Enable the module

In order to enable the module in your project run:

```bash
./vendor/bin/drush en oe_paragraphs
```

## Paragraphs

The module provides the following paragraph types:

- **Accordion**: The Accordion paragraph is a collection of collapsible items with icon, title and long text required fields.
- **Accordion item**: The Accordion item paragraph is a single accordion with icon, title and long text required fields. It is intended to be 
used exclusively in conjunction with the "Accordion" paragraph.
- **Content row**: The Content row paragraph allows editors to group multiple paragraphs together. An in-page navigation can be shown 
optionally.
- **Contextual navigation**: The Contextual navigation paragraph allows editors to add a number of links that helps the user navigate the page. The 
links, their visibility limit and navigation label can be configured using the available fields.
- **Links block**: The Links block paragraph displays a list of links, with an optional title. It is used for instance with the dropdown component.
- **Listing item**: The Listing item paragraph displays content teasers. A list item is available in the following variants: default, date 
item, highlighted and thumbnail with primary or secondary image.
- **Listing item block**: The Listing item block paragraph allows editors to group multiple Listing item paragraphs in one, two or three columns.
An optional link can be added to the block.
- **Quote**: The Quote paragraph allows editors to add a quotation with quote and its attribution.
- **Rich text**: The Rich text paragraph adds an optional title field and a long text field.
- **Facts and figures**: The facts and figures paragraph is used to deliver numerical representations of facts that are easier to portray visually through the use of statistics.
- **Fact**: Single fact item, to be used exclusively in conjunction with the "Facts and figures" paragraph type.
- **Social media follow**: The Social media follow paragraph allows the editor to add a list of social media links.

This project also ships with optional submodules, providing optional paragraph types. Check the [`./modules`](./modules)
directory for more information.

#### Banner
Allows editors to create Banners that display a prominent message and related action. To get this paragraph type enable
the OpenEuropa Paragraphs Media submodule.

## Development setup

You can build a test site by running the following steps.

* Install all the composer dependencies:

```bash
composer install
```

* Customize build settings by copying `runner.yml.dist` to `runner.yml` and
changing relevant values.

* Install test site by running:

```bash
./vendor/bin/run drupal:site-install
```

Your test site will be available at `./build`.

### Using Docker Compose

Alternatively, you can build a development site using [Docker](https://www.docker.com/get-docker) and 
[Docker Compose](https://docs.docker.com/compose/) with the provided configuration.

Docker provides the necessary services and tools such as a web server and a database server to get the site running, 
regardless of your local host configuration.

#### Requirements:

- [Docker](https://www.docker.com/get-docker)
- [Docker Compose](https://docs.docker.com/compose/)

#### Configuration

By default, Docker Compose reads two files, a `docker-compose.yml` and an optional `docker-compose.override.yml` file.
By convention, the `docker-compose.yml` contains your base configuration and it's provided by default.
The override file, as its name implies, can contain configuration overrides for existing services or entirely new 
services.
If a service is defined in both files, Docker Compose merges the configurations.

Find more information on Docker Compose extension mechanism on [the official Docker Compose documentation](https://docs.docker.com/compose/extends/).

#### Usage

To start, run:

```bash
docker-compose up
```

It's advised to not daemonize `docker-compose` so you can turn it off (`CTRL+C`) quickly when you're done working.
However, if you'd like to daemonize it, you have to add the flag `-d`:

```bash
docker-compose up -d
```

Then:

```bash
docker-compose exec web composer install
docker-compose exec web ./vendor/bin/run drupal:site-install
```

Using default configuration, the development site files should be available in the `build` directory and the development site
should be available at: [http://127.0.0.1:8080/build](http://127.0.0.1:8080/build).

#### Running the tests

To run the grumphp checks:

```bash
docker-compose exec web ./vendor/bin/grumphp run
```

To run the phpunit tests:

```bash
docker-compose exec web ./vendor/bin/phpunit
```

To run the behat tests:

```bash
docker-compose exec web ./vendor/bin/behat
```

## Contributing

Please read [the full documentation](https://github.com/openeuropa/openeuropa) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the available versions, see the [tags on this repository](https://github.com/openeuropa/oe_paragraphs/tags).

[1]: https://www.drupal.org/docs/develop/using-composer/using-composer-to-manage-drupal-site-dependencies#managing-contributed
