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
								printf("<li><a href=\"./mytask.php\">My Tasks</a></li>");
								printf("<li class=\"active\"><a href=\"./claimedtask.php\">Claimed Tasks</a></li>");
								try {
									$dbh = new PDO("mysql:host=localhost;dbname=group18","group18","STREAM-suit-PLUTO-team");
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
								//printf("<li><a href=\"./Register.php\">Register</a></li>");
							}
						?>
					</ul>
				</div>				 
			</div>
		</div>		 
		<!-- End Nav bar -------------------------------------------------------------------------->
		
		<!-- Main page ---------------------------------------------------------------------------->		
		<?php
			if (isset($_SESSION["user_id"])) {
				$id = $_SESSION["user_id"];
				if (isset($_POST['complete']) && isset($_POST["taskno"])) {
					//complete
					$taskno = $_POST["taskno"];
					//printf("Task: %s\n",$taskno);
					try {
						$dbh = new PDO("mysql:host=localhost;dbname=group18","group18","STREAM-suit-PLUTO-team");
						$query = "SELECT idStatusName FROM statusname WHERE status='COMPLETED'";
						$stmt = $dbh->prepare($query);
						$stmt->execute();
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						$idstatus = $row['idStatusName'];
						//printf("status %s",$idstatus);
						$date = date ("Y/m/d H:i:s");
						//printf("date %s",$date);
						//idStatus 	TaskNo 	StatusName 	Date 
						$query = "UPDATE status SET  StatusName = :statusname, Date = :date WHERE TaskNo = :taskno";
						$stmt = $dbh->prepare($query);
						$affectedRows = $stmt->execute(array(':taskno' => $taskno, ':statusname' => $idstatus, ':date' => $date));
						printf("<h2>Task %s Completed</h2>",$taskno);
					} catch (PDOException $exception) {
						printf("Connection error: %s", $exception->getMessage());
						}
				} else if (isset($_POST['cancel']) && isset($_POST["taskno"])) {
					//cancel
					$taskno = $_POST["taskno"];
					try {
						$dbh = new PDO("mysql:host=localhost;dbname=group18","group18","STREAM-suit-PLUTO-team");
						$query = "SELECT idStatusName FROM statusname WHERE status='CANCELLED'";
						$stmt = $dbh->prepare($query);
						$stmt->execute();
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						$idstatus = $row['idStatusName'];
						$date = date ("Y/m/d H:i:s");
							//printf("date %s",$date);
							//idStatus 	TaskNo 	StatusName 	Date 
						$query = "UPDATE status SET StatusName = :statusname, Date = :date WHERE TaskNo = :taskno";
						$stmt = $dbh->prepare($query);
						$affectedRows = $stmt->execute(array(':taskno' => $taskno, ':statusname' => $idstatus, ':date' => $date));
						$query = "SELECT Reputation FROM user WHERE ID = :id";
						$stmt = $dbh->prepare($query);
						$stmt->bindValue(':id',$id);
						$stmt->execute();
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						$reputation = $row['Reputation'];
						$reputation = $reputation -15;
						//printf("Reputation %s",$reputation);
						$query = "UPDATE user SET Reputation = :reputation WHERE ID = :id";
						$stmt = $dbh->prepare($query);
						$stmt->bindValue(':reputation',$reputation);
						$stmt->bindValue(':id',$id);			
						$stmt->execute();
						printf("<h2>Task %s cancelled</h2>",$taskno);
					} catch (PDOException $exception) {
						printf("Connection error: %s", $exception->getMessage());
					}
				} else if (isset($_POST['request'])) {
						$taskno = $_POST["taskno"];
						$dbh = new PDO("mysql:host=localhost;dbname=group18","group18","STREAM-suit-PLUTO-team");
						$query = "SELECT UserCreated, Title FROM task WHERE idTaskNo = :taskno";
						$stmt = $dbh->prepare($query);
						$stmt->bindValue(':taskno',$taskno);	
						$stmt->execute();
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						$usercreated = $row['UserCreated'];
						$title = urlencode($row['Title']);//str_replace(' ', '%20', $row['Title']);
						$query = "SELECT Email FROM user WHERE id = :id";
						$stmt = $dbh->prepare($query);
						$stmt->bindValue(':id',$usercreated);	
						$stmt->execute();
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						$email = $row['Email'];
					header("Location: emailtemplate.php?recepient=$email&title=$title");		
				}
			}
		?>
			
		<div class="container">	    
			<form action="emailcompletecancel.php" method="post">
				<fieldset>											 
					<div class="row">								 
					   <div class="col-md-offset-3 col-md-6">        	
							<div class="form-group">
							<?php
								if (isset($_GET["taskno"])) {
									$taskno = $_GET["taskno"];
									if (!isset($_POST['request']) && !isset($_POST['complete']) && !isset($_POST['cancel'])) {
										if (isset($_SESSION["user_id"])) {
											$id = $_SESSION["user_id"];
											printf("<div class='form-group'>");										
											printf("<button type='submit' class='btn btn-success' name='request'>Request Document</button>");
											printf("</div>");
											printf("<div class='form-group'>");										
											printf("<button type='submit' class='btn btn-success' name='complete'>Complete</button>");
											printf("</div>");
											printf("<div class='form-group'>");
											printf("<button type='submit' class='btn btn-success' name='cancel'>Cancel</button>");
											printf("</div>");
											printf("<input type='hidden' name='taskno' value=%s />",$taskno);
										}
									}
								}
							?>	
							</div> 							
						</div>
					</div>
				</fieldset>
			</form>
		</div>									
		<!-- End Main ----------------------------------------------------------------------------->
						   
				
		<!-- Footer ------------------------------------------------------------------------------->
		<footer id="footer">				
		</footer>

		<!-- Scripts ------------------------------------------------------------------------------>	
	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
	</body>
</html>
