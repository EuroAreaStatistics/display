<?php

// add manually for each server
require_once ('mainConfig.php');

$domain = $_SERVER["SERVER_NAME"];

//set resources path from $liveURL
$staticURL = $liveURL.'/02resources';
$vendorsURL = $liveURL.'/resources';

//set special API URLs

//redirect for pdf and data download
$urls['/pdf.php'] = '/pdf/pdf.php';
$urls['/data'] = '/dataDownload/data_cyc.php';
$urls['/data_'.$themeURL] = '/dataDownload/data_cyc_'.$themeURL.'.php';

// landing page
$urls['/'] = '/staticPages/'.$themeURL.'/landingPage.php';

// robots.txt
$urls['/robots.txt'] = '/staticPages/robots.php';


// embed page
$urls['/embed']             = '/embed/embed.php';
$urls['/snapshotspreview']  = '/embed/snapshotspreview.php';
$urls['/embed-single']      = '/embed/single.php';
//    $urls['/simpleurl']  = '/embed/simpleurl.php';

// map and country names resources
$urls['/countryNames.js']     = '/countryNames/root/index.php';
$urls['/maps.js']             = '/maps/root/index.php';
$urls['/flags.png']           = '/flags/root/index.php';

// api content
$urls['/api-project']           = '/apiClientNotesData/root/project.php';
$urls['/api-labels']            = '/apiClientNotesData/root/labels.php';
$urls['/api-notes']             = '/apiClientNotesData/root/notes.php';
$urls['/api-data']              = '/apiClientNotesData/root/data.php';
$urls['/api-embed']             = '/apiClientNotesData/root/embed.php';

if (!function_exists('dataURL')) {
    /**
     * Builds URL for a project data file
     *
     * @param string $name Name of data file, relative to data folder
     * @param string $project Optional name of project, defaults to $GLOBALS['project']
     * @return string
    */
    function dataURL($name, $project = NULL, $path = NULL) {
        if ($project === NULL) {
             if (!isset($GLOBALS['project'])) throw new Exception('$project not set');
             $project = $GLOBALS['project'];
        }

        if ($path === 'json') {
            return sprintf('%s/projects/%s/dataJSON/%s/%s', $GLOBALS['baseURL'], $GLOBALS['themeURL'], $project, $name);
        } else {
            return sprintf('%s/projects/%s/projectConfig/%s/data/%s', $GLOBALS['baseURL'], $GLOBALS['themeURL'], $project, $name);
        }
    }
}

if (!function_exists('mainFile')) {
    /**
     * Builds absolute file name for a project data file
     *
     * @param string $name Name of data file, relative to data folder
     * @param string $project Optional name of project, defaults to $GLOBALS['project']
     * @return string
    */

    function mainFile($name, $type) {

        $path = array (
            'config'            => 'config',
        );          

        return sprintf('%s/%s/%s', __DIR__, $path[$type], $name);

    }
    
}


