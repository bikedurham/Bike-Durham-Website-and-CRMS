<?php

/**
 * @file
 * Contains \Drupal\commerce_product\Plugin\Action\PublishProduct.
 */

namespace Drupal\commerce_product\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Publishes a product.
 *
 * @Action(
 *   id = "publish_product_action",
 *   label = @Translation("Publish selected product"),
 *   type = "commerce_product"
 * )
 */
class PublishProduct extends ActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    $entity->setPublished(TRUE);
    $entity->save();
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var \Drupal\commerce_product\ProductInterface $object */
    $result = $object
      ->access('update', $account, TRUE)
      ->andIf($object->status->access('edit', $account, TRUE));

    return $return_as_object ? $result : $result->isAllowed();
  }

}
