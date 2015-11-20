<?php

/**
 * Contains \Drupal\inline_entity_form\InlineEntityForm\NodeInlineEntityFormHandler.
 */

namespace Drupal\inline_entity_form\InlineEntityForm;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Node inline form handler.
 */
class NodeInlineEntityFormHandler extends EntityInlineEntityFormHandler {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function labels() {
    $labels = [
      'singular' => $this->t('node'),
      'plural' => $this->t('nodes'),
    ];
    return $labels;
  }

  /**
   * {@inheritdoc}
   */
  public function tableFields($bundles) {
    $fields = parent::tableFields($bundles);

    $fields['status'] = [
      'type' => 'field',
      'label' => $this->t('Status'),
      'weight' => 100,
      'display_options' => array(
        'settings' => array(
          'format' => 'custom',
          'format_custom_false' => $this->t('Unpublished'),
          'format_custom_true' => $this->t('Published'),
        ),
      ),
    ];

    return $fields;
  }

}
