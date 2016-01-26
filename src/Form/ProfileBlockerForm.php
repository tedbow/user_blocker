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
 * Form extend our previous form(BlockerForm).
 * This form will be very similar except it will not have a textfield for the username.
 * It will be displayed by our block and only work if it is on a user page.
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
    // Call parent class(BlockerForm) to build the form.
    $form = parent::buildForm($form, $form_state);
    $route_match = \Drupal::routeMatch();
    // To find out what the route name is for the profile page un-comment the next line.
    //debug('route is ' . $route_match->getRouteName(), 'route name');
    if ($route_match->getRouteName() == 'entity.user.canonical') {
      /** @var User $user */
      // To find out what parameters the route has uncomment the next line.
      //debug($route_match->getRawParameters(), 'parameters');
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
