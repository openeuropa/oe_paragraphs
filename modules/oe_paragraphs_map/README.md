OpenEuropa Paragraphs Map
==============================

This module provides the Map paragraph that depends on the [OpenEuropa Paragraphs Media Field Storage](https://github.com/openeuropa/oe_paragraphs/tree/master/modules/oe_paragraphs_media_field_storage) and
 [OpenEuropa Media Webtools](https://github.com/openeuropa/oe_media/blob/master/modules/oe_media_webtools) components.

The paragraph displays Webtools map entities.

## Installation

Make sure you you have read the OpenEuropa Webtools [README.md](https://github.com/openeuropa/oe_webtools#openeuropa-webtools-media)
before enabling this module.

After enabling this module make sure you assign the following permissions to appropriate role:
- `Webtools map: Create new media`
- `Webtools map: Edit own media`
- `Webtools map: Edit any media`

## Required contrib modules

Before enabling this module, make sure that the following modules are present in your codebase by adding them to your
`composer.json` and by running `composer update`:

```json
"require": {
    "drupal/json_field": "~1.0.0-rc3"
}
```
