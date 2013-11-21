<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * Template document that sets the page structure of a logged in user. 
 *
 * It should be noted that the author-defined function xssClean() is defined in the MY_security_helper.php file (see /application/helpers for more 
 * information).
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
         <ul id="nav" class="col-md-4 list-inline">
            <li><a href="/users/logout" class="btn btn-default">Logout</a></li>
            <li><a href="/users/edit" class="btn btn-default"><?php echo xssClean($_SESSION["First_Name"], 60); ?></a></li>
            <li><a href="/notes/index" class="btn btn-default">My Notes</a></li>
         </ul>
         
         <p id="createNoteButton" class="col-md-2 col-md-push-6">
            <a href="/notes/create" class="btn btn-default btn-block">Create Note</a>
         </p>
         
      </header>
      
      <section id="main" class="row">
      
         <?php $this->load->view($main); ?>
         
      </section>
      
      <footer class="row">
      
         <p class="text-center">Copyright <?php echo date("Y"); ?> Mark Johnman. All Rights Reserved.</p>
         
      </footer>
      
   </body>
   
</html>