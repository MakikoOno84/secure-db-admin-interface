<!-- addstudent.php -->
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
        <h2>Add a student...</h2>

        <?php
        session_start();
        // Specify the type of transaction
        $_SESSION['transactiontype']="insert";
        ?>

        <form action="form-processor.php" method="post">
            <fieldset>
                <div>
                    <input type="text" id="studentnumber" name="studentnumber">
                    <label for="studentnumber">Student #</label>
                </div>
                <div>
                    <input type="text" id="firstname" name="firstname">
                    <label for="firstname">Firstname</label>
                </div>
                <div>
                    <input type="text" id="lastname" name="lastname">
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