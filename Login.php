<?php

session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE);

if(isset($_SESSION["validation"]))
{
    header("Location: Logout.php");
    exit( );
}

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

function ValidatePass($pin1)
{
    $errorMsg = "";
    if ($pin1 == "")
    {
        $errorMsg = "<b>Password</b> cannot be blank";
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
 

$studentIdErrorMsg = "";
$pin1ErrorMsg = "";
$pinErrorMsg = "";

if (isset($_POST["submit"]))
{
    extract($_POST);
    

    $formValidation = true;
    $studentIdErrorMsg = ValidateID($studentId);
    $pin1ErrorMsg = ValidatePass($pin1);
    
    if ($studentIdErrorMsg != "" || $pin1ErrorMsg != "")
    {
        $formValidation = false;
    }

    // saving all values from the form to session
    $_SESSION["studentId"] = $studentId;
    
    if ($formValidation == true)
    {
            // WORKING WITH DATABASE
            // Check user name and password from database
            $dbConnection = parse_ini_file("Project.ini");
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
            $sql = "SELECT studentId, name, password FROM Student
                    WHERE StudentId = :studentId
                    LIMIT 1";
            $pStmt = $pdo -> prepare($sql);
            $pStmt -> execute(['studentId' => $studentId]);

            $row = $pStmt->fetch(PDO::FETCH_ASSOC);
                        

            if ($row == false || !password_verify($pin1, $row["password"])) {
                $pinErrorMsg = "Incorrect student ID and/or password!";
            } else {
                $_SESSION["validation"] = $formValidation;
                $_SESSION["StudentId"] = $StudentId;
                $_SESSION["name"] = $row["name"];
                header("Location: CourseSelection.php");
                exit();
            }
    }
}


?>

<div class="container">
    
    <div class="row"><h1 class="col-md-5 text-center">Log In </h1></div>
    <div><p class="text-warning">You need to <a href="NewUser.php">sign up</a> if you a new user now</p></div><br>
    
    
    <form <?php echo "action=\"$_SERVER[PHP_SELF]\""?> method="POST">
        
        <div class="row"><span class="col-md-3 text-danger" id="pinErrorMsg"><?php echo $pinErrorMsg; ?></span></div><br>

        <div class="row">
            <label class="col-md-2" for="studentId">Student ID:</label>
            <div class="col-md-3">
                <input class="form-control" type="text" id="studentId" name="studentId" value="<?php echo $studentId; ?>">
            </div>
            <span class="col-md-3 text-danger" id="studentIdErrorMsg"><?php echo $studentIdErrorMsg; ?></span>
        </div><br>

        <div class="row" style="margin-top: 10px;">
            <label class="col-md-2" for="pin1">Password:</label>
            <div class="col-md-3">
                <input class="form-control" type="password" id="pin1" name="pin1" size='30' />
            </div>
            <span class="col-md-7 text-danger" id="pin1ErrorMsg"> <?php echo $pin1ErrorMsg; ?> </span>
        </div><br>
        
        <div class="row form-group">
            <div class="col-md-2"></div>
            <div class="col-md-1"><input class="btn btn-primary" name="submit" type="submit" value="Submit" /></div>
            <div class="col-md-1"><input class="btn btn-primary" name="btnReset" type="submit" value="Clear" /></div>
        </div>
    </form>
    

</div>

<?php include ("./Common/Footer.php"); ?>
