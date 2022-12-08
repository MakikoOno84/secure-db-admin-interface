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
    <title>Secure Database Admin Interface</title>

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
                <h1 class='sun'>Secure Database Admin Interface</h1>
                <img src='images/sun.jpeg' alt='sun'>";
        } else {
            $headerImg = "
                <h1 class='moon'>Secure Database Admin Interface</h1>
                <img src='images/moon.jpeg' alt='moon'>";
        }
        echo "$headerImg";
    ?>


    </header>
    <main>          
        <section>
            <!-- <h1>Secure Database Admin Interface</h1> -->
            <p>This project by : Makiko Ono</p>

            <!-- error messages -->
            
            <?php
            session_start();
            // error messages from form processor : input checking
            if (isset($_SESSION['messages'])) {
            foreach( $_SESSION['messages'] as $error) {
                echo $error;
            }
            unset($_SESSION['messages']);
            } 
            // error messages from form processor : db transaction
            if (isset($_SESSION['resultmessage'])) {
                echo $_SESSION['resultmessage'];
                unset($_SESSION['resultmessage']);
                } 

            ?>

            <a class='link-button' href="addstudent-form.php">Add a Student</a>
            
            <?php
                // create database connection
                require_once("dbconnect.php");

                $query = "SELECT id, firstname, lastname FROM students;";
                $result = $mysqli->query( $query );


                // create table
                echo "<table>";
                // get header
                $arrayOfFieldNames = $result->fetch_fields();
                echo "<tr>";
                foreach($arrayOfFieldNames as $oneFieldAsAnObject) {
                    echo "<th>".$oneFieldAsAnObject->name."</th>";
                }
                echo "</tr>";
                // get records
                while ($oneRecord = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach($oneRecord as $field) {
                        echo "<td>$field</td>";
                    }
                    // Add column for delete and update
                    echo "
                        <td><a href='deletestudent-form.php?id=".$oneRecord['id']."'>Delete</a></td>
                        <td><a href='updatestudent-form.php?id=".$oneRecord['id']."'>Update</a></td>
                        ";
                    echo "</tr>";
                }
                echo "</table>";

            ?>
        


        </section>       
    </main>
    <footer>

    </footer>
</div>    
</body>
</html>