<?php
namespace Drupal\date_countdown\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\node\Entity\Node;

/**
 * Provides a 'Date Countdown' Block
 *
 * @Block(
 * id = "date_countdown_block",
 * admin_label = @Translation("Date Countdown Block"),
 * )
 */
class DateCountdownBlock extends BlockBase implements BlockPluginInterface {

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
    $nodeParameter = \Drupal::routeMatch()->getParameter('node');
    
    // if node isn't an event, exit
    if($nodeParameter->getType() != "event") {

      // set #markup into $return_array
      $return_array['#markup'] = $this->t('Error in calculating time difference');

      // exit from the method
      return $return_array;
    }

    // get field_event_date value 
    $date = $nodeParameter->get("field_event_date")->getValue();
    $date = $date[0]['value'];

    // returns false if calculating was not possible
    $datedifference = \Drupal::service('date_countdown.day_difference')->countDayDifference($date);

    // set #markup into $return_array
    $this->setArrayDateDifference($datedifference, $return_array);

    return $return_array;
  }

  /**
   * Takes a reference to $return_array and adds #markup to it
   *
   * @param int $datedifference
   * @param array $return_array
   */
  private function setArrayDateDifference($datedifference, &$return_array) {

    // @todo misinformative information passed when event time is less than 24 hours away but still next day.
    // Example: in either case, if current time is 10:00am and event is 09:00am the next day, you get "This event is happening today".

    // checks if date calculation was successful
    gettype($datedifference);

    // @todo perhaps hide the block instead of error text
    if(is_bool($datedifference)) {
      $return_array['#markup'] = $this->t('Error in calculating time difference');
    }
    elseif($datedifference > 2) {
      $return_array['#markup'] = $this->t('@daydiff days left until event starts', array('@daydiff' => $datedifference));
    }
    elseif($datedifference == 1) {
      $return_array['#markup'] = $this->t('1 day left until event starts');
    }
    elseif($datedifference == 0) {
       $return_array['#markup'] = $this->t('This event is happening today');
    }
    else {
      $return_array['#markup'] = $this->t('This event already passed.');
    }
  }

}