OpenEuropa Paragraphs Contact
==============================

This module provides the Contact paragraph that depends on the [OpenEuropa Content Entity Contact](https://github.com/openeuropa/oe_content/tree/2.x/modules/oe_content_entity/modules/oe_content_entity_contact)
component and [Inline Entity Form](https://www.drupal.org/project/inline_entity_form) and
[Composite Reference](https://www.drupal.org/project/composite_reference) contrib modules.

The paragraph displays General and Press Contact entities.

## Installation

Make sure you have read the OpenEuropa Content [README.md](https://github.com/openeuropa/oe_content#openeuropa-content)
before enabling this module.

In order to be able to manage General and Press Contact entities, assign the following permissions to appropriate roles:
- `Contact: Create new General entity`
- `Contact: Create new Press entity`
- `Contact: Delete any General entity`
- `Contact: Delete any Press entity`
- `Contact: Edit any General entity`
- `Contact: Edit any Press entity`

Grant the `Contact: View any published entity` permission to the anonymous user role in order to allow your site's
visitors to view contact entities.

#### Required contrib modules
The OpenEuropa Paragraphs Contact requires the following contrib modules:

* [OpenEuropa Content](https://github.com/openeuropa/oe_content) (~2.1.1)
* [Inline Entity Form](https://www.drupal.org/project/inline_entity_form) (^1.0@RC)
* [Composite Reference](https://www.drupal.org/project/composite_reference) (^1.0@alpha)
