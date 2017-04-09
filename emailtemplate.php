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
		<?php
			if (isset($_SESSION["user_id"])) {
				$id = $_SESSION["user_id"];
				if (isset($_POST['to']) && isset($_POST["subject"]) && isset($_POST["text"])) {
					$to = $_POST['to'];
					$subject = $_POST['subject'];
					$text = $_POST['text'];
					// Set content-type header for sending HTML email
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

					// Additional headers
					$headers .= 'From: CodexWorld<sender@example.com>' . "\r\n";
					//$headers .= 'Cc: welcome@example.com' . "\r\n";
					//$headers .= 'Bcc: welcome2@example.com' . "\r\n";

					// Send email
					if(mail($to,$subject,$text,$headers))
						printf("<h2>Email sent successfully</h2>");
					else {
						printf("<h2>Email failure</h2>");
					}
				}
			}
		?>
		<!-- Email Template Form ------------------------------------------------------------------>
		<?php
			if (!isset($_POST) || count ($_POST) <= 0) {
									
		?>
				<div class="container">	    
			        <form enctype="multipart/form-data" action="emailtemplate.php" method="post" >
					    <fieldset>
						    <div class="col-md-6">
						    <h2>Email</h2>
						    <div class="form-group">
							
						        <label> To:</label>
								<?php
								      if (isset($_GET)) { 
										$email = $_GET['recepient'];
									    printf("<input autofocus class=\"form-control\" name=\"to\" placeholder=\"To\" type=\"text\" \"required\" value= %s />",$email);
								      } else printf("<input autofocus class=\"form-control\" name=\"to\" placeholder=\"To\" type=\"text\" \"required\"/>");
								?>
						    </div>
						    <div class="form-group">
						        <label> Subject:</label>
								<?php
								      if (isset($_GET)) { 
										$title = $_GET['title'];
										$title = urldecode($title);
										//printf("title %s<br>",$title);
									    printf("<input autofocus class=\"form-control\" name=\"subject\" placeholder=\"Subject\" type=\"text\" \"required\" value= \"%s\" />",$title);
								      } else printf("<input autofocus class=\"form-control\" name=\"subject\" placeholder=\"Subject\" type=\"text\" \"required\"/>");
								?>
						    </div>
							<div class="form-group">
						        <label> Text:</label>
							    <textarea class="form-control" rows="5" name="text" placeholder="Text" type="text" "required" ></textarea>               
						    </div>
						    <div class="form-group">
							    <button type="submit" class="btn btn-success">Send</button>
						    </div>
							</div>
					    </fieldset>
				    </form>
                </div>
		<?php
				}
		?>		

        <!-- End Task Form ------------------------------------------------------------------------>