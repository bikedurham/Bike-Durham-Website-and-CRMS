<?php

/**
 * @file
 * Contains \Drupal\commerce_order\Entity\LineItem.
 */

namespace Drupal\commerce_order\Entity;

use Drupal\commerce\PurchasableEntityInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the line item entity class.
 *
 * @ContentEntityType(
 *   id = "commerce_line_item",
 *   label = @Translation("Line Item"),
 *   handlers = {
 *     "event" = "Drupal\commerce_order\Event\LineItemEvent",
 *     "storage" = "Drupal\commerce\CommerceContentEntityStorage",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "default" = "Drupal\Core\Entity\ContentEntityForm",
 *     }
 *   },
 *   base_table = "commerce_line_item",
 *   admin_permission = "administer orders",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id" = "line_item_id",
 *     "uuid" = "uuid",
 *     "bundle" = "type",
 *     "label" = "title",
 *   },
 *   links = {
 *     "canonical" = "/admin/commerce/config/line-item/{commerce_line_item}",
 *     "edit-form" = "/admin/commerce/config/line-item/{commerce_line_item}/edit",
 *     "delete-form" = "/admin/commerce/config/line-item/{commerce_line_item}/delete",
 *     "collection" = "/admin/commerce/config/line-item"
 *   },
 *   bundle_entity_type = "commerce_line_item_type",
 *   field_ui_base_route = "entity.commerce_line_item_type.edit_form",
 * )
 */
class LineItem extends ContentEntityBase implements LineItemInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function createFromPurchasableEntity(PurchasableEntityInterface $entity) {
    $values = [
      'type' => $entity->getLineItemType(),
      'title' => $entity->getLineItemTitle(),
      'purchased_entity' => $entity,
    ];
    return self::create($values);
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return $this->bundle();
  }

  /**
   * {@inheritdoc}
   */
  public function getOrder() {
    return $this->get('order_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOrderId() {
    return $this->get('order_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getPurchasedEntity() {
    return $this->get('purchased_entity')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getPurchasedEntityId() {
    return $this->get('purchased_entity')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getData() {
    return $this->get('data')->first()->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setData($data) {
    $this->set('data', [$data]);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entityType) {
    $fields['line_item_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Line Item ID'))
      ->setDescription(t('The line item ID.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The line item UUID.'))
      ->setReadOnly(TRUE);

    $fields['type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Type'))
      ->setDescription(t('The line item type.'))
      ->setSetting('target_type', 'commerce_line_item_type')
      ->setReadOnly(TRUE);

    // The order backreference, populated by Order::postSave().
    $fields['order_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Order'))
      ->setDescription(t('The parent order.'))
      ->setSetting('target_type', 'commerce_order')
      ->setReadOnly(TRUE);

    $fields['purchased_entity'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Purchased entity'))
      ->setDescription(t('The purchased entity.'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => -1,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The line item title.'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 512,
      ]);

    $fields['quantity'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity'))
      ->setDescription(t('The number of purchased units.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'string',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['unit_price'] = BaseFieldDefinition::create('price')
      ->setLabel(t('Unit price'))
      ->setDescription(t('The price of a single unit.'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'price_simple',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['line_total'] = BaseFieldDefinition::create('price')
      ->setLabel(t('Total price'))
      ->setDescription(t('The total price of the line item.'))
      ->setReadOnly(TRUE)
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['data'] = BaseFieldDefinition::create('map')
      ->setLabel(t('Data'))
      ->setDescription(t('A serialized array of additional data.'));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time when the line item was created.'))
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'timestamp',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time when the line item was last edited.'))
      ->setRequired(TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public static function bundleFieldDefinitions(EntityTypeInterface $entityType, $bundle, array $baseFieldDefinitions) {
    $lineItemType = LineItemType::load($bundle);
    $purchasableEntityType = $lineItemType->getPurchasableEntityType();
    $fields = [];
    $fields['purchased_entity'] = clone $baseFieldDefinitions['purchased_entity'];
    if ($purchasableEntityType) {
      $fields['purchased_entity']->setSetting('target_type', $purchasableEntityType);
    }
    else {
      // This line item type won't reference a purchasable entity. The field
      // can't be removed here, or converted to a configurable one, so it's
      // hidden instead. See https://www.drupal.org/node/2346347#comment-10254087.
      $fields['purchased_entity']->setRequired(FALSE);
      $fields['purchased_entity']->setDisplayOptions('form', [
        'type' => 'hidden',
      ]);
      $fields['purchased_entity']->setDisplayConfigurable('form', FALSE);
      $fields['purchased_entity']->setDisplayConfigurable('view', FALSE);
      $fields['purchased_entity']->setReadOnly(TRUE);

      // Make the title field visible and required.
      $fields['title'] = clone $baseFieldDefinitions['title'];
      $fields['title']->setRequired(TRUE);
      $fields['title']->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -1,
      ]);
      $fields['title']->setDisplayConfigurable('form', TRUE);
      $fields['title']->setDisplayConfigurable('view', TRUE);
    }

    return $fields;
  }

}
