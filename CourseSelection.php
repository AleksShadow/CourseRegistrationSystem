<?php

session_start();

if(!isset($_SESSION["validation"]))
{
    header("Location: Login.php");
    exit( );
}

// Define active menu item
$homeActive = "";
$courseSelection = 'class="active"'; // Home menu item is active
$currentRegistration = "";
$logIn = "";

// Define menu Log In / Log Out title
$logStatus ="Log Out";

$jsFileName = "courses_select.js";
include ("./Common/Header.php");

$name = $_SESSION["name"];
$studentId = $_SESSION["studentId"];
$courseErrorMsg = "";
$totalWeeklyHours = 16;
$regWeeklyHours = 0;


$SemesterSelected = "";
if (isset($_GET["SemesterSelected"])) {
    $SemesterSelected = $_GET['SemesterSelected'];
}

if (isset($_POST["SelSemester"])) {
    $SelSemester = $_POST["SelSemester"];
}

                        // getting Weekly hours for selected semester
                        // WORKING WITH DATABASE
                         // Get semester dropdown list
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
                        
                        // define selected semester from drop down list
                        if (isset($SelSemester)) {
                            $semesterCode = $SelSemester; // user pressed submit button
                        } elseif ($SemesterSelected) {
                            $semesterCode = $SemesterSelected; // user chose/changed semester from the list
                        } else {
                            $semesterCode = '01'; // default: user get access to this page
                        }
                      
                        $sql = "Select sum(c.WeeklyHours) as WeeklyHours from registration as r ";
                        $sql .= "left join course as c On c.CourseCode = r.CourseCode ";
                        $sql .= "where r.studentId = '$studentId' and r.SemesterCode = '$semesterCode'";
                        $resultSet = $pdo->query($sql);
                        $result = $resultSet->fetch(PDO::FETCH_ASSOC);

                        if ($result['WeeklyHours'] != null) {
                            $regWeeklyHours = $result['WeeklyHours'];
                        }
                        
// handling and saving selected courses
if (isset($_POST["submit"])) {
    
    // Selected Course validation
    if (!isset($_POST["courseId"])) {
        $courseErrorMsg = "At least one course must be selected!";
    } else {

        $courseId = $_POST["courseId"];
        
        // form string for sql request to get sum of Weekly Hours for selected courses
        $selCoursesList = "";
        foreach ($courseId as $id) {
            $selCoursesList .= "'$id', ";
        }
        $selCoursesList = trim($selCoursesList, ", ");

        // ----------------------------------------------------------------------------------
        // $totalWeeklyHours - $regWeeklyHours
        
        
        
                        //Count selected hours
                        // WORKING WITH DATABASE
       
        
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
                         
                        $sql = "Select sum(WeeklyHours) as WeeklyHours from course ";
                        $sql .= "where CourseCode in ($selCoursesList)";
                        
                        $resultSet = $pdo->query($sql);
                        $result = $resultSet->fetch(PDO::FETCH_ASSOC);
                        $selWeeklyHoursTotal = $result['WeeklyHours'];
                        
                        $pdo = null;
                        
                        
                        if (($totalWeeklyHours - $regWeeklyHours - $selWeeklyHoursTotal) < 0) {
                            $courseErrorMsg = "Your selection exeed the max weekly hours";
                        } else {
        
        // ----------------------------------------------------------------------------------
        //passed validation - inserting the new records into db
        
        // WORKING WITH DATABASE
        // Get semester dropdown list
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
        foreach ($courseId as $courseCode) {
            $sql = "INSERT INTO Registration VALUES (:studentId,:courseCode,:semesterCode)";
            $pStmt = $pdo -> prepare($sql);
            $pStmt -> bindParam(':studentId', $studentId);
            $pStmt -> bindParam(':courseCode', $courseCode);
            $pStmt -> bindParam(':semesterCode', $SelSemester);
            $pStmt -> execute();
        }
        $pdo = null;
        $regWeeklyHours += $selWeeklyHoursTotal;
        //header("Location: CourseSelection.php");
        //exit();

    }
    
    } 
   
}

?>

<div class="col-bg-10" style="min-height: calc(100vh - 70px); margin: 20px 50px 30px 80px; ">
    <h1>Course Selection </h1>
        <div>
            <p class="lead">Welcome, <b><?php echo $name?></b>. (Not you? Change user <a href="Login.php">here</a>)</p>
            <p>You have registered <b><?php echo $regWeeklyHours?></b> hours for the selected semester.</p>
            <p>You can register <b><?php echo $totalWeeklyHours - $regWeeklyHours ?></b> more hours of course(s) for the semester.</p>
            <p>Please, note that the courses you have registered will not be displayed in the list</p>
        </div>
    
        <div class="row">
            <div class="col-sm-9"></div>
                <div class="col-sm-3">
                    <select class="form-control" name="Semester" id="Semester">
                        <?php
                        
                        //$SemesterCodes = [];

                        // WORKING WITH DATABASE
                        // Get semester dropdown list
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

                        if ($pdo != null) {
                            
                            if (isset($SelSemester)) {
                                $SemesterSelected = $SelSemester;
                            }
                            
                            
                            $SemesterCodes = [];
                            $sql = "select * from Semester";
                            $semesterSet = $pdo->query($sql);
                            foreach ($semesterSet as $row) {
                                $SemesterCodes[] = $row['SemesterCode'];
                                $selected = ($SemesterSelected == $row['SemesterCode']) ? "selected" : "";
                                echo "<option value='" . $row['SemesterCode'] . "' " . $selected . ">" . $row['Year'] . " " . $row['Term'] . "</option>";
                            }
                            $_SESSION["SemesterSelected"] = $SemesterSelected;
                            // echo $SemesterCodes[0];
                        }
                        ?>
                    </select>
                </div>
        </div><br>
    
    <form <?php echo "action=\"$_SERVER[PHP_SELF]\""?> method="POST">
        
        <?php $SemesterSelected = $_SESSION["SemesterSelected"]; ?>
        <?php $SelSemester = ($SemesterSelected != "") ? $SemesterSelected : $SemesterCodes[0]; ?>
            <input type="hidden" name="SelSemester" value="<?php echo $SelSemester; ?>">
            <table class="table">
                <tr>
                    <th>Code</th>
                    <th>Course Title</th>
                    <th>Hours</th>
                    <th>Select</th>
                </tr>               
                
                <?php
                if ($pdo != null) {
                    try {
                        $sql = <<<SQL_Query
                Select co.CourseCode, Title, WeeklyHours, co.SemesterCode from CourseOffer as co 
                left join Course as c on co.CourseCode = c.CourseCode 
                left join Semester as s on s.SemesterCode = co.SemesterCode
                SQL_Query;

                        $sql .= " where s.SemesterCode='";
                        $semesterCode = ($SemesterSelected != "") ? $SemesterSelected : $SemesterCodes[0];                        
                        $sql .= "$semesterCode' and co.courseCode NOT IN (select courseCode from registration ";
                        $sql .= "where studentId = '$studentId' and semesterCode = '$semesterCode')";
                       
                        
                        $resultSet = $pdo->query($sql);

                        if ($resultSet -> rowCount() > 0) {
                            $idIndex = 0; // index is for course id in html ID property
                            foreach ($resultSet as $row) {

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
                            <td>{$row["CourseCode"]}</td>
                            <td>{$row["Title"]}</td>
                            <td>{$row["WeeklyHours"]}</td>
                            <td><input type="checkbox" name="courseId[]" id="courseId_$idIndex" $checked value="{$row['CourseCode']}"></td>
                        </tr>
                HTML;
                            $idIndex++;
                        }
                        
                       
                    } else {
                        $courseErrorMsg = "<b>You have registered for all courses for this term. Try the other terms please.<b>";
                    }
                    $pdo = null;
                    
                    } catch (\Throwable $th) {
                        echo $th->getMessage();
                    }
                }
                ?>
            </table>
    <div class="row">
        <div class="col-sm-9 text-warning" id="errorMsg"><b><?php echo $courseErrorMsg ?></b></div>
        <div class="col-sm-1"><input class="btn btn-primary" type="submit" name="submit" value="Submit"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-1"><input class="btn btn-primary" type="reset" value="Clear"></div>
    </form>
    </div>    
</div>

<?php include ("./Common/Footer.php"); ?>