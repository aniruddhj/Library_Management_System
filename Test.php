<html>
    <body>
        <form name="test" method="POST">
            <table>
                <tr>
                    <th></th>
                    <th>book_id</th>
                    <th>card no</th>
                    <th>fname</th>
                    <th>lname</th>
                </tr>
                <?php
                $conn = mysql_connect("localhost", "root", "");
                if ($conn) {
                    mysql_select_db("library");
                } else {
                    echo 'no such database';
                }

                $query_test = "select book_id2,a.card_no,fname,lname from book_loans a, borrower b"
                        . " where a.card_no = b.card_no ";

                $result = mysql_query($query_test);

                if ($result === FALSE) {
                    mysql_error();
                } else {

                    while ($rows = mysql_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td><input type='radio' name='test' value='" . $rows['book_id2'] . " " . $rows['card_no'] . " " . $rows['fname'] . " " . $rows['lname'] . "'></td>";
                        //echo "<td><input type='radio' name='test'></td>";
                        echo "<td>" . $rows['book_id2'] . "</td>";
                        echo "<td>" . $rows['card_no'] . "</td>";
                        echo "<td>" . $rows['fname'] . "</td>";
                        echo "<td>" . $rows['lname'] . "</td>";
                        echo "</tr>";
                    }
                    echo "<tr>";
                    echo "<td><input type='submit' name='test_val' value='submit'/></td>";
                    echo "</tr>";    
                }

                if ($_POST) {
                    if (isset($_POST['test_val'])) {

                        //echo "You have selected :" .$_POST['test']; 
                        test_display();
                    }
                }

                function test_display() {
                   $test =  explode(" ",$_POST['test']) ;
                   echo $test[0];
                }
                ?>
                
            </table>
        </form>
    </body>    
</html>
