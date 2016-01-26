<?php

/**
 * @file
 * Contains \Drupal\user_blocker\Plugin\Block\UserBlockerBlock.
 */

namespace Drupal\user_blocker\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'UserBlockerBlock' block.
 *
 * @Block(
 *  id = "user_blocker_block",
 *  admin_label = @Translation("User Blocker"),
 * )
 */
class UserBlockerBlock extends BlockBase {


  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['user_blocker_block']['#markup'] = 'Implement UserBlockerBlock.';

    return $build;
  }

}
