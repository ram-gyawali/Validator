# Validator **v0.1.0**

This very small library is based on only in PHP language. This is intended for only small projects.

## Usage

1. Require The Validator.php through RTDev\Validator namespace.
2. Instantiante the Validator Class.
   ```php
    $validator = new \RTDev\Validator();
   ```
3. You should have your data as an array and rules also as an array.
  ```php
    $data = ['email' => 'ramgyawali.webdeveloper@gmail.com', 'password' => 'lorem ipsum'];
    //specify the rule for each field separetor by '|' operator and ':' operator
    $rule = ['email' => 'required|email', 'password' => 'required|min:8|max:15'];
  ```
 4. Finally, add above ```$data``` and ```$rule``` array to the validate method.
    ```php
    $is_valid = $validator -> validate($data, $rule);
    ```
    
## Error Handling
  Errors can be retrieve from three different methods.
  ```php
    $validator -> get_all_errors(); // return array of array
    $validator -> get_errors('email'); // return array of error only for email field
    $validator -> get_error('email', 0); //return the first error of the field email
  ```
## Supported Methods
  - validate() => For validating purposes
  - get_all_errors() => For retrieving all the errors
  - get_errors($field) => For retrieving errors related with ```$field```
  - get_error($field, $index) => For retrieving errors related with ```$field``` and the ```$index```. The second parameter is optional.
  
## Supported Validation Fields
  - required => For must have field
  - email => For valid email
  - min:8 => For minimum of 8 characters
  - max:10 => For maximum of 10 characters
  - username => For valid usernames

### Notes
  1. Email Validation is based on the PHP default method called ```filter_validate()```.
  2. Username is consider valid for numbers, letters, underscores and spaces. But name cannot start with anything except letters.
