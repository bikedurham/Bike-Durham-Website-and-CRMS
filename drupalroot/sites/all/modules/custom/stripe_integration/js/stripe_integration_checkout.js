(function ($) {

    // Custom Stripe checkout handler for both the user registration and update payment forms.

    var handler = StripeCheckout.configure({
        key: Drupal.settings.stripeIntegration.publishableKey,
        image: Drupal.settings.basePath + Drupal.settings.stripeIntegration.logoURL,
        token: function(token, args) {
            var form = $('#stripe-integration-membership-add-form');
            var input = $("<input name='stripeToken' value='" + token.id + "' style='display:none;' />");
            form.append(input[0]);
            form.find('#edit-submit').attr('disabled','disabled');
            form.submit();
        }
    });

    if (typeof Drupal.settings.stripeIntegration.stripeToken === 'undefined') {
        // variable is undefined

        document.getElementById('edit-submit').addEventListener('click', function(e) {

            // Check for email and membership type selected
            if (!$('#edit-membership-type').val()) {
                alert('Please select membership type.');
            }
            else if (!$('#edit-mail').val()) {
                alert('Please fill out email field.');
            }
            else {


              // Open Checkout with further options
              //var optionValue = jQuery('#edit-membership-type').val();
              //var optionText = jQuery("#edit-membership-type option[value='" + optionValue + "']").text();
              //alert(optionText);

              // On the update payment form, only open Checkout if update existing credit card is checked.
              // Mail is a hidden field on the update payment form.
              handler.open({
                name: 'Bike Durham',
                description: 'Annual Membership',
                //amount: optionText,
                email: jQuery('#edit-mail').val()
              });
            }
              e.preventDefault();

        });
    } else{
        var form = $('#stripe-integration-membership-add-form');
        var input = $("<input name='stripeToken' value='" + Drupal.settings.stripeIntegration.stripeToken + "' style='display:none;' />");
        form.append(input[0]);
    }

}(jQuery));
