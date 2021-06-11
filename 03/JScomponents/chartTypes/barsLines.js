
	$(document).ready(function() {

      $('#BreadcrumbCountry').change(updatePage);
      $('#BreadcrumbCountry').val(urlcountry);
      function updatePage () {
        var value = $(this).val();
        location = '?page='+page+'&cr='+value+'&lg='+lang+'';
      };

  });
