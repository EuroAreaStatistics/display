
function updateFlowData (indicator) {
  $.ajax({
    dataType: "json",
    url: baseURL+"/api-data?project="+project+"&id="+indicator,
    success: ajaxSuccess,
    error: function (xhr, ajaxOptions, thrownError) {
      if (xhr.status == '404') {
        alert ('No data available.')};
    }
  });
  
  function ajaxSuccess(data) {
    if (data != null) {
      window.DataforFlow=data;
    } else {
      alert('No data available.');
    }
  }

}

