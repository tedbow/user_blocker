<?php

/**
 * @file
 * Contains \Drupal\user_blocker\Form\BlockerForm.
 */

namespace Drupal\user_blocker\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Form to allow users to block other users
 */
class BlockerForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'blocker_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['username'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Username'),
      '#description' => $this->t('Enter the username of the user you want to block'),
      '#maxlength' => 64,
      '#size' => 64,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Block User'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $username = $form_state->getValue('username');
    /** @var User $account */
    if ($user = user_load_by_name($username)) {
      $user->block();
      $user->save();
      drupal_set_message($this->t('User @username has been blocked.', ['@username' => $username]));
    }
    else {
      drupal_set_message($this->t('User @username was not found.', ['@username' => $username]), 'warning');
    }
  }

}
