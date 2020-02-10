<?php
namespace Drupal\date_countdown\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Date Countdown' Block
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

    // build method return array
    $return_array = array();

    // no block cache
    $return_array['#cache'] = [
      'max-age' => 0,
    ];

    // get node
    $node_parameter = \Drupal::routeMatch()->getParameter('node');

    // if node isn't an event, exit
    if($node_parameter->getType() != "event") {

      // set #markup into $return_array
      $return_array['#markup'] = $this->t('Error in calculating time difference');

      // exit from the method
      return $return_array;
    }

    // get field_event_date value
    $date = $node_parameter->get("field_event_date")->getValue();
    $date = $date[0]['value'];

    // returns false if calculating was not possible
    $date_difference = \Drupal::service('date_countdown.day_difference')->countDayDifference($date);

    // set #markup into $return_array
    $this->setArrayDateDifference($date_difference, $return_array);

    return $return_array;
  }

  /**
   * Takes a reference to $return_array and adds #markup to it based on $date_difference
   *
   * @param int $date_difference
   * @param array $return_array
   */
  private function setArrayDateDifference($date_difference, &$return_array) {

    // checks if date calculation was successful
    // @todo perhaps hide the block instead of error text
    if(!$date_difference['is_valid']) {
      $return_array['#markup'] = $this->t('Error in calculating time difference');
    }
    elseif($date_difference['is_today']) {
      $return_array['#markup'] = $this->t('This event is happening today');
    }
    elseif($date_difference['days'] > 1) {
      $return_array['#markup'] = $this->t('@daydiff days left until event starts', array('@daydiff' => $date_difference['days']));
    }
    elseif($date_difference['days'] == 1) {
      $return_array['#markup'] = $this->t('1 day left until event starts');
    }
    else {
      $return_array['#markup'] = $this->t('This event already passed.');
    }
  }

  // Another no-cache choice
  public function getCacheMaxAge() {
    return 0;
  }

}