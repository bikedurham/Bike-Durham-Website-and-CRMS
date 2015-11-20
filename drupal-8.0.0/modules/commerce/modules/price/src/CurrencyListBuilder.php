<?php

/**
 * @file
 * Contains \Drupal\commerce_price\CurrencyListBuilder.
 */

namespace Drupal\commerce_price;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Defines the list builder for currencies.
 */
class CurrencyListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header = [
      'name' => $this->t('Name'),
      'currencyCode' => $this->t('Currency code'),
      'status' => $this->t('Status'),
    ];

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row = [
      'name' => $this->getLabel($entity),
      'currencyCode' => $entity->id(),
      'status' => $entity->status() ? $this->t('Enabled') : $this->t('Disabled'),
    ];

    return $row + parent::buildRow($entity);
  }

}
