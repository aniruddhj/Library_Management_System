<html>
    <head> <center> <h1>Library Management System</h1> </center>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title> Library Management System </title>
</head>

<body>
    <h2><left><a href="Page1.html">Homepage</a></left></h2>
    
    <form name="check" method="post">
        <br><br><br>
        <h1> <center>  Book Check Out </center> </h1>
        <center><table cellspacing="10">
                <tr>
                    <td>Book Id: </td>
                    <td><input type="text" name="bookid" id="bookid"/> </td>
                </tr>

                <tr>
                    <td>Branch ID: </td> 
                    <td> <input type="text" name="branchid" id="branchid"/> </td>
                </tr>

                <tr>
                    <td> Card No.: </td>
                    <td> <input type="text" name="card" id="card"/></td>
                </tr>

                <tr>
                    <td></td>
                    <td> <input type="submit" name="checkout" value="CheckOut"/></td>
                </tr>
            </table></center>

        <br><br>
        
    </form>

    <?php
    $con = mysql_connect("localhost", "root", "");
    if ($con) {
        mysql_select_db("library");
    } else {
        echo 'could not connect' . "<br/>";
    }

    if ($_POST) {
        if (isset($_POST['checkout'])) {
            checkOut();
        } elseif (isset($_POST['checkin'])) {
            checkIn();
        }
    }

    function checkOut() {

        $bookid = $_POST['bookid'];
        $branchid = $_POST['branchid'];
        $card = $_POST['card'];
        
          
                $queryno= " select count(*) as 'no_of_books_taken' from book_loans "
                        . " where card_no='$card' and isnull(date_in) ";
                
                $no_taken = mysql_query($queryno);
                
                if($no_taken === FALSE)
                {
                    echo mysql_error();
                }
                else{
                while ($no= mysql_fetch_assoc($no_taken)){
                    if($no['no_of_books_taken']>3)
                    {
                        echo "<script type='text/javascript'> alert('Cannot take more books');</script>";
                        exit("Already have 3 books!");
                    }
                   }
                }
        $querycheck = " select sum(no_of_copies) as no_of_copies from book_copies "
                . " where book_id = '$bookid' and branch_id = $branchid ";

        $rec = mysql_query($querycheck);

        if ($rec === FALSE) {
            echo mysql_error();
            
        }

        while ($row = mysql_fetch_assoc($rec)) {

            if ($row['no_of_copies'] > 0) {
                
                
               $query = "insert into book_loans(book_id2,branch_id1,card_no,date_out,due_date)
            values ('$bookid',$branchid,$card, current_date(),date_add(current_date(), interval 14 day))";

                mysql_query($query);

                $querysub = "update book_copies 
                set no_of_copies = no_of_copies-1 
                where book_id = '$bookid' and branch_id='$branchid';";

                    mysql_query($querysub);
                }
                else {
                echo "<script type='text/javascript'>alert('NO Books available');</script>";
            }
            } 
        }
   
    ?>
    
</body>
</html>

