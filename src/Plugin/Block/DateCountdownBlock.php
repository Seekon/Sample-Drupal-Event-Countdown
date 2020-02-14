<?php

namespace Drupal\date_countdown\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Date Countdown' Block.
 *
 * @Block(
 * id = "date_countdown_block",
 * admin_label = @Translation("Date Countdown Block"),
 * )
 */
class DateCountdownBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Build method return array.
    $return_array = [];

    // Cache prohibiter.
    $return_array['#cache'] = [
      'max-age' => 0,
    ];

    // If node can not be retrieved, return from the function.
    if (empty($node_parameter = \Drupal::routeMatch()->getParameter('node'))) {
      // Set #markup into $return_array.
      $return_array['#markup'] = $this->t('Cannot retrieve current page node');
      return $return_array;
    }

    // If node isn't an event, return from the function.
    if ($node_parameter->getType() != "event4") {
      // Set #markup into $return_array.
      $return_array['#markup'] = $this->t('Current node is not an event');
      return $return_array;
    }

    // Get field_event_date value.
    if ($date = $node_parameter->get("field_event_date")->getValue()) {
      $date = $date[0]['value'];
    }

    // Returns false if calculating was not possible.
    $date_difference = \Drupal::service('date_countdown.day_difference')->countDayDifference($date);

    // Set #markup into $return_array.
    $this->setArrayDateDifference($date_difference, $return_array);

    return $return_array;
  }

  /**
   * Takes a reference to $return_array and adds #markup to it.
   *
   * @param int $date_difference
   *   Day difference between current and searchable date.
   * @param array &$return_array
   *   Array that receives result.
   */
  private function setArrayDateDifference($date_difference, array &$return_array) {

    // Checks if date calculation was successful.
    // @todo hide the block instead of showing error text?
    if (!$date_difference['is_valid']) {
      $return_array['#markup'] = $this->t('Error in calculating time difference');
    }
    elseif ($date_difference['is_today']) {
      $return_array['#markup'] = $this->t('This event is happening today');
    }
    elseif ($date_difference['days'] > 1) {
      $return_array['#markup'] = $this->t('@daydiff days left until event starts', ['@daydiff' => $date_difference['days']]);
    }
    elseif ($date_difference['days'] == 1) {
      $return_array['#markup'] = $this->t('1 day left until event starts');
    }
    else {
      $return_array['#markup'] = $this->t('This event already passed.');
    }
  }

  /**
   * Another cache prohibiter.
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
