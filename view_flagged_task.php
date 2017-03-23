<!DOCTYPE html>
<html>
    <head>
	    <title>Proofreading Website</title>
		<meta name="viewport" content="width=device-widht, initial-scale=1.0"/>
		<link href="css/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/styles.css" rel="stylesheet"/>
	</head>
	
	<body>	
		<!-- Header -->
		<header id="header" class="alt">
		</header>	
		
		<!-- Nav bar --->
		<div class="navbar navbar-inverse navbar-static-top">
		    <div class="container">
			 
			    <a href="" class="navbar-brand">Proofreading Website</a>
				 
				<!-- Mobile responsiveness -->
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
                                								
								printf("<li class=\"active\"><a href=\"./createtask.php\">Create Task</a></li>");
								printf("<li><a href=\"./register.php\">Register</a></li>");
								//printf("<li><a href=\"./logout.php\">Logout</a></li>");	
							} else {
								printf("<li><a href=\"./login.php\">Login</a></li>");
							}
							?>
					</ul>
				</div>				 
			</div>
		</div>		 
		<!-- End Nav bar ------------------------------------------------------------------->
		
		<!-- Main page ------------------------------------------------------------------------>
		<?php
				if (isset($_GET["id"])) {
					$id = $_GET["id"];
					try {
						$dbh = new PDO("mysql:host=localhost;dbname=group18", "root", "");
						/*$stmt = $dbh->prepare("SELECT title, description FROM `task` WHERE id=:id" );
						$stmt->bindValue(':id', $id);
						$stmt->execute();
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						
						if ($row) {
							printf("<h2> %s </h2> <p> %s </p>\n", $row["title"], $row["description"]);
						} else {
							printf("Task not found.");
						}*/
					} catch (PDOException $exception) {
						printf("Connection error: %s", $exception->getMessage());
				
					}
				}

        ?>

<div class="container">	    
	<form  action="taskpage.php" method="post">
		<fieldset>
			<div class="row">
			   <div class="col-md-offset-3 col-md-6">
				  <h2>Flagged Task</h2>
					<div class="form-group">
						    <label> Title:</label>
							<input autofocus class="form-control" name="title" placeholder="Title" "required" type="text" />
						    </div>
						    <div class="form-group">
						        <label> Description:</label>
							    <textarea class="form-control" rows="5" id="description" placeholder="Description" "required"></textarea>
						    </div>
						    <div class="form-group">
						        <label> Tag 1:</label>
							    <input autofocus class="form-control" name="tag1" placeholder="Tag 1" "required" type="text" />
						    </div>
						    <div class="form-group">
						        <label> Tag 2:</label>
							    <input autofocus class="form-control" name="tag2" placeholder="Tag 2" "required" type="text" />
		                    
						    </div>
						    <div class="form-group">
						        <label> Tag 3:</label>
							    <input autofocus class="form-control" name="tag3" placeholder="Tag 3" type="text" "required"/>
						    </div>
						    <div class="form-group">
						        <label> Tag 4:</label>
							    <input class="form-control" name="tag4" placeholder="Tag 4" type="text" "required"/>
						    </div>
						    <div class="form-group">
						        <label> Number of page(s):</label>
							    <input class="form-control" name="pagenumber" placeholder="" type="text" "required"/>
						    </div>
						    <div class="form-group">
						        <label> Number of words:</label>
							    <input class="form-control" name="wordnumber" placeholder="" type="text"/ "required">
						    </div>
							
						    <div class="form-group">
							    <button type="submit" class="btn btn-success">Download Sample</button>
						    </div>
							<div class="form-group">
							    <button type="submit" class="btn btn-default">Un-Publish</button>
						    </div>
							<div class="form-group">
							    <button type="submit" class="btn btn-danger">Ban User</button>
						    </div>
							</div>
						  </div>
					    </fieldset>
				    </form>
                </div>
				
<!-- Footer ------------------------------------------------------------------------->
		<footer id="footer">				
		</footer>

		<!-- Scripts -->	
	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
	</body>
</html>
