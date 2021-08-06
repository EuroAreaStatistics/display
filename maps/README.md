API to generate map object

Delivers

- get JavaScript variable 'country-names' at '/countryNames?lg=parameter&th=parameter&mode=parameter'
- get PHP variable by includind and calling 'getCountryNames($language,$theme,$mode)'

Parameters

language
defaults to 'en'

theme
defaults to 'oecd'

mode
defaults to 'standard'

---

The map data is subject to [Eurostat's download provisions](https://ec.europa.eu/eurostat/web/gisco/geodata/reference-data/administrative-units-statistical-units).
© EuroGeographics for the administrative boundaries.

The following Eurostat GeoJSON files were used:
  - boundaries (in various resolutions) and labels for:
    - Countries 2020
    - NUTS 2021 (levels 0,1,2,3)
