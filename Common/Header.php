<!DOCTYPE html>
<html lang="en" style="position: relative; min-height: 100%;">
<head>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.6/dist/css/bootstrap.min.css">
        <meta name="author" content="Aleksandr Tselikovskii">
        <meta name="email" content="tsel0003@algonquinlive.com">
        <script src="./Common/scripts/jquery-3.6.0.min.js" defer></script>
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>-->
        <?php
        // get appropriate JavaScript file for current page
        if (isset($jsFileName)) {
            echo "<script src='./Common/scripts/$jsFileName' defer></script>";
        }
        ?>
	<title>Student Registration System</title>

</head>
<body style="padding-top: 50px; margin-bottom: 60px;">
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" style="padding: 10px" href="http://www.algonquincollege.com">
              <img src="Common/AC.png"
                   alt="Algonquin College" style="max-width:100%; max-height:100%;"/>
          </a>    
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li <?php echo $homeActive ?> ><a href="Index.php">Home</a></li>
            <li <?php echo $courseSelection ?> ><a href="CourseSelection.php">Course Selection</a></li>
            <li <?php echo $currentRegistration ?> > <a href="CurrentRegistration.php">Current Registration</a></li>
            <li <?php echo $logIn ?> ><a href="Login.php"><?php echo $logStatus ?></a></li>
        </div>
      </div>  
    </nav>
