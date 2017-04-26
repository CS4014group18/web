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
								printf("<li class=\"active\"><a href=\"./createtask.php\">Create Task</a></li>");
								printf("<li><a href=\"./tasklist.php\">Task Stream</a></li>");
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
				if (isset($_SESSION["user_id"])) {
					$id = $_SESSION["user_id"];
					/*printf("id %s\n",$id);*/
					if (isset($_POST) && count ($_POST) > 0 && $_POST["title"] != "" && $_POST["description"] != "" && $_POST["type"] != "" && $_POST["pages"] != "" && $_POST["words"] != "" && $_POST["format"] != ""  && $_FILES['userfile']['name'] != "" 
					    && $_POST["deadline_claiming"] != "" && $_POST["deadline_completion"] != "" && $_POST["tag1"] != "" && $_POST["tag2"] != "" && $_POST["tag3"] != "" && $_POST["tag4"] != "") {
						$title = htmlspecialchars(trim($_POST["title"]));
						/*printf("title %s\n",$title);*/
						$description = htmlspecialchars(trim($_POST["description"]));
						$description = substr($description,0,200);
						/*printf("description %s\n",$description);*/
						$type = htmlspecialchars(trim($_POST["type"]));
						/*printf("type %s\n",$type);*/
						$pages = htmlspecialchars(trim($_POST["pages"]));
						/*printf("pages %s\n",$pages);*/
						$words = htmlspecialchars(trim($_POST["words"]));
						/*printf("words %s\n",$words);*/
						$format = htmlspecialchars(trim($_POST["format"]));
						/*printf("format %s\n",$format);*/
						
						/*if (isset($_POST["userfile"])) {
							$sample = htmlspecialchars(trim($_POST["userfile"]));
						} else */
						$sample = $_FILES['userfile']['name'];
						$deadlineclaiming = htmlspecialchars(trim($_POST["deadline_claiming"]));
						/*printf("deadlineclaiming %s\n",$deadlineclaiming);*/
						$deadlinecompletion = htmlspecialchars(trim($_POST["deadline_completion"]));
						/*printf("deadlinecompletion %s\n",$deadlinecompletion);*/
						
						try {
							$dbh = new PDO("mysql:host=localhost;dbname=group18","group18","STREAM-suit-PLUTO-team");
							/*idTaskNo	UserCreated	Title	Type	Description	Pages	Words	Format	Sample	DeadlineClaiming	DeadlineSubmission*/
							$query = "INSERT INTO task SET UserCreated = :usercreated, Title = :title, Type = :type, Description = :description, Pages = :pages, Words = :words, Format = :format, Sample = :sample, DeadlineClaiming = :deadlineclaiming, DeadlineSubmission = :deadlinecompletion";
							$stmt = $dbh->prepare($query);
							$affectedRows = $stmt->execute(array(':usercreated' => $id, ':title' => $title, ':type' => $type, ':description'=> $description, ':pages' => $pages, ':words' => $words, ':format' => $format, ':sample' => $sample, ':deadlineclaiming' => $deadlineclaiming, ':deadlinecompletion' => $deadlinecompletion));
							if ($affectedRows > 0) {
								$taskno = $dbh->lastInsertId();
								$query = "SELECT idStatusName FROM statusname WHERE status='PENDING'";
								$stmt = $dbh->prepare($query);
								$stmt->execute();
								$row = $stmt->fetch(PDO::FETCH_ASSOC);
								$idstatus = $row['idStatusName'];								
								$date = date ("Y/m/d H:i:s");
								$query = "INSERT INTO status SET TaskNo = :taskno, StatusName = :statusname, Date = :date";
								$stmt = $dbh->prepare($query);
								$stmt->execute(array(':taskno' => $taskno, ':statusname' => $idstatus, ':date' => $date));
								//printf("taskno %s",$taskno);
							
						
								$tags = array($_POST["tag1"], $_POST["tag2"], $_POST["tag3"], $_POST["tag4"]);
								
								for ($x = 0; $x < 4; $x++) {
									//search first for tag 
									unset($tagno);
									$query = "SELECT idtags,description FROM tags WHERE description = ?";
									$stmt = $dbh->prepare($query);
									$stmt->execute(array($tags[$x]));
									while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {        // while loop not needed? only one or no rows returned
										$desc = $row['description'];
										$tagno = $row['idtags'];
									}
									if (!isset($tagno)) { 
									// insert into tags
										$query = "INSERT INTO tags SET description = :tag";
										$stmt = $dbh->prepare($query);
										$affectedRows = $stmt->execute(array(':tag' => $tags[$x]));						
										//if ($affectedRows > 0) {
										$tagno = $dbh->lastInsertId();
										//printf("tagno %s",$tagno);
										//}
									}
									//printf("tagno %s",$tagno);
									// insert into tasktags
									$query = "INSERT INTO tasktags SET taskno = :taskno, tag = :tagno";
									$stmt = $dbh->prepare($query);
									$affectedRows = $stmt->execute(array(':taskno' => $taskno, ':tagno' => $tagno ));						
							
									// insert into usertags
									
									$query = "SELECT * from usertags WHERE id = :id and tag = :tagno";
									$stmt = $dbh->prepare($query);
									$stmt->execute(array(':id' => $id, ':tagno' => $tagno ));
									$affectedRows = $stmt->rowCount();
									if ($affectedRows == 0) {
										$query = "INSERT INTO usertags SET id = :id, tag = :tagno";
										$stmt = $dbh->prepare($query);
										$affectedRows = $stmt->execute(array(':id' => $id, ':tagno' => $tagno ));
									}
								}
							}
						} catch (PDOException $exception) {
							printf("Connection error: %s", $exception->getMessage());			
						}
					} if (isset($_POST) && count ($_POST) > 0) 
							if (isset($taskno)) printf("<h2>Task %s Created</h2>",$taskno);
							else printf("<h2>Task Creation Failure</h2>");
				}
        ?>
							
		<!-- Create Task Form --------------------------------------------------------------------->
		<?php
			if (!isset($_POST) || count ($_POST) <= 0) {
		?>
				<div class="container">	    
			        <form enctype="multipart/form-data" id ="createtask-form" action="createtask.php" method="post" >
					    <fieldset>
						    <div class="col-md-6">
						    <h2>Create Task</h2>
						    <div class="form-group">
						        <label> Title:</label>
							    <input autofocus class="form-control" name="title" placeholder="Title" type="text" required />
						    </div>
						    <div class="form-group">
						        <label> Description:</label>
							    <textarea class="form-control" rows="5" name="description" placeholder="Description" required ></textarea>
						    </div>
							<div class="form-group">
						        <label> Type:</label>
							    <input class="form-control" name="type" placeholder="type" type="text" required />               
						    </div>
						    <div class="form-group">
						        <label> Tag 1:</label>
							    <input class="form-control" name="tag1" placeholder="Tag 1" type="text" required />
						    </div>
						    <div class="form-group">
						        <label> Tag 2:</label>
							    <input class="form-control" name="tag2" placeholder="Tag 2" type="text" required />		                    
						    </div>
						    <div class="form-group">
						        <label> Tag 3:</label>
							    <input class="form-control" name="tag3" placeholder="Tag 3" type="text" required />
						    </div>
						    <div class="form-group">
						        <label> Tag 4:</label>
							    <input class="form-control" name="tag4" placeholder="Tag 4" type="text" required />
						    </div>
						    <div class="form-group"> 
						        <label> Number of page(s):</label>
							    <input class="form-control" name="pages" placeholder="" type="text" required />
						    </div>
						    <div class="form-group">
						        <label> Number of words:</label>
							    <input class="form-control" name="words" placeholder="" type="text" required />
						    </div>
							<div class="form-group">
						        <label> Source format:</label>
							    <input class="form-control" name="format" placeholder="" type="text" required/>
						    </div>
							<div class="form-group"> <!-- remove seconds?-->
						        <label> Deadline Claiming (YYYY-MM-DD HH:MM:SS):</label>
							    <input class="form-control" name="deadline_claiming" placeholder="YYYY-MM-DD HH:MM:SS" type="text" onblur = "checkDate(this)"; required />
						    </div>
							<div class="form-group"> <!-- remove seconds? -->
						        <label> Deadline Completion (YYYY-MM-DD HH:MM:SS):</label>
							    <input class="form-control" name="deadline_completion" placeholder="YYYY-MM-DD HH:MM:SS" type="text" onblur = "checkDate(this)"; required/>
						    </div>
							<div class="form-group">
							<input type="file" name="userfile" />
							</div>
						    <div class="form-group">
							    <button type="submit" class="btn btn-success">Create Task</button>
						    </div>
							</div>
					    </fieldset>
				    </form>
                </div>
		<?php
				}
		?>		
        <!-- End Task Form ------------------------------------------------------------------------>
		
		<!-- Uploadmanager ------------------------------------------------------------------------>
		<?php 
			if (isset($_POST) && count ($_POST) > 0) {
				/*define ("FILEREPOSITORY","C://inetpub//wwwroot//modules//cs4014//group18//uploads"); //Set a constant*/
				define ("FILEREPOSITORY","uploads/");
				if (is_uploaded_file($_FILES['userfile']['tmp_name'])) { //file posted?

					$finfo = finfo_open(FILEINFO_MIME_TYPE);
					$mime = finfo_file($finfo, $_FILES['userfile']['tmp_name']);

					if ($mime != "application/pdf") { //is it a pdf?
						echo "<p>Sample must be uploaded in PDF format.</p>";     
					} else { // move uploaded file to final destination. 
						
						$name = $_FILES['userfile']['name'];
						$result = move_uploaded_file($_FILES['userfile']['tmp_name'],  FILEREPOSITORY."$name");
            
						if ($result == 1) {
							echo "<p>File successfully uploaded.</p>";
						} else {
							echo "<p>There was a problem uploading the file.</p>";
						}
					} //endIF
				} //endIF
			} 
		?>		
		<!-- End Uploadmanager -------------------------------------------------------------------->
		
		<!-- End Main page -->
		 		
		<!-- Footer ------------------------------------------------------------------------------->
		<footer id="footer">				
		</footer>

		<!-- Scripts ------------------------------------------------------------------------------>	
	    <script src="js/jquery-3.2.1.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/bootstrapValidator.js"></script>
		<script src="js/createtaskValidator.js"></script>
		<script type="text/javascript"></script>
		
		<script>
		<!-- adapted from http://www.the-art-of-web.com/javascript/validate-date/ -->
		  function checkDate(deadline)
		  {
			// regular expression to match required date and time format
			re = /^(\d{4})-(\d{2})-(\d{2})\s+(\d{2}):(\d{2}):(\d{2})$/;

			if(deadline.value != '') {
			    if(regs = deadline.value.match(re)) {				
			    // year value greater or equal this year 
					var todayDate = new Date(),
        			endDate = new Date( todayDate.getFullYear()+1, todayDate.getMonth(), todayDate.getDate());
					if(regs[1] < todayDate.getFullYear() || regs[1] > endDate.getFullYear()) {
					  alert("Invalid value for year: " + regs[1] + " - must be between " + todayDate.getFullYear() + " and " + endDate.getFullYear());
					  deadline.focus();
					  return false;
					}				
					// month value between 1 and 12
					if(regs[2] < 1 || regs[2] > 12) {
					  alert("Invalid value for month: " + regs[2]);
					  deadline.focus();
					  return false;
					}
					// day value between 1 and 31
					if(regs[3] < 1 || regs[3] > 31) {
					  alert("Invalid value for day: " + regs[1]);
					  deadline.focus();
					  return false;
					}
					  // 24-hour value between 0 and 23
					if(regs[4] < 1 || regs[4] > 23) {
						alert("Invalid value for hours: " + regs[4]);
						deadline.focus();
						return false;
					  }				
					// minute value between 0 and 59
					if(regs[5] > 59) {
					  alert("Invalid value for minutes: " + regs[5]);
					  deadline.focus();
					  return false;
					}
					// seconds value between 0 and 59
					if(regs[6] > 59) {
					  alert("Invalid value for seeconds: " + regs[6]);
					  deadline.focus();
					  return false;
					}
				}
			} else {
				alert("Invalid date time format: " + deadline.value);
				deadline.focus();
				return false;
			}		
			//alert("All input fields have been validated!");
			return true;
		  }
		</script>
	</body>
</html>