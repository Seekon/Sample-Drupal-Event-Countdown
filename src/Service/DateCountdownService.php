<?php

namespace Drupal\date_countdown\Service;

/**
 * DateCountdown service.
 */
class DateCountdownService {

  /**
   * Calculates day difference.
   *
   * @param string $date
   *   Time to count from till now.
   *
   * @return array
   *   Asociative Array with information about day difference.
   */
  public function countDayDifference($date) {
    // Convert date to time.
    $anchor_time = strtotime($date);
    // If conversion failed return from a function.
    if ($anchor_time == FALSE) {
      $is_valid = FALSE;
    }
    else {
      $is_valid = TRUE;
    }
    // Get current time.
    $current_time = time();
    // Get difference time in miliseconds.
    $remaining_time = $anchor_time - $current_time;
    // Get difference in days.
    $days_remaining = floor($remaining_time / 86400);

    // Check if today.
    if (date('dd', $anchor_time) == date('dd', $current_time)) {
      $is_today = TRUE;
    }
    else {
      $is_today = FALSE;
    }

    return [
      'is_valid' => $is_valid,
      'days' => $days_remaining,
      'is_today' => $is_today,
    ];
  }

}
