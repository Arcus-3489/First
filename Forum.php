<!DOCTYPE html>
<?php

// DB接続設定
$dsn = 'データーベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

// テーブル作成
    
$sql = "CREATE TABLE IF NOT EXISTS tbtest"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "date TEXT,"
. "password TEXT"
.");";
$stmt = $pdo->query($sql);
$sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
    echo "<hr>";
    
$newname = null;
$newcomment = null;
$id = null;
$pw = null;

if (isset($_POST["submit2"]) && strlen($_POST["pw3"])) {
    $id = $_POST["num1"];
    $pw = $_POST["pw3"];

    // パスワードが一致していたら、該当の投稿を編集
    $sql = "select * from tbtest where id=$id";
    $stmt = $pdo->query($sql);
    //$results = $stmt->fetchAll();
    foreach ($stmt as $row){
        //$rowの中にはテーブルのカラム名が入る
        $oldpw = $row['password'];
    }
    if (isset($oldpw)) {
        if ($pw == $oldpw) {
            // 該当の投稿を取り出し
            $sql = "select * from tbtest where id=$id";
            $stmt = $pdo->query($sql);
            //$results = $stmt->fetchAll();
            foreach ($stmt as $row){
                //$rowの中にはテーブルのカラム名が入る
                $id = $row['id'];
                $newname = $row['name'];
                $newcomment = $row['comment'];
                $pw = $row['password'];
            }
        }
    } else {
        $id = null;
        $pw = null;
    }
}

?>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-01</title>
    </head>
    <body>
    <f1> ただの掲示板</f1>
        <form action = "" method = "post">
            名前
            <input type = "text" name = "str0"
            value = "<?php 
            ini_set('display_errors', 0);
            echo $newname;
            ?>">
            <br>
            
            コメント
            <input type = "text" name = "str1"
            value = "<?php 
            ini_set('display_errors', 0);
            echo $newcomment;
            ?>">
            <br>

            パスワード
            <input type = "text" name = "pw1">
            <input type = "submit" name = "submit0">
            <br><br>
            
            削除対象番号
            <input type = "number" name = "num"
            value = "">
            <br>
            削除用パスワード
            <input type = "text" name = "pw2">
            <input type = "submit" name = "submit1"
            value = "削除">
            <br><br>
            
            編集対象番号
            <input type = "number" name = "num1"
            value = "">
            <br>
            編集用パスワード
            <input type = "text" name = "pw3">
            <input type = "submit" name = "submit2"
            value = "編集">
                
            <input type = "hidden" name = "num2" value = "<?php
            echo $id;
            ?>">
            <input type = "hidden" name = "num3" value = "<?php
            echo $pw;
            ?>">
            <br>
            ※編集する場合は、編集対象番号と編集用パスワードを入力すると入力した名前とコメントが
            <br>
            フォーム内に表示されます。編集を終えたら同一パスワードを入力して「送信」をクリックしてください。
            <br><br>
            ＜掲示板＞<br>
            No.／名前／コメント／投稿日時
            <br><br>

        </form>
    
    </body>
    </html>
    
<?php
if (isset($_POST["submit0"]) && strlen($_POST["pw1"])) {
    if ($_POST["num2"] != null) {
        $id = $_POST["num2"];  //変更する投稿番号
        $pw = $_POST["pw1"];

        // パスワードが一致していたら、該当の投稿を編集
        $sql = "select * from tbtest where id=$id";
        $stmt = $pdo->query($sql);
        //$results = $stmt->fetchAll();
        foreach ($stmt as $row){
            //$rowの中にはテーブルのカラム名が入る
            $oldpw = $row['password'];
        }

    
        if ($pw == $oldpw) {
            
            $name = $_POST["str0"];
            $comment = $_POST["str1"]; //変更したい名前、変更したいコメントは自分で決めること
            $date = date("Y/m/d H:i:s");

            $sql = "UPDATE tbtest SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':password', $pw, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $pw, PDO::PARAM_STR);
            $stmt->execute();
        } else {
        }

    } else {

        $name = $_POST["str0"];
        $comment = $_POST["str1"]; //好きな名前、好きな言葉は自分で決めること
        $date = date("Y/m/d H:i:s");
        $pw = $_POST["pw1"];
        // レコード挿入    
        $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, password) 
        VALUES (:name, :comment, :date, :password)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password', $pw, PDO::PARAM_STR);
        $sql -> execute();
    }


} else if (isset($_POST["submit1"]) && strlen($_POST["pw2"]) ) {
    $id = $_POST["num"];
    $pw = $_POST["pw2"];
    $oldpw = "";

// パスワードが一致していたら、該当の投稿を削除
    $sql = "select * from tbtest where id=$id";
    $stmt = $pdo->query($sql);
    //$results = $stmt->fetchAll();
    foreach ($stmt as $row){
		//$rowの中にはテーブルのカラム名が入る
        $oldpw = $row['password'];
    }
    
  if ($pw == $oldpw) {
    $sql = "delete from tbtest where id=$id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    }

}

    /* // レコード編集
    $id = 1; //変更する投稿番号
	$name = "rain";
	$comment = "Hello PHP"; //変更したい名前、変更したいコメントは自分で決めること
	$sql = 'UPDATE tbtest SET name=:name,comment=:comment WHERE id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();

    */

    /*
    // レコード削除
    $id = 3;
	$sql = 'delete from tbtest where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    */

/*
     //【！この SQLは tbtest テーブルを削除します！】
		$sql = 'DROP TABLE tbtest';
        $stmt = $pdo->query($sql);
  */  
    
    // レコード表示
 
    $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
    foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'';
        //echo $row['password'].'<br>';
        echo '<br>';
	echo "<hr>";
	}

?>