<?php
    if(empty($_GET['search'])){
        $location = "index.php";
    }
    else{
        
        $location = "Search.php?search=".$_GET['search'];
    }
    header("Location: ".$location);
    die();
?>