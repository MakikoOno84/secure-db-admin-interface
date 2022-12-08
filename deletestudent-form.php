<!-- deletestudent-form.php -->
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
        <h2>Delete a student...</h2>

        <?php
        require_once("functions.php");
        $studentNumber = "";
        $firstname = "";
        $lastname = "";
        $studentNumber = "";
        $firstname = "";
        $lastname = "";

        session_start();
        $_SESSION['transactiontype']="delete";

        // Input Checking Phase
        if (isset($_GET['id'])) {
            if (empty($_GET['id'])) {
                die("<p class='bad'>Student Number is empty.</p>");
            }
            if (checkStudentNumber($_GET['id'])==false) {
                die("<p class='bad'>The pattern of Student Number is invalid.</p>");
            }
            $studentNumber = trim($_GET['id']);
        } else {
            die("<p class='bad'>Student Number is not set.</p>");
        }

        
        // 
        // create db connection
        require_once("dbconnect.php");
        // avoid sql injection
        $studentNumber = $mysqli->real_escape_string($studentNumber);
        // create and run query
        $query = "SELECT primary_key, id, firstname, lastname FROM students where id = '$studentNumber';";
        $result = $mysqli->query($query);
        $record = $result->fetch_assoc();

        $_SESSION['primary_key'] = $record['primary_key'];
        $_SESSION['studentnumber'] = $record['id'];
        $_SESSION['firstname'] = $record['firstname'];
        $_SESSION['lastname'] = $record['lastname'];
        $studentNumber = $record['id'];
        $firstname = $record['firstname'];
        $lastname = $record['lastname'];

        ?>
        <form action="form-processor.php" method="post">
            <fieldset>
                <div>
                    <legend>delete a record - Are you sure?</legend>
                    <?php
                        echo "<p>$studentNumber $firstname $lastname</p>";
                    ?>
                    <div>
                    <input type="radio" name="delete" value="yes" id="yes">
                    <label for="yes">Yes</label>
                    </div>
                    <div>
                    <input type="radio" name="delete" value="no" id="no" checked="checked">
                    <label for="no">No</label>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <input type="submit" value="Submit" />
            </fieldset>
        </form>



        </section>       
    </main>
    <footer>

    </footer>
</div>    
</body>
</html>