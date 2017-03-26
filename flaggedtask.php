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
			 
			    <a href="" class="navbar-brand">Proofreading Website</a>
				 
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
										printf("<li class=\"active\"><a href=\"flaggedtask.php\">Flagged Tasks</a></li>");
									}
								}
								catch (PDOException $exception) {
									printf("Connection error: %s", $exception->getMessage());
								}
								printf("<li><a href=\"./logout.php\">Logout</a></li>");	
							} else {
								printf("<li><a href=\"./login.php\">Login</a></li>");
								printf("<li><a href=\"./register.php\">Register</a></li>");
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
			<h2>Flagged Tasks </h2>                              
			<table class="table table-responsive table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Title</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if (isset($_SESSION["user_id"])) {
							$id = $_SESSION["user_id"];
							try {
								$dbh = new PDO("mysql:host=localhost;dbname=group18", "root", "");
								$query = "SELECT idStatusName FROM statusname WHERE Status = 'INAPPROPRIATE'";
								$stmt = $dbh->prepare($query);
								$stmt->execute();
								$row = $stmt->fetch(PDO::FETCH_ASSOC);
								$idstatus = $row['idStatusName'];
								$query = "SELECT idTaskNo, Title, StatusName FROM task JOIN status ON task.idTaskNo = status.TaskNo WHERE StatusName = :StatusName ORDER BY DeadlineClaiming desc";
								$stmt = $dbh->prepare($query);
								$stmt->bindValue(':StatusName', $idstatus);
								$stmt->execute();
								while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
									$taskno = $row['idTaskNo'];
									$title = $row['Title'];
									if ($row) {
										printf("<tr><td><a href='taskpage.php?taskno=$taskno'> %s </a></td> <td> <a href='taskpage.php?taskno=$taskno'> %s</a></td></tr>", $row['idTaskNo'],$row['Title']);	
									}
								}
							} catch (PDOException $exception) {
								printf("Connection error: %s", $exception->getMessage());
							}
						}
					?>	  
				</tbody>
			</table>
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
