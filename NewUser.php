<?php

session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Define Validation functions --------------------------------------------------
function ValidateID($id)
{
$id = trim($id);
$errorMsg = "";
if ($id === "")
    {
        $errorMsg = "<b>Your ID</b> cannot be blank";
    }
    return $errorMsg;
}

function ValidateName($name)
{
$name = trim($name);
$errorMsg = "";
if (is_numeric($name) || $name === "")
    {
        $errorMsg = "<b>Your Name</b> cannot be blank or numeric";
    }
    return $errorMsg;
}

function ValidatePhone($phone)
{
    $phoneRegex = "/^([2-9]\d\d)-([2-9][0-9][0-9])-(\d\d\d\d)$/";
    $errorMsg = "";
    if ($phone == "")
    {
        $errorMsg = "<b>Phone number</b> cannot be blank";
    } elseif (!preg_match($phoneRegex, $phone))
    {
        $errorMsg = "<b>Phone number</b> is incorrect";
    }
    return $errorMsg;
}

function ValidatePass($pin1, $pin2)
{
    $pinRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/";
    $errorMsg = "";
    if ($pin1 == "")
    {
        $errorMsg = "<b>Password</b> cannot be blank";
    } elseif (!preg_match($pinRegex, $pin1))
    {
        $errorMsg = "<b>Password</b> must be at least 6 characters long and contain one upper case, one lowercase character, a digit";
    } elseif ($pin1 != $pin2)
    {
        $errorMsg = "<b>Passwords</b> in both fields must match";
    }
    return $errorMsg;
}

// Define active menu item
$homeActive = "";
$courseSelection = "";
$currentRegistration = "";
$logIn = 'class="active"'; // Log In menu item is active

// Define menu Log In / Log Out title
if(isset($_SESSION["validation"]))
{
    $logStatus ="Log Out";
} else {
    $logStatus ="Log In";
}

$jsFileName = "login.js";
include ("./Common/Header.php");

if(isset($_POST["btnReset"]))
{
    // clear all values ------------------------
    foreach(array_keys(get_defined_vars()) as $strVarName)
    {
        unset(${$strVarName});
    }
    session_unset();
}

// getting Student ID session value
$studentId = "";
if(isset($_SESSION["studentId"]))
 {
    $studentId = $_SESSION["studentId"];
 }

// getting name session value
$name = "";
if(isset($_SESSION["name"]))
 {
    $name = $_SESSION["name"];
 }
 
 // getting phone number session value
$phoneNumber = "";
if(isset($_SESSION["phoneNumber"]))
 {
    $phoneNumber = $_SESSION["phoneNumber"];
 }
 

$studentIdErrorMsg = "";
$nameErrorMsg = "";
$phoneNumberErrorMsg = "";
$pin1ErrorMsg = "";


if (isset($_POST["submit"]))
{
    extract($_POST);
    
    $formValidation = true;
    $studentIdErrorMsg = ValidateID($studentId);
    $nameErrorMsg = ValidateName($name);
    $phoneNumberErrorMsg = ValidatePhone($phoneNumber);
    $pin1ErrorMsg = ValidatePass($pin1, $pin2);
    
    if ($studentIdErrorMsg != "" || $nameErrorMsg != ""
            || $phoneNumberErrorMsg != "" || $pin1ErrorMsg != "")
    {
        $formValidation = false;
    }

    // saving all values from the form to session
    $_SESSION["studentId"] = $studentId;
    $_SESSION["name"] = $name;
    $_SESSION["phoneNumber"] = $phoneNumber;   
   
    if ($formValidation == true)
    {
        // WORKING WITH DATABASE
        // Check Student Id from database
        $dbConnection = parse_ini_file("Lab6.ini");
        extract($dbConnection);

        // DB error handling
        $pdo_options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

        try {
            $pdo = new PDO($dsn, $user, $password, $pdo_options);

            if ($pdo) {
                //echo "<code> Got connection...</code>";
            }
        } catch(PDOException $e) {
            echo "<code>Error: </code>" . $e -> getMessage();
        }


        // PDO Prepared Statement to prevent SQL Injection Attack ------------
        $sql = "SELECT StudentId FROM Student
                WHERE StudentId = :studentId
                LIMIT 1";
        $pStmt = $pdo -> prepare($sql);
        $pStmt -> execute(['studentId' => $studentId]);

        $row = $pStmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $studentIdErrorMsg = "A student with this ID has already signed up";
            } else {
                $_SESSION["validation"] = $formValidation;

                $hash = password_hash($pin1, PASSWORD_ARGON2ID); // encrypt the password

                // PDO Prepared Statement to prevent SQL Injection Attack ------------
                $sql = "INSERT INTO Student VALUES (:studentId,:name,:phoneNumber,:hash)";
                $pStmt = $pdo -> prepare($sql);
                $pStmt -> bindParam(':studentId', $studentId);
                $pStmt -> bindParam(':name', $name);
                $pStmt -> bindParam(':phoneNumber', $phoneNumber);
                $pStmt -> bindParam(':hash', $hash);
                $pStmt -> execute();

                $pdo = null;
                header("Location: CourseSelection.php");
                exit();                    
            }
    }
    
    //elseif (isset($_SESSION["validation"]))
    //{
    //    unset($_SESSION["validation"]);
    //}
}

    
?>


<div class="container">
    <div class="row"><h1 class="col-md-5 text-center">Sign Up </h1></div>
    <div><p class="text-warning">All fields are required</p></div>
    
        <form <?php echo "action=\"$_SERVER[PHP_SELF]\""?> method="POST">

        <div class="row">
            <label class="col-md-2" for="studentId">Student ID:</label>
            <div class="col-md-3">
                <input class="form-control" type="text" id="studentId" name="studentId" value="<?php echo $studentId; ?>">
            </div>
            <span class="col-md-4 text-danger" id="studentIdErrorMsg"><?php echo $studentIdErrorMsg; ?></span>
        </div><br>
        
        
        <div class="row">
            <label class="col-md-2" for="name">Name:</label>
            <div class="col-md-3">
                <input class="form-control" type="text" id="name" name="name" value="<?php echo $name; ?>">
            </div>
            <span class="col-md-3 text-danger" id="nameErrorMsg"><?php echo $nameErrorMsg; ?></span>
        </div><br>

        <div class="row">
            <label class="col-md-2" for="phoneNumber">Phone Number:<br>
                <span ><em><small>(nnn-nnn-nnnn)</em></small></span></label>
            <div class="col-md-3">
                <input class="col-3 form-control justify-content-start" type="text" id="phoneNumber" name="phoneNumber" value="<?php echo $phoneNumber; ?>">
            </div>
            <span class="col-md-3 text-danger" id="phoneNumberErrorMsg"><?php echo $phoneNumberErrorMsg; ?></span>
        </div>

        <div class="row" style="margin-top: 10px;">
            <label class="col-md-2" for="pin1">Password:</label>
            <div class="col-md-3">
                <input class="form-control" type="password" id="pin1" name="pin1" size='30' />
            </div>
            <span class="col-md-7 text-danger" id="pin1ErrorMsg"> <?php echo $pin1ErrorMsg; ?> </span>
        </div><br>
        
        <div class="row">
            <label class="col-md-2" for="pin2">Password Again:</label>
            <div class="col-md-3">
                <input class="form-control" type="password" id="pin2" name="pin2" size='30' />
            </div>
            <span class="col-md-3 text-danger" id="pin2ErrorMsg"> <?php echo $pin2ErrorMsg; ?> </span>
        </div><br>

        <div class="row form-group">
            <div class="col-md-2"></div>
            <div class="col-md-1"><input class="btn btn-primary" name="submit" type="submit" value="Submit" /></div>
            <div class="col-md-1"><input class="btn btn-primary" name="btnReset" type="submit" value="Clear" /></div>
        </div>
    </form>
    
        
</div>

<?php include ("./Common/Footer.php"); ?>