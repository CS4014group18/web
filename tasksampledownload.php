
<?php
if (!isset ($_SESSION)) {
	session_start();
}		
if (isset($_SESSION["user_id"])) {
	$id = $_SESSION["user_id"];
	if (isset($_GET)) { 
		$taskno = $_GET['taskno'];
		//printf("<!DOCTYPE html><html><head></head><body>");
		//printf("taskno %s\n",$taskno);
		//printf("</body>");
		try {
			$dbh = new PDO("mysql:host=localhost;dbname=group18","group18","STREAM-suit-PLUTO-team");
							//$taskno = $_POST["taskno"];
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
			//header("Location: tasklist.php");
		} catch (PDOException $exception) {
			printf("Connection error: %s", $exception->getMessage());
		}
	}						
}
?>
