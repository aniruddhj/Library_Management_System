<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
        require 'test1.php';
        // put your code here
        
        $query = mysql_query("SELECT no_of_copies FROM book_copies");
        
        if($query === FALSE) { 
    echo mysql_error();
}
        
         while($row = mysql_fetch_assoc($query))
         {
             print($row['no_of_copies']."<br />"); 
         }
        ?>
    </body>
</html>
