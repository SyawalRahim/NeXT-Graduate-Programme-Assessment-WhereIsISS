<?php
	error_reporting(0);
	session_start();
	
	$_SESSION["a"]=$adata;
	$_SESSION["b"]=$bdata;
?>
<!DOCTYPE html>
<html lang="en">

  
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ISS Doko?</title>

    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">
  </head>

  <body id="page-top">
    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">
      <a class="navbar-brand mr-1" href="#">ISS Doko? - Look For ISS Location</a>
      <!-- Navbar -->
      
    </nav>
    <div id="wrapper">
      <div id="content-wrapper">
        <div class="container-fluid">
          <!-- Page Content -->

          <h1>Welcome</h1>

          <hr>
		<p>Enter the Date and Time to look for ISS location at that time.</p>
		
			<form method="post" action="search.php">
				<label for="appt">Select a date:</label>			
				<input type="date" id="birthday" name="date">
				<label for="appt">Select a time:</label>
				<input type="time" id="appt" name="appt">
				<input type="submit" value="Search">
			</form>
			<br>
        </div>
		<div class="col-md-12">
			<div class="panel panel-default">
                        <div class="panel-heading">
                             Search Results
                        </div>
					<div class="panel-body">
				<?php
			
			$bdata = $_SESSION["b"];
			
			//to get all current news source
			$nsource = array($last->source_name);
			for($i=0;$i<$last->position;$i++){
				$nsource[$i] = $bdata-> news_results[$i] -> source_name;
			}
			$nsource[count($nsource)] = "All Source"; 
			$nsource = array_values(array_unique($nsource,SORT_REGULAR));
			
			//to use with html select tag
				
			echo"<table class='table' style='width:100%'>";
			echo"<thead class='thead-dark'>";
			echo"<tr style='text-align: center'>";
			echo"<th scope='col'>  </th>";
	
			echo"</tr>";
			echo"</thead>";
			echo"<tbody>";
			for($a=0;$a < $last->position; $a++){
				echo" <tr>";
				echo"<td> <h3>".$bdata-> news_results[$a]-> position.". ".$bdata-> news_results[$a]-> title."</h3><br> Source: ".$bdata-> news_results[$a] -> source_name."<br> URL:<a style='font-size: 10px' href='".$bdata-> news_results[$a]-> url."'>".$bdata-> news_results[$a]-> url."</a>  Posted ".$bdata-> news_results[$a]-> uploaded."<br> Snippet: ".$bdata-> news_results[$a]-> snippet."&nbsp</td>";
				echo" </tr>";
			}
			echo" </tbody>";
			echo" </table>";
					
				
				?>
						</div>
					</div>
				</div>				
		
        <!-- Sticky Footer -->
        <footer style="position: fixed; bottom: 0; width: 100%; text-align: center; padding: 3px; background-color: #EBEDEF; color: black;">
            <span>.....</span>

        </footer>
      </div>

      <!-- /.content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>

  </body>


</html>