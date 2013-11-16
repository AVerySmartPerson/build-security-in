<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * Displays a form that allows a user to create a note.
 *
 * It should be noted that the author-defined function xssClean() is defined in the MY_security_helper.php file (see /application/helpers for more 
 * information).
 *
 * Author: Mark Johnman
 * Last Modified: 17/11/13
 */
?>

<div class="col-md-8 col-md-push-2">
   <h1 class="text-center">Create Note</h1>
   <br>
   
   <?php 
      if ($this->session->flashdata("error_title") || $this->session->flashdata("error_content")) {
         
         echo "<section id='create_note_error_messages' class='alert alert-danger alert-dismissable text-center center-block'>";
         
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
   
   <form id="create_note_form" name="create_note_form" class="form-horizontal lead" action="/notes/create" method="post" autocomplete="on">         
      <div class="form-group">
         <label for="title" class="control-label">Title</label>
         <input type="text" id="title" name="title" class="form-control" maxlength="150" placeholder="A New Note" autofocus required>
      </div>
      
      <div class="form-group">
         <label for="content" class="control-label">Content</label>
         <textarea id="content" name="content" class="form-control" rows="20" placeholder="Today, I created a note in a web application!" required></textarea>
      </div>
      
      <input type="submit" class="btn btn-default btn-lg center-block" value="Create Note">   
   </form>
</div>