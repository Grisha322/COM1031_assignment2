<?php 
    session_start();
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    if(!empty($_SESSION['logged']) && $_SESSION['logged'] == 1){
        $staffid = $_SESSION['id'];
        $fname = $_SESSION['fname'];
        $lname = $_SESSION['lname'];
        $storeid = $_SESSION['storeid'];
    }
    else{
        $storeid = $staffid = $fname = $lname = "";
    }
    $servername = 'localhost';
    $username = 'id13616247_grishakirpa';
    $password = 'E3yn}oCd?St>6v$y';
    $dbname = 'id13616247_Sakila';
    $last_id = 0;
    $update = 0;
    if(!empty($_GET['id'])){
        $last_id = $_GET['id'];
    }
    $conn = new mysqli($servername, $username, $password, $dbname);
    if($conn->connect_error){
        die("Connection failed: ".$conn->connect_error);
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if($_GET['action'] == 'add' || !empty($_POST['addOrupdate'])){
            if($_GET['action'] == 'add'){
                $sql = "INSERT INTO Customer (Store_id, First_Name, Last_Name, Email, Address_id, Active, Create_date, Last_updated) VALUES (".$storeid.", '".$_POST['fname']."', '".$_POST['lname']."', '".$_POST['email']."', '".$_POST['addressid']."', 1,'".date("Y-m-d")." ".date("H:i:s")."', '".date("Y-m-d")." ".date("H:i:s")."')";
                $msg = "<script>alert('Record added successfully')</script>";
                $web = "Refresh:0";
            }
            else{
                $sql = "UPDATE Customer SET Store_id = ".$storeid.", First_Name = '".$_POST['fname']."', Last_Name = '".$_POST['lname']."', Email = '".$_POST['email']."', Address_id = '".$_POST['addressid']."', Last_updated = '".date("Y-m-d")." ".date("H:i:s")."' WHERE Customer_id = ".$_SESSION['data'];
                $msg = "<script>alert('Record updated successfully')</script>";
                $web = "Refresh:0; url = Customer.php?action=update";
            }
            $result = $conn->query($sql);
            if(!$conn->connect_error){
                echo $msg;
                header($web);
            }
            else{
                echo "<script>alert('Invalid Address id')</script>";
                header("Refresh:0; url=index.php");
            }
        }
        else if($_GET['action'] == 'delete' || $_GET['action'] == 'update'){
            if(!empty($_POST['deleteid'])){
                $data = $_POST['deleteid'];
            }else{$data = $_POST['updateid'];}
            $data = test_input($data);
            $sql = "SELECT Customer_id FROM Customer WHERE Customer_id =".$data;
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                if(!empty($_POST['deleteid'])){
                    $sql = "DELETE FROM Customer WHERE Customer_id =".$data;
                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('Record deleted successfully')</script>";
                        header("Refresh:0");
                    } else {
                        echo "<script>alert(Error deleting record: " .$conn->error.")</script>";
                        header("Refresh:0");
                    }
                }else{
                    $GLOBALS['update'] = 1;
                    $_SESSION['data'] = $data;
                }
            }
            else{
                echo "<script>alert('Specified ID doesn't exist')</script>";
                header("Refresh:0");
            }
        }
    }
    else{
        $_GLOBAL['update'] = 0;
    }   
    if(!empty($GLOBALS['update'])){
        $update = $GLOBALS['update'];
    }
?>
<!DOCTYPE html>
<html lang = "en">
    <head>
        <title>NottGoingToWork</title>
        <link rel = "icon" href = "Logo.png" type = "image/x-icon">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel = "stylesheet" type = "text/css" href = "index.css">
        <link rel = "stylesheet" type = "text/css" href = "Account.css">
        <link rel = "stylesheet" type = "text/css" href = "header.css">
    </head>
    <body>
        <?php
            $sql = "SELECT Category FROM film GROUP BY Category";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $category = array_fill(0, $result->num_rows, 0);
                $i = 0;
                while($row = $result->fetch_assoc()){
                    $category[$i] = $row["Category"];
                    $i++;
                }
            }
        ?>
        <div class = "actHeader">
            <form action = "Search.php" method = "get">
                <span class = "Logo">
                    <img id = "Logo" src = "Logo.png" width = "50px" height = "39px" alt = "Logo"></img>
                    <span id = "LogoLabel">NottGoingToWork</span>
                </span>
                <span>
                    <input size = "20" id = "search" type = "text" placeholder = "Search" autocomplete = "off" name = "search">
                </span>
            </form>
            <div class = "dd">
                <button id = 'loginbtn' onclick="AccDD()" class = "btn">Sign in</button>
                <div class = 'dd-content' id = 'Acc'>
                    <a href = 'Customer.php?action=add'>Add Customer</a>
                    <a href = 'Customer.php?action=delete'>Delete Customer</a>
                    <a href = 'Customer.php?action=update'>Update Customer</a>
                    <a href = 'index.php?logout=1'>Log out</a>
                </div>
            </div>
        </div>
        <script type = 'text/JavaScript'>
            var element = document.getElementById('loginbtn');
            element.innerText = <?php echo "'".$fname." ".$lname."'"; ?>;
            element.style.color = 'white';
            element.style.backgroundColor = 'green';
            element.style.fontSize = '14px';
            element.style.width = '100px';
            element.style.left = '90%';
        </script>
        <header  id = "navbar">
            <nav>
                <ul>
                    <li class="dropdown">
                        <div onclick="GenreDD()" class="dropbtn">Genres
                            <span class = "GenreSign"></span>
                        </div>
                        <div id = "GenresDD" class="dropdown-content">
                            <?php
                                foreach($category as &$value){
                                    echo "<a href = 'Genres.php?genre=".$value."&id=0'>".$value."</a>";
                                }
                            ?>
                        </div>
                    </li>
                    <li><a href="index.php">Main</a></li>
                    <li><a href="New.php?id=0">New Arrivals</a></li>
                </ul>
            </nav>
        </header>
        <div class = "content">
            <script>
                // Get the navbar
                var navbar = document.getElementById("navbar");
                // Get the offset position of the navbar
                var sticky = navbar.offsetTop;

                var modal = document.getElementById("id01");
                var modal2 = document.getElementById("id02");
            </script>
            <div class = "row">
                <div class = "column side"></div>
                <div class = "column middle">
                    <?php
                        if(!empty($_GET['action'])){
                            if($_GET['action'] == 'add' || $update){
                                if($_GET['action'] == 'add'){
                                    echo "<h2>New Customer</h2>
                                        <form class = 'idform' action = 'Customer.php?action=add' method = 'post'>";
                                }
                                else{
                                    echo "<h2>Update Customer ".$data."</h2>
                                        <form class = 'idform' action = 'Customer.php?action='update' method = 'post'>";
                                }
                                echo "<label for = 'fname'>First Name</label>
                                        <input required type = 'text' size = '35' class = 'nameIN' placeholder = 'Greg' autocomplete = 'off' name = 'fname'>

                                        <label for = 'lname'>Last Name</label>
                                        <input required type = 'text' size = '35' class = 'nameIN' placeholder = 'Kirpa' autocomplete = 'off' name = 'lname'>

                                        <label for = 'email'>Email</label>
                                        <input required type = 'text' size = '75' class = 'email' placeholder = '123@mail.com' autocomplete = 'off' name = 'email'>

                                        <label for = 'fname'>Address ID</label>
                                        <input required type = 'number' size = '9' class = 'address' placeholder = '123' autocomplete = 'off' name = 'addressid'>

                                        <input class = 'submitbtn' type = 'submit' name = 'addOrupdate' value = 'submit'>
                                    </form>";
                            }
                            else if($_GET['action'] == 'delete' || $_GET['action'] == 'update' ){
                                $sql = "SELECT Customer_id, First_Name, Last_Name FROM customer WHERE Customer_id >".$last_id." LIMIT 200";
                                $result = $conn->query($sql);
                                if($result->num_rows > 0){
                                    echo "<h2>Customer Table</h2>";
                                    if($_GET['action'] == 'delete'){
                                        echo "<form class = 'idform' action = 'Customer.php?action=delete' method = 'post'>
                                            <label for='deleteid'>Type an id of the customer to be deleted</label>
                                            <input size = '9' id = 'delete' type = 'number' placeholder = '123' autocomplete = 'off' name = 'deleteid'>
                                            <input class = 'submitbtn' type = 'submit' value = 'delete'></input>
                                        <form>";
                                    }
                                    else{
                                        echo "<form class = 'idform' action = 'Customer.php?action=update' method = 'post'>
                                            <label for='updateid'>Type an id of the customer to be updated</label>
                                            <input size = '9' id = 'update' type = 'number' placeholder = '123' autocomplete = 'off' name = 'updateid'>
                                            <input class = 'submitbtn' type = 'submit' value = 'update'></input>
                                        <form>";
                                    }
                                    echo "<table>
                                        <tr><th>Id</th><th>First Name</th><th>Last Name</th></tr>";
                                    while($row = $result->fetch_assoc()){
                                        echo "<tr>
                                            <td>".$row['Customer_id']."</td> 
                                            <td>".$row['First_Name']."</td>
                                            <td>".$row['Last_Name']."</td>
                                        </tr>";
                                        $last_id = $row["Customer_id"];
                                    }
                                    $sql = "SELECT MAX(Customer_id) AS maxid FROM Customer";
                                    $result = $conn->query($sql);
                                    if($result->num_rows > 0){
                                        if(($row = $result->fetch_assoc())["maxid"] > $last_id){
                                            echo "<div class = 'catfooter'>";
                                            if($_GET['action'] == 'delete'){
                                                echo "<a class = 'Morebtn' href = 'Customer.php?action=delete&id=".$last_id."'>More</a>";
                                            }
                                            else{
                                                echo "<a class = 'Morebtn' href = 'Customer.php?action=update&id=".$last_id."'>More</a>";
                                            }
                                            echo "</div>";
                                        }
                                    }           
                                }
                            }
                        }
                    ?>
                </div>
                <div class = "column side"></div>
            </div>
        </div>
    </body>
    <script>
        // When the user scrolls the page, execute myFunction
        window.onscroll = function() {
            NavStick();
            var dropdowns = document.getElementsByClassName("dd-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        };

        // Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
        function NavStick() {
            if (window.pageYOffset >= sticky) {
                navbar.classList.add("sticky")
            } else {
                navbar.classList.remove("sticky");
            }
        } 
        /* When the user clicks on the button, 
        toggle between hiding and showing the dropdown content */
        function GenreDD() {
            document.getElementById("GenresDD").classList.toggle("show");
        }
        function AccDD(){
            document.getElementById("Acc").classList.toggle("show");
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
            if (!event.target.matches('.btn')) {
                var dropdowns = document.getElementsByClassName("dd-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
            if (event.target == modal) {
                modal.style.display = "none";
            }
            if (event.target == modal2) {
                modal2.style.display = "none";
            }
        }
    </script>
</html