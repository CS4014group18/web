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
								printf("<li class=\"active\"><a href=\"./tasklist.php\">Task Stream</a></li>");
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

		<!--Create Table -------------------------------------------------------------------------->
		<div class="container">	    
	
			<h2>Task Stream</h2>
                              
			<table class="table table-responsive table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Title</th>
						<th>Deadline Claiming</th>
						<th>Deadline Submission</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<?php
							if (isset($_SESSION["user_id"])) {
								$id = $_SESSION["user_id"];
								try {
									$dbh = new PDO("mysql:host=localhost;dbname=group18", "root", "");
									$query = "SELECT idStatusName FROM statusname WHERE Status = 'CLAIMED'";
									$stmt = $dbh->prepare($query);
									$stmt->execute();
									$row = $stmt->fetch(PDO::FETCH_ASSOC);
									$idstatus = $row['idStatusName'];
									$query = "SELECT idStatusName FROM statusname WHERE Status = 'CANCELLED'";
									$stmt = $dbh->prepare($query);
									$stmt->execute();
									$row = $stmt->fetch(PDO::FETCH_ASSOC);
									$idstatus1 = $row['idStatusName'];
									$query = "SELECT idTaskNo FROM task join status on task.idTaskNo = status.TaskNo WHERE StatusName=:StatusName AND DeadLineSubmission < :date";
									$stmt = $dbh->prepare($query);
									$date = date ("Y/m/d H:i:s");
									$stmt->execute(array(':StatusName' => $idstatus, ':date' => $date));
									$rowoutofdate = $stmt->fetchAll(PDO::FETCH_ASSOC);
									foreach( $rowoutofdate as $row) { // change status to cancelled
									    $taskno = $row['TaskNo'];								
										$query = "UPDATE status SET  StatusName = :statusname, Date = :date WHERE TaskNo = :taskno";
										$stmt = $dbh->prepare($query);
										$date = date ("Y/m/d H:i:s");
										$stmt->execute(array(':taskno' => $taskno, ':statusname' => $idstatus1, ':date' => $date));
										$query = "SELECT ID FROM claimed where TaskNo = :taskno ORDER BY date";
										$stmt = $dbh->prepare($query);
										$stmt->execute(array(':taskno' => $task));
										$rowid = $stmt->fetch(PDO::FETCH_ASSOC);
										$query = "SELECT Reputation FROM user WHERE ID = :id";
										$stmt = $dbh->prepare($query);
										$stmt->bindValue(':id',$rowid);
										$stmt->execute();
										$rowrep = $stmt->fetch(PDO::FETCH_ASSOC);
										$reputation = $rowrep['Reputation'];
										$reputation = $reputation -30;	
										//printf("Reputation %s",$reputation);
										$query = "UPDATE user SET Reputation = :reputation WHERE ID = :id";
										$stmt = $dbh->prepare($query);
										$stmt->bindValue(':reputation',$reputation);
										$stmt->bindValue(':id',$rowid);			
										$stmt->execute();
									}			
									//delete out of date tasks
									$query = "DELETE FROM task WHERE DeadlineClaiming < :date";
									$stmt = $dbh->prepare($query);
									$date = date ("Y/m/d H:i:s");
									$stmt->execute(array(':date' => $date));
									$query = "SELECT idStatusName FROM statusname WHERE Status = 'CANCELLED'";
									$stmt = $dbh->prepare($query);
									$stmt->execute();
									$row = $stmt->fetch(PDO::FETCH_ASSOC);
									$idstatus = $row['idStatusName'];		
									$query = "SELECT idStatusName FROM statusname WHERE Status = 'PENDING'";
									$stmt = $dbh->prepare($query);
									$stmt->execute();
									$row = $stmt->fetch(PDO::FETCH_ASSOC);
									$idstatus1 = $row['idStatusName'];
									//printf("status %s",$idstatus);
									$query = "SELECT idTaskNo, Title, DeadlineClaiming, DeadlineSubmission FROM task join status on task.idTaskNo = status.TaskNo WHERE (StatusName=:StatusName OR StatusName=:StatusName1) AND UserCreated!=:id ORDER BY DeadlineClaiming desc";
									$stmt = $dbh->prepare($query);
									$stmt->execute(array(':StatusName' => $idstatus, ':StatusName1' => $idstatus1, ':id' => $id));
									$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
									/*while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
										$taskno = $row['idTaskNo'];										
										$title = $row['Title'];										
										$deadlineclaiming = $row['DeadlineClaiming'];
										$deadlinesubmission = $row['DeadlineSubmission'];
										if ($row) {
											printf("<tr><td><a href='taskpage.php?taskno=$taskno'> %s </a></td> <td> <a href='taskpage.php?taskno=$taskno'> %s</a></td><td>%s</td><td>%s</td></tr>", $row['idTaskNo'],$row['Title'],$row['DeadlineClaiming'],$row['DeadlineSubmission']);
										}
									}*/
									$displayrows = array();
									foreach ($row as $x) {
										$taskno = $x['idTaskNo'];
										$query = "SELECT Tag FROM tasktags WHERE TaskNo = :TaskNo"; 
										$stmt = $dbh->prepare($query);
										$stmt->execute(array(':TaskNo' => $taskno));
										$rowtasktags = $stmt->fetchAll(PDO::FETCH_ASSOC);
										$count = 0;
										foreach ($rowtasktags as $y) {
											$tasktag = $y['Tag'];
											$query = "SELECT Tag FROM usertags WHERE ID = :id and Tag = :tasktag"; 
											$stmt = $dbh->prepare($query);
											$stmt->execute(array(':id' => $id, ':tasktag' => $tasktag));
											$rowusertags = $stmt->fetchAll(PDO::FETCH_ASSOC);
											if ($rowusertags>0) {
												$count++;	
											}					
										}
										$input = array('taskno' => $taskno, 'count' => $count);	
										array_push($displayrows, $input);
										
									}
									/*
									foreach ($displayrows as $y) {
										printf("task no %s<br>",$y['taskno']);
										printf("count %d<br>",$y['count']);
									}
									*/
									// 4 tags match
									foreach ($row as $x) {
										$taskno = $x['idTaskNo'];
										$title = $x['Title'];
										$deadlineclaiming = $x['DeadlineClaiming'];
										$deadlinesubmission = $x['DeadlineSubmission'];
										foreach ($displayrows as $y) {
											if ($taskno === $y['taskno'] && $y['count'] == 4) {
												printf("<tr><td><a href='taskpage.php?taskno=$taskno'> %s </a></td> <td> <a href='taskpage.php?taskno=$taskno'> %s</a></td><td>%s</td><td>%s</td></tr>", $x['idTaskNo'],$x['Title'],$x['DeadlineClaiming'],$x['DeadlineSubmission']);
											}
										}
									} 
									// 3 tags match
									foreach ($row as $x) {
										$taskno = $x['idTaskNo'];
										$title = $x['Title'];
										$deadlineclaiming = $x['DeadlineClaiming'];
										$deadlinesubmission = $x['DeadlineSubmission'];
										foreach ($displayrows as $y) {
											if ($taskno === $y['taskno'] && $y['count'] == 3) {
												printf("<tr><td><a href='taskpage.php?taskno=$taskno'> %s </a></td> <td> <a href='taskpage.php?taskno=$taskno'> %s</a></td><td>%s</td><td>%s</td></tr>", $x['idTaskNo'],$x['Title'],$x['DeadlineClaiming'],$x['DeadlineSubmission']);
											}
										}
									} 
									// 2 tags match
									foreach ($row as $x) {
										$taskno = $x['idTaskNo'];
										$title = $x['Title'];
										$deadlineclaiming = $x['DeadlineClaiming'];
										$deadlinesubmission = $x['DeadlineSubmission'];
										foreach ($displayrows as $y) {
											if ($taskno === $y['taskno'] && $y['count'] == 2) {
												printf("<tr><td><a href='taskpage.php?taskno=$taskno'> %s </a></td> <td> <a href='taskpage.php?taskno=$taskno'> %s</a></td><td>%s</td><td>%s</td></tr>", $x['idTaskNo'],$x['Title'],$x['DeadlineClaiming'],$x['DeadlineSubmission']);
											}
										}
									} 
									// 1 tag match
									foreach ($row as $x) {
										$taskno = $x['idTaskNo'];
										$title = $x['Title'];
										$deadlineclaiming = $x['DeadlineClaiming'];
										$deadlinesubmission = $x['DeadlineSubmission'];
										foreach ($displayrows as $y) {		
											if ($taskno === $y['taskno'] && $y['count'] == 1) {
												printf("<tr><td><a href='taskpage.php?taskno=$taskno'> %s </a></td> <td> <a href='taskpage.php?taskno=$taskno'> %s</a></td><td>%s</td><td>%s</td></tr>", $x['idTaskNo'],$x['Title'],$x['DeadlineClaiming'],$x['DeadlineSubmission']);
											}
										}
									} 
									// rest of the list
									foreach ($row as $x) { 
										$taskno = $x['idTaskNo'];										
										$title = $x['Title'];										
										$deadlineclaiming = $x['DeadlineClaiming'];
										$deadlinesubmission = $x['DeadlineSubmission'];
										foreach ($displayrows as $y) {		
											if ($taskno === $y['taskno'] && $y['count'] == 0) {
												printf("<tr><td><a href='taskpage.php?taskno=$taskno'> %s </a></td> <td> <a href='taskpage.php?taskno=$taskno'> %s</a></td><td>%s</td><td>%s</td></tr>", $x['idTaskNo'],$x['Title'],$x['DeadlineClaiming'],$x['DeadlineSubmission']);
											}
										}
									}
								} catch (PDOException $exception) {
										printf("Connection error: %s", $exception->getMessage());
								}
							}
						?>	  
					</tr>
				</tbody>
			</table>
		</div>
		<!-- End Table ---------------------------------------------------------------------------->
		<!-- End Main ----------------------------------------------------------------------------->
						   
				
		<!-- Footer ------------------------------------------------------------------------------->
		<footer id="footer">				
		</footer>

		<!-- Scripts ------------------------------------------------------------------------------>	
	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
	</body>
</html>
