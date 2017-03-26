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
					    <li><a href="index.php">Home</a></li>
                            <?php 
							    if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != ''){ 
							        printf("<li class= \"active\"><a href=\"./logout.php\">Logout</a></li>");
							    } 
								else 
								{
								    printf("<li><a href=\"./login.php\">Login</a></li>");
							    }
							?>				     
					</ul>
				</div>				 
			</div>
		</div>		 
		<!-- End Nav bar -->
		
		<!-- Main -->
		<div id="main">			
			<header class="major">
				<h2>Proofreader</h2>
			</header>
            <?php
                /*http://php.net/manual/en/function.session-unset.php*/
                session_start();
                session_unset();
                session_destroy();
                session_write_close();
                setcookie(session_name(),'',0,'/');
                session_regenerate_id(true);	
            ?>
			<h2>You have been logged out.</h2>								
		</div>

		<!-- Footer -->
		<footer id="footer">				
		</footer>

		<!-- Scripts -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
	</body>
</html>