<?php

session_start();

if(!isset($_SESSION["validation"]))
{
    header("Location: Login.php");
    exit( );
}

// Define active menu item
$homeActive = "";
$courseSelection = "";
$currentRegistration = 'class="active"'; // Home menu item is active
$logIn = "";

// Define menu Log In / Log Out title
$logStatus ="Log Out";

$jsFileName = "reg_delete.js";
include ("./Common/Header.php");

$name = $_SESSION["name"];
$studentId = $_SESSION["studentId"];
$courseErrorMsg = "";
$studentHaveRecords = false;


// handling and saving selected courses
if (isset($_POST["submit"])) {
    $studentId = $_SESSION["studentId"];
    
    // Selected Course validation
    if (!isset($_POST["courseId"])) {
        $courseErrorMsg = "<b>At least one course must be selected!</b>";
    } else {
        
        //passed validation
        $courseId = $_POST["courseId"];
        
        // WORKING WITH DATABASE
        // Get semester dropdown list
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

        // PDO Prepared Statement to prevent SQL Injection Attack ------------
        foreach ($courseId as $courseCode) {
            $sql = "DELETE FROM Registration WHERE studentId = :studentId AND courseCode = :courseCode";
            $pStmt = $pdo -> prepare($sql);
            $pStmt -> bindParam(':studentId', $studentId);
            $pStmt -> bindParam(':courseCode', $courseCode);

            $pStmt -> execute();
        }
        $pdo = null;
        //header("Location: CourseSelection.php");
        //exit();
   
    // temporary code - debugging - remove! ---------
    // echo "courseId - " . $courseId["0"] . "  ";
    // echo "student id - " . $studentId . "  ";
    // ----------------------------------------------
    }
}

?>

<div class="col-bg-10" style="min-height: calc(100vh - 70px); margin: 20px 50px 30px 80px; ">
    <h1>Current Registration </h1>
        <div>
            <p class="lead">Hello, <b><?php echo $name?></b>. (Not you? Change user <a href="Login.php">here</a>)
               The following are your current registration:</p>
        </div>
  
    <form <?php echo "action=\"$_SERVER[PHP_SELF]\""?> method="POST">
        
        
            <table class="table">
                <tr>
                    <th>Year</th>
                    <th>Term</th>
                    <th>Course Code</th>
                    <th>Course Title</th>
                    <th>Hours</th>
                    <th>Select</th>
                </tr>               
                
                <?php
                $SemesterCodes = [];

                // WORKING WITH DATABASE
                // Get semester dropdown list
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
                
                
                
                if ($pdo != null) {
                    try {
                            
                       
                        $SemesterCodes = [];
                        $sql = "select * from Semester";
                        $semesterSet = $pdo->query($sql);
                        
                        if ($semesterSet -> rowCount() > 0) {
                            $idIndex = 0; // to set id property for each element of checkbox
                            foreach ($semesterSet as $row) {
                                
                                $weeklyHours = 0;
                                $sql = <<<SQL_Query

                                Select s.Year, s.Term, r.CourseCode, c.Title, c.WeeklyHours from Registration as r
                                left join course as c on r.CourseCode = c.CourseCode
                                left join semester as s on r.SemesterCode = s.SemesterCode
                                where r.studentId = "$studentId" and r.SemesterCode = $row[SemesterCode]
                                SQL_Query;
                                        $resultSet = $pdo -> query($sql);
                                        
                                        // checking if query is empty
                                        if ($resultSet -> rowCount() > 0) {
                                            $studentHaveRecords = true;
                                            
                                            foreach ($resultSet as $row) {
                                                $weeklyHours += $row["WeeklyHours"];
                                                // $courseErrorMsg = "";
                                                // select courses from previous input
                                                $checked = "";
                                                if (isset($courseId) && $courseErrorMsg != "") {
                                                    foreach($courseId as $item) {
                                                        if ($item === $row["CourseCode"]) {
                                                            $checked = "checked";
                                                        } 
                                                    }
                                                }

                                            print <<<HTML
                                    <tr>
                                        <td>{$row["Year"]}</td>
                                        <td>{$row["Term"]}</td>
                                        <td>{$row["CourseCode"]}</td>
                                        <td>{$row["Title"]}</td>
                                        <td>{$row["WeeklyHours"]}</td>
                                        <td><input type="checkbox" name="courseId[]" id="courseId_$idIndex" $checked value="{$row['CourseCode']}"></td>
                                    </tr>
                            HTML;
                                    $idIndex++;
                                    }
                                    
                                    
                                    if ($weeklyHours != 0) {
                                        echo "<tr class='danger'> <td /><td /><td />";
                                        echo "<td class='text-right'><b>Total Weekly Hours:<b>";
                                        echo "<td><b>$weeklyHours</b></td><td /></tr>";
                                    }                                            
                                    }
                                }
                                if ($studentHaveRecords === false) {
                                    $courseErrorMsg = "<b>You do not have any registrations yet. Click <a href='CourseSelection.php'> here </a> for registration.<b>";
                                }
                               
                            } else {
                                $courseErrorMsg = "There are not semesters yet...";
                            }
                            $pdo = null;
                        } catch (\Throwable $th) {
                            echo $th->getMessage();
                        }
                    }
                ?>
            </table>
    <div class="row">
        <div class="col-sm-9 text-warning"> <?php echo $courseErrorMsg ?></div>
        <div class="col-sm-1"><input class="btn btn-primary" type="submit" name="submit" id="delete" value="Delete Selected"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-1"><input class="btn btn-primary" type="reset" value="Clear"></div>
    </form>
    </div>
            <div class="row"></div>
</div>
    
    
    
    
</div>

<?php include ("./Common/Footer.php"); ?>
