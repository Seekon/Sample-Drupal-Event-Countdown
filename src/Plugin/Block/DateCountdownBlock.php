<?php
namespace Drupal\date_countdown\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

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

    return array(
      '#markup' => $this->t('Hello world'),
    );
  }

}