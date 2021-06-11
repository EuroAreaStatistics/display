
////add to mapChartWizard.php
////=============================
//if (isset($config['tabs'][$tabID]['pdf'])) {
//  $pdfWizard = $config['tabs'][$tabID]['pdf'];
//  $ConfigPDF= array(
//      'template'            =>  $pdfWizard,
//      'labels'              =>  array (
//          'headline'                      =>  $lang['page_title'],
//          'subheading'                    =>  $lang['titleCountrySnapshot'].' - ',
//          'headlineRelatedPublication'    =>  $lang['aboutRelatedPublication'],
//  //$relatedPublication link ersetzen
//          'coverImage'                    =>  $relatedPublication['CoverLink'][$project],
//          'title'                         =>  $relatedPublication['publicationName'][$project],
//          'website'                       =>  '<a href="'.$relatedPublication['OECDwebsite'][$project].'">'.$relatedPublication['OECDwebsite'][$project].'</a>',
//          'contact'                       =>  $contacts,
//      ),
//  );
//}
////=============================
//
//
//
////add to mapChartWizard.php
////=============================
//<?php if (isset($pdfWizard)) : ?>
//  <script src='<?= $liveURL ?>/resources/highcharts-release/modules/exporting.js'></script>
//  <script src='<?= $liveURL ?>/resources/jquery-json/build/jquery.json.min.js'></script>
//  <script src='<?= $liveURL ?>/03resources/js/PDFexport.min.js'></script>
//<?php endif ?>
//  <script>
//    var pdfWizard = <?= json_encode($pdfWizard) ?>;
//    var ConfigPDF = <?= json_encode($ConfigPDF) ?>;
//  </script>
////=============================




function generatePDF(template){
    var data = [];
    var charts = Highcharts.charts;
    var i;
    var labels = ConfigPDF['labels'];
    var pdfText = [];
    var language = lang;

    for (i=0; i<charts.length; i++) {
        if (charts[i]!=null) {
            data.push({
                svg: Highcharts.Chart.prototype.getSVG.call(charts[i])
                    .replace(/color:#606060;/g,'color:#000;')
                    .replace(/fill:#606060;/g,'fill:#000;')
                    .replace(/<title>[^<]*<\/title>/g,''),
                container: $(charts[i].container).parent().attr('id'),
            });
        }
    }

    $("body").append("<canvas id='imgCanvas'>");

//    formatTables(template);

    if (template == 'OECDchart5') {
        pdfText.push($('#subTab0').html());
        pdfText.push($('#subTab1').html());
    }

    var json={
        'template'  : $.toJSON(ConfigPDF['template']),
        'data'      : $.toJSON(data),
        'labels'    : $.toJSON(labels),
        'text'      : $.toJSON(pdfText),
//        'sections'  : $.toJSON(pdfSections),
        'language'  : $.toJSON(language),
    };

    console.log(json);
//    uploadPDF(json);
};

        
function formatTables(template) {

};

function uploadPDF(data){
    data = typeof data == 'string' ? data : jQuery.param(data);
    //split params into form inputs
    var inputs = '';
    jQuery.each(data.split('&'), function(){
      var pair = this.split('=');
      inputs+='<input type="hidden" name="'+ pair[0] +'" value="'+ pair[1] +'" />';
    });
    //send request
//    jQuery('<form target="_blanc" action="'+ baseURL +'/pdf" method="post">'+inputs+'</form>')
    jQuery('<form  action="'+ baseURL +'/pdf" method="post">'+inputs+'</form>')
    .appendTo('body').submit().remove();
};

