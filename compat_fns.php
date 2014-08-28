<?php
/**
 * compat_fns.php
 *
 * Copyright (c) 2014 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Php version compatibility functions.
 *
 */

function session_register(){ 
  $args = func_get_args(); 
  foreach ($args as $key){ 
      $_SESSION[$key]=$GLOBALS[$key]; 
  } 
} 
function session_is_registered($key){ 
  return isset($_SESSION[$key]); 
} 
function session_unregister($key){ 
  unset($_SESSION[$key]); 
} 

