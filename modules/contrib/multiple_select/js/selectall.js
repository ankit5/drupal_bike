/**
 * @file
 * Select all feature.
 */

 (function ($) {

  'use strict';

   var fields = $('[id^="multiple_select"]');
  if (fields !== null && fields.length > 0){
    for (var i = 0; i < fields.length; i++) {
      var field = $('#' + fields[i].id)[0];
      field.onclick = checkUncheckAll;
    }
  }

   function checkUncheckAll(theElement, selectorId) {
    var theFormId = theElement.target.id;
    var selectorId = theFormId.replace('multiple_select', 'edit').replace(/\_/g, '-');
    $('input[type="checkbox"][data-drupal-selector^="' + selectorId + '-"]').each(function () {
      this.checked = $('#' + theFormId).is(':checked');
    });
  }

}(jQuery));
