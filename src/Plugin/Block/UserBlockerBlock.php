<?php

/**
 * @file
 * Contains \Drupal\user_blocker\Plugin\Block\UserBlockerBlock.
 */

namespace Drupal\user_blocker\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\Entity\User;

/**
 * Provides a 'UserBlockerBlock' block.
 *
 * This block will be used to show "Block" button on the user profile page.
 * It will create a form using the ProfileBlockerForm class.
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
    $build['form'] = \Drupal::formBuilder()->getForm('\Drupal\user_blocker\Form\ProfileBlockerForm');
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    $route_match = \Drupal::routeMatch();
    if (!$account->hasPermission('block users')) {
      return AccessResult::forbidden();
    }
    /** @var User $profile_user */
    if ($profile_user = $route_match->getParameter('user')) {
      if ($profile_user->id() == $account->id()) {
        // User cannot block themselves
        return AccessResult::forbidden();
      }
      if ($profile_user->isBlocked()) {
        // No need to show if user is already blocked.
        return AccessResult::forbidden();
      }
    }
    else {
      // Do not show if not on user profile page.
      return AccessResult::forbidden();
    }
    return AccessResult::allowed();
  }


}
