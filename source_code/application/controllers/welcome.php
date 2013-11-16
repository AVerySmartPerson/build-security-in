<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Controller responsible for displaying pages (except error pages) and responding to user actions on the pages that can be accessed by the user 
 * without them having to be logged in.
 *
 * It should be noted that the author-defined functions validateSession() and killSession() are defined in the MY_security_helper.php file (see /
 * application/helpers for more information).
 *
 * Author: Mark Johnman
 * Last Modified: 17/11/13
 */
class Welcome extends CI_Controller {

   /**
    * Constructor that performs the initial set up of the controller and starts/reopens a session. If the session isn't valid (i.e. the correct 
    * session variables aren't set or they don't belong to an active user of the application), it's destroyed.
    */
   public function __construct() {
      parent::__construct();
      session_start();     
      
      if (!validateSession() || !$this->MUsers->verifyUser($_SESSION["User_ID"], $_SESSION["First_Name"], $_SESSION["Last_Name"])) {
         killSession();
      }
   }
	
	/**
	 * Displays the home page of the application, which provides the user with forms to login to the application and to register with the 
	 * application. The function is also responsible for processing the login and registration forms. If the user happens to be logged in
	 * when trying to access this page, they are redirected to the notes index page.
	 */
	public function index() { 
   	// If session variable User_ID is set, then the user must be logged in (as the session was validated in the constructor).
   	if (isset($_SESSION["User_ID"])) {
   	   redirect("/notes/index", "location");
   	} else if ($this->input->post("login_email") && $this->MUsers->loginUser($this->input->post("login_email"), $this->input->post("login_password"))) {
      	redirect("/notes/index", "location");
   	} else if ($this->input->post("email") && $this->MUsers->addUser()) {
      	redirect("/notes/index", "location");
      /*
       * If post variables are set at this point, the login user/add user operation was unsucessful. Refreshes the page so that any error messages 
       * created using flashdata can be displayed to the user.
       */
      } else if ($this->input->post("login_email") || $this->input->post("email")) {
         redirect("/welcome/index", "location");
   	} else {
      	$data = array ("title" => "Welcome to the Build Security In Notes Application",
	                     "main" => "welcome/home"
         );
         
         $this->load->vars($data);
         $this->load->view("global/logged_out_template");
   	}
	}
}