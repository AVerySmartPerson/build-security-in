<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * Displays a form that allows a user to edit their profile (e.g. name, email, and password). 
 *
 * It should be noted that the author-defined function xssClean() is defined in the MY_security_helper.php file (see /application/helpers for more 
 * information).
 *
 * Author: Mark Johnman
 * Last Modified: 17/11/13
 */
?>

<div class="col-md-8 col-md-push-2">
   <h1 class="text-center">Update Your Profile</h1>
   <br>
   
   <?php 
      if ($this->session->flashdata("error_email_invalid") || $this->session->flashdata("error_email_already_used") || $this->session->flashdata("error_first_name") || $this->session->flashdata("error_last_name") || $this->session->flashdata("error_current_password") || $this->session->flashdata("error_password")) {
         
         echo "<section id='edit_user_error_messages' class='alert alert-danger alert-dismissable text-center center-block'>";
         
         echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
         
         if ($this->session->flashdata("error_email_invalid")) {
            echo "<p>" . xssClean($this->session->flashdata("error_email_invalid"), 255) . "</p>";
         }
         
         if ($this->session->flashdata("error_email_already_used")) {
            echo"<p>" .  xssClean($this->session->flashdata("error_email_already_used"), 255) . "</p>";
         }
         
         if ($this->session->flashdata("error_first_name")) {
            echo "<p>" . xssClean($this->session->flashdata("error_first_name"), 255) . "</p>";
         }
         
         if ($this->session->flashdata("error_last_name")) {
            echo "<p>" . xssClean($this->session->flashdata("error_last_name"), 255) . "</p>";
         }
         
         if ($this->session->flashdata("error_current_password")) {
            echo "<p>" . xssClean($this->session->flashdata("error_current_password"), 255) . "</p>";
         }
         
         if ($this->session->flashdata("error_password")) {
            echo "<p>" . xssClean($this->session->flashdata("error_password"), 255) . "</p>";
         }
          
         echo "</section>";
      } 
   ?>
   
   <form id="edit_user_form" name="edit_user_form" class="form-horizontal" action="/users/edit/<?php echo $user["User_ID"]; ?>" method="post" autocomplete="on">                 
      <input type="hidden" id="user_id" name="user_id" value="<?php echo $user["User_ID"]; ?>">
      
      <div class="form-group">
         <label for="email" class="control-label">Email</label>
         <input type="email" id="email" name="email" class="form-control" maxlength="255" value="<?php echo $user["Email"]; ?>" required>
      </div>
      
      <div class="form-group">
         <label for="first_name" class="control-label">First Name</label>
         <input type="text" id="first_name" name="first_name" class="form-control" maxlength="60" value="<?php echo $user["First_Name"]; ?>" required>
      </div>
      
      <div class="form-group">
         <label for="last_name" class="control-label">Last Name</label>
         <input type="text" id="last_name" name="last_name" class="form-control" maxlength="60" value="<?php echo $user["Last_Name"]; ?>" required>
      </div>
      
      <div class="form-group">
         <label for="old_password" class="control-label">Current Password</label>
         <input type="password" id="current_password" name="current_password" class="form-control" pattern="^.{8,20}$" title="Please provide a password between 8 and 20 characters long." autocomplete="off" maxlength="20" placeholder="Your Current Password" required>
      </div>
      
      <div class="form-group">
         <label for="old_password" class="control-label">New Password (if you don't want to change your password, simply re-enter your current password)</label>
         <input type="password" id="new_password" name="new_password" class="form-control" pattern="^.{8,20}$" title="Please provide a password between 8 and 20 characters long." autocomplete="off" maxlength="20" placeholder="8-20 characters long" required>
      </div>
      <br>
      <input type="submit" class="btn btn-default btn-lg center-block" value="Update Your Profile">   
   </form>
</div>