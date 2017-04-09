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
							if (!isset ($_SESSION)) {
								session_start();
							}
							
							if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != ''){ 
                                $id = $_SESSION["user_id"];									
								printf("<li><a href=\"./createtask.php\">Create Task</a></li>");
								printf("<li><a href=\"./tasklist.php\">Task Stream</a></li>");
								printf("<li class=\"active\"><a href=\"./mytask.php\">My Tasks</a></li>");
								printf("<li><a href=\"./claimedtask.php\">Claimed Tasks</a></li>");
								try {
									$dbh = new PDO("mysql:host=localhost;dbname=group18", "root", "");
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
							} else {
								header("Location: index.php"); /* Redirect browser */
								exit();
								//printf("<li><a href=\"./login.php\">Login</a></li>");
								//printf("<li><a href=\"./register.php\">Register</a></li>");
							}
							?>
					</ul>
				</div>				 
			</div>
		</div>		 
		<!-- End Nav bar -------------------------------------------------------------------------->
		
		<!-- Main page ---------------------------------------------------------------------------->

		<div class="container">	    	                           
					<?php
						if (isset($_SESSION["user_id"])) {
							$id = $_SESSION["user_id"];
							if (isset($_POST['satisfactory']) || isset($_POST['unsatisfactory'])) {
								if (isset($_POST["status"]) && isset($_POST["taskno"])) {
									$status = $_POST["status"];
									$taskno = $_POST["taskno"];
									//printf("Status %s",$status);	
									try {
										$dbh = new PDO("mysql:host=localhost;dbname=group18", "root", "");
										$query = "SELECT idStatusName FROM statusname WHERE Status = 'COMPLETED'";
										$stmt = $dbh->prepare($query);
										$stmt->execute();
										$row = $stmt->fetch(PDO::FETCH_ASSOC);
										$idstatus1 = $row['idStatusName'];
										if ($status == $idstatus1) {
											$query = "SELECT ID FROM claimed WHERE TaskNo = :taskno";
											$stmt = $dbh->prepare($query);
											$stmt->bindValue(':taskno',$taskno);
											$stmt->execute();
											$row = $stmt->fetch(PDO::FETCH_ASSOC);
											$claimid = $row['ID'];
											//printf("User Created %s",$usercreated);
											$query = "SELECT Reputation FROM user WHERE ID = :claimid";
											$stmt = $dbh->prepare($query);
											$stmt->bindValue(':claimid',$claimid);
											$stmt->execute();
											$row = $stmt->fetch(PDO::FETCH_ASSOC);
											$reputation = $row['Reputation'];
											if (isset($_POST['satisfactory'])) 
												$reputation = $reputation +5;
											else
												$reputation = $reputation -5;
											//printf("Reputation %s",$reputation);
											$query = "UPDATE user SET Reputation = :reputation WHERE ID = :claimid";
											$stmt = $dbh->prepare($query);
											$stmt->bindValue(':reputation',$reputation);
											$stmt->bindValue(':claimid',$claimid);			
											$stmt->execute();
											printf("<h2>User %s Reputation Updated<h2>",$claimid);
											// delete task
											$query = "DELETE FROM task WHERE idTaskNo = :taskno";
											$stmt = $dbh->prepare($query);
											$stmt->bindValue(':taskno',$taskno);
											$stmt->execute();
										}
									} catch (PDOException $exception) {
										printf("Connection error: %s", $exception->getMessage());
									}
								}
							}else {
							}
						}
					?>
		<div class="container">	    
			<form action="claimrate.php" method="post">
				<fieldset>											 
					<div class="row">								 
					    <div class="col-md-offset-3 col-md-6">        	
							<?php	
								if (!isset($_POST["satisfactory"]) && !isset($_POST["unsatisfactory"])) {
									if (isset($_GET["status"]) && isset($_GET["taskno"])) {
										try {
											$status = $_GET["status"];
											$dbh = new PDO("mysql:host=localhost;dbname=group18", "root", "");
											$query = "SELECT idStatusName FROM statusname WHERE Status = 'COMPLETED'";
											$stmt = $dbh->prepare($query);
											$stmt->execute();
											$row = $stmt->fetch(PDO::FETCH_ASSOC);
											$idstatus1 = $row['idStatusName'];
											if ($status == $idstatus1) {
												printf("<h2>Rate Task<h2>");
												printf("<div class='form-group'>");
												printf("<button type='submit' class='btn btn-success' name='satisfactory'>satisfactory</button>");
												printf("</div>");
												printf("<div class='form-group'>");
												printf("<button type='submit' class='btn btn-success' name='unsatisfactory'>unsatisfactory</button>");
												printf("</div>");
												$status = $_GET["status"];
												$taskno = $_GET["taskno"];
												printf("<input type='hidden' name='taskno' value=%s />",$taskno);
												printf("<input type='hidden' name='status' value=%s />",$status);
											} else {
												$taskno = $_GET["taskno"];
												$query = "SELECT ID FROM claimed WHERE TaskNo = :taskno";
												$stmt = $dbh->prepare($query);
												$stmt->bindValue(':taskno',$taskno);
												$stmt->execute();
												$row = $stmt->fetch(PDO::FETCH_ASSOC);
												$id = $row['ID'];
												$query = "SELECT FirstName, LastName, Email FROM user WHERE ID = :id";
												$stmt = $dbh->prepare($query);
												$stmt->bindValue(':id',$id);
												$stmt->execute();
												$row = $stmt->fetch(PDO::FETCH_ASSOC);
												$firstname = $row['FirstName'];
												$lastname = $row['LastName'];
												$email = $row['Email'];
												printf("<h2>Claimer</h2><br>");
												printf("<h2>First Name: %s</h2><br>",$firstname);
												printf("<h2>Last Name: %s</h2><br>",$lastname);
												printf("<h2>Email: %s</h2><br>",$email);
											}
										} catch (PDOException $exception) {
											printf("Connection error: %s", $exception->getMessage());
										}
									}
								}
							?>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		<!-- End Main ---------------------------------------------------------------------------->
						   
		<!-- Footer ------------------------------------------------------------------------------->
		<footer id="footer">				
		</footer>

		<!-- Scripts ------------------------------------------------------------------------------>	
	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
	</body>
</html>
