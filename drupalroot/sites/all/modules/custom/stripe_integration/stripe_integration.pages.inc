<?php

/**
 * @file
 * Page callbacks for the Stripe Integration module.
 */

/**
 * @param string $event
 * @todo
 */
function _stripe_integration_webhook_consume($event = 'e') {
  _stripe_integration_stripe_init();

  $input = @file_get_contents("php://input");
  $event = json_decode($input);

  switch ($event->type) {
    case 'invoice.payment_failed' :

      // @todo: Decide how we want to handle this
      break;
  }
}
