<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * The functions below are incorportated into CodeIgniter's built-in security helper, making them available whenever the built-in security helper
 * is loaded. It should be noted that the title of the file must be named in the way it has been to ensure that it is incorporated into the
 * built-in security helper.
 *
 * Author: Mark Johnman
 * Date Last Modified: 17/11/13
 */

/**
 * Includes a library that is intended to provide forward compatibility with the password_* functions being worked on for PHP 5.5. This allows
 * the application to make use of the password_hash and password_verify functions, which create hashes using automatically generated salts and
 * strong hashing algorithms (currently they use the BCrypt algorithm).
 */
require("lib/password_compat/lib/password.php");

/**
 * Cleans the given ID so that it is ready to be used in SQL queries. This is accomplished by first reducing the length of the ID down to the given
 * size and then returning an integer value of it. This guarantees that an integer will always be returned, thereby negating the threat of SQL
 * injection.
 * @param id the ID to be cleaned.
 * @param size the size (length) that the ID is to be reduced to.
 * @return int the integer value of the given ID (after it has been reduced to the specified size).
 */
if (!function_exists("idClean")) {
   function idClean($id, $size=11) {
      $id_cut_to_size = substr($id, 0, $size);
      return intval($id_cut_to_size);
   }
}

/**
 * Attempts to clean the given data string from potential XSS attacks by reducing the string's length down to a given size, stripping it of any
 * HTML or PHP tags, turning any characters in it that have HTML entity codes into those entities and running it through CodeIgniter's built-in 
 * XSS filter (called by xss_clean(data to be cleaned)). The built-in XSS filter turns suspicious characters into character entities.
 * @param data the string to be cleaned.
 * @param size the size (length) that the data string is to be reduced to.
 * @return string the data after it has been cut to size, stripped of any HTML or PHP tags and run through the XSS filter.
 */
if (!function_exists("xssClean")) {
   function xssClean($data, $size=255) {
      $data_cut_to_size = substr($data, 0, $size);
      $data_tags_stripped = strip_tags($data_cut_to_size);
      $xss_filtered_data = xss_clean(htmlentities($data_tags_stripped));
      return $xss_filtered_data;
   }
}

/**
 * Checks if a session is valid by testing to see if the appropriate session variables are set. 
 * @return boolean true/false depending upon if the appropriate session variables are set.
 */
if (!function_exists("validateSession")) {
   function validateSession() {
      return count($_SESSION) == 3 && isset($_SESSION["User_ID"]) && isset($_SESSION["First_Name"]) && isset($_SESSION["Last_Name"]);
   } 
}

/**
 * Kills the current session by unsetting all of the session variables, destroying the session cookie and destroying the session data.
 */
if (!function_exists("killSession")) {
   function killSession() {
      foreach ($_SESSION as $name => $value) {
         unset($name);
      }

      if (isset($_COOKIES[session_name()])) {
         setcookie (session_name(), "", 1);
         setcookie (session_name(), false);
         unset($_COOKIE[session_name()]);
      }
      
      session_destroy();
   }  
}