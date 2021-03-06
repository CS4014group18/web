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
								//printf("<li><a href=\"./register.php\">Register</a></li>");
							}
							?>
					</ul>
				</div>				 
			</div>
		</div>		 
		<!-- End Nav bar -------------------------------------------------------------------------->
		
		<!-- Main page ---------------------------------------------------------------------------->

		<!--Create Table -------------------------------------------------------------------------->
		<div class="container">	    	
			<h2>My Tasks </h2>                              
			<table class="table table-responsive table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Title</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if (isset($_SESSION["user_id"])) {
							$id = $_SESSION["user_id"];
							try {
									$dbh = new PDO("mysql:host=localhost;dbname=group18","group18","STREAM-suit-PLUTO-team");
									$query = "SELECT idStatusName FROM statusname WHERE Status = 'UNCLAIMED'";
									$stmt = $dbh->prepare($query);
									$stmt->execute();
									$row = $stmt->fetch(PDO::FETCH_ASSOC);
									$idstatus = $row['idStatusName'];
									$query = "SELECT idStatusName FROM statusname WHERE Status = 'PENDING'";
									$stmt = $dbh->prepare($query);
									$stmt->execute();
									$row = $stmt->fetch(PDO::FETCH_ASSOC);
									$idstatus1 = $row['idStatusName'];
									$query = "SELECT idTaskNo FROM task join status on task.idTaskNo = status.TaskNo WHERE StatusName=:StatusName AND DeadLineClaiming < :date";
									$stmt = $dbh->prepare($query);
									$date = date ("Y/m/d H:i:s");
									$stmt->execute(array(':StatusName' => $idstatus1, ':date' => $date));
									$rowoutofdate = $stmt->fetchAll(PDO::FETCH_ASSOC);
									foreach( $rowoutofdate as $row) { // change status to UNCLAIMED
									    $taskno = $row['idTaskNo'];								
										$query = "UPDATE status SET  StatusName = :statusname, Date = :date WHERE TaskNo = :taskno";
										$stmt = $dbh->prepare($query);
										$stmt->execute(array(':statusname' => $idstatus, ':date' => $date, ':taskno' => $taskno));
									}
									$query = "SELECT idTaskNo, Title, StatusName FROM task JOIN status ON task.idTaskNo = status.TaskNo WHERE UserCreated = :id ORDER BY DeadlineClaiming";
									$stmt = $dbh->prepare($query);
									$stmt->bindValue(':id', $id);
									$stmt->execute();
									$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
									foreach ($row as $x) { 
										$taskno = $x['idTaskNo'];
										//$title = $x['Title'];
										$idStatus = $x['StatusName'];								
										$query = "SELECT Status FROM statusname WHERE idStatusName = :StatusName";
										$stmt = $dbh->prepare($query);
										$stmt->bindValue('StatusName',$idStatus);
										$stmt->execute();
										$row = $stmt->fetch(PDO::FETCH_ASSOC);  // todo no link for non completed tasks - ok just gives blanks for claimers name and email
										printf("<tr><td><a href='claimrate.php?taskno=$taskno&status=$idStatus'> %s </a></td> <td> <a href='claimrate.php?taskno=$taskno&status=$idStatus'> %s</a></td><td> %s</td></tr>", $x['idTaskNo'],$x['Title'],$row['Status']);
									}
							} catch (PDOException $exception) {
								printf("Connection error: %s", $exception->getMessage());
							}
						}
					?>	  
				</tbody>
			</table>
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
