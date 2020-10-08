<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset=UTF-8>
    <title>"mission_5-1"</title>
</head>
<body>
    <form action=""method="post">
        <p>［投稿フォーム ]<br><input type="text" name="name" placeholder="名前">
        <input type="text" name="comment" placeholder="コメントをどうぞ"><br>
        <input type="password" name="pass" style="ime-mode:disabled;" placeholder="password">
        <input type="submit" name="submit"><br>*********************************************
        </p>
        <p>[ 削除フォーム]<br><input type="num" name="del" style="width:100px" placeholder="削除対象番号"><br>
        <input type="password" name="del_pass" style="ime-mode:disabled;" placeholder="password">
        <input type="submit" name="delete" value="削除"><br>*********************************************
        </p>
        <p>[ 編集フォーム ]<br>投稿番号:<input type="num" name="edit_num" placeholder="番号"><br>
        編集内容:<input type="str" name="edit_comment" ><br>
        <input type="password" name="edit_pass" style="ime-mode:disabled;" placeholder="password">
        <input type="submit" name="edit_submit" value="edit">
        </p><hr>
    </form>
<?php
    //データベース接続に必要な情報、接続
	$dsn = 'データベース名';
	$user = 'ユーザ名';
	$password = 'パスワード';
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
	$comment = $_POST["edit_comment"]; //コメント変更
	$sql = 'UPDATE first SET comment=:comment WHERE id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
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
		echo $row['comment'].',';
		echo $row['timestamp'].',';
		echo $row['pass'].'<br>';
    	echo "<hr>";
}
 
?>
</body>
</html>
