<!DOCTYPE html>
<html>
    <head>
	    <title>Proofreading Website</title>
		<meta name="viewport" content="width=device-widht, initial-scale=1.0"/>
		<link href="css/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/styles.css" rel="stylesheet"/>
	</head>
	
	<body>	
		<!-- Header -->
		<header id="header" class="alt">
		</header>	
		
		<!-- Nav bar --->
		<div class="navbar navbar-inverse navbar-static-top">
		    <div class="container">
			 
			    <a href="" class="navbar-brand">Proofreading Website</a>
				 
				<!-- Mobile responsiveness -->
				<button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
				    <span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				 
				<div class="collapse navbar-collapse navHeaderCollapse">				     
					<ul class="nav navbar-nav navbar-right">					     
						<li class="active"><a href="index.php">Home</a></li>
						<?php 
							if (!isset ($_SESSION)) {
								session_start();
							}
							
							if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != ''){ 
                                								
								printf("<li class=\"active\"><a href=\"./createtask.php\">Create Task</a></li>");
								printf("<li><a href=\"./register.php\">Register</a></li>");
								//printf("<li><a href=\"./logout.php\">Logout</a></li>");	
							} else {
								printf("<li><a href=\"./login.php\">Login</a></li>");
							}
							?>
					</ul>
				</div>				 
			</div>
		</div>		 
		<!-- End Nav bar ------------------------------------------------------------------->
		
		<!-- Main page ------------------------------------------------------------------------>
		<?php
				if (isset($_GET["id"])) {
					$id = $_GET["id"];
					try {
						$dbh = new PDO("mysql:host=localhost;dbname=group18", "root", "");
						/*$stmt = $dbh->prepare("SELECT title, description FROM `task` WHERE id=:id" );
						$stmt->bindValue(':id', $id);
						$stmt->execute();
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						
						if ($row) {
							printf("<h2> %s </h2> <p> %s </p>\n", $row["title"], $row["description"]);
						} else {
							printf("Task not found.");
						}*/
					} catch (PDOException $exception) {
						printf("Connection error: %s", $exception->getMessage());
				
					}
				}

        ?>


<!--Create Table ------------------------------->
<div class="container">	    
	
  <h2>Task List</h2>
                              
  <table class="table table-responsive table-hover">
    <thead>
      <tr>
        <th>#</th>
        <th>Title</th>
	 </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td></td>
      </tr>
      <tr>
        <td>2</td>
        <td></td>
      </tr>
      <tr>
        <td>3</td>
        <td></td>
      </tr>
	  <tr>
        <td>4</td>
        <td></td>
      </tr>
	  <tr>
        <td>5</td>
        <td></td>
      </tr>
	  <tr>
        <td>6</td>
        <td></td>
      </tr>
	  <tr>
        <td>7</td>
        <td></td>
      </tr>
	  <tr>
        <td>8</td>
        <td></td>
      </tr>
	  <tr>
        <td>9</td>
        <td></td>
      </tr>
	  <tr>
        <td>10</td>
        <td></td>
      </tr>
	 
    </tbody>
  </table>
</div>
<!-- End Table ------------------------------------------------>

						   
				
<!-- Footer ------------------------------------------------------------------------->
		<footer id="footer">				
		</footer>

		<!-- Scripts -->	
	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
	</body>
</html>
