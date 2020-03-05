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
   * Return build array.
   *
   * @var array
   */
  private $build = [];

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Set build array cache flag.
    $this->setBuildCache();

    // If node can not be retrieved, return from the function.
    if (empty($node_parameter = \Drupal::routeMatch()->getParameter('node'))) {
      // Set #markup into $return_array.
      $this->build['#markup'] = $this->t('Cannot retrieve current page node');
      return $this->build;
    }

    // If node isn't an event, return from the function.
    if ($node_parameter->getType() != "event") {
      // Set #markup into $return_array.
      $this->build['#markup'] = $this->t('Current node is not an event');
      return $this->build;
    }

    // Get field_event_date value.
    if ($date = $node_parameter->get("field_event_date")->getValue()) {
      $date = $date[0]['value'];
    }

    // Returns false if calculating was not possible.
    $date_difference = \Drupal::service('date_countdown.day_difference')->countDayDifference($date);

    // Set #markup into $return_array.
    $this->setArrayDateDifference($date_difference);

    return $this->build;
  }

  /**
   * Takes a reference to $return_array and adds #markup to it.
   *
   * @param int $date_difference
   *   Day difference between current and searchable date.
   */
  private function setArrayDateDifference($date_difference) {

    // Checks if date calculation was successful.
    // @todo hide the block instead of showing error text?
    if (!$date_difference['is_valid']) {
      $this->build['#markup'] = $this->t('Error in calculating time difference');
      return;
    }

    // Is it today?
    if ($date_difference['is_today']) {
      $this->build['#markup'] = $this->t('This event is happening today');
      return;
    }

    // Markup based on day difference.
    switch ($date_difference['days']) {

      case 0:
      case 1:
        $this->build['#markup'] = $this->t('1 day left until event starts');
        break;

      case ($date_difference['days'] > 1):
        $this->build['#markup'] = $this->t('@daydiff days left until event starts', ['@daydiff' => $date_difference['days']]);
        break;

      default:
        $this->build['#markup'] = $this->t('This event already passed.');
    }
  }

  /**
   * Adds build array no cache flag.
   */
  public function setBuildCache() {
    // Cache prohibiter.
    $this->build['#cache'] = [
      'max-age' => 0,
    ];
  }

  /**
   * Cache prohibiter.
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
