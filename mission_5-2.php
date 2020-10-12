<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset=UTF-8>
    <title>"mission_5-1"</title>
    <link rel="stylesheet" href="mission_5-3.css">
</head>
<body>
<header>
    <div class="title">簡易掲示板</div>
</header>

    
    <form action=""method="post">
      <section class="forms">
        <p>
        <h3>[ 投稿フォーム ]</h3><br>
        <div class="form">
        NAME:<input type="text" name="name" placeholder="名前"><br>
        <input type="text" name="comment" style="width:500px;height:100px" placeholder="コメントをどうぞ"><br>
        PASSWORD:<input type="password" name="pass" style="ime-mode:disabled;" placeholder="password">
        <input type="submit" name="submit"><br>*********************************************
        </p></div>
        <p>
        <h3>[ 削除フォーム ]</h3><br>
        <div class="form">
        NO.<input type="num" name="del" style="width:100px" placeholder="削除対象番号"><br>
        PASSWORD:<input type="password" name="del_pass" style="ime-mode:disabled;" placeholder="password">
        <input type="submit" name="delete" value="削除"><br>*********************************************
        </p></div>
        <p>
        <h3>[ 編集フォーム ]</h3><br>
        <div class="form">
        NO.<input type="num" name="edit_num" placeholder="投稿番号"><br>
        <input type="str" name="edit_comment" style="width:500px;height:100px" placeholder="編集内容"><br>
        PASSWORD:<input type="password" name="edit_pass" style="ime-mode:disabled;" placeholder="password">
        <input type="submit" name="edit_submit" value="編集">
        </p></div>
    </form><hr>
    

<?php
    //データベース接続に必要な情報、接続
	$dsn = 'mysql:dbname=tb220606db;host=localhost';
	$user = 'tb-220606';
	$password = 'FQFDD3Xvre';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	//テーブル作成（名前、コメント、投稿日時、パスワードの情報を入れる）
    $sql = "CREATE TABLE IF NOT EXISTS first"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	."timestamp char(32),"
	."password char(32)"
	.");";
	$stmt = $pdo->query($sql);
	
    //フォームに打ち込まれたものを受け取る
  if($_POST["submit"]){
     if(isset($_POST["name"]) && isset($_POST["comment"])){
       $name=$_POST["name"];
       $comment=$_POST["comment"];
       $date=date("Y/m/d H:i:s");
       $pass=$_POST["pass"];
      
    //データベースにデータを保存
        $sql = $pdo -> prepare("INSERT INTO first (name, comment,timestamp,password) VALUES (:name, :comment,:timestamp,:password)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':timestamp', $date, PDO::PARAM_STR);
	$sql -> bindParam(':password', $pass, PDO::PARAM_STR);
	$sql -> execute();
	
}}
    //入力されたデータの削除
  if($_POST["delete"]){
     if(isset($_POST["del"]) && isset($_POST["del_pass"])){
        $id = $_POST["del"];//どのidのものを削除するか
	$pass=$_POST["del_pass"];
	$sql = 'delete from first where id=:id and password=:password';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':password', $pass, PDO::PARAM_STR);
	$stmt->execute();
		
}}
    //編集
  if($_POST["edit_submit"]){
     if(isset($_POST["edit_comment"]) && isset($_POST["edit_pass"]) && isset($_POST["edit_num"])){
        $id = $_POST["edit_num"]; //変更する投稿番号
	$pass=$_POST["edit_pass"];     
	$comment = $_POST["edit_comment"]; //コメント変更
	$sql = 'UPDATE first SET comment=:comment WHERE id=:id and password=:password';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':password', $pass, PDO::PARAM_STR);
	$stmt->execute();  
	 
}}

	
	//データベースに入っている情報を表示
    $sql = 'SELECT * FROM first';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
	    
	//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['timestamp'].',';
		echo $row['comment'].'<br>';
    	echo "<hr>";
}
 
?>
</section>
</body>
</html>
