<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Controller that is responsible for connecting the views displaying notes' information with the MNotes model. It dictates application logic
 * for any user interaction with a note (browsing, reading, adding, editing and deleting).
 *
 * It should be noted that the author-defined functions idClean(), validateSession() and killSession() are defined in the MY_security_helper.php 
 * file (see /application/helpers for more information).
 *
 * Author: Mark Johnman
 * Last Modified: 17/11/13
 */
class Notes extends CI_Controller {

   /**
    * Constructor that performs the initial set up of the controller and ensures that the session is valid (i.e. a user is actually logged in).
    * If the session isn't valid (i.e. the correct session variables aren't set or they don't belong to an active user of the 
    * application), it's destroyed and the user is redirected back to the home page.
    */
   public function __construct() {
      parent::__construct();    
      session_start();  
      
      if (!validateSession() || !$this->MUsers->verifyUser($_SESSION["User_ID"], $_SESSION["First_Name"], $_SESSION["Last_Name"])) {
         killSession();
         redirect("/welcome/index", "location");
      }
   }

   /**
    * Displays a list of the currently logged in user's notes, along with options for viewing, editing and deleting them. The user is 
    * also provided with a link (in the page's header) that will take them to a page where they can add more notes (i.e. the add note page).
    */
	public function index() {
	   $data = array ("title" => "My Notes",
	                  "main" => "notes/my_notes",
	                  "notes" => $this->MNotes->getCurrentUserNotes()
	   );
	   
	   $this->load->vars($data);
		$this->load->view("global/logged_in_template");
	}
	
   /**
    * Provides the user with a page where they can create a new note. The function also processes the note, coordinating its validation and
    * addition to the user's collection of notes. If the note is successfully added to the user's collection, the user is redirected to the 
    * notes index page, along with a confirmation message. If not, the user remains on the add note page, where they will be provided with
    * specific error information. 
    */
	public function create() {
   	if ($this->input->post("title") && $this->MNotes->createNote()) {
      	$this->session->set_flashdata("confirmation_message", "Your note was successfully created!");
         redirect("/notes/index", "location");
   	/*
       * If post variables are set at this point, the create note operation was unsucessful. Refreshes the page so that any error messages 
       * created using flashdata can be displayed to the user.
       */
      } else if ($this->input->post("title")) {
         redirect("/notes/create", "location");
   	} else {
      	$data = array ("title" => "Create Note",
	                     "main" => "notes/create_note"
         );
         
         $this->load->vars($data);
         $this->load->view("global/logged_in_template");
   	}
	}
	
	/**
	 * Retrieves and displays the requested note to the user. If requested note doesn't exist or doesn't belong to the user, they are redirected
	 * to the page not found error page.
	 * @param note_id the ID of the note to be viewed
	 */
	public function view($note_id) {
      $data = array ("title" => "View Note",
	                  "main" => "notes/view_note",
	                  "note" => $this->MNotes->getNote($note_id)
	   );
	   
	   if ($data["note"] == null) {
   	   redirect("/errors/message/page_not_found", "location");
	   } else {
	      $this->load->vars($data);
         $this->load->view("global/logged_in_template");
      }  
	}
	
	/**
	 * Presents the user with an interface in which they can edit a given note. Once they have edited the note, the function processes the
	 * changes. If the update operation is successful, the user is redirected to the notes index page, along with a confirmation message. 
	 * Alternatively, the user remains on the edit note page and is provided with specific error information. Also, if the given note ID
	 * doesn't exist or doesn't belong to a note that the user owns, the user is redirected to the page not found error page. 
	 * @param note_id the ID of the note to be edited. 
	 */
	public function edit($note_id) {
   	if ($this->input->post("title") && $this->MNotes->editNote()) {
      	$this->session->set_flashdata("confirmation_message", "Your note was successfully edited!");
         redirect("/notes/index", "location");
   	/*
       * If post variables are set at this point, the edit note operation was unsucessful. Refreshes the page so that any error messages 
       * created using flashdata can be displayed to the user.
       */
   	} else if ($this->input->post("title")) {
         redirect("/notes/edit/" . idClean($this->input->post("note_id")), "location");
   	} else {
      	$data = array ("title" => "Edit Note",
	                     "main" => "notes/edit_note",
                        "note" => $this->MNotes->getNote($note_id)
         );
         
         if ($data["note"] == null) {
   	      redirect("/errors/message/page_not_found", "location");
         } else {
	         $this->load->vars($data);
            $this->load->view("global/logged_in_template");
         }
      }   
	}
	
	/**
	 * Deletes the note with the given ID, redirecting the user to the notes index page and providing them with a confirmation message. If 
	 * the given note doesn't belong to the user, it is not deleted, but the user is still notified as if it had been. This is to give 
	 * malicious users a false impression that their attack has been successful.
	 * @param note_id the ID of the note to be deleted
	 */
	public function delete($note_id) {
   	$this->MNotes->deleteNote($note_id);
   	$this->session->set_flashdata("confirmation_message", "Your note was successfully deleted!");
   	redirect("/notes/index", "location");
	}
}
