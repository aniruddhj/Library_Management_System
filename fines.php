<html>
    <head> <center> <h1>Library Management System</h1> </center>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title> Library Management System </title>
</head>

<body>
    <h2><left><a href="Page1.html">Homepage</a></left></h2>
    
    
    <form name="check" method="post">
        <h1> <center>  Fines </center> </h1>
        <center> <table>
           <tr>
                <th>Name</th>
                <th>Card No.</th>
                <th>Fine</th>
                <th>Paid</th>
            </tr>
            <?php
            $con = mysql_connect("localhost", "root", "");
            if ($con) {
                mysql_select_db("library");
            } else {
                echo 'could not connect' . "<br/>";
            }

            if($_POST)
            {
                
                if(isset($_POST['refresh']))
                {
                    refresh();
                }
                 elseif (isset($_POST['filter_fines']))
                 {
                        filter_fine();
                }
                /*elseif (isset ($_POST['estimate']))
                {
                    estimate_fine();
                }
                elseif(isset ($_POST['fine_paid']))
                {
                    pay_fine();
                }*/
                
            }
            
            function refresh()
            {
                $query_fine_insert = "insert into fines(loan_id,fine_amt) select loan_id, datediff(date_in,due_date)*0.25 as amt from book_loans 
                    where date_in > due_date or current_date> due_date;";
                mysql_query($query_fine_insert);
                
                $query_update = "update fines set paid = 1 where fine_amt > 0.0";
                mysql_query($query_update);
                
                $query_refresh = "select c.loan_id,b.card_no,sum(c.fine_amt) as total_fine, a.fname , a.lname, c.paid 
                from borrower a,book_loans b ,fines c 
                where a.card_no = b.card_no 
                and c.loan_id = b.loan_id 
                group by b.card_no;";
                
                   $result = mysql_query($query_refresh);
                
                   if($result === FALSE)
                   {
                       mysql_error();
                       echo "<script type='text/javascript'> alert('no data');</script>";
                   }
                    else{   
                     
                        while($row = mysql_fetch_assoc($result))
                       {
                           
                           echo "<tr>";
                           echo "<td>" .$row['fname']. ' ' .$row['lname'].  "</td>";
                            echo "<td><center>" .$row['card_no']. "</center></td>";
                           echo "<td>" .$row['total_fine']. "</td>";
                           echo "<td><center>" .$row['paid']. "</center></td>";
                           echo "</tr>";
                       }
                    }
            }
            
            function filter_fine()
            {
                $query_filter = "select c.loan_id,b.card_no,sum(c.fine_amt) as total_fine, a.fname , a.lname, c.paid 
                from borrower a,book_loans b ,fines c 
                where a.card_no = b.card_no 
                and c.loan_id = b.loan_id and c.paid = 0 
                group by b.card_no;";
            
                $result = mysql_query($query_filter);
                
                   if($result === FALSE)
                   {
                       mysql_error();
                       echo "<script type='text/javascript'> alert('no data');</script>";
                   }
                    else{   
                     
                        while($row = mysql_fetch_assoc($result))
                       {
                           
                           echo "<tr>";
                           echo "<td>" .$row['fname']. ' ' .$row['lname'].  "</td>";
                            echo "<td><center>" .$row['card_no']. "</center></td>";
                           echo "<td>$" .$row['total_fine']. "</td>";
                           echo "<td><center>" .$row['paid']. "</center></td>";
                           echo "</tr>";
                       }
                    }
            }
            
            
            
           
            ?>
            
         
            <tr>
                <td><input type="submit" name="refresh" value="Refresh"/></td>
                <td><input type="submit" name="filter_fines" value="Previous Fines"</td>
                </tr>
                <tr>
                    <td> 0 - Paid</td>
                     <td>1 - Unpaid</td>
                </tr>
            </table></center>
        
        <br><br><br><br>
       <center><table cellspacing="15">
               <tr>
                   <td>Card No.</td>
                   <td>Total Fine</td>
               </tr>
                    <?php
                 
                    if($_POST)
                    {
                    if (isset ($_POST['estimate']))
                {
                    estimate_fine();
                }
                elseif(isset ($_POST['fine_paid']))
                {
                    pay_fine();
                }
                    }
                    
                    
                    function estimate_fine()
            {
                $cardno = $_POST['card_no'];
                
                $estimate = "Select a.card_no, sum(b.fine_amt) as total_fine, a.date_in from book_loans a,fines b 
                where a.card_no = '$cardno' and a.loan_id = b.loan_id 
                group by a.card_no;";
                
                $result = mysql_query($estimate);
                
                if($result === FALSE)
                   {
                       mysql_error();
                       echo "<script type='text/javascript'> alert('no data');</script>";
                   }
                    else{   
                     
                        while($row = mysql_fetch_assoc($result))
                       {
                           
                           echo "<tr>";
                           echo "<td><center>" .$row['card_no']. "</center></td>";
                           echo "<td>$" .$row['total_fine']. "</td>";
                           //echo "<td>" .$row['date_in']. "</td>";
                           echo "</tr>";
                       }
                    }
                
            }
            
            function pay_fine()
            {
                $cardno = $_POST['card_no'];
                
                $check = "select date_in from book_loans where card_no in('$cardno')";
                
                $check_result = mysql_query($check);
                
                if($check_result === FALSE)
                {
                    echo mysql_error();
                }
                
                else{
                    while($rows = mysql_fetch_assoc($check_result))
                    {
                        if($rows['date_in'] === NULL)
                        {
                            echo "<script type='text/javascript'> alert('Book is yet to be checked in')</script>";
                        }
                        else{
                            pay_fine_final($cardno);
                        }
                    }
                }
            }
            
            function pay_fine_final($cardno1)
            {
                 $cardno = $cardno1;
               
                $pay = "update fines set fine_amt=0.0, paid= 0 where loan_id IN (
                Select loan_id from book_loans where card_no = '$cardno' group by card_no);";

                $result = mysql_query($pay);
                
                if($result === FALSE)
                   {
                       mysql_error();
                       echo "<script type='text/javascript'> alert('no data');</script>";
                   }
                   
                    else{   
                         $check = "select paid from fines where loan_id in (select loan_id from book_loans where card_no= '$cardno' group by card_no);";
                         $result1 = mysql_query($check);
                         
                     while($row = mysql_fetch_assoc($result1))
                     {
                         if($row['paid'] === '0')
                         {
                             echo "<script type='text/javascript'> alert('Fine Already Paid')</script>";
                         }
                         else
                         {
                             echo "<script type='text/javascript'> alert('Fine Paid')</script>";
                         }
                     }
                        
            }
                   }
                   
                
            
                    ?>
                    <tr>
                        <td>Card No:</td> 
                        <td> <input type="text" name="card_no" id="card_no"/> </td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td><!--<input type="submit" name="search" value="Search"/>--> 
                            &nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="estimate" value="Estimate"/>
                        &nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="fine_paid" value="Pay"/></td>
                    </tr>
                    
                </table></center>
</form>    

            <?php
            
?>  
</body>
</html>


