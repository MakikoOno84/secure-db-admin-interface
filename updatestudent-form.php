<!-- updatestudent-form.php -->
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
        <h2>Update a student...</h2>

        <?php
        require_once("functions.php");
        $studentNumber = "";
        $firstname = "";
        $lastname = "";
        $studentNumberBeforeUpdate = "";
        $firstnameBeforeUpdate = "";
        $lastnameBeforeUpdate = "";

        session_start();
        // Specify the type of transaction
        $_SESSION['transactiontype']="update";
        // $_SESSION['transactiontype']="";

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
        $studentNumberBeforeUpdate = $record['id'];
        $firstnameBeforeUpdate = $record['firstname'];
        $lastnameBeforeUpdate = $record['lastname'];

        ?>
        <form action="form-processor.php" method="post">
            <fieldset>
                <div>
                    <input type="text" id="studentnumber" name="studentnumber" value="<?php echo $studentNumberBeforeUpdate; ?>">
                    <label for="studentnumber">Student #</label>
                </div>
                <div>
                    <input type="text" id="firstname" name="firstname" value="<?php echo $firstnameBeforeUpdate; ?>">
                    <label for="firstname" value=>Firstname</label>
                </div>
                <div>
                    <input type="text" id="lastname" name="lastname" value="<?php echo $lastnameBeforeUpdate; ?>">
                    <label for="lastname">Lastname</label>
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