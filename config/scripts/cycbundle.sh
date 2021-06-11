#!/bin/bash
set -ex

cd ../../03resources

export MODJS_DIR=../../03/JScomponents #deklariert globale variable
export MODSCSS_DIR=../../03/SCSScomponents #deklariert globale variable


pushd js

uglifyjs --source-map-include-sources --source-map slide.js.map -o slide.min.js \
    ${MODJS_DIR}/chartTypes/slider.js \
    ${MODJS_DIR}/modules/dataHandler/filterLocation.js \
    ${MODJS_DIR}/modules/dataHandler/filterYear.js \
    ${MODJS_DIR}/modules/dataHandler/flipData.js \
    ${MODJS_DIR}/modules/dataHandler/getHumanReadData.js \
    ${MODJS_DIR}/modules/dataHandler/sortDimensions0.js \
    ${MODJS_DIR}/modules/dataHandler/sortDimensions0sum.js \
    ${MODJS_DIR}/modules/dataHandler/sortDimensions1.js \
    ${MODJS_DIR}/modules/dataHandler/getChartWithLabels.js \
    ${MODJS_DIR}/modules/dataHandler/getMaxCountries.js \
    ${MODJS_DIR}/modules/highcharts/simpleChartsCore.js \
    ${MODJS_DIR}/modules/highcharts/sliderChartConfigs.js

uglifyjs --source-map-include-sources --source-map simple.js.map -o simple.min.js \
    ${MODJS_DIR}/chartTypes/simpleChart.js \
    ${MODJS_DIR}/modules/dataHandler/filterLocation.js \
    ${MODJS_DIR}/modules/dataHandler/filterYear.js \
    ${MODJS_DIR}/modules/dataHandler/flipData.js \
    ${MODJS_DIR}/modules/dataHandler/getHumanReadData.js \
    ${MODJS_DIR}/modules/dataHandler/sortDimensions0.js \
    ${MODJS_DIR}/modules/dataHandler/sortDimensions0sum.js \
    ${MODJS_DIR}/modules/dataHandler/sortDimensions1.js \
    ${MODJS_DIR}/modules/dataHandler/urlQuery.js \
    ${MODJS_DIR}/modules/dataHandler/getChartWithLabels.js \
    ${MODJS_DIR}/modules/dataHandler/getMaxCountries.js \
    ${MODJS_DIR}/modules/highcharts/simpleChartsCore.js \
    ${MODJS_DIR}/modules/highcharts/simpleChartConfigs.js

uglifyjs --source-map-include-sources --source-map stacked.js.map -o stacked.min.js \
    ${MODJS_DIR}/chartTypes/stackedChart.js \
    ${MODJS_DIR}/modules/dataHandler/filterDimension.js \
    ${MODJS_DIR}/modules/dataHandler/flipData.js \
    ${MODJS_DIR}/modules/dataHandler/getHumanReadData.js \
    ${MODJS_DIR}/modules/dataHandler/urlQuery.js \
    ${MODJS_DIR}/modules/dataHandler/getChartWithLabels.js \
    ${MODJS_DIR}/modules/highcharts/simpleChartsCore.js \
    ${MODJS_DIR}/modules/highcharts/simpleChartConfigs.js

uglifyjs --source-map-include-sources --source-map mapWizardSimple.js.map -o mapWizardSimple.min.js \
    ${MODJS_DIR}/modules/dataHandler/getHumanReadData.js \
    ${MODJS_DIR}/modules/dataHandler/getLatestYearData.js \
    ${MODJS_DIR}/modules/dataHandler/filterYear.js \
    ${MODJS_DIR}/modules/maps/getMapData.js \
    ${MODJS_DIR}/modules/maps/getIndicatorColorScale.js \
    ${MODJS_DIR}/modules/maps/getBubbleSize.js \
    ${MODJS_DIR}/modules/maps/getDisputedLines.js \
    ${MODJS_DIR}/modules/maps/getMapSubPoints.js \
    ${MODJS_DIR}/modules/maps/addMapLayers.js \
    ${MODJS_DIR}/modules/maps/updateLegend.js \
    ${MODJS_DIR}/modules/maps/mapStandardPlotting.js \
    ${MODJS_DIR}/modules/maps/getFlows.js \
    ${MODJS_DIR}/modules/maps/updateFlowData.js \
    ${MODJS_DIR}/modules/maps/leaflet.polylineDecorator.min.js \
    ${MODJS_DIR}/modules/maps/highlightAndPopup.js \
    ${MODJS_DIR}/modules/mapTeaser/mapTeaserExportRestrictions.js \
    ${MODJS_DIR}/modules/mapTeaser/mapTeaserExportRestrictionsMain.js \
    ${MODJS_DIR}/modules/mapTeaser/mapTeaserIfiCountryData.js \
    ${MODJS_DIR}/modules/mapTeaser/mapTeaserText.js \
    ${MODJS_DIR}/modules/mapTeaser/mapTeaserTextMulti.js

uglifyjs --source-map-include-sources --source-map mapWizardLayer.js.map -o mapWizardLayer.min.js \
    ${MODJS_DIR}/modules/dataHandler/filterLocation.js \
    ${MODJS_DIR}/modules/dataHandler/filterYear.js \
    ${MODJS_DIR}/modules/dataHandler/flipData.js \
    ${MODJS_DIR}/modules/dataHandler/getHumanReadData.js \
    ${MODJS_DIR}/modules/dataHandler/sortDimensions0.js \
    ${MODJS_DIR}/modules/dataHandler/sortDimensions0sum.js \
    ${MODJS_DIR}/modules/dataHandler/sortDimensions1.js \
    ${MODJS_DIR}/modules/dataHandler/getLatestYearData.js \
    ${MODJS_DIR}/modules/dataHandler/getTableData.js \
    ${MODJS_DIR}/modules/maps/getMapData.js \
    ${MODJS_DIR}/modules/maps/getIndicatorColorScale.js \
    ${MODJS_DIR}/modules/maps/getBubbleSize.js \
    ${MODJS_DIR}/modules/maps/getDisputedLines.js \
    ${MODJS_DIR}/modules/maps/getMapSubPoints.js \
    ${MODJS_DIR}/modules/maps/addMapLayers.js \
    ${MODJS_DIR}/modules/maps/updateLegend.js \
    ${MODJS_DIR}/modules/maps/mapStandardPlotting.js \
    ${MODJS_DIR}/modules/maps/highlightAndPopup.js \
    ${MODJS_DIR}/modules/maps/getFlows.js \
    ${MODJS_DIR}/modules/maps/updateFlowData.js \
    ${MODJS_DIR}/modules/mapTeaser/mapTeaserText.js \
    ${MODJS_DIR}/modules/mapTeaser/mapTeaserTextMulti.js \
    ${MODJS_DIR}/modules/mapTeaser/mapTeaserExportRestrictions.js \
    ${MODJS_DIR}/modules/mapTeaser/mapTeaserExportRestrictionsMain.js \
    ${MODJS_DIR}/modules/mapTeaser/mapTeaserIfiCountryData.js \
    ${MODJS_DIR}/modules/mapLayers/mapLayerFuncs.js \
    ${MODJS_DIR}/modules/mapLayers/mapWizardLayer.js \
    ${MODJS_DIR}/modules/mapLayers/updateCountryProfile.js \
    ${MODJS_DIR}/modules/mapLayers/updateCountryProfileSimple.js \
    ${MODJS_DIR}/modules/tables/addSimpleTable.js \
    ${MODJS_DIR}/modules/tables/addComplexTable.js \
    ${MODJS_DIR}/modules/highcharts/simpleChartsCore.js \
    ${MODJS_DIR}/modules/highcharts/simpleChartConfigs.js

uglifyjs --source-map-include-sources --source-map fourLines.js.map -o fourLines.min.js \
    ${MODJS_DIR}/modules/dataHandler/getHumanReadData.js \
    ${MODJS_DIR}/chartTypes/fourLines/main.js \
    ${MODJS_DIR}/chartTypes/fourLines/drawFourLinesMulti.js \
    ${MODJS_DIR}/chartTypes/fourLines/MakeChart.js \
    ${MODJS_DIR}/chartTypes/fourLines/syncScales.js \
    ${MODJS_DIR}/chartTypes/fourLines/updateCharts.js \
    ${MODJS_DIR}/chartTypes/fourLines/linesInteract.js

uglifyjs --source-map-include-sources --source-map downloadPage.js.map -o downloadPage.min.js \
    ${MODJS_DIR}/chartTypes/downloadPage.js \

uglifyjs --source-map-include-sources --source-map barsTable.js.map -o barsTable.min.js \
    ${MODJS_DIR}/chartTypes/barsTable.js \

uglifyjs --source-map-include-sources --source-map barsLines.js.map -o barsLines.min.js \
    ${MODJS_DIR}/chartTypes/barsLines.js \

uglifyjs --source-map-include-sources --source-map regionalTable.js.map -o regionalTable.min.js \
    ${MODJS_DIR}/chartTypes/regionalTable.js \


uglifyjs --source-map-include-sources --source-map headerFooter.js.map -o headerFooter.min.js \
    ${MODJS_DIR}/headerFooter/headerFooter.js \

uglifyjs --source-map-include-sources --source-map PDFexport.js.map -o PDFexport.min.js \
    ${MODJS_DIR}/extensions/DataPDFwizard.js \


popd
pushd css

sass ${MODSCSS_DIR}/themesWizard/config_oecd.scss --style compressed > oecd.css
sass ${MODSCSS_DIR}/themesWizard/config_ecb.scss --style compressed > ecb.css
sass ${MODSCSS_DIR}/themesWizard/config_default.scss --style compressed > default.css
#
sass ${MODSCSS_DIR}/wizardShort/main.scss --style compressed > wizardShort.css
#
sass ${MODSCSS_DIR}/chartTypes/barsTable.scss --style compressed > barsTable.css
sass ${MODSCSS_DIR}/chartTypes/barsLines.scss --style compressed > barsLines.css
sass ${MODSCSS_DIR}/chartTypes/chartStandard.scss --style compressed > chartStandard.css
sass ${MODSCSS_DIR}/chartTypes/downloadPage.scss --style compressed > downloadPage.css
sass ${MODSCSS_DIR}/chartTypes/mapWizard.scss --style compressed > mapWizard.css
sass ${MODSCSS_DIR}/chartTypes/simpleChart.scss --style compressed > simpleChart.css

