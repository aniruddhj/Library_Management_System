<html>
    <head> <center> <h1>Library Management System</h1> </center>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title> Library Management System </title>
</head>

<body>
    <h2><left><a href="Page1.html">Homepage</a></left></h2>
    <div id="header">
        <form name="search_avail" method="post">
            <h1> <center> Search & Availability </center> </h1>
            <center><table cellspacing="15">
                    <tr>
                        <td>Book Id</td>
                        <td><input type="text" name="book_id" id="book_id"/> </td>

                    </tr>

                    <tr>
                        <td>Title</td> 
                        <td> <input type="text" name="title" id="title"/> </td>

                    </tr>

                    <tr>
                        <td>Author Name</td>
                        <td><input type="text" name="full_name" id="full_name"/></td>

                    </tr>


                    <tr>
                        <td></td>
                        <td> <input type="submit" name="search" value="Search"/></td>
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
        if (isset($_POST['search'])) {
            search();
        }
    }

    function search() {
        $bookid = $_POST['book_id'];
        $title = $_POST['title'];
        $fullname = $_POST['full_name'];



        $query = "SELECT a.book_id , title, author_name, type, branch_id, no_of_copies,(no_of_copies-1) as no_of_copies_avail 
            FROM book a INNER JOIN book_authors b ON 
            b.book_id=a.book_id 
            INNER JOIN book_copies c 
            ON a.book_id = c.book_id
            WHERE 
            a.book_id LIKE '%$bookid%' AND title LIKE '%$title%' AND author_name LIKE '%$fullname%';
             ";

        $records = mysql_query($query);
        ?>

<center> <table border="1" cellspacing="2" >
            <tr>
                <th>Book Id</th>
                <th>Title</th>
                <th>Author Name</th>
                <th>Type</th>
                <th>Branch Id</th>
                <th>No. of Copies</th>
                <th>No. of Available Copies</th>
            </tr>


    <?php
    if ($records === FALSE) {
        echo mysql_error();
    }
    while ($row = mysql_fetch_assoc($records)) {

        echo "<tr>";
        echo "<td>" . $row['book_id'] . "</td>";
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['author_name'] . "</td>";
        echo "<td>" . $row['type'] . "</td>";
        echo "<td>" . $row['branch_id'] . "</td>";
        echo "<td>" . $row['no_of_copies'] . "</td>";
        echo "<td>" . $row['no_of_copies_avail'] . "</td>";
        echo "</tr>";
    }
}

?>
    </table></center>
</body>

</html>