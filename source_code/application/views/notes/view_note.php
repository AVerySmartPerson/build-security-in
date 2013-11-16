<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * Allows a user to view the content of a note. 
 *
 * Author: Mark Johnman
 * Last Modified: 17/11/13
 */
?>

<div class="col-md-8 col-md-push-2">
   <h1 class="text-center">View Note</h1>
   <br>

   <div class="panel panel-default">                       
      <div class="panel-heading">
         <h3 class="panel-title note_title"><?php echo $note["Title"]; ?></h3>
      </div>            
               
      <div class="panel-body">        
         <?php echo $note["Content"]; ?>    
      </div>            
               
      <div class="panel-footer">
         <strong>Date Last Modified:</strong><?php echo " " . $note["Date_Last_Modified"]; ?>    
         <div class="pull-right">
            <a href="/notes/edit/<?php echo $note["Note_ID"]; ?>" class="label label-warning add_margin_right">
               <span class="add_margin_right">Edit</span>
               <span class="glyphicon glyphicon-edit"></span>
            </a>
            <a href="/notes/delete/<?php echo $note["Note_ID"]; ?>" class="label label-danger">
               <span class="add_margin_right">Delete</span>
               <span class="glyphicon glyphicon-remove"></span>
            </a>  
         </div>
      </div>
   </div>
</div>