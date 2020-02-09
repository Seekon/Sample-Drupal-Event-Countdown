<?php
namespace Drupal\date_countdown\Service;

/**
 * Simple example of a Service
 */
class DateCountdownService {

  /**
   * DateCountdown Service method that receives DateTime parameter and returns an array of options based on the current time
   *
   * @param DateTime $date
   * @return array
   */
  public function countDayDifference($date) {
    // convert date to time
    $anchor_time = strtotime($date);
    // if conversion failed return from a function
    if($anchor_time == FALSE) {
      $is_valid = FALSE;
    }
    else {
      $is_valid = TRUE;
    }
    // get current time
    $current_time = time();
    // get difference time in ms
    $remaining_time = $anchor_time - $current_time;
    // get difference in days 
    $days_remaining = floor($remaining_time / 86400);

    // check if today
    if(date('dd', $anchor_time) == date('dd', $current_time)) {
      $is_today = TRUE;
    }
    else {
      $is_today = FALSE;
    }

    return array('is_valid' => $is_valid, 'days' => $days_remaining, 'is_today' => $is_today);
  }

}