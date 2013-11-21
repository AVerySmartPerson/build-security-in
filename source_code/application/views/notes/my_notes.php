<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * Displays a summarised version of each of the user's notes. This page is the "home" page of a logged in user. 
 *
 * It should be noted that the author-defined function xssClean() is defined in the MY_security_helper.php file (see /application/helpers for more 
 * information).
 *
 * Author: Mark Johnman
 * Last Modified: 17/11/13
 */
?>

<div class="col-md-8 col-md-push-2">
   <h1 class="text-center">My Notes</h1>
   <br>
   
   <?php 
   if ($this->session->flashdata("confirmation_message")) {
      echo "<section id='confirmation_message' class='alert alert-success alert-dismissable text-center center-block'>";
      echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
      echo "<p>" . xssClean($this->session->flashdata("confirmation_message"), 255) . "</p>";
      echo "</section>";
   } 
   ?>
  
   <?php
   if (count($notes) < 1) {
      echo "<p class='text-center'>You currently have no saved notes. Click the Create Note link above to create one!</p>";
   } else {
      foreach ($notes as $key => $note) {            
         echo "<div class='panel panel-default'>";            
         
         echo "<div class='panel-heading'>";
         echo "<a href='/notes/view/" . $note["Note_ID"] . "'><h3 class='panel-title note_title'>" . $note["Title"] . "</a></h3>";
         echo "</div>";            
         
         echo "<div class='panel-body'>";            
         echo "<a href='/notes/view/" . $note["Note_ID"] . "' class='btn btn-success col-sm-2'>View&nbsp;&nbsp;";
         echo "<span class='glyphicon glyphicon-folder-open'></span>";
         echo "</a>"; 
         echo "<a href='/notes/edit/" . $note["Note_ID"] . "' class='btn btn-warning col-sm-2 col-sm-push-3'>";
         echo "<span class='add_margin_right'>Edit</span>";
         echo "<span class='glyphicon glyphicon-edit'></span>";
         echo "</a>";          
         echo "<a href='/notes/delete/" . $note["Note_ID"] . "' class='btn btn-danger col-sm-2 col-sm-push-6'>";
         echo "<span class='add_margin_right'>Delete</span>";
         echo "<span class='glyphicon glyphicon-remove'></span>";
         echo "</a>";        
         echo "</div>";            
         
         echo "<div class='panel-footer'><strong>Date Last Modified:</strong> " . $note["Date_Last_Modified"] . "</div>";
         
         echo "</div>";            
      }
   }
   ?>
</div>


