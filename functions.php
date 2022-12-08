<!-- functtions.php -->
<?php
// Student Number Format Check Function
function checkStudentNumber ($inputStudentNumber) {
    $pattern = "/^A00[0-9]{6}$/";
    if (preg_match($pattern, $inputStudentNumber) ) {
        return true;
    } else {
        return false;
        
    }
}

function clearSessionVar() {
    /* Also you can use 
        $_SESSION = array();
        to unset all session variables.
        See more details on session08/code/02-sessions-overview.php
    */ 
    if (isset($_SESSION['primary_key'])) {
        unset($_SESSION['primary_key']);
    }
    if (isset($_SESSION['studentnumber'])) {
        unset($_SESSION['studentnumber']);
    }
    if (isset($_SESSION['firstname'])) {
        unset($_SESSION['firstname']);
    }
    if (isset($_SESSION['lastname'])) {
        unset($_SESSION['lastname']);
    }
    if (isset($_SESSION['transactiontype'])) {
        unset($_SESSION['transactiontype']);
    }
            
}
?>