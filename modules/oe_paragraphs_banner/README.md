OpenEuropa Paragraphs Banner
============================

This module provides the Banner paragraph that depends on the [OpenEuropa Media](https://github.com/openeuropa/oe_media)
component.

The paragraph allows editors to create Banners that display a prominent message and related action.

### Deprecate the "Banner type" field.

By running the `oe_paragraphs_banner_post_update_00002` post update, the "Banner type" field will be replaced in
the form display by two new fields "Size" and "Alignment". In order to keep the form data consistent, a drush
command is provided to migrate the existing value of the "Banner type" in the "Size" and "Alignment" fields.

After the post update has been successfully ran, please run the `oe-paragraphs-update-banner-data:run` drush
command to update the existing data.
