<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * A model that provides an interface between the notes application and the notes table in the database. 
 *
 * It should be noted that the author-defined functions xssClean() and idClean() are defined in the MY_security_helper.php file (see /application/
 * helpers for more information).
 *
 * Author: Mark Johnman
 * Last Modified: 17/11/13
 */ 
class MNotes extends CI_Model {
   
   /**
    * Constructor that performs the initial set up of the model.
    */
   public function __construct() {
      parent::__construct();      
   }

   /**
    * Retrieves a summary (ID, Date Last Modified and Title) of each of the user's notes in the database
    * @return Array that consists of information about all of the user's notes.
    */
   public function getCurrentUserNotes() {
      $data = array();
      $this->db->select("Note_ID, Date_Last_Modified, Title"); 
      $this->db->where("User_ID", idClean($_SESSION["User_ID"], 11));
      $this->db->where("Status", "Active"); 
      $this->db->order_by("Date_Last_Modified DESC");
      $query = $this->db->get('BSI_Note');
      
      if ($query->num_rows() > 0) {
         foreach ($query->result_array() as $row) { 
            // Cleans the data in case it has been modified to include an XSS attack whilst residing in the database.
            $row["Note_ID"] = idClean($row["Note_ID"], 11);
            $row["Date_Last_Modified"] = xssClean($row["Date_Last_Modified"], 255);
            $row["Title"] = xssClean($row["Title"], 150);
            
            $data[] = $row;
         }
      }
      
      $query->free_result();
      return $data;
   }
   
   /**
    * Takes a note posted by the user (title and content) and inserts it into the database if it is valid (e.g. there is actual content
    * in the note).
    * @return boolean true/false depending upon whether the note information posted by the user was successfully added to the database.
    */
   public function createNote() {
      // A value for Date_Last_Modified is not present here as it is created by MySQL.
      $new_note = array ("User_ID" => idClean($_SESSION["User_ID"], 11),
                         "Title" => xssClean($this->input->post("title"), 150),
                         "Content" => xssClean($this->input->post("content"), 21844),
                         "Status" => "Active"
      );
      
      if ($this->isNoteValid($new_note)) {
         $this->db->insert("BSI_Note", $new_note);
         return true;
      } else {
         return false;
      }
   }   
   
   /**
    * Validates the given note to ensure that the user has provided it with a title and content. If one or both of these conditions is not met,
    * appropriate flashdata error messages are set.
    * @param note the note to be validated.
    * @return boolean true/false depending upon whether the note is valid or not.
    */
   private function isNoteValid($note) {       
      if ($note["Title"] == "") {
         $this->session->set_flashdata("error_title", "Please provide a title for your note!");
      }
      
      if ($note["Content"] == "") {
         $this->session->set_flashdata("error_content", "Please provide some content for your note!");
      } 
      
      return $note["Title"] != "" && $note["Content"] != "";  
   }
   
   /**
    * Retrieves the note with the given ID from the database (assuming that it belongs to the user and has an active Status - i.e. it hasn't
    * been "deleted").
    * @param note_id the ID of the note to be retrieved.
    * @return Array that contains the note ID, date last modified, title and content of the note requested by the user.
    */
   public function getNote($note_id) {
      $data = array();
      $this->db->select("Note_ID, Date_Last_Modified, Title, Content"); 
      $this->db->where("Note_ID", idClean($note_id, 11));
      $this->db->where("User_ID", idClean($_SESSION["User_ID"], 11));
      $this->db->where("Status", "Active");
      $this->db->limit(1); 
      $query = $this->db->get('BSI_Note');
      
      if ($query->num_rows() > 0) {
         $data = $query->row_array();
         
         // Cleans the data in case it has been modified to include an XSS attack whilst residing in the database.
         $data["Note_ID"] = idClean($data["Note_ID"], 11);
         $data["Date_Last_Modified"] = xssClean($data["Date_Last_Modified"], 255);
         $data["Title"] = xssClean($data["Title"], 150);
         $data["Content"] = xssClean($data["Content"], 21844);
      }
      
      $query->free_result();
      return $data;
   }
   
   /**
    * Takes a note posted by the user and updates the relevant entry in the notes table of the database (assuming the input is valid).
    * @return boolean true/false depending upon whether the update operation was carried out successfully.
    */
   public function editNote() {
      $updated_note = array ("Date_Last_Modified" => date("Y-m-d H:i:s"),
                             "Title" => xssClean($this->input->post("title"), 150),
                             "Content" => xssClean($this->input->post("content"), 21844)
      );
      
      if ($this->isNoteValid($updated_note)) {
         $this->db->where("Note_ID", idClean($this->input->post("note_id"), 11));
         $this->db->where("User_ID", idClean($_SESSION["User_ID"], 11));
         $this->db->where("Status", "Active");
         $this->db->update("BSI_Note", $updated_note);
         return true;
      } else {
         return false;
      }
   }
   
   /**
    * Deletes the note with the given ID from the database. It should be noted that the note is not actually removed from the database.
    * Instead the Status field is changed to Inactive so that it won't be displayed any more.
    * @param note_id the ID of the note to be deleted.
    */
   public function deleteNote($note_id) {
      $updated_status = array("Status" => "Inactive");
      $this->db->where("Note_ID", idClean($note_id, 11));
      $this->db->where("User_ID", idClean($_SESSION["User_ID"], 11));
      $this->db->update("BSI_Note", $updated_status);
   }
}