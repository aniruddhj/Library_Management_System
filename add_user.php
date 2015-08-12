<html>
    <head>
    <center> <h1>Library Management System</h1> </center>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title> Library Management System </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h2><left><a href="Page1.html">Homepage</a></left></h2>
    <div id="header">
        <form name="adduser" method="post" action="add_user.php">
            <center><table cellspacing="5">
                    <tr>
                        <td></td>
                        <td><h1> Add User </h1></td>
                    </tr>

                    <tr>
                        <td>First Name: </td> 
                        <td> <input type="text" id="f_name" name="f_name"/> </td>

                    </tr>

                    <tr>
                        <td>Last Name: </td>
                        <td> <input type="text" id="l_name" name="l_name"/> </td>

                    </tr>

                    <tr>
                        <td>Address: </td>
                        <td> <textarea rows="3" cols="20" id="address" name="address"></textarea> </td>


                    <tr>
                        <td>Phone No.:
                            <br> Format (000)-000-0000
                        </td>
                        <td> <input type="text" id="phone" name="phone"/> </td>

                    </tr>

                    <tr>
                        <td></td>
                        <td> <input type="submit" name="add" value="Add"/></td>
                    </tr>
                </table></center>
        </form>
    </div>		


    <?php
    $con = mysql_connect("localhost", "root", "");
    if ($con) {
        mysql_select_db("library");
    } else {
        echo 'could not connect' . "<br/>";
    }

    if ($_POST) {
        if (isset($_POST['add'])) {
            add_user();
        }
    }

    function add_user() {
        $f_name = $_POST['f_name'];
        $l_name = $_POST['l_name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        $queryuser = "select count(*) as no_times from borrower"
                . " where fname LIKE '$f_name' AND lname LIKE '$l_name' AND address LIKE '$address'";


        $records = mysql_query($queryuser);

        while($chk = mysql_fetch_assoc($records))
        {
            if($chk['no_times']>=1)
            {
                
            echo "<script type='text/javascript'>alert('USER ALREADY PRESENT');</script>";
            exit();
            }
        }
         
     
            $query = "insert into borrower(fname,lname,address,phone) values('$f_name','$l_name','$address','$phone');";
            mysql_query($query);

            if ($query === FALSE) {
                echo mysql_error();
            } else {
                echo "<script type='text/javascript'>alert('USER ADDED');</script>";
            }
        }
    

    ?>
</body>
</html>
