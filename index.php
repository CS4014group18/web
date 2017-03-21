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
						if (!isset($_SESSION)) {
							session_start();							
						}	
						if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != ''){ 
							
							// Display Register and Login in Navbar
							printf("<li><a href=\"./register.php\">Register</a></li>");
							printf("<li><a href=\"./login.php\">Login</a></li>");
							
							
						} 
						else 
						{
							printf("<li><a href=\"./logout.php\">Logout</a></li>");
						   
					    }
						?>		 
					</ul>
				</div>				 
			</div>
		</div>		 
		<!-- End Nav bar -->
		 		
		<!-- Footer -->
		<footer id="footer">				
		</footer>

		<!-- Scripts -->	
	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
	</body>
</html>