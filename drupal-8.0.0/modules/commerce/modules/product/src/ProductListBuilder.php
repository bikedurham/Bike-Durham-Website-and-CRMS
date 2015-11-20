<?php

/**
 * @file
 * Contains \Drupal\commerce_product\ProductListBuilder.
 */

namespace Drupal\commerce_product;

use Drupal\commerce_product\Entity\ProductType;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Defines the list builder for products.
 */
class ProductListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['title'] = t('Title');
    $header['type'] = t('Type');
    $header['type'] = t('Status');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\commerce_product\Entity\Product */
    $productType = ProductType::load($entity->bundle());

    $row['title']['data'] = [
      '#type' => 'link',
      '#title' => $entity->label(),
    ] + $entity->urlInfo()->toRenderArray();
    $row['type'] = $productType->label();
    $row['status'] = $entity->isPublished() ? $this->t('Published') : $this->t('Unpublished');

    return $row + parent::buildRow($entity);
  }

}
