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
    return 'user_blocker_form';
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
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $username = $form_state->getValue('username');
    /** @var User $user */
    $user = $this->getUser($form_state);
    if (empty($user)) {
      $form_state->setError(
        $form['username'],
        $this->t('User @username was not found.', ['@username' => $username])
      );
    }
    else {
      $current_user = \Drupal::currentUser();
      if ($user->id() == $current_user->id()) {
        $form_state->setError(
          $form['username'],
          $this->t('You cannot block your own account.')
        );
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $username = $form_state->getValue('username');
    /** @var User $user */
    $user = $this->getUser($form_state);
    $user->block();
    $user->save();
    drupal_set_message($this->t('User @username has been blocked.', ['@username' => $username]));
  }

  /**
   * Get the user object for the submitted user.
   * 
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return mixed
   */
  protected function getUser(FormStateInterface $form_state) {
    $user_storage = \Drupal::entityTypeManager()->getStorage('user');
    $users = $user_storage->loadByProperties(['name' => $form_state->getValue('username')]);
    return array_pop($users);
  }

}
