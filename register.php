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
							    if (!isset ($_SESSION)) {
								    session_start();		
							    }							
							    if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != ''){ 
							        printf("<li><a href=\"./logout.php\">Logout</a></li>");
							    } else {
								    printf("<li><a href=\"./login.php\">Login</a></li>");
							    }
							?>				 
					</ul>
				</div>				 
			</div>
		</div>		 
		<!-- End Nav bar -------------------------------------------------------------------------->

		<!-- Main --------------------------------------------------------------------------------->
		<div id="main">
            <?php
                if (isset($_POST) && count ($_POST) > 0) {
	                $firstName = htmlspecialchars(ucfirst(trim($_POST["firstname"])));
	                $lastName = htmlspecialchars(ucfirst(trim($_POST["lastname"])));
					$id = htmlspecialchars(trim($_POST["id"]));
					$major = $_POST["subject"];
	                $email = trim(strtolower($_POST["email"]));
	                $passOne = $_POST["password"];
	                $passTwo = $_POST["confirm_password"];
					$emailOne = $_POST["email"];
	                $emailTwo = $_POST["confirm_email"];
					$reputation = 0;
		
	                //check whether user/email alerady exists
	                $dbh = new PDO("mysql:host=localhost;dbname=group18","root","");
	                $stmt = $dbh->prepare("SELECT password FROM User WHERE id = ?" );
	                $stmt->execute(array($id));
	                $rowCount = $stmt->rowCount();
					// code missing need to stop registration if id already exists
					
	                if ($passOne != $passTwo) { //in case Javascript is disabled.
		                printf("<h2> Passwords do not match. </h2>");
	                } 
					else 
					{
						if ($emailOne != $emailTwo) { //in case Javascript is disabled.
						    printf("<h2> Email address does not match. </h2>");
						} 
						else 
						{
		                    if ($rowCount > 0) { 
			                    printf("<h2> An account already exists with that ID.</h2>");
		                    } 
							else 
							{
			                    $query = "INSERT INTO user SET id = :id, email = :email, firstname = :firstname, lastname = :lastname, password = :password, reputation = :reputation, major = :major";
			                    $stmt = $dbh->prepare($query);
			                    $siteSalt  = "proofreader";
			                    $saltedHash = hash('sha256', $passOne.$siteSalt);
			                    $affectedRows = $stmt->execute(array(':id' => $id, ':email' => $email, ':firstname' => $firstName, ':lastname' => $lastName, ':password' => $saltedHash, ':reputation' => $reputation, ':major' => $major));
			
			                    if ($affectedRows > 0) {
					                $insertId = $dbh->lastInsertId();
					                printf("<h2> Welcome %s! Please <a href=\"./login.php\"> login </a> to proceed. </h2>", $firstName);
							        //logout first
							        /*http://php.net/manual/en/function.session-unset.php*/
					                session_unset();
					                session_destroy();
					                session_write_close();
					                setcookie(session_name(),'',0,'/');
					                session_regenerate_id(true);
								}
								// code missing user wass not registered
			                }
		                }
	                }
                }
            ?>
		
		    <!-- Register form -------------------------------------------------------------------->
		    <?php 
			    if (!isset($_POST) || count($_POST) == 0) { ?>		
		        <div class="container">	    
			        <form action="register.php" id ="register-form" role="form" data-toggle="validator" method="post">
					    <fieldset>
						<div class="row">
					    <div class="col-md-offset-3 col-md-6">
						
						<h2>Sign up</h2>
						    <div class="form-group">
						        <label> First name*:</label>
							    <input autofocus class="form-control" name="firstname" placeholder="First Name" type="text"/>
						    </div>
						    <div class="form-group">
						        <label> Last name*:</label>
							    <input class="form-control" name="lastname" placeholder="Last Name" type="text" />
						    </div>
						    <div class="form-group">
						        <label> ID*:</label>
							    <input class="form-control" name="id" placeholder="Enter your ID" type="text" />
						    </div>
						    <div class="form-group">
						        <label> Major*:</label>
							    <select class="form-control" name="subject" placeholder="Major Subject" type="text" />
							    <?php
									// build the dropdown list
									$dbh = new PDO("mysql:host=localhost;dbname=group18","root","");
									foreach($dbh->query('SELECT idmajors,majornames FROM majornames') as $row) {
										$idmajors=$row["idmajors"];
										$major=$row["majornames"];
										echo '<option value="' . $idmajors . '">' . $major . '</option>';
							    }						
                                ?>
							    </select>
						    </div>
						    <div class="form-group">
						        <label> Email*:</label>
							    <input class="form-control" name="email" placeholder="Email" type="text"/>
						    </div>
						    <div class="form-group">
						        <label> Confirm Email*:</label>
							    <input class="form-control" name="confirm_email" placeholder="Confirm email" type="text"/>
						    </div>
						    <div class="form-group">
						        <label> Password*:</label>
							    <input class="form-control" name="password" placeholder="Password" type="password"/>
						    </div>
						    <div class="form-group">
						        <label> Confirm Password*:</label>
							    <input class="form-control" name="confirm_password" placeholder="Confirm Password" type="password"/>
						    </div>
						    <div class="form-group">
							    <button type="submit" class="btn btn-success">Register</button>
						    </div>
							</div>
							</div>
					    </fieldset>
				    </form>			
		        </div>
		    <?php } ?>	
		    <!-- End Form ------------------------------------------------------------------------->
		</div>
		
	    <!-- Footer ------------------------------------------------------------------------------->
		<footer id="footer">				
		</footer>
		
		<!-- Scripts ------------------------------------------------------------------------------>		
	    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->	
		<script src="js/jquery-3.2.1.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/bootstrapValidator.js"></script>
		<script src="js/registrationValidator.js"></script>
	</body>
</html>