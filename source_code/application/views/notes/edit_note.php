<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * View that allows a user to edit a note 
 *
 * It should be noted that the author-defined function xssClean() is defined in the MY_security_helper.php file (see /application/helpers for more 
 * information).
 *
 * Author: Mark Johnman
 * Last Modified: 17/11/13
 */
?>

<div class="col-md-8 col-md-push-2">
   <h1 class="text-center">Edit Note</h1>
   <br>
   
   <?php 
   if ($this->session->flashdata("error_title") || $this->session->flashdata("error_content")) {
      
      echo "<section id='edit_note_error_messages' class='alert alert-danger alert-dismissable text-center center-block'>";
      
      echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
      
      if ($this->session->flashdata("error_title")) {
         echo "<p>" . xssClean($this->session->flashdata("error_title"), 255) . "</p>";
      }
      
      if ($this->session->flashdata("error_content")) {
         echo"<p>" .  xssClean($this->session->flashdata("error_content"), 255) . "</p>";
      }
       
      echo "</section>";
   } 
   ?>
   
   <form id="edit_note_form" name="edit_note_form" class="form-horizontal lead" action="/notes/edit/<?php echo $note["Note_ID"]; ?>" method="post" autocomplete="on">        
      <input type="hidden" id="note_id" name="note_id" value="<?php echo $note["Note_ID"]; ?>">
          
      <div class="form-group">
         <label for="title" class="control-label">Title</label>
         <input type="text" id="title" name="title" class="form-control" maxlength="150" value="<?php echo $note["Title"]; ?>" autofocus required>
      </div>
      
      <div class="form-group">
         <label for="content" class="control-label">Content</label>
         <textarea id="content" name="content" class="form-control" rows="20" required><?php echo $note["Content"]; ?></textarea>
      </div>

      <input type="submit" class="btn btn-default btn-lg center-block" value="Edit Note">   
   </form>
</div>