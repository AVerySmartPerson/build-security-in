<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Controller that is responsible for displaying error pages that correspond to various HTML error codes.
 *
 * It should be noted that the author-defined functions validateSession() and killSession() is defined in the MY_security_helper.php file (see /
 * application/helpers for more information).
 *
 * Author: Mark Johnman
 * Last Modified: 17/11/13
 */
class Errors extends CI_Controller {

   /**
    * Constructor that performs the initial set up of the controller and starts/reopens a session (used to work out what header and footer to
    * display to the user). If the session isn't valid (i.e. the correct session variables aren't set or they don't belong to an active user of the 
    * application), it's destroyed.
    */
   public function __construct() {
      parent::__construct();
      
      /*
       * Need to check if session has been set as logged in users could be redirected to this page if they got the controller part of the URL right, 
       * but not the method part (e.g. /notes/wrongurl). If this is the case, the session will have already been started in the controller's
       * constructor, meaning that session_start() shouldn't be run again.
       */  
      if (!isset($_SESSION["User_ID"])) {
         session_start();
      }
      
      if (!validateSession() || !$this->MUsers->verifyUser($_SESSION["User_ID"], $_SESSION["First_Name"], $_SESSION["Last_Name"])) {
         killSession();
      }    
   }

   /**
    * Displays a page that reports a 404 Not Found, 401 Unauthorized or 403 Forbidden error to the user. If one of these errors is not provided
    * to the method, the error is changed or defaults to the 404 Not Found error (i.e. page_not_found). 
    */
   public function message($error) {
      if ($error != "page_not_found" && $error != "access_denied" && $error != "authorization_required") {
         redirect("/errors/message/page_not_found", "location");
      }
      
      $error_words = explode("_", $error);
      $error_title = ucwords(join(" ", $error_words));
      $data = array ("title" => $error_title, 
                     "main" => "errors/message", 
                     "error_image" => "/images/" . $error . ".jpg"
      );
	   
	   $this->load->vars($data);
	   
	   // If session variable User_ID is set, then the user must be logged in (as the session was validated in the constructor).
	   if (isset($_SESSION["User_ID"])) {
         $this->load->view("global/logged_in_template");
      } else {
         $this->load->view("global/logged_out_template");
      }
   }
}