<html>
	<meta charset="UTF-8">
	<body>
			
		<?php	
$dsn = 'mysql:dbname=データベース名;host=localhost';
	$user = 'ユーザー';
	$password = 'パスワード';
	$pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
			echo "database login...<br>";
			
			$formid=0;
			$formname ="名前";
			$formcomment ="コメント";
			$formpass="パスワード";
			
			if (isset($_POST["add"])){
				$formID=$_POST["judge"];
				$name = $_POST["name"];
				$comment = $_POST["comment"]; //get comment
				$addpass = $_POST["password"]; 
				$time = date("Y/m/d H:i:s");
				
				if ($formID==0){
					$sql = "CREATE TABLE IF NOT EXISTS formtableb"
					." ("
					. "id INT AUTO_INCREMENT PRIMARY KEY,"
					. "name char(32),"
					. "comment TEXT,"
					. "time datetime,"
					. "pass TEXT"
					.");";
					$stmt = $pdo->query($sql);
					echo "create table...<br>";		
		
					$sql = $pdo -> prepare("INSERT INTO formtableb (name, comment, time, pass) VALUES (:name, :comment, :time, :pass)");
					$sql -> bindParam(':name', $name, PDO::PARAM_STR);
					$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
					$sql -> bindParam(':time', $time, PDO::PARAM_STR);
					$sql -> bindParam(':pass', $addpass, PDO::PARAM_STR);
					$sql -> execute();
					echo "insert info...<br>";
				} else {
					$id = $formID; //変更する投稿番号
					$sql = 'update formtableb set name=:name,comment=:comment,time=:time,pass=:pass where id=:id';
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':name', $name, PDO::PARAM_STR);
					$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
					$stmt -> bindParam(':time', $time, PDO::PARAM_STR);
					$stmt -> bindParam(':pass', $addpass, PDO::PARAM_STR);
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->execute();
				}
			}
			
			if (isset($_POST["delete"])){
				$id = $_POST["ID"]; //get ID which need to be deleted
				$delpass = $_POST["delpass"];
				$check = $pdo -> query("SELECT * FROM formtableb where id = $id");
				$results = $check->fetchAll();
				$count=count($results);
					if ($count==0){
						echo "no data...<br>";
					}  else {
						foreach ($results as $row){
							$judge = $row['pass'];
						}
					
						if ($judge==$delpass) {
							$check = $pdo -> query("SELECT * FROM formtableb where id = $id");
							$results = $check->fetchAll();
							$count=count($results);
							if ($count==0){
								echo "no data...<br>";
							} else {
								echo "deleted ".$id."...<br>";
								
								$sql = 'delete from formtableb where id=:id';
								$stmt = $pdo->prepare($sql);
								$stmt->bindParam(':id', $id, PDO::PARAM_INT);
								$stmt->execute();
							}
						} else {
							echo "password mismatch";
						}
					}
			}
			
			if (isset($_POST["edit"])){
 				$id = $_POST["ediID"]; 
 				$edipass = $_POST["edipass"];
 				$check = $pdo -> query("SELECT * FROM formtableb where id = $id");
				$results = $check->fetchAll();
				$count=count($results);
					if ($count==0){
						echo "no data...<br>";
					}  else {
					foreach ($results as $row){
						$judge = $row['pass'];
					}
					if ($judge==$edipass) {
						$check = $pdo -> query("SELECT * FROM formtableb where id = $id");
						$results = $check->fetchAll();
						
							echo "read ".$id."...<br>";
							foreach ($results as $row){
								//$rowの中にはテーブルのカラム名が入る
								$formid = $row['id'];
								$formname = $row['name'];
								$formcomment = $row['comment'];
							}
					} else {
						echo "password mismatch";
					}
				}
			}
			
			echo "<hr>";	
		?>
		
		
		<form method="post">
			<input type="text" name="name" size="" value=<?php echo $formname; ?> > <br>
			<input type="text" name="comment" size="" value=<?php echo $formcomment; ?> > <br>
			<input type="text" name="password" size="" value=<?php echo $formpass; ?> > <br>
			<input type="hidden" name="judge" size="" value=<?php echo $formid; ?> >
			<input type = "submit" name = "add" value ="送信">
		</form>
		
		<form method="post">
			<input type="number" name="ID" size="" value="1" min=1> <br>
			<input type="text" name="delpass" size="" value=<?php echo $formpass; ?> > <br>
			<input type = "submit" name = "delete" value ="削除">
		</form>
	
		<form method="post">
			<input type="number" name="ediID" size="" value="1" min=1 > <br>
			<input type="text" name="edipass" size="" value=<?php echo $formpass; ?> > <br>
			<input type = "submit" name = "edit" value ="編集">
		</form>
			
			
		<?php
			$sql = 'SELECT * FROM formtableb';
			$stmt = $pdo->query($sql);
			$results = $stmt->fetchAll();
			echo "<hr>";
			foreach ($results as $row){
				echo $row['id'].'<br>';
				echo $row['name'].'<br>';
				echo $row['comment'].'<br>';
				echo $row['time'].'<br>';
				echo "<hr>";
			}
		?>
	

	</body>
<html>