<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * Template document that sets the page structure of a logged out user. 
 *
 * Author: Mark Johnman
 * Last Modified: 17/11/13
 */
?>

<!DOCTYPE html>
<html lang="en">

   <head>
   
      <meta name="author" content="Mark Johnman">
      <meta name="application-name" content="Build Security In Notes Application">
      <meta name="description" content="A notes application that allows users to register, login and create, read, edit and delete their own notes">
      <meta name="keywords" content="note taking, note taking application, notes, remembering information">
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
      
      <title><?php echo $title; ?></title>
      
      <link rel="icon" href="/images/favicon.ico">
      
      <link rel="stylesheet" href="/lib/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="/css/default.css">
      
      <script src="/lib/jquery/jquery-1.10.2.min.js"></script>
      <!--[if lt IE 9]>
         <script src="/lib/html5shiv/dist/html5shiv.js"></script>
      <![endif]-->
      <script src="/lib/bootstrap/js/bootstrap.min.js"></script>
      
   </head>
   
   <body class="container"> 
   
      <header class="row">    
        
         <h2 class="col-md-6 text-center">Build Security In Notes Application</h2>
         
         <form id="login_form" name="login_form" class="col-md-6" action="/welcome/index" method="post" autocomplete="on">      
            
            <label for="login_email" class="sr-only">Email</label>
            <input type="email" id="login_email" name="login_email" maxlength="255" placeholder="Your Email" autofocus required>
         
            <label for="login_password" class="sr-only">Password</label>
            <input type="password" id="login_password" name="login_password" pattern="^.{8,20}$" title="Please provide a password between 8 and 20 characters long." autocomplete="off" maxlength="20" placeholder="Your Password" required>
            
            <input type="submit" class="btn btn-default" value="Login">                       
         
         </form>
         
      </header>
      
      <section id="main" class="row">
      
         <?php $this->load->view($main); ?>
         
      </section>
      
      <footer class="row">
      
         <p class="text-center">Copyright <?php echo date("Y"); ?> Mark Johnman. All Rights Reserved.</p>
         
      </footer>
      
   </body>
   
</html>