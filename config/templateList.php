<?php

// template list for chartWizard and WizardTemplates.php

$templateList = array(
                      
    1 => array (
        'name'     =>  'Trends',
        'displayName'   =>  'Trends',
        'dev'     =>  'A collection of line charts to display data trends across different indicators. Depending on the screen size, two to four charts are displayed. Additional indicators can be added from drop-down menus above each chart. Up to four series can be added to and removed from all charts simultaneously from the button panel.',
        'image'   =>  'LinesButtons.png',
        'options' =>  array (
                    'chartType' => 'FourLines',
                    'chartDisplay' => 'lines',        
                    'withCountries' => TRUE,
                    'maxCharts' => 4,
                    'maxSeries' => 4,
                           ),
    ),
    2 => array (
        'name'  =>  'Rankings',
        'displayName'   =>  'Rankings',
        'dev'   =>  'Depreciated',
        'image' =>  'ColumnsButtons.png',
        'options' =>  array (
                    'chartType' => 'FourLines',
                    'chartDisplay' => 'columns',        
                    'maxCharts' => 4,
                           ),
    ),
    3 => array (
        'name'  =>  'Rankings & trends',
        'displayName'   =>  'Rankings & trends',
        'dev'   =>  'A collection of column and line charts. The column charts display the latest available value. Users can highlight simultaneously up to two countries on both charts.',
        'image' =>  'ColumnsLines.png',
        'options' =>  array (
                    'chartType' => 'BarsLines',        
                           ),
    ),
    4 => array (
        'name'  =>  'Two points in time',
        'displayName'   =>  'Rankings',
        'dev'   =>  'Compares two points in time for the first indicator on your dataset. The user can select the starting and the end point. If no selection is made, the first year and the latest available year for each country are displayed.',
        'image' =>  'BarsDiamondsTable.png',
        'options' =>  array (
                    'chartType' => 'barsTable',
                    'chartDisplay' => 'BarsDiamondsTime',
                           ),
    ),
    5 => array (
        'name'  =>  'Rankings',
        'displayName'   =>  'Rankings',
        'dev'   =>  'Compares the latest available year for two indicators. The first indicator in your dataset is displayed as bars, the second indicator as circles. The country list can be sorted on both indicators.',
        'image' =>  'BarsDiamondsTable.png',
        'options' =>  array (
                    'chartType' => 'barsTable',
                    'chartDisplay' => 'BarsDiamondsIndicators',
                           ),
    ),
    6 => array (
        'name'  =>  'Rankings',
        'displayName'   =>  'Rankings',
        'dev'   =>  'Compares the latest available year for up to five indicators. All indicators in your dataset are displayed as bars. The country list can be sorted on all indicators.',
        'image' =>  'BarsBarsTable.png',
        'options' =>  array (
                    'chartType' => 'barsTable',
                    'chartDisplay' => 'BarsBarsIndicators',
                    'maxCharts' => 4,
                           ),
    ),
    7 => array (
        'name'  =>  'Color-coded map',
        'displayName'   =>  'Map',
        'dev'   =>  'A basic map with color-coded countries for one indicator. The first indicator is displayed on page-load. Users can switch between the indicators from a selector. The values for all indicators are shown in a tooltip on click.',
        'image' =>  'Map.png',
        'options' =>  array (
                    'chartType' => 'mapWizard',
                    'bubbleMap' => null,
                           ),
    ),
    8 => array (
        'name'  =>  'Bubble map',
        'displayName'   =>  'Map',
        'dev'   =>  'A basic bubble map displaying one indicators. A good solution if you would like to visualize an absolute indicator (GDP total volume, number of people, total volume of imports/exports). The first indicator is displayed on page-load. Users can switch between the indicators from a selector. The values for all indicators are shown in tooltips on click.',
        'image' =>  'BubbleMapSimple.png',
        'options' =>  array (
                    'chartType' => 'mapWizard',
                    'bubbleMap' => 'simple',
                           ),
    ),
    9 => array (
        'name'  =>  'Color-coded bubble map (data model 1)',
        'displayName'   =>  'Map',
        'dev'   =>  'A bubble map displaying two indicators at a time. A good solution if you would like to visualize a relative indicator (GDP per capita, GDP growth) and a corresponding absolute indicator (GDP total volume). The first indicator collection will display as bubble size, the second as bubble color. All subsequent indicators are plotted as bubble color. When you switch between indicators the bubble size remains unchanged. Values for all indicators are displayed on tooltips.',
        'image' =>  'BubbleColorMap.png',
        'options' =>  array (
                    'chartType' => 'mapWizard',
                    'bubbleMap' => 'colored',
                           ),
    ),
    10 => array (
        'name'  =>  'Data download',
        'displayName'   =>  'Data download',
        'dev'   =>  'A single page with download links for all charts in all tabs.',
        'image' =>  'DownloadPage.png',
        'options' =>  array (
                    'chartType' => 'dataDownload',
                          ),
    ),
    11 => array (
        'name'  =>  'Color-coded bubble map (data model 2)',
        'displayName'   =>  'Map',
        'dev'   =>  'A bubble map displaying two indicators at a time. A good solution if you would like to visualize a relative indicator (GDP per capita, GDP growth) and a corresponding absolute indicator (GDP total volume). The first indicator collection will display as bubble size, the second as bubble color. The third indicator will display as bubble size, the forth indicator as bubble color. When you switch between indicators, you will change bubble size and bubble color at the same time. Values for all indicators are displayed on tooltips.',
        'image' =>  'BubbleColorMap.png',
        'options' =>  array (
                    'chartType' => 'mapWizard',
                    'bubbleMap' => 'coloredPairUnpair',
                           ),
    ),
    'stackedarea' => array (
        'name'    =>  'Stacked Area Chart',
        'displayName'   =>  'Stacked Area Chart',
        'dev'     =>  'Stacked area chart.',
        'image'   =>  'StackedArea.png',
        'options' =>  array (
                    'chartType' => 'stackedAreaChart',
//                    'altHeader' => 1,
                           ),
    ),
    'sbar' => array (
        'name'    =>  'Sorted Bars',
        'displayName'   =>  'Bar chart',
        'dev'     =>  'Simple bar charts to display data for a single indicators. Countries are sorted in descending order. The latest available year used to render chart.',
        'image'   =>  'SimpleBar.png',
        'options' =>  array (
                    'chartType' => 'simpleChart',
                    'altHeader' => 1,
                           ),
    ),
    'sbarflags' => array (
        'name'    =>  'Sorted Bars with flags',
        'displayName'   =>  'Bar chart',
        'dev'     =>  'Simple bar charts to display data for a single indicators. Countries are sorted in descending order. The latest available year used to render chart.',
        'image'   =>  'SimpleBar.png',
        'options' =>  array (
                    'chartType' => 'simpleChart',
                    'altHeader' => 1,
                           ),
    ),
    'sbardiamond' => array (
        'name'    =>  'Sorted Bars with Diamonds',
        'displayName'   =>  'Bar chart',
        'dev'     =>  'A bar chart with diamonds for additional series. Countries are displayed in decending order.',
        'image'   =>  'SimpleBarsDiamonds.png',
        'options' =>  array (
                    'chartType' => 'simpleChart',
                    'altHeader' => 1,
                           ),
    ),
    'sstackedcolumn' => array (
        'name'    =>  'Stacked Columns',
        'displayName'   =>  'Column chart',
        'dev'     =>  'A stacked column chart to show composite indicators for different countries.',
        'image'   =>  'SimpleBarsDiamonds.png',
        'options' =>  array (
                    'chartType' => 'simpleChart',
                    'altHeader' => 1,
                           ),
    ),
    'sstackedbar' => array (
        'name'    =>  'Stacked Bars',
        'displayName'   =>  'Bar chart',
        'dev'     =>  'A stacked bar chart to show composite indicators for different countries. Countries are sorted in a dscending order on the sum of the bars.',
        'image'   =>  'SimpleBarsDiamonds.png',
        'options' =>  array (
                    'chartType' => 'simpleChart',
                    'altHeader' => 1,
                           ),
    ),
    'slines' => array (
        'name'    =>  'Simple Lines',
        'displayName'   =>  'Trend',
        'dev'     =>  'A simple line chart to display trends for different countries.',
        'image'   =>  'SimpleLine.png',
        'options' =>  array (
                    'chartType' => 'simpleChart',
                    'altHeader' => 1,
                           ),
    ),
    'spie' => array (
        'name'    =>  'Pie Chart',
        'displayName'   =>  'Distribution',
        'dev'     =>  'A simple pie chart',
        'image'   =>  'SimpleLine.png',
        'options' =>  array (
                    'chartType' => 'simpleChart',
                    'altHeader' => 1,
                           ),
    ),
    'scolumn' => array (
        'name'    =>  'Column Chart',
        'displayName'   =>  'Ranking',
        'dev'     =>  'A simple column chart',
        'image'   =>  'SimpleLine.png',
        'options' =>  array (
                    'chartType' => 'simpleChart',
                    'altHeader' => 1,
                           ),
    ),
    'sbarstable' => array (
        'name'    =>  'Bars with flags',
        'displayName'   =>  'Ranking',
        'dev'     =>  'A bar chart with diamonds for additional series.',
        'image'   =>  'SimpleBarsTable.png',
        'options' =>  array (
                    'chartType' => 'barsTable',
                    'chartDisplay' => 'BarsBarsIndicatorsSimple',
                    'altHeader' => true,
                           ),
    ),
    'scolormap' => array (
        'name'    =>  'Simple map',
        'displayName'   =>  'Map',
        'dev'     =>  'A simple color coded map.',
        'image'   =>  'SimpleColorMap.png',
        'options' =>  array (
                    'chartType' => 'mapWizard',
                    'bubbleMap' => null,
                    'mapLegendFull' => true,
                    'altHeader' => true,
                           ),
    ),
    'chartPackage' => array (
        'name'    =>  'Complex Chart Package',
        'displayName'   =>  'Map',
        'dev'     =>  'A simple color coded map with text layers for individual countries.',
        'image'   =>  'Map.png',
        'options' =>  array (
                    'chartType' => 'mapWizardStandard',
                    'bubbleMap' => null,
                           ),
    ),
    'chartPackageBubble' => array (
        'name'    =>  'Complex Chart Package',
        'displayName'   =>  'Map',
        'dev'     =>  'A simple bubble map with text layers for individual countries.',
        'image'   =>  'BubbleMapSimple.png',
        'options' =>  array (
                    'chartType' => 'mapWizardStandard',
                    'bubbleMap' => 'simple',
                           ),
    ),
    'chartPackageColoredBubble' => array (
        'name'    =>  'Complex Chart Package',
        'displayName'   =>  'Map',
        'dev'     =>  'A colored bubble map with text layers for individual countries. The first chart sets the bubble size, the second the bubble color.',
        'image'   =>  'BubbleColorMap.png',
        'options' =>  array (
                    'chartType' => 'mapWizardStandard',
                    'bubbleMap' => 'colored',
                           ),
    ),
    'multipleColoredBubbles' => array (
        'name'    =>  'Multiple Colored Bubbles',
        'displayName'   =>  'Map',
        'dev'     =>  'A colored bubble map. The first chart sets the bubble size, the second the bubble color. The second dimension (YEARS) is represented as a separate indicator.',
        'image'   =>  'BubbleColorMap.png',
        'options' =>  array (
                    'chartType' => 'mapWizard',
                    'bubbleMap' => 'multiColored',
                           ),
    ),
    'D3histogramm' => array (
        'name'    =>  'D3 Histogram',
        'displayName'   =>  'Histogram',
        'dev'     =>  'A simple color coded map.',
        'image'   =>  'SimpleColorMap.png',
        'options' =>  array (
                    'chartType' => 'd3Chart',
                    'simplePlotting' => 'histogramm.js',
                    'altHeader' => true,
                    'chartEngine' => 'D3js'
                           ),
    ),
    'ged-viz-flows' => array (
        'name'    =>  'Flow diagram',
        'displayName'   =>  'Flow diagram',
        'dev'     =>  'A diagram to display flows',
        'image'   =>  'SimpleColorMap.png',
        'options' =>  array (
                    'chartType' => 'flowDiagram',
                           ),
    ),
    'ged-viz-flows2' => array (
        'name'    =>  'Flow diagrams (adjacent)',
        'displayName'   =>  'Flow diagrams (adjacent)',
        'dev'     =>  'A diagram to display flows',
        'image'   =>  'SimpleColorMap.png',
        'options' =>  array (
                    'chartType' => 'flowDiagram2',
                           ),
    ),
    'regionalTable' => array (
        'name'    =>  'Regional table',
        'displayName'   =>  'Regional disparities',
        'dev'     =>  'A table to show regional differences within countries',
        'image'   =>  'SimpleColorMap.png',
        'options' =>  array (
                    'chartType' => 'regionalTable',
                           ),
    ),
    'mapNUTS0' => array (
        'name'  =>  'Color-coded map',
        'displayName'   =>  'Map (Countries)',
        'dev'   =>  'A basic map with color-coded countries for one indicator. The first indicator is displayed on page-load. Users can switch between the indicators from a selector. The values for all indicators are shown in a tooltip on click.',
        'image' =>  'Map.png',
        'options' =>  array (
                    'chartType' => 'mapWizard',
                    'bubbleMap' => null,
                           ),
    ),
    'mapNUTS1' => array (
        'name'  =>  'Color-coded map',
        'displayName'   =>  'Map (Level 1)',
        'dev'   =>  'A basic map with color-coded countries for one indicator. The first indicator is displayed on page-load. Users can switch between the indicators from a selector. The values for all indicators are shown in a tooltip on click.',
        'image' =>  'Map.png',
        'options' =>  array (
                    'chartType' => 'mapWizard',
                    'bubbleMap' => null,
                           ),
    ),
    'mapNUTS2' => array (
        'name'  =>  'Color-coded map',
        'displayName'   =>  'Map (Level 2)',
        'dev'   =>  'A basic map with color-coded countries for one indicator. The first indicator is displayed on page-load. Users can switch between the indicators from a selector. The values for all indicators are shown in a tooltip on click.',
        'image' =>  'Map.png',
        'options' =>  array (
                    'chartType' => 'mapWizard',
                    'bubbleMap' => null,
                           ),
    ),
    'mapNUTS3' => array (
        'name'  =>  'Color-coded map',
        'displayName'   =>  'Map (Level 3)',
        'dev'   =>  'A basic map with color-coded countries for one indicator. The first indicator is displayed on page-load. Users can switch between the indicators from a selector. The values for all indicators are shown in a tooltip on click.',
        'image' =>  'Map.png',
        'options' =>  array (
                    'chartType' => 'mapWizard',
                    'bubbleMap' => null,
                           ),
    ),
);
