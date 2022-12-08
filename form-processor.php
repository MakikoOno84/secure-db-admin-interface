<!-- 
    Internal Message Format:
        INFO: <message>
        WARN: <message>

    About This Script:
    This script consits of two phases
        - Input Checking Phase
            This phase will check all the input from form and also internal input such as transaction type.
        - DB Transaction Phase 
            This phase will run only if no errors found in previous phase. 
    Note:
        - DB connection close
            Since creating db connection is expensive operation,
            I try to avoid an unnecessary db connection close.
            Thus I decide if db connection close is needed in "Error Checking" phase in this script.
            If error(s) found, close the db connection and if not, keep the connection for the subsequence db transaction.
 -->
<?php

    require_once("functions.php");
    session_start();

    $errors = array();
    $primaryKey = "";
    $studentNumber = "";
    $firstname = "";
    $lastname = "";
    $transactiontype = "";
    $arrayOfTransactionType = ["insert","update","delete"];
    $resultmessage = ""; // feedback for db transaction status for users

    /* Input Checking Phase ------------------------------------
    */
    // check transaction type
    if (isset(($_SESSION['transactiontype']))==true && 
        empty($_SESSION['transactiontype'])==false && 
        in_array($_SESSION['transactiontype'], $arrayOfTransactionType)==true) 
    {
            $transactiontype = trim($_SESSION['transactiontype']);
    } else {
        die("<p>WARN: Transactiontype is invalid.</p>");
    }

    // Check form input
    if (in_array($transactiontype,array("insert","update"))) {
        if (isset($_POST['studentnumber']) && 
            isset($_POST['firstname']) && 
            isset($_POST['lastname'])
            ) {
            
            // form fields exist
            echo "<p>INFO: Form fields DO exist.</p>";
            $studentNumber = trim($_POST['studentnumber']);
            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST['lastname']);
            

            // For update and delete, check primary key 
            if ($transactiontype=="update") {
                if (isset($_SESSION['primary_key'])) {
                    $primaryKey = trim($_SESSION['primary_key']);
                } else {
                    die("<p>WARN: Primary Key is not set.</p>");
                }
            }

            // ensure fields are filled in
            if (empty($studentNumber)) {
                $errors[] = "<p class='bad'>Student Number cannot be empty.</p>";
            }

            if (empty($firstname)) {
                $errors[] = "<p class='bad'>Firstname cannot be empty.</p>";
            }
            if (empty($lastname)) {
                $errors[] = "<p class='bad'>Lastname cannot be empty.</p>";
            }

            if (checkStudentNumber($studentNumber)) {
                echo "<p>INFO: The pattern of Student Number is valid.<p>";
            } else {
                echo "<p>WARN: The pattern of Student Number is invalid.<p>";
                $errors[] = "<p class='bad'>The pattern of Student Number is invalid.</p>";
            }

            // Check if studntnumber already registered.
            // create db connection
            require_once("dbconnect.php");
            // avoid sql injection
            $studentNumber = $mysqli->real_escape_string($studentNumber);
            // create and run query
            if ($transactiontype=="update") {
                $query = "SELECT COUNT(1) FROM students WHERE id='$studentNumber' AND primary_key!='$primaryKey';";
            } else {
                $query = "SELECT COUNT(1) FROM students WHERE id='$studentNumber';";
            }
            $result = $mysqli->query($query);
            // fetch record and ensure if student id does not exist
            $record = $result->fetch_row();
            echo "<p>INFO: # of student with student id($studentNumber): $record[0]</p>";
            if ($record[0]!=0) {
                $errors[] = "<p class='bad'>The Student Number you entered is already taken.</p>";
            }
            /* About closing db connection
                See the note on the top of this script 
            */

        } else {
            // form fields don't exist
            $errors[] = "<p class='bad'>Please fill in the form</p>";
        }
    } else if ($transactiontype=="delete") {
        if (isset($_SESSION['primary_key']) &&
        isset($_SESSION['studentnumber']) &&
        isset($_SESSION['firstname']) &&
        isset($_SESSION['lastname']) 
        ) {
            $primaryKey = trim($_SESSION['primary_key']);
            $studentNumber = trim($_SESSION['studentnumber']);
            $firstname = trim($_SESSION['firstname']);
            $lastname = trim($_SESSION['lastname']);

            // ensure fields are filled in
            if (empty($primaryKey)) {
                echo "<p>WARN: Primary Key cannot be empty.</p>";
            }
            if (empty($studentNumber)) {
                echo "<p>WARN: Student Number cannot be empty.</p>";
            }

            if (empty($firstname)) {
                echo "<p>WARN: Firstname cannot be empty.</p>";
            }
            if (empty($lastname)) {
                echo "<p>WARN: Lastname cannot be empty.</p>";
            }

            if (isset($_POST['delete'])) {
                $delete = trim($_POST['delete']);
                if (empty($delete)) {
                    echo "<p>WARN: The radio button can not be empty.</p>";
                } else {
                    if (in_array($delete,array("yes","no"))==false) {
                        echo "<p>WARN: Invalid radio button value detected.</p>";
                    }
                    if ($delete=="no") {
                        // 
                        $_SESSION['resultmessage'] = "<p class='resultmessage bad'>Record Delete Canceled: $studentNumber $firstname $lastname </p>";
                        // clear session variables
                        clearSessionVar();
                        // redirect to index.php
                        header("location: index.php");
                        die();
                    }
                }
    
            } else {
                echo "<p>WARN: Please select the radio button.</p>";
            }

        } else {
            echo "<p>WARN: SESSION variables are not set.</p>";
        }



    }
    // Error Checking 
    // see if we found any errors during processing
    if (count($errors) > 0 ) {
        $_SESSION['messages'] = $errors;
        echo "<p>WARN: Your form has error(s).</p>";
        // close db connection
        $mysqli->close();
        // clear session variables
        clearSessionVar();
        // redirect to index.php
        header("location: index.php");
        die();
    } else {
        echo "<p>INFO: Your form has no error.</p>";

        /* DB Transaction Phase ------------------------------------
        */
        // create db connection if delete transaction
        if ($transactiontype=="delete") {
            require_once("dbconnect.php");
        }
        // avoid sql injection 
        $firstname = $mysqli->real_escape_string($firstname);
        $lastname = $mysqli->real_escape_string($lastname);
        if (in_array($transactiontype,array("update","delete"))) {
            $primaryKey = $mysqli->real_escape_string($primaryKey);
        }

        if ($transactiontype == "insert") {
            /* insert ------------------------------------
            */
            // create and run query
            $query = "INSERT INTO students (id, firstname, lastname) VALUES ('$studentNumber','$firstname','$lastname');";
            $result = $mysqli->query( $query );

        } else if ($transactiontype == "update") {
            /* update ------------------------------------
            */
            // create and run query
            // sample sql:
            // update students set id='A00123499', firstname='MAKIKO', lastname='ONO' where primary_key = 34;
            $query = "UPDATE students SET id='$studentNumber', firstname='$firstname', lastname='$lastname' where primary_key = $primaryKey;";
            $result = $mysqli->query( $query );

        } else if ($transactiontype == "delete") {
            /* delete ------------------------------------
            */
            $query = "DELETE FROM students WHERE primary_key = $primaryKey;";
            $result = $mysqli->query( $query );

        }
        // check query status
        if ($result==true) {
            echo "<p>INFO: Running a $transactiontype query returned 'true'. The most recenty run query was accepted by the database</p>";

            if ($transactiontype == "insert") {
                $resultmessage = "<p class='resultmessage'>Record Added: $studentNumber $firstname $lastname </p>";
            } else if ($transactiontype == "update") {
                $resultmessage = "<p class='resultmessage'>Record Updated: $studentNumber $firstname $lastname </p>";
            } else if ($transactiontype == "delete") {
                $resultmessage = "<p class='resultmessage'>Record Deleted: $studentNumber $firstname $lastname </p>";
            }

            $_SESSION['resultmessage'] = $resultmessage;

        } else {
            echo "<p>WARN: Running a $transactiontype query returned 'false'.</p>";
            $resultmessage = "<p class='resultmessage bad'>Something went wrong with the transaction. See the DB administrator.</p>";
            $_SESSION['resultmessage'] = $resultmessage;
        }

        // close db connection
        $mysqli->close();
        // clear session variables
        clearSessionVar();
        // redirect to index.php
        header("location: index.php");
        die();
    }
?>