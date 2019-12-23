<?php
/**
 * @author RTDev ramgyawali.webdeveloper@gmai.com
 * @version 0.1.0
 * @package RTDev
 *
 */
namespace RTDev;

class Validator {
	private $errors;

	/**
	* add the new error inside private $errors array
	*
	* @param String The error string which wanted to store
	* @param String The Field with which the $status related
	* @return void
	*/
	private function add_error($status, $field = "developer") {
		$this -> errors[$field][] = $status;
	}

	/**
	* Check if the specify error field exist
	*
	* @param String The field from private $errors array
	* @return Boolean
	*/
	private function is_error_field_exist($field) {
		return !empty($this -> errors[$field]);
	}

	/**
	* Validate for mininum value
	*
	* @param String The field like email, password, username
	* @param String The value of the $field
	* @param String The minimum value to test
	* @return Boolean 
	*/
	private function validate_min($field, $value, $parameter) {
		if(!empty($value)) {
			if(strlen($value) < $parameter) {
				$this -> add_error("The " . ucfirst($field) . " Should Have At Least \"" . $parameter . "\" Characters Long", $field);
				return false;
			}

			return true;
		}

		return false;
	}

	/**
	* Validate for maximum value
	*
	* @param String The field like email, password, username
	* @param String The value of the $field
	* @param String The maximum value to test
	* @return Boolean
	*/
	private function validate_max($field, $value, $parameter) {
		if(!empty($value)) {
			if(strlen($value) > $parameter) {
				$this -> add_error("The " . ucfirst($field) . " Should Have At Most \"" . $parameter . "\" Characters Long", $field);
				return false;
			}

			return true;
		}

		return false;
	}

	/**
	* Validate for required field. Field which should not be empty
	*
	* @param String The field like email, password, username
	* @param String The value of the $field
	* @param String Empty string for this test
	* @return Boolean
	*/
	private function validate_required($field, $value, $parameter) {
		if(!$value) {
			$this -> add_error("Please Fill Out \"" . ucfirst($field) . "\" Field", $field);
			return false;
		}

		return true;
	}

	/**
	* Validate for email field based on php default provided method
	*
	* @param String The field like email, password, username
	* @param String The value of the $field
	* @param String Empty string for this test
	* @return Boolean
	*/
	private function validate_email($field, $value, $parameter) {
		if(!empty($value)) {

			if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				$this -> add_error("The Provided \"" . ucfirst($field) . "\" Is Not Valid", $field);
				return false;
			}

			return true;
		}

		return false;

	}

	/**
	* Validate for username field.
	*
	* This test only only numbers, space, letters and underscore
	* The name cannot start with numbers, spaces or underscores
	* This field is to use as a profile-name not for login purpose
	*
	* @param String The field like email, password, username
	* @param String The value of the $field
	* @param String Empty string for this test
	* @return Boolean
	*/
	private function validate_username($field, $value, $parameter) {
		if(!empty($value)) {
			if(preg_match('/^[a-zA-Z]+[a-zA-Z_0-9\ ]+$/', $value)) {
				return true;
			} else {
				$this -> add_error('The ' . $field . ' Is Not Valid', 'username');
			}
		}

		return false;
	}

	/**
	* Loop through the rules, chunk all the rule again and call respective methods
	*
	* @param array The user data like "email => email_value"
	* @param array Rule how user like to validate like "email => required|email"
	* @return Boolean
	*/
	public function validate(Array $data, Array $rules) {
		if(empty($data) && empty($rules)) {
			//show error to the developer
			$this -> add_error("Please Provide Rules and Data");
			return false;
		}

		$is_valid = true;

		foreach($rules as $field => $rule) {
			//$field: email, password
			//$rule: required|email|min:8

			$rule_sets = explode('|', $rule);
			//rule_sets: ['required', 'email', 'min:8']

			foreach ($rule_sets as $rule_set) {
				$pos = strpos($rule_set, ":");

				if($pos) {
					//if $rule_sets = min:8
					//$parameter= 8
					//$rule_set = min
					$parameter = substr($rule_set, $pos + 1);
					$rule_set = substr($rule_set, 0, $pos);
				} else {
					$parameter = '';
				}

				$method_name = 'validate_' . lcfirst($rule_set);

				//$value is the actual value the user provide
				$value = $data[$field];

				if(method_exists($this, $method_name)) {
					$this -> $method_name($field, $value, $parameter) || $is_valid = false;
				} else {
					//this execute when the $data and $rules are not as we wanted
					$this -> add_error('Not Valid Rule. Please Check Out Documentation For Supported Rules');
					return false;
				}
			}
		}

		return $is_valid;

	}

	/**
	* Return all the errors store on errors private variable
	*
	* @return Array if errors exist
	* @return String if erros does not exist
	*/
	public function get_all_errors() {
		return $this -> errors ? $this -> errors :  'No Error Found';
	}

	/**
	* Return all specific errors like email errors
	*
	* @param String The field from where the error want to retrieve
	* @return array if errors exist
	* @return String if errors does not exist
	*/
	public function get_errors($field) {
		if($this -> is_error_field_exist($field)) {
			return $this -> errors[$field];
		}

		return 'No Such Field Called ' . $field;
	}

	/**
	* return one and only string of error
	*
	* @param String The field from where the error want to retrieve
	* @param int The exact index of field
	* @return String
	*/
	public function get_error($field, $inner_field = 0) {
		if($this -> is_error_field_exist($field)) {
			return $this -> errors[$field][$inner_field];
		}

		return 'No Such Field Called ' . $field;
	}
}


