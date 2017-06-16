<?php

namespace Drupal\custom_fb_instant_articles\Plugin\views\row;

use Drupal\fb_instant_articles_views\Plugin\views\row\FiaFields;
use Drupal\views\Plugin\views\row\EntityRow;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;

/**
 * Renders an RSS item based on fields.
 *
 * @ViewsRow(
 *   id = "custom_fiafields",
 *   title = @Translation("Custom FIA Fields"),
 *   help = @Translation("Display fields as custom FIA (facebook instant articles) items."),
 *   theme = "views_view_row_fia",
 *   display_types = {"feed"}
 * )
 */
class CustomFiaFields extends FiaFields {

  public function render($row) {
    GLOBAL $base_url;

    $entity = $row->_entity;
    $options = $this->options;
    $item = parent::render($row);
    $options['langcode'] = \Drupal::languageManager()->getCurrentLanguage()->getId();

    switch (true) {
      default:
      case ($entity instanceof \Drupal\node\Entity\Node):
        $options['row']       = $row;
        $options['title']     = $entity->getTitle();
        $options['author']    = $entity->getOwner()->getAccountName();
        $options['created']   = '@'.$entity->getCreatedTime();
        $options['modified']  = '@'.$entity->getChangedTime();
        $options['link']      = $entity->toLink(NULL, 'canonical', ['absolute'=>true]);
        $options['guid']      = $entity->uuid();

        if ($entity->hasField('field_byline')) {
          $options['author']  = $entity->get('field_byline')->value;
        }
    }

    $build = [
      '#theme' => $this->themeFunctions(),
      '#view' => $this->view,
      '#options' => $options,
      '#row' => $item,
      '#field_alias' => isset($this->field_alias) ? $this->field_alias : '',
    ];

    return $build;
  }

}