<?php

/**
 * @file
 * Contains \Drupal\user_blocker\Form\ProfileBlockerForm.
 */

namespace Drupal\user_blocker\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Class ProfileBlockerForm.
 *
 * @package Drupal\user_blocker\Form
 */
class ProfileBlockerForm extends BlockerForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_blocker_profile_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $route_match = \Drupal::routeMatch();
    if ($route_match->getRouteName() == 'entity.user.canonical') {
      /** @var User $user */
      $user = $route_match->getParameter('user');
      // Override existing textfield with "value" element. User does not enter this.
      $form['username'] = [
        '#type' => 'value',
        '#value' => $user->getAccountName(),
      ];
    }
    else {
      // If the current route does not have user parameter return empty form.
      $form = [];
    }
    return $form;
  }



}
