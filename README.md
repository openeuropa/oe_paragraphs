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
- **Document**: The Document paragraph allows editors to render documents.
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
the OpenEuropa Paragraphs Banner submodule.

#### Carousel
The paragraph allows editors to create Carousel items similar to a Banner with multiple slides. To get this paragraph
type enable the OpenEuropa Paragraphs Carousel submodule.

#### Chart
Allows editors to create paragraphs that display Webtools chart media entities. To get this paragraph type enable
the OpenEuropa Paragraphs Chart submodule.

#### Description list
Allows editors to create paragraphs that provide HTML description lists with a heading.
To get this paragraph type enable the OpenEuropa Paragraphs Description list submodule.

#### Iframe
Allows editors to create paragraphs that display Iframe media entities.
To get this paragraph type enable the OpenEuropa Paragraphs Iframe submodule.

#### Map
Allows editors to create paragraphs that display Webtools map media entities. To get this paragraph type enable
the OpenEuropa Paragraphs Map submodule.

#### Text with feature media
Allows editors to create paragraphs that show a rich text with a title and a featured media on the right, if any.
To get this paragraph type enable the OpenEuropa Text with featured Media paragraph submodule.

#### Social feed - Deprecated (will be removed in 2.0 as the Webtools *smk* service is no longer supported)
Allows editors to create paragraphs that display Webtools social feed media entities. To get this paragraph type enable
the OpenEuropa Paragraphs Social feed submodule.

#### Timeline
Allows editors to create paragraphs that display displays concurrent and/or sequential items visually on a time axis.
To get this paragraph type enable the OpenEuropa Paragraphs Timeline submodule.

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

**Please note:** project files and directories are symlinked within the test site by using the
[OpenEuropa Task Runner's Drupal project symlink](https://github.com/openeuropa/task-runner-drupal-project-symlink) command.

If you add a new file or directory in the root of the project, you need to re-run `drupal:site-setup` in order to make
sure they are be correctly symlinked.

If you don't want to re-run a full site setup for that, you can simply run:

```
$ ./vendor/bin/run drupal:symlink-project
```

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

#### Step debugging

To enable step debugging from the command line, pass the `XDEBUG_SESSION` environment variable with any value to
the container:

```bash
docker-compose exec -e XDEBUG_SESSION=1 web <your command>
```

Please note that, starting from XDebug 3, a connection error message will be outputted in the console if the variable is
set but your client is not listening for debugging connections. The error message will cause false negatives for PHPUnit
tests.

To initiate step debugging from the browser, set the correct cookie using a browser extension or a bookmarklet
like the ones generated at https://www.jetbrains.com/phpstorm/marklets/.

## Contributing

Please read [the full documentation](https://github.com/openeuropa/openeuropa) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the available versions, see the [tags on this repository](https://github.com/openeuropa/oe_paragraphs/tags).

[1]: https://www.drupal.org/docs/develop/using-composer/using-composer-to-manage-drupal-site-dependencies#managing-contributed

## Upgrade to 1.27.0

In 1.27.0 the requirement of the contrib [allowed_formats](https://www.drupal.org/project/allowed_formats) module has been removed.
When upgrading to this version, specifically require the contrib module in your project's code base and uninstall the module. This
should be released and then the requirement can be safely removed in a feature release.
