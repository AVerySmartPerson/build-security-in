<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * A model that provides an interface between the notes application and the users table in the database.
 * 
 * It should be noted that the author-defined functions xssClean() and idClean() are defined in the MY_security_helper.php file (see /application/
 * helpers for more information).
 *
 * Author: Mark Johnman
 * Last Modified: 17/11/13
 */
class MUsers extends CI_Model {
   
   /**
    * Constructor that performs the initial set up of the model. 
    */
   public function __construct() {
      parent::__construct();      
   }

   /**
    * Registers a user to the notes application by adding their information to the database. If the information provided by the user is invalid, 
    * appropriate error messages are set using flashdata and the information isn't inserted into the database.
    * @return boolean true/false depending upon whether the user was successfully added to the database or not. 
    */
   public function addUser() {
      $new_user = array ("Email" => xssClean($this->input->post("email"), 255),
                         "Password" => xssClean($this->input->post("password"), 20),
                         "First_Name" => xssClean($this->input->post("first_name"), 60),
                         "Last_Name" => xssClean($this->input->post("last_name"), 60),
                         "Status" => "Active"
      );
      
      if ($this->isUserValid($new_user)) {
         $plaintext_password = $new_user["Password"];
         $new_user["Password"] = password_hash($new_user["Password"], PASSWORD_DEFAULT);
         
         $this->db->insert("BSI_User", $new_user);
         return $this->loginUser($new_user["Email"], $plaintext_password);
      } else {
         return false;
      }
   }
   
   /**
    * Takes a user's information and validates it to ensure that it meets the specifications required by the application. If a piece of data is
    * invalid, an appropriate error message is set using flashdata.
    * @param user an Array containing the user's information (e.g. email, hashed password, first name, last name and status).
    * @return boolean true/false depending upon whether the user's information is valid or not.
    */
    private function isUserValid($user) {      
      $email_valid = filter_var($user["Email"], FILTER_VALIDATE_EMAIL);
      if (!$email_valid) {
         $this->session->set_flashdata("error_email_invalid", "Please enter a valid email!");
      }

      $email_already_taken = $this->isEmailAreadyTaken($user["Email"]);
      if ($email_already_taken) {
         $this->session->set_flashdata("error_email_already_used", "Sorry, your email is already being used by another user. Please enter a new one!");
      }
      
      if ($user["First_Name"] == "") {
         $this->session->set_flashdata("error_first_name", "Please enter your first name!");
      }
      
      if ($user["Last_Name"] == "") {
         $this->session->set_flashdata("error_last_name", "Please enter your last name!");
      }
      
      $valid_password = preg_match("/^.{8,20}$/", $user["Password"]) == 1;
      if (!$valid_password) {
         $this->session->set_flashdata("error_password", "Please provide a password between 8 and 20 characters long!");
      }
      
      return $email_valid && !$email_already_taken && $user["First_Name"] != "" && $user["Last_Name"] != "" && $valid_password;
   }
   
   /**
    * Tests if a given email is already in use by an active user. This is required to ensure that all active users of the application have
    * unique login details (email and password).
    * @param email the email to be checked to see if it's already in use by an active user.
    * @return boolean true/false depending upon if the given email already belongs to an active user or not.
    */
   private function isEmailAreadyTaken($email) {
      $this->db->select("User_ID");
      $this->db->where("Email", $email);
      $this->db->where("Status", "Active");
      $query = $this->db->get("BSI_User");
      
      /*
       * Ensures that if this function is being used in an edit user operation, the user can keep using the email address that they already 
       * have.
       */
      if (isset($_SESSION["User_ID"])) {
         $user_data = $query->result_array();
         
         foreach ($user_data as $key => $user) {
            $email_already_taken = $query->num_rows() > 0 && $user["User_ID"] != $_SESSION["User_ID"];
         }
      } else {
         $email_already_taken = $query->num_rows() > 0;
      }
      
      
      $query->free_result();
      return $email_already_taken;
   }

   /**
    * Verifies that the given email and password belong to an active user of the notes application. If they do, PHP session variables are set
    * and the user is "logged in" to the notes application. If they don't, a login error message is created using flashdata.
    * @param email the user's email address that they used when registering with the application.
    * @param password the user's password that they used when registering with the application.
    * @return boolean true/false depending upon whether the email and password belong to an active user or not.
    */
   public function loginUser($email, $password) {
      $this->db->select("User_ID, Password, First_Name, Last_Name");
      $this->db->where("Email", xssClean($email, 255));
      $this->db->where("Status", "Active"); 
      $this->db->limit(1);
      $query = $this->db->get("BSI_User");
      
      // If true, then the user entered in an email address being used by a user of the application.
      if ($query->num_rows() > 0) {
         $user_data = $query->row_array();

         // If true, then the user entered in the matching password for the email address.
         if (password_verify(xssClean($password, 20), $user_data["Password"])) {
            
            // Need to restart the session because it will have been killed when the page first loaded as the user was not logged in.
            session_start();
            session_regenerate_id();
            
            // Cleans the data in case it has been modified to include an XSS attack whilst residing in the database.
            $_SESSION["User_ID"] = idClean($user_data["User_ID"], 11);
            $_SESSION["First_Name"] = xssClean($user_data["First_Name"], 60);
            $_SESSION["Last_Name"] = xssClean($user_data["Last_Name"], 60);

            $query->free_result();
            return true;
         } 
      }
      
      $this->session->set_flashdata("login_error", "Sorry, your email or password is incorrect!");
      $query->free_result();
      return false;
   }

   /**
    * Returns the details of the user currently logged into the application.
    * @return Array an array containing the email address, first name and last name of the user currently logged into the application.
    */
   public function getCurrentUser() {
      $data = array();
      $this->db->select("User_ID, Email, First_Name, Last_Name"); 
      $this->db->where("User_ID", idClean($_SESSION["User_ID"], 11));
      $this->db->where("Status", "Active"); 
      $this->db->limit(1);
      $query = $this->db->get('BSI_User');
      
      if ($query->num_rows() > 0) {
         $data = $query->row_array();
         
         // Cleans the data in case it has been modified to include an XSS attack whilst residing in the database.
         $data["User_ID"] = idClean($data["User_ID"], 11);
         $data["Email"] = xssClean($data["Email"], 255);
         $data["First_Name"] = xssClean($data["First_Name"], 60);
         $data["Last_Name"] = xssClean($data["Last_Name"], 60);
      }
      
      $query->free_result();
      return $data;
   }

   /**
    * Takes the user's input and updates their information in the database using it. The function also updates the User_Name PHP session variable 
    * to the input provided by the user. If any of the information provided is invalid, the update operation isn't performed and appropriate error 
    * messages are specified using flashdata. It should be noted that this edit function requires the user to provide their old password along
    * with updated information. This is to help protect users if they forget to logout of the application and leave it open for someone else to
    * find. 
    * @return boolean true/false depending upon whether the user's information was successfully updated.
    */
   public function editUser() {
      $current_plaintext_password = $this->input->post("current_password");
      $updated_user = array ("Email" => xssClean($this->input->post("email"), 255), 
                             "Password" => xssClean($this->input->post("new_password"), 20),
                             "First_Name" => xssClean($this->input->post("first_name"), 60),
                             "Last_Name" => xssClean($this->input->post("last_name"), 60)
      );
      
      if ($this->isUserUpdateValid($updated_user, $current_plaintext_password)) {
         $updated_user["Password"] = password_hash($updated_user["Password"], PASSWORD_DEFAULT);
         $this->db->where("User_ID", idClean($_SESSION["User_ID"], 11));
         $this->db->where("Status", "Active");
         $this->db->update("BSI_User", $updated_user);
         
         session_regenerate_id();
         $_SESSION["First_Name"] = $updated_user["First_Name"];
         $_SESSION["Last_Name"] = $updated_user["Last_Name"];
         return true;
      } else {
         return false;
      }
   }

   /**
    * Performs the usual user validation and also ensures that the old password provided by the user is correct. This increases the likelihood
    * that it is actually the user themselves making an update to their profile information and not someone else trying to hijack their account.
    * @param updated_user an Array that contains the updated user information.
    * @param old_plaintext_password a plaintext version of the user's current password.
    * @return boolean true/false depending upon if the user's information is valid and their old password is correct.
    */
   private function isUserUpdateValid($updated_user, $current_plaintext_password) {
      $user_valid = $this->isUserValid($updated_user);
      
      $current_password_correct = $this->isPasswordCorrect($current_plaintext_password);
      if (!$current_password_correct) {
         $this->session->set_flashdata("current_password_error_message", "Sorry, your current password is incorrect!");
      }
      
      return $user_valid && $current_password_correct;
   }

   /**
    * Checks that the given password matches the current user's password.
    * @param plaintext_password the current user's password in plaintext form.
    * @param boolean true/false depending upon if the given password belongs to the logged in user.
    */
   private function isPasswordCorrect($plaintext_password) {
      $this->db->select("Password");
      $this->db->where("User_ID", idClean($_SESSION["User_ID"], 11));
      $this->db->where("Status", "Active");
      $this->db->limit(1);
      $query = $this->db->get("BSI_User");
      
      if ($query->num_rows() > 0) {
         $user_data = $query->row_array();
         
         if (password_verify(xssClean($plaintext_password, 20), $user_data["Password"])) {
            $query->free_result();
            return true;
         }
      }
      
      $query->free_result();
      return false;
   }
   
   /**
    * Verifies that the given user ID, first name and last name belong to a single, active user of the notes application. This function
    * is used to help authenticate sessions in the MY_security_helper.php file (see /application/helpers for more information).
    * @return boolean true/false depending upon whether the given user ID, first name and last name belong to a single, active user.
    */
   public function verifyUser($user_id, $first_name, $last_name) {
      $this->db->select("User_ID");
      $this->db->where("User_ID", idClean($user_id, 11));
      $this->db->where("First_Name", xssClean($first_name, 60));
      $this->db->where("Last_Name", xssClean($last_name, 60));
      $this->db->where("Status", "Active");
      $query = $this->db->get("BSI_User");
      
      $user_verified = $query->num_rows() > 0;
      $query->free_result();
      return $user_verified;
   }  
}