<?php
namespace Drupal\date_countdown\Service;

/**
 * Simple example of a Service
 */
class DateCountdownService {

  /**
   * return day difference
   */
  public function countDayDifference($date) {
    $datetotime = strtotime($date);
    if($datetotime == false)
      return false;

    $remaining = $datetotime - time();
    $days_remaining = floor($remaining / 86400);

    return $days_remaining;
  }

}