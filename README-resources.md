# Overview of external resources

All files in resources/ are managed by Bower (http://bower.io/).
The file .bowerrc defines global settings for Bower, the file bower.json lists our dependencies.
Each dependency is installed in a separate directory in resources/ and
includes meta information in the file .bower.json located in a dependency's directory.
License information missing from the .bower.json files are documented below.

## Useful commands:

* Install a new dependecy:
        bower install 'package#version' --save --save-exact
* Search for a package ([online](http://bower.io/search))
        bower search package
* Remove unused files from resources/ (requires extra configuration in bower.json):
        preen

## Notes

* kartograph.js: unmaintained sinced Jul 16, 2015
* leaflet-dist: only for versions <= 0.7.2, newer versions are distributed
  in the bower package 'leaflet' instead (currently ignored as a dependency in .bowerrc
  since proj4leaflet incorrectly depends on 'leaflet'
* proj4leaflet: master branch is only for leaflet versions <= 0.7.2,
  for 1.0-beta1 use the leaflet-proj-refactor branch

## Additional licenses

* d3: BSD
* gosquared-flags: MIT
* highcharts-release: commercial http://shop.highsoft.com/highcharts.html
* isotope: commercial http://isotope.metafizzy.co/v1/docs/license.html
* jquery.isotope-masonry-column-shift-layout-mode: commercial http://isotope.metafizzy.co/v1/docs/license.html
* jquery-json: MIT
* jquery-migrate: MIT
* jquery-ui: MIT
* kartograph.js: LGPL-3.0
* leaflet-dist: BSD-2-Clause
* modernizr: MIT
* tablesorter: MIT

## Additional resources (not managed by Bower)

* Ajaxload http://ajaxload.info/
  License: WTFPL http://www.wtfpl.net/
  Installed: Indicator Big 2, background #FFFFF, foreground #606060, not transparent
  Installed: Bert2, background #FFFFF, foreground #2973BD, transparent
  Files:
  * 02resources/img/ajax-loader.gif
  * 02resources/img/images/ajax-loader.gif
  * 02resources/img/images/ajax-loader2.gif

* Isotope http://isotope.metafizzy.co/v1/
  License: commercial http://isotope.metafizzy.co/v1/docs/license.html
  Files:
  * 02resources/libs/isotopeMasonryColumnShift.js
  * 02resources/libs/isotope_extend.js [ MODIFIED ]
  * 02resources/css/isotope.css
