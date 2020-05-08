<!DOCTYPE html>
<html lang = "en">
    <head>
        <title>NottGoingToWork</title>
        <link rel = "icon" href = "Logo.png" type = "image/x-icon">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel = "stylesheet" type = "text/css" href = "index.css">
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
            }
        </script>
    </head>
    <body>
    <?php
            $servername = 'localhost';
            $username = 'root';
            $password = 'Greg@135';
            $dbname = 'Sakila';

            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error){
                die("Connection failed: ".$conn->connect_error);
            }
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
            <form>
                <span class = "Logo">
                    <img id = "Logo" src = "Logo.png" width = "50px" height = "39px" alt = "Logo"></img>
                    <span id = "LogoLabel">NottGoingToWork</span>
                </span>
                <span>
                    <input size = "20" id = "search" type = "text" placeholder = "Search" autocomplete = "off">
                </span>
            </form>
            <button class = "btn">Sign in</button>
        </div>
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
                    <li><a href="Locator.php" class = "active">Find Us</a></li>
                </ul>
            </nav>
        </header>
        <div  class = "content">
            <script>
                // Get the navbar
                var navbar = document.getElementById("navbar");
                // Get the offset position of the navbar
                var sticky = navbar.offsetTop;
            </script>
            <div class = "row">
                <div class = "column side"></div>

                <section class = "column middle">
                <div id="googleMap" style="width:100%;height:400px;"></div>

                <script>
                    function myMap() {
                        var mapProp= {
                            center:new google.maps.LatLng(51.508742,-0.120850),
                            zoom:5,
                        };
                        var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
                    }
                    </script>

                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA8N8_xwmJ5ElwZxGNUi6iZ5vzRItj0wSs&callback=myMap"></script>
                </section>

                <div class = "column side"></div>
        </div>
    <body>
</html>