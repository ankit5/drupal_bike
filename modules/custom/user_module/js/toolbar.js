/*(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.toolbarAlwaysVerticalSetToolbarDefaults = {
    attach: function (context, settings) {
      if (Drupal.toolbar.views.toolbarVisualView) {
        // this forces the vertical orientation
        localStorage.setItem('Drupal.toolbar.trayVerticalLocked', 'true');
        Drupal.toolbar.views.toolbarVisualView.model.set({locked: true, orientation: 'vertical'}, {validate: true, override: true});
        if (drupalSettings.path.currentPathIsAdmin && Drupal.toolbar.views.toolbarVisualView.model.attributes.isFixed) {
          // show administration toolbar when in admin and not collapsed
          Drupal.toolbar.views.toolbarVisualView.model.set({activeTab: $('#toolbar-item-administration')});
        }
        else {
          // hide toolbar when on frontend page
          Drupal.toolbar.views.toolbarVisualView.model.set({activeTab: null});
        }
      }
    }
  };
})(jQuery, Drupal, drupalSettings);*/