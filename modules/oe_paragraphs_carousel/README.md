OpenEuropa Paragraphs Carousel
============================

This module provides the Carousel paragraph that depends on the [OpenEuropa Media](https://github.com/openeuropa/oe_media)
component.

The paragraph allows editors to create Carousel items similar to a Banner with multiple slides.

### New "Size" field.

By running the `oe_paragraphs_carousel_post_update_00001` post update, a new "Size" field will be added in
the form display. In order to keep the form data consistent, a drush command is provided to set its value to "medium"
for the existing Carousel paragraphs.

After the post update has been successfully ran, please run the `oe-paragraphs-update-carousel-data:run` drush
command to update the existing data.
