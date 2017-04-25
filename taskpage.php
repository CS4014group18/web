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
							if (!isset ($_SESSION)) {
								session_start();
							}						
							if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != ''){ 
								$id = $_SESSION["user_id"];	
                                printf("<li><a href=\"./createtask.php\">Create Task</a></li>");
								printf("<li class=\"active\"><a href=\"./tasklist.php\">Task Stream</a></li>");
								printf("<li><a href=\"./mytask.php\">My Tasks</a></li>");
								printf("<li><a href=\"./claimedtask.php\">Claimed Tasks</a></li>");
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
			if (isset($_POST) && count ($_POST) > 0) {
				if (isset($_SESSION["user_id"])) {
					$id = $_SESSION["user_id"];
					if (isset($_POST['claim']) && isset($_POST["taskno"])) {
						//claim
						$taskno = $_POST["taskno"];
						//printf("Task: %s\n",$taskno);
						try {
								$dbh = new PDO("mysql:host=localhost;dbname=group18","group18","STREAM-suit-PLUTO-team");
								$query = "SELECT idStatusName FROM statusname WHERE status='CLAIMED'";
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
								$query = "INSERT INTO claimed SET ID = :id, TaskNo = :taskno, Date = :date";
								$stmt = $dbh->prepare($query);
								$affectedRows = $stmt->execute(array(':id' => $id,':taskno' => $taskno, ':date' => $date));
								$query = "SELECT Reputation FROM user WHERE ID = :claimer";
								$stmt = $dbh->prepare($query);
								$stmt->bindValue(':claimer',$id);
								$stmt->execute();
								$row = $stmt->fetch(PDO::FETCH_ASSOC);
								$reputation = $row['Reputation'];
								$reputation = $reputation +10;
								$query = "UPDATE user SET Reputation = :reputation WHERE ID = :claimer";
								$stmt = $dbh->prepare($query);
								$stmt->bindValue(':reputation',$reputation);
								$stmt->bindValue(':claimer',$id);	
								$stmt->execute();
								printf("<h2>Task %s Claimed</h2>",$taskno);
						} catch (PDOException $exception) {
								printf("Connection error: %s", $exception->getMessage());
							}
					} else if (isset($_POST['inappropriate']) && isset($_POST["taskno"])) {
						//inappropriate
						$taskno = $_POST["taskno"];
						try {
							$dbh = new PDO("mysql:host=localhost;dbname=group18","group18","STREAM-suit-PLUTO-team");
							$query = "SELECT idStatusName FROM statusname WHERE status='INAPPROPRIATE'";
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
							$query = "SELECT Reputation FROM user WHERE ID = :claimer";
							$stmt = $dbh->prepare($query);
							$stmt->bindValue(':claimer',$id);
							$stmt->execute();
							$row = $stmt->fetch(PDO::FETCH_ASSOC);
							$reputation = $row['Reputation'];
							$reputation = $reputation +2;
							$query = "UPDATE user SET Reputation = :reputation WHERE ID = :claimer";
							$stmt = $dbh->prepare($query);
							$stmt->bindValue(':reputation',$reputation);
							$stmt->bindValue(':claimer',$id);	
							$stmt->execute();
							printf("<h2>Task Status changed to inappropriate</h2>");
						} catch (PDOException $exception) {
							printf("Connection error: %s", $exception->getMessage());
						}
					} else if (isset($_POST['download']) && isset($_POST["taskno"])) {
						try {
							$dbh = new PDO("mysql:host=localhost;dbname=group18","group18","STREAM-suit-PLUTO-team");
							$taskno = $_POST["taskno"];
							//printf("taskno %s",$taskno);
							$query = "SELECT Sample FROM task where idTaskNo = :taskno";
							$stmt = $dbh->prepare($query);
							$stmt->bindValue(':taskno',$taskno);
							$affectedRows = $stmt->execute();
							$row = $stmt->fetch(PDO::FETCH_ASSOC);
							$sample = $row["Sample"];
							//printf("sample %s",$sample);	
							header("Cache-Control: private");
							header("Content-Transfer-Encoding: binary");					
							header("Content-disposition: attachment; filename="."\"".$sample."\"");
							header("Content-type: application/pdf");
							ob_flush();
							flush();
							
							readfile("uploads/".$sample); // file is being corrupted html is appended at start of file see http://stackoverflow.com/questions/37159150/php-how-to-solve-that-html-page-source-is-appending-to-the-download-file
							//readfile("C://XAMPP/htdocs/uploads/".$sample);
						} catch (PDOException $exception) {
							printf("Connection error: %s", $exception->getMessage());
						}	
					} else {	
					//no button pressed
					}
				}
			}
		?>
		<div class="container">	    
			<form action="taskpage.php" method="post">
				<fieldset>											 
					<div class="row">								 
					   <div class="col-md-offset-3 col-md-6">        	
							<?php	
								//if (!isset($_POST)) {
									if (isset($_GET["taskno"])) {
										$taskno = $_GET["taskno"];
										try {
											$dbh = new PDO("mysql:host=localhost;dbname=group18","group18","STREAM-suit-PLUTO-team");
											$stmt = $dbh->prepare("SELECT title, description, type, pages, words, format FROM `task` WHERE idTaskno=:taskno" );
											$stmt->bindValue(':taskno', $taskno);
											$stmt->execute();
											$row = $stmt->fetch(PDO::FETCH_ASSOC);
											if ($row) {								
												printf("<h2>Title: %s </h2><h2>Description:</h2> <h2>%s</h2><h2>Type %s</h2>", $row["title"], $row["description"], $row["type"]);
												$stmt = $dbh->prepare("SELECT Tag FROM `tasktags` WHERE TaskNo = :taskno");
												$stmt->bindValue(':taskno', $taskno);
												//printf("taskno %s",$taskno);
												$stmt->execute();
												$rowtasktags = $stmt->fetchAll(PDO::FETCH_ASSOC);
												//printf("Tag %s",$rowtasktags["Tag"]);
												$i = 1;
												foreach($rowtasktags as $tag) {
													$stmt = $dbh->prepare("SELECT Description FROM `tags` WHERE idTags=:tag");
													$stmt->bindValue(':tag', $tag["Tag"]);
													//printf("tag %s",$tag["Tag"]);
													$stmt->execute();
													$rowtag = $stmt->fetch(PDO::FETCH_ASSOC);
													$description=$rowtag["Description"];
													printf("<h2>Tag%s: %s<h2>",$i,$description);
													$i++;
												}
												printf("<h2>Pages: %s </h2><h2>Words: %s</h2><h2>Format %s", $row["pages"], $row["words"], $row["format"]);
												printf("<input type='hidden' name='taskno' value=%s />",$taskno);								
											} else {
												printf("Task not found.");
											}
										} catch (PDOException $exception) {
											printf("Connection error: %s", $exception->getMessage());
										}
									}
								//}
							?>
							<div class="form-group">
	
							<?php	
								if (!isset($_POST['claim']) && !isset($_POST['inappropriate'])) {
									if (isset($_SESSION["user_id"])) {
										$id = $_SESSION["user_id"];
										printf("<div class='form-group'>");
										printf("<button type='submit' class='btn btn-success' name='claim'>claim</button>");
										printf("</div>");
										printf("<div class='form-group'>");
										printf("<button type='submit' class='btn btn-success' name='inappropriate'>Inappropriate</button>");
										printf("</div>");
										printf("<div class='form-group'>");
										printf("<button type='submit' class='btn btn-success' name='download'>Download Sample</button>");
										printf("</div>");								
									}
								}
							?>	
							</div> 							
						</div>
					</div>
				</fieldset>
			</form>
		</div>
						
		<!-- Footer ------------------------------------------------------------------------------->
		<footer id="footer">				
		</footer>

		<!-- Scripts ------------------------------------------------------------------------------>	
	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
	</body>
</html>
