<html>
    <head> <center> <h1>Library Management System</h1> </center>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title> Library Management System </title>

    <body>
        <h2><left><a href="Page1.html">Homepage</a></left></h2>
        <h2><a href="fines.php">Fines</a></h2>
        <form name="check" method="post">

            <h1> <center>  Check_In </center> </h1>
            <center><table cellspacing="5">
                    <tr>
                        <td>Book Id: </td>
                        <td><input type="text" name="book_id" id="book_id"/> </td>
                    </tr>

                    <tr>
                        <td>Card No.: </td> 
                        <td> <input type="text" name="cardno" id="cardno"/> </td>
                    </tr>


                    <tr>
                        <td colspan="2"><center> Borrower Name</center> </td>
                    </tr>

                    <tr>
                        <td>First Name: </td> 
                        <td> <input type="text" name="fname" id="fname"/> </td>
                    </tr>

                    <tr>
                        <td>Last Name: </td> 
                        <td> <input type="text" name="lname" id="lname"/> </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td> <input type="submit" name="checkin" value="CheckIn"/></td>
                    </tr>
                </table></center>
            <br><br><br><br>

            <?php
            $con = mysql_connect("localhost", "root", "");
            if ($con) {
                mysql_select_db("library");
            } else {
                echo 'could not connect' . "<br/>";
            }

            if ($_POST) {
                if (isset($_POST['checkin'])) {
                    checkIn();
                }
            }

            function checkIn() {
                $bookid = $_POST['book_id'];
                $card = $_POST['cardno'];
                $fname = $_POST['fname'];
                $lname = $_POST['lname'];

                $query = "select a.book_id2, a.card_no,a.branch_id1,date_in, due_date, fname, lname from book_loans a inner join borrower c 
                    on a.card_no = c.card_no and 
            (book_id2 LIKE '%$bookid%' or a.card_no = '%$card%' or fname LIKE '%$fname%' or 
            lname LIKE '%$lname%');";

                if ($query === FALSE) {
                    echo "<script type='text/javascript'>alert('NO such book present');</script>";
                    mysql_error();
                } else {
                    $query_test = mysql_query($query);
                    echo "<center><table cellspacing='10'>";
                    echo "<tr>";
                    echo "<th></th>";
                    echo "<th>Card No</th>";
                    //echo "<th> DateIn</th>";
                   echo "<th> Due Date</th>";
                    echo "<th>First Name </th>";
                    echo "<th>Last Name</th>";
                    echo "</tr>";

                    while ($rows = mysql_fetch_assoc($query_test)) {
                        echo "<tr>";
                       echo "<td><input type='radio' name='test' value='" . $rows['book_id2'] . " " . $rows['card_no'] . " " . $rows['branch_id1'] . " " . $rows['date_in'] . " " . $rows['due_date'] . " " . $rows['fname'] . " " . $rows['lname'] . "'></td>";
                        echo "<td>" . $rows['card_no'] . "</td>";
                        //echo "<td>" . $rows['date_in'] . "</td>";
                        echo "<td>" . $rows['due_date'] . "</td>";
                        echo "<td> <center>" . $rows['fname'] . " </center></td>";
                        echo "<td> <center>" . $rows['lname'] . " </center></td>";
                        echo "</tr>";
                    }
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td><input type='submit' name='test_val' value='submit'/></td>";
                    echo "</tr>";
                    echo "</table></center>";
                }
            }
            ?>
            <?php
            if ($_POST) {
                if (isset($_POST['test_val'])) {
                    if(isset($_POST['test']))
                    {
                    checkin_display();
                    }
                    
                    }
            }

            function checkin_display() {
                $test = explode(" ", $_POST['test']);
                $bookid = $test[0];
                $card = $test[1];
                $branchid = $test[2];
                /* $datein = $test[2];
                  $duedate = $test[3];
                  $fname = $test[4];
                  $lname = $test[5];
                 */
                
                $queryin = "update book_loans
                              set date_in = current_date()
                              where book_id2 = '$bookid' and
                              card_no= '$card' and branch_id1 = '$branchid'";

                mysql_query($queryin);

                if ($queryin === FALSE) {
                    echo mysql_error();
                } else {

                    $queryadd = "update book_copies
                              set no_of_copies = no_of_copies+1
                              where book_id = '$bookid' and branch_id= '$branchid'";

                    mysql_query($queryadd);

                    $query_check = "select date_in, due_date from book_loans where book_id2='$bookid' and 
                              card_no='$card'";
                    $result = mysql_query($query_check);

                    while ($row = mysql_fetch_assoc($result)) {
                        if ($row['date_in'] > $row['due_date']) {
                            echo "<script type='text/javascript'> alert('You have exceeded the due date lookup fines table for late fee')</script>";
                        }
                    }
                }
            }
            ?>
        </form>
    </body>
</html>