<?php
    // General Values
    $normalized_style_sheet = "normalize-fwd.css";
    $style_sheet            = "styles.css";
    // Assignment 02
    $currentAnteMeridiem    = date("A");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Project - PHP FWD Web Scripting 2</title>

    <?php
       echo "
        <link rel='stylesheet' href='styles/$normalized_style_sheet'>
        <link rel='stylesheet' href='styles/$style_sheet'>
       ";
    ?> 
     <!-- <link rel='stylesheet' href='styles/styles.css'> -->

</head>
<body>
<div id="wrapper">
    <header class="page-header">

    <?php

        if ($currentAnteMeridiem == "AM") {
            $headerImg = "
                <h1 class='sun'>Project - FWD Web Scripting 2</h1>
                <img src='images/sun.jpeg' alt='sun'>";
        } else {
            $headerImg = "
                <h1 class='moon'>Project - FWD Web Scripting 2</h1>
                <img src='images/moon.jpeg' alt='moon'>";
        }
        echo "$headerImg";
    ?>


    </header>
    <main>          
        <section>
        <h1>Administering DB From a Form</h1>
        <h2>Sub title here</h2>

        </section>       
    </main>
    <footer>

    </footer>
</div>    
</body>
</html>