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
					    <li><a href="index.php">Home</a></li>
						<?php 
							if (!isset($_SESSION)) {
							    session_start();							
							}				
							if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != ''){ 
							    printf("<li><a href=\"./logout.php\">Logout</a></li>");
							} 
							else 
							{
								printf("<li><a href=\"./register.php\">Register</a></li>");
							}
						?>					     
					</ul>
				</div>				 
			</div>
		</div>		 
		<!-- End Nav bar -------------------------------------------------------------------------->
		
	    <?php   	
		    if (isset($_POST["id"]) && isset($_POST["password"]) && trim($_POST["id"]) !='' && trim($_POST["password"]) != ''  ){
			    try {
				    $dbh = new PDO("mysql:host=localhost;dbname=group18","group18","STREAM-suit-PLUTO-team");
			
				    $id = trim($_POST["id"]);
				    $password = $_POST["password"];	
				    $passwordHash = "";	
				    $stmt = $dbh->prepare("SELECT password FROM User WHERE id = ?" );
				    $stmt->execute(array($id));
				    
				    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {        
					    $passwordHash = $row['password'];
				    }
					if (!is_null($passwordHash)) { 
				        $siteSalt  = "proofreader";
				        $saltedHash = hash('sha256', $password.$siteSalt);
		
				        if ($passwordHash == $saltedHash) {	
							// check if banned
							//$dbh = new PDO("mysql:host=localhost;dbname=group18","group18","STREAM-suit-PLUTO-team");
							$query = "SELECT ID FROM banned where ID = :id";
							$stmt = $dbh->prepare($query);
							$stmt->bindValue(':id',$id);
							$affectedRows = $stmt->execute();
							$row = $stmt->fetch(PDO::FETCH_ASSOC);
							$banned = $row['ID'];
							if ($banned == $id) {
								printf("<h2>User %s banned</h2>",$id);
							}
							else {
								$_SESSION['user_id'] = $id; 
								header("Location:./index.php");
								printf("<h2> Loggedin Sucessfully </h2>");
							}
				        }
						else 
						{	
							printf("<h2> Password incorrect</h2>");
						}
					} 
					else 
					{
					     printf("<h2> Account not found. </h2>");
				    }
			    } catch (PDOException $exception) {
			            printf("Connection error: %s", $exception->getMessage());
					}
		    }
        ?>        
		<!-- Login form --------------------------------------------------------------------------->
		 <div class="container">			
			    <form action="login.php" id="login-form" role="form" data-toggle="validator" method="post">
					<fieldset>
                      <div class="row">
					    <div class="col-md-offset-3 col-md-3">
							<h2>Login</h2>
							<div class="form-group">
								<input autofocus class="form-control" name="id" placeholder="ID" pattern="^[0-9]{1,}$" type="text"/>
							</div>
							
							<div class="form-group">
								<input class="form-control" name="password" placeholder="Password" type="password"/>
							</div>
							
							<div class="form-group">
								<button type="submit" class="btn btn-success">Login</button>
								<!--<label>Don't have account yet ! <a href="./register.php">Sign Up</a></label>-->
							</div>
						</div>
					  </div>
					</fieldset>
				</form>			
		</div>
		<!-- End  Login Form ---------------------------------------------------------------------->
		
	    <!-- Footer ------------------------------------------------------------------------------->
		<footer id="footer">				
		</footer>
		
		<!-- Scripts ------------------------------------------------------------------------------>
        <script src="js/jquery-3.2.1.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/bootstrapValidator.js"></script>
		<script src="js/loginValidator.js"></script>
	</body>
</html>
		
