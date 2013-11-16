<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * The home page of the application if the user is not logged in. It provides a brief description of the application, along with a registration and 
 * a login form (if the user has already registered). 
 *
 * It should be noted that the author-defined function xssClean() is defined in the MY_security_helper.php file (see /application/helpers for more 
 * information).
 *
 * Author: Mark Johnman
 * Last Modified: 17/11/13
 */
?>

<?php 
   if ($this->session->flashdata("login_error")) {
      echo "<section id='login_error_message' class='alert alert-danger alert-dismissable text-center center-block'>";
      echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
      echo "<p>" . xssClean($this->session->flashdata("login_error"), 255) . "</p>"; 
      echo "</section>";
   } 
?>

<section id="about" class="col-md-6">

   <h3 class="text-center">Why Sign Up?</h3>
   <br>

   <ul class="list-unstyled">   
      <li>
         <span class="glyphicon glyphicon-pencil"></span>
         Create, read, edit and delete your own notes
      </li>
      <li>
         <span class="glyphicon glyphicon-lock"></span>
         Notes are private and cannot be viewed by anyone but yourself
      </li>
      <li>
         <span class="glyphicon glyphicon-usd"></span>
         There's no Sign Up costs, so why not start managing your notes better today?
      </li>      
   </ul>
   
   <br>
   <p>
      <img src="/images/make_note_taking_fun.gif" alt="Make Note Taking Fun" class="img-responsive img-rounded center-block" width="350" height="300">
   </p>
</section>

<section id="registration" class="col-md-5 col-md-push-1">

   <h2 class="text-center">Sign Up</h2>
   <br>
   
   <?php 
      if ($this->session->flashdata("error_email_invalid") || $this->session->flashdata("error_email_already_used") || $this->session->flashdata("error_first_name") || $this->session->flashdata("error_last_name") || $this->session->flashdata("error_password")) {
         
         echo "<section id='registration_error_messages' class='alert alert-danger alert-dismissable text-center center-block'>";
         
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
         
         if ($this->session->flashdata("error_password")) {
            echo "<p>" . xssClean($this->session->flashdata("error_password"), 255) . "</p>";
         }
          
         echo "</section>";
      } 
   ?>
   
   <form id="registration_form" name="registration_form" class="form-horizontal lead" action="/welcome/index" method="post" autocomplete="on">        
      <div class="form-group">
         <label for="email" class="col-xs-3 control-label">Email</label>
         <input type="email" id="email" name="email" class="col-xs-6" maxlength="255" placeholder="sam@example.com" required>
      </div>
      
      <div class="form-group">
         <label for="first_name" class="col-xs-3 control-label">First Name</label>
         <input type="text" id="first_name" name="first_name" class="col-xs-6" maxlength="60" placeholder="Sam" required>
      </div>
      
      <div class="form-group">
         <label for="last_name" class="col-xs-3 control-label">Last Name</label>
         <input type="text" id="last_name" name="last_name" class="col-xs-6" maxlength="60" placeholder="Smith" required>
      </div>
      
      <div class="form-group">
         <label for="password" class="col-xs-3 control-label">Password</label>
         <input type="password" id="password" name="password" class="col-xs-6" pattern="^.{8,20}$" title="Please provide a password between 8 and 20 characters long." autocomplete="off" maxlength="20" placeholder="8-20 characters long" required>
      </div>
      
      <input type="submit" class="btn btn-default btn-lg center-block" value="Sign Up">   
   </form>

</section>

