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

* leaflet-dist: only for versions <= 0.7.2, newer versions are distributed
  in the bower package 'leaflet' instead (currently ignored as a dependency in .bowerrc
  since proj4leaflet incorrectly depends on 'leaflet'
* proj4leaflet: master branch is only for leaflet versions <= 0.7.2,
  for 1.0-beta1 use the leaflet-proj-refactor branch

## Additional licenses

* d3: BSD
* gosquared-flags: MIT
* highcharts-release: commercial http://shop.highsoft.com/highcharts.html
* jquery-ui: MIT
* leaflet-dist: BSD-2-Clause
* modernizr: MIT
* tablesorter: MIT
