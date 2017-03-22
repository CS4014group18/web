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
						<li><a href="index.php">Home</a></li>
						<?php 
							if (!isset ($_SESSION)) {
								session_start();
							}
							
							if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != ''){ 
                                
                                // Display create task in navbar								
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
		
				
		<!-- Create Task Form ------------>
		<div class="container">	    
			        <form  enctype="multipart/form-data" action="createtask.php" method="post">
					    <fieldset>
						    <div class="col-md-6">
						    <h2>Create Task</h2>
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
						        <label> Source format:</label>
							    <input class="form-control" name="sourceformat" placeholder="" type="text" "required"/>
						    </div>
							<div class="form-group">
						        <label> Deadline Claiming:</label>
							    <input class="form-control" name="deadeline_claiming" placeholder="" type="text" "required"/>
						    </div>
							<div class="form-group">
						        <label> Deadline Completion:</label>
							    <input class="form-control" name="deadline_completion" placeholder="" type="text" "required"/>
						    </div>
							<div  class="form-group">
							    <label>Last Name:<br /></label> 
							    <input type="text" class="form-control" name="name" value="" /><br />
							</div>
							<div class="form-group">
							<label>Class Notes:<br /></label> 
							<input type="file" name="classnotes" value="" /><br />
							<button type="submit" class="btn btn-primary" name="submit" value="Submit Notes" />Submit Notes</button>
							</div>
						    <div class="form-group">
							    <button type="submit" class="btn btn-success">Create Task</button>
						    </div>
							</div>
					    </fieldset>
				    </form>
                </div>

        <!-- End Task Form ------------------------------------------>
		
		<!-- Uploadmanager ------>
		 <?php
    define ("FILEREPOSITORY","C://file_uploads"); //Set a constant
    
    
    if (is_uploaded_file($_FILES['classnotes']['tmp_name'])) { //file posted?

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['classnotes']['tmp_name']);

        if ($mime != "application/pdf") { //is it a pdf?
            echo "<p>Class notes must be uploaded in PDF format.</p>"; 
        
        } else { /* move uploaded file to final destination. */
            
            $name = $_POST['name'];
            $result = move_uploaded_file($_FILES['classnotes']['tmp_name'],  FILEREPOSITORY."//$name.pdf");
            
            if ($result == 1) {
                echo "<p>File successfully uploaded.</p>";
            } else {
                echo "<p>There was a problem uploading the file.</p>";
            }
        } //endIF
    } //endIF
    ?>
	
	<!-- End Uploadmanager ----->
				
    <!-- End Main page -->
		 		
		<!-- Footer ------------------------------------------------------------------------->
		<footer id="footer">				
		</footer>

		<!-- Scripts -->	
	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
	</body>
</html>