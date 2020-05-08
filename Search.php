<?php 
    session_start();
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    
    $servername = 'localhost';
    $username = 'id13616247_grishakirpa';
    $password = 'E3yn}oCd?St>6v$y';
    $dbname = 'id13616247_Sakila';
    $last_id = 0;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if($conn->connect_error){
        die("Connection failed: ".$conn->connect_error);
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $_SESSION['logged'] = 0;
        if (!(empty($_POST["username"]) && !empty($_POST["password"]))) {
            $data1 = $_POST["username"];
            $data2 = $_POST["password"];
            $data1 = test_input($data1);
            $data2 = test_input($data2);
            $sql = "SELECT Staff_id, Store_id, First_Name, Last_Name FROM staff WHERE Username = '".$data1."' AND Password = '".$data2."'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $User = $result->fetch_assoc();
                $_SESSION['fname'] = $User["First_Name"];
                $_SESSION['lname'] = $User["Last_Name"];
                $_SESSION['logged'] = 1;
                $_SESSION['id'] = $User["Staff_id"];
                $_SESSION['storeid'] = $User["Store_id"];
            }
        }
    }
    if(!empty($_GET['logout'])){
        unset($_SESSION['logged']);
    }
    if(!empty($_SESSION['logged']) && $_SESSION['logged'] == 1){
        $staffid = $_SESSION['id'];
        $fname = $_SESSION['fname'];
        $lname = $_SESSION['lname'];
        
    }
    else{
        $staffid = $fname = $lname = "";
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
            <form action = "Check.php" method = "get">
                <span class = "Logo">
                    <img id = "Logo" src = "Logo.png" width = "50px" height = "39px" alt = "Logo"></img>
                    <span id = "LogoLabel">NottGoingToWork</span>
                </span>
                <span>
                    <input size = "20" id = "search" type = "text" placeholder = "Search" autocomplete = "off" name = "search">
                </span>
            </form>
            <?php 
                if(!empty($_SESSION['logged']) && $_SESSION['logged'] == 1){
                    echo "<div class = dd>";
                }
            ?>
            <button id = 'loginbtn' onclick="document.getElementById('id01').style.display='block'" class = "btn">Sign in</button>
            <?php 
                if(!empty($_SESSION['logged']) && $_SESSION['logged'] == 1){
                    echo "<div class = 'dd-content' id = 'Acc'>
                            <a href = 'Customer.php?action=add'>Add Customer</a>
                            <a href = 'Customer.php?action=delete'>Delete Customer</a>
                            <a href = 'Customer.php?action=update'>Update Customer</a>
                            <a href = 'Search.php?search=".$_GET['search']."&id=".$last_id."&logout=1'>Log out</a>
                        </div>
                    </div>";
                }
            ?>
        </div>
        
        <div id="id01" class="modal">
    
            <form class="modal-content animate" action="" method="post">
                <div class="imgcontainer">
                    <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
                    <img src="Logo.png" alt="Avatar" class="avatar">
                </div>
            
                <div class="logincontainer">
                    <label for="username"><b>Username</b></label>
                    <input type="text" placeholder="Enter Username" name="username" required>
            
                    <label for="password"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="password" required>
                    
                    <button type="submit">Login</button>
                </div>
            
                <div class="logincontainer" style="background-color:#f1f1f1">
                    <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
                </div>
            </form>
        </div>
        <?php
            if(!empty($_SESSION['logged'])){
                if($_SESSION['logged'] == 1){
                    echo "<script type = 'text/JavaScript'>
                        var element = document.getElementById('loginbtn');
                        element.innerText = '".$fname." ".$lname."';
                        element.style.color = 'white';
                        element.style.backgroundColor = 'green';
                        element.style.fontSize = '14px';
                        element.style.width = '100px';
                        element.style.left = '90%';
                        element.onclick = function(){AccDD();};
                    </script>";
                }
                else{
                    echo "<script type = 'text/JavaScript'>
                            document.getElementById('id01').style.display='block'
                            alert('Incorrect Password or Username')
                        </script>";
                    
                        
                }
            }
        ?>
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
        <div  class = "content">
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
                <section class = "column middle">
                    <?php
                        if(empty($_GET['id'])){
                            $sql = "SELECT DISTINCT Film_id, Title, Rental_Rate, Length FROM film WHERE Title LIKE '".$_GET['search']."%' LIMIT 80";
                        }
                        else{
                            $sql = "SELECT DISTINCT Film_id, Title, Rental_Rate, Length FROM film WHERE Title LIKE '".$_GET['search']."%' AND Film_id > ".$_GET['id']." LIMIT 80";
                        }
                        $result = $conn->query($sql);
                        if($result->num_rows > 0){
                            echo "<div class = 'Catheader'>";
                                echo "<h2 style ='float:left;min-width:100px;'>Movies</h2>";
                            echo "</div>";
                            echo "<div class = 'Catbody'>";
                                while($row2 = $result->fetch_assoc()){
                                    echo "<div class='card-view'>";
                                        echo "<div class='card-movie-content'>";
                                            echo "<div class='card-movie-content-head'>";
                                                echo "<a href='#'>";
                                                    echo "<h3 class='card-movie-title'>".$row2['Title']."</h3>";
                                                echo "</a>";
                                            echo "</div>";
                                            echo "<div class='card-movie-info'>";
                                                echo "<div class='movie-running-time'>";
                                                    echo "<label>Running time</label>";
                                    $hours = floor($row2["Length"] / 60);
                                    $mins = $row2["Length"] - ($hours * 60);
                                                    echo "<span>".$hours."hr".$mins."min</span>";
                                                echo "</div>";
                                                echo "<div class='movie-running-time'>";
                                                    echo "<label>Price</label>";
                                                    echo "<span class='movie-price'>".$row2["Rental_Rate"]." MYR</span>";
                                                echo "</div>";
                                            echo "</div>";
                                        echo "</div>";
                                    echo "</div>";
                                    $last_id = $row2["Film_id"]; 
                                }
                            echo "</div>";
                            $sql = "SELECT MAX(Film_id) AS maxid FROM film WHERE Title LIKE '".$_GET['search']."%'";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0){
                                if(($row = $result->fetch_assoc())["maxid"] > $last_id){
                                    echo "<div class = 'catfooter'>";
                                        echo "<a class = 'Morebtn' href = 'Search.php?search=".$_GET['search']."&id=".$last_id."'>More</a>";
                                    echo "</div>";
                                }
                            }
                        }
                        else{
                            echo "<h2>No results were found</h2>";
                        }
                    ?>  
                </section>
                <div class = "column side"></div>
            </div>
        </div>
    </body>
    <script>
        // When the user scrolls the page, execute myFunction
        window.onscroll = function() {NavStick()};

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
</html>