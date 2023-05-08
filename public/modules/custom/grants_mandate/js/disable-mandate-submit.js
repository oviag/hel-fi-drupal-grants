(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.GrantsHandlerMandateSubmitBehavior = {
    attach: function (context, settings) {
      $('#edit-submit--2').prop('disabled', true);

      $('select').on('change', function() {
        if ($(this).find('option:selected').text() == 'Valitse') {
          $('#edit-submit--2').prop('disabled', true);
        }
        else {
          $('#edit-submit--2').prop('disabled', false);
        }
     });
    }
  };
})(jQuery, Drupal, drupalSettings);
