<!DOCTYPE html>
<html>
    <head>
	    <title>Proofreading Website</title>
		<meta name="viewport" content="width=device-widht, initial-scale=1.0"/>
		<link href="css/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/styles.css" rel="stylesheet"/>
	</head>
	
	<body>	
		<!-- Header ------------------------------------------------------------------------------->
		<header id="header" class="alt">
		</header>	
		
		<!-- Nav bar ------------------------------------------------------------------------------>
		<div class="navbar navbar-inverse navbar-static-top">
		    <div class="container">
			 
			    <a href="index.php" class="navbar-brand">Proofreading Website</a>
				 
				<!-- Mobile responsiveness -------------------------------------------------------->
				<button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
				    <span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				 
				<div class="collapse navbar-collapse navHeaderCollapse">				     
					<ul class="nav navbar-nav navbar-right">					     
						<li class="active"><a href="index.php">Home</a></li>
						<?php
						if (!isset($_SESSION)) {
							session_start();							
						}	
						if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != ''){
							$id = $_SESSION["user_id"];	
							printf("<li><a href=\"./createtask.php\">Create Task</a></li>");
						    printf("<li><a href=\"./tasklist.php\">Task Stream</a></li>");
							printf("<li><a href=\"./mytask.php\">My Tasks</a></li>");
							printf("<li><a href=\"./claimedtask.php\">Claimed Tasks</a></li>");
							try {
									$dbh = new PDO("mysql:host=localhost;dbname=group18","root","");
									$query = "SELECT Reputation FROM user where id = :id";									
									$stmt = $dbh->prepare($query);
									$stmt->bindValue(':id', $id);
									$stmt->execute();
									$row = $stmt->fetch(PDO::FETCH_ASSOC);
									$reputation = $row['Reputation'];
									if ($reputation >= 40) {
										printf("<li><a href=\"flaggedtask.php\">Flagged Tasks</a></li>");
									}
								}
								catch (PDOException $exception) {
									printf("Connection error: %s", $exception->getMessage());
								}
							printf("<li><a href=\"./logout.php\">Logout</a></li>");
						} else 
						{
							printf("<li><a href=\"./login.php\">Login</a></li>");
						    printf("<li><a href=\"./register.php\">Register</a></li>");
					    }
						?>		 
					</ul>
				</div>				 
			</div>
		</div>		 
		<!-- End Nav bar -------------------------------------------------------------------------->
		<div class="container">
          <div class="row">
            <div class="col-md-offset-4 col-md-4 ">		  
			<h2>Welcome to ProofReader</h2>
			<?php
						if (!isset($_SESSION["user_id"]))
							printf("<h2>Please <a href=\"login.php\">Log in</a> or <a href=\"./register.php\">Sign up</a><br/></h2>");
				   ?>
		</div>
		</div>
		</div>
		
		<!-- Footer ------------------------------------------------------------------------------->
		<footer id="footer">				
		</footer>

		<!-- Scripts ------------------------------------------------------------------------------>	
	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
	</body>
</html>