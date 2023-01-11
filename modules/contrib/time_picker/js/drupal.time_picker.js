/**
 * @file
 */

(function ($, Drupal, drupalSettings) {
  'use strict';

  Drupal.behaviors.materialize_datetime_picker = {
    attach: function (context, settings) {

      // Setting the current language for the calendar.
      //var language = drupalSettings.path.currentLanguage;
      /*var time_range_hour_format = drupalSettings.time_range_picker.hour_format;
      var time_range_theme_color = drupalSettings.time_range_picker.theme_color; */
       var time_range_hour_format = '';
      var time_range_theme_color = '';
      if (drupalSettings.time_picker) {
        time_hour_format = drupalSettings.time_picker.hour_format;
        time_theme_color = drupalSettings.time_picker.theme_color; 
      }       
      $('.time_range_fieldset').addClass(time_range_theme_color);
      if(time_range_hour_format === '24h') {
        var time_range_format = false;
      }
      else {
        var time_range_format = true;
      }      
      $('.time_range_picker').timepicker({
        twelveHour: time_range_format
      });
      
      // Time picker only.
      /*var time_hour_format = drupalSettings.time_picker.hour_format;
      var time_theme_color = drupalSettings.time_picker.theme_color; */
       var time_hour_format = '';
      var time_theme_color = '';
      if (drupalSettings.time_picker) {
        time_hour_format = drupalSettings.time_picker.hour_format;
        time_theme_color = drupalSettings.time_picker.theme_color; 
      }     
      $('.time_fieldset').addClass(time_theme_color);
      if(time_hour_format === '24h') {
        var time_format = false;
      }
      else {
        var time_format = true;
      }      
      $('.timepicker').timepicker({
        twelveHour: time_format
      });
    }
  };

})(jQuery, Drupal, drupalSettings);
