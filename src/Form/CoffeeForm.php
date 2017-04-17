<?php
/**
@file
Contains \Drupal\coffee\Form\CoffeeForm.
 */

// Declare the namespace and other classes you want to use here.
namespace Drupal\coffee\Form;

// 'use' keyword tells PHP to import these classes
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

 
class CoffeeForm extends FormBase {
	/**
  * {@inheritdoc}
  */
		
	// The above line means inherit the documentation from a parent class/interface.

	// Return a string that is the unique ID of the form.
	public function getFormId() {
		return 'coffee_form';
	}

	/**
  * {@inheritdoc}
  */

	// Return a Form API array that defines each of the elements the form is composed of.
	// The $form_state object contains the user-submitted values.
	public function buildForm(array $form, FormStateInterface $form_state) {

		// Make all of the elements required
		$form['name'] = array(
			'#type' => 'textfield',
      '#title' => 'Name:',
      '#required' => TRUE,
    );

		$form['email'] = array(
			'#type' => 'email',
      '#title' => 'Email:',
      '#required' => TRUE,
    );

		// Set the max date to today. Coffee is only for people already born. A bot could put in a random date.
    $form['dob'] = array (
      '#type' => 'date',
      '#max' => date('Y-m-d'),
      '#title' => t('Birthday:'),
      '#required' => TRUE,
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );

    return $form;
	}

	/**
  * {@inheritdoc}
  */

	public function validateForm(array &$form, FormStateInterface $form_state) {

		// If the name is just a space character, show an error message.
		if ($form_state->getValue('name') == ' ') {
			$form_state->setErrorByName('name', $this->t('Name cannot be only spaces.'));
		}

		// A valid email address contains only one @ symbol, at least one period, and has more than 5 characters. example: t@t.t
		// Validated already in HTML

		// if (substr_count($form_state->getValue('email'), '@') > 1 || substr_count($form_state->getValue('email'), '.') === 0) || strlen($form_state->getValue('email') < 5) {
		// if (substr_count($form_state->getValue('email'), '@') > 1) {
		// 		$form_state->setErrorByName('email', $this->t('Please enter a valid email address.'));
		// }
	}

	/**
  * {@inheritdoc}
  */

  public function submitForm(array &$form, FormStateInterface $form_state) {
		// drupal_set_message($this-> t("Thank you, <b>@name</b>! We'll send you a coupon for free coffee to <b>@email</b> next <b>@dob</b>.", array(
		// 	'@name' => $form_state->getValue('name'),
		// 	'@email' => $form_state->getValue('email'),
		// 	'@dob' => date("M d", strtotime($form_state->getValue('dob'))),
		// 	)));

		// The below is deprecated(?) and doesn't work, but was worth a try.
		// https://www.drupal.org/node/431666
		/*

		$name = $form_state->getValue('name');
	  $email = $form_state->getValue('email');
	  $dob = $form_state->getValue('dob');
		$created = time();

		$sql_query = "INSERT INTO coffee_data ('name','email','dob','created') VALUES ('%s', '%s', '%s', %d)";


		if ($success = db_query($sql_query, $name, $email, $dob, $created)) {
    		drupal_set_message($this-> t("Thank you, <b>@name</b>! We'll send you a coupon for free coffee to <b>@email</b> next <b>@dob</b>.", array(
						'@name' => $form_state->getValue('name'),
						'@email' => $form_state->getValue('email'),
						'@dob' => date("M d", strtotime($form_state->getValue('dob'))),
				)));
	  } else { 
	    drupal_set_message($this-> t('There was an error saving your data. Please try again.'));
	  }
	  */

	  // Also doesn't work:
	  // https://www.drupal.org/docs/7/api/database-api/insert-queries
	  // But there isn't one for Drupal 8?
	  // https://www.drupal.org/docs/8/api/database-api
	  
		$name = $form_state->getValue('name');
	  $email = $form_state->getValue('email');
	  $dob = $form_state->getValue('dob');
		$created = time();


		$nid = db_insert('coffee_data') 
		->fields(array(  
			'name' => $name,  
			'email' => $email,  
			'dob' => $dob,
			'created' => $created,
		))
		->execute();

		drupal_set_message($this-> t("Thank you, <b>@name</b>! We'll send you a coupon for free coffee to <b>@email</b> next <b>@dob</b>.", array(
			'@name' => $form_state->getValue('name'),
			'@email' => $form_state->getValue('email'),
			'@dob' => date("M d", strtotime($form_state->getValue('dob'))),
		)));

  }

}