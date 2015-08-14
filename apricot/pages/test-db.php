<?php
echo "DB test";
   try {
      $conn = mysql_connect("db525602008.db.1and1.com","dbo525602008","aeon_db") or die("Unable to Connect");
         if (! $conn) {
            throw new Exception("Could not connect!");
        }
   }
   catch (Exception $e) {
      echo "Error (File: ".getFile().", line ".$e->getLine()."): ".$e->getMessage();
   }

?>
