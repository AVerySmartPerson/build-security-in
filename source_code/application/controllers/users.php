<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Controller that is responsible for allowing users to edit their profile information and log out of the application.
 *
 * It should be noted that the author-defined functions validateSession() and killSession() are defined in the MY_security_helper.php file (see /
 * application/helpers for more information).
 *
 * Author: Mark Johnman
 * Last Modified: 17/11/13
 */
class Users extends CI_Controller {

   /**
    * Constructor that performs the initial set up of the controller and ensures that the session is valid (i.e. a user is actually logged in).
    * If the session isn't valid (i.e. the correct session variables aren't set or they don't belong to an active user of the 
    * application, or the session has timed out), it's destroyed and the user is redirected back to the home page.
    */
   public function __construct() {
      parent::__construct();    
      session_start();  
      
      if (!validateSession() || !$this->MUsers->verifyUser($_SESSION["User_ID"], $_SESSION["First_Name"], $_SESSION["Last_Name"])) {
         killSession();
         redirect("/welcome/index", "location");
      } else {
         $_SESSION["Most_Recent_Action_Time"] = time();
      }
   }
   
   /**
	 * Presents the user with an interface in which they can edit their profile. Once they have edited their profile, the function processes the
	 * changes. If the update operation is successful, the user is redirected to the notes index page, along with a confirmation message. 
	 * Alternatively, the user remains on the edit user page and is provided with specific error information. Also, if the current user's 
	 * information isn't available (i.e. the session has somehow been corrupted), the user is logged out of the application. 
	 */
   public function edit() {
   	if ($this->input->post("email") && $this->MUsers->editUser()) {
      	$this->session->set_flashdata("confirmation_message", "Your profile was successfully updated!");
         redirect("/notes/index", "location");
   	/*
       * If post variables are set at this point, the edit user operation was unsucessful. Refreshes the page so that any error messages 
       * created using flashdata can be displayed to the user.
       */
   	} else if ($this->input->post("email")) {
         redirect("/users/edit", "location");
   	} else {
      	$data = array ("title" => "Edit Profile",
	                     "main" => "users/edit_user",
                        "user" => $this->MUsers->getCurrentUser()
         );
         
         if ($data["user"] == null) {
            $this->logout();
         } else {
            $this->load->vars($data);
            $this->load->view("global/logged_in_template");
         }
   	}
	}
   
   /**
    * Logs the user out of the notes application by unsetting the session variables, destroying the session and redirecting the user to the 
    * home page of the application.
    */
   public function logout() {
   	killSession();
   	redirect("/welcome/index", "location");
	}
}