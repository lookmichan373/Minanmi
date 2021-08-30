<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1-1</title>
</head>
<body>
<?php
//データベース接続設定
    $dsn='mysql:dbname=tb******db;host=localhost';
    $user='tb-******';
    $password='PASSWORD';
    $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //↑データベース操作でエラーが発生した場合に警告表示するための設定オプション
    //SQL文でテーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS tbm5"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"/*AUTO_INCREMENT。データを追加した時にカラムに対して現在格納されている最大の数値に 1 を追加した数値を自動で格納*/
    . "name char(32),"
    . "comment TEXT,"
    ."date DATETIME,"
    ."password char(32)"
    .");";
     $stmt = $pdo->query($sql);
     
     $date= date("Y/m/d H:i:s");
    if(!empty($_POST["edinum"]) && !empty($_POST["edipassword"])){
    $edinum=$_POST["edinum"];
    $edipass=$_POST["edipassword"];
    $sql = 'SELECT id, name, comment, password FROM tbm5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
    $row['id'];
    $row['password'];  
    
    if($row['id'] == $edinum && $row['password'] == $edipass){
    $ediname=$row['name'];
    $edicom=$row['comment'];
    }
    elseif(!empty($_POST["edinum"]) && !empty($_POST["edipassword"]) && $row['id'] == $edinum){
    echo "!!パスワードが違います。入力し直して下さい。!!";
    }
    }
    }
 ?>   
 
<form action="" method="post">
    <p>入力フォーム</p>
    <input type="text" name="name" placeholder="名前" value="<?php if(!empty($ediname)){echo $ediname;}?>">
    <br>
    <input type="text" name="com" placeholder="コメント" value="<?php if(!empty($edicom)){echo $edicom;}?>">
    <br>
    <input type="hidden" name="displayedinum" value="<?php if(!empty ($edinum) && !empty($ediname) && !empty($edicom)){echo $edinum;}?>">
    <input type="password" name="password" placeholder="パスワード">
    <input type="submit" name="submit" value="送信">
    <p>削除フォーム</p>
    <input type="number" name="delnum" placeholder="削除対象番号">
    <br>
    <input type="password" name="delpassword" placeholder="パスワード">
    <input type="submit" name="submit" value="削除">
    <p>編集フォーム</p>
    <input type="number" name="edinum" placeholder="編集対象番号">
    <br>
    <input type="password" name="edipassword" placeholder="パスワード">
    <input type="submit" name="submit" value="編集">
    </form> 
    
    <?php
    //投稿フォームに投稿があった場合
    if(!empty($_POST["name"]) && !empty($_POST["com"]) && empty($_POST["displayedinum"]) && !empty($_POST["password"])){
    //受信した内容を変数に代入
    $name=$_POST["name"]; 
    $com=$_POST["com"];
    $pass=$_POST["password"];
    //データ入力
    $sql = $pdo -> prepare("INSERT INTO tbm5 (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $com, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':password', $pass, PDO::PARAM_STR);
    $sql -> execute();
    
    $sql = 'SELECT id,name,comment,date FROM tbm5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
    echo $row['id'].' ';
    echo $row['name'].' ';
    echo $row['comment'].' ';
    echo $row['date'].'<br>';
    }
    
    }
    //削除フォームに投稿があった場合
    if(!empty($_POST["delnum"]) && !empty($_POST["delpassword"])){
    //削除フォームで受信した内容を変数に代入
    $delnum=$_POST["delnum"];
    $delpass=$_POST["delpassword"];
  
    //投稿番号とパスワードを取得するために配列から1行ずつ取り出す
    $sql = 'SELECT id, password FROM tbm5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){  
    //投稿番号とパスワードを取得
    $row['id'];
    $row['password'];
    //投稿番号と削除番号が一致かつパスワードが一致していれば削除
    if($row['id']==$delnum && $row['password']==$delpass){
    //削除番号の行を削除
    //テーブルを作る時にidにPRIMARY KEYを付けたため、id指定だけで一行丸ごと削除可能
    $delid = $delnum;
    $sql = 'delete from tbm5 where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $delid, PDO::PARAM_INT);
    $stmt->execute();
    //表示
    $sql = 'SELECT id,name,comment,date FROM tbm5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
    echo $row['id'].' ';
    echo $row['name'].' ';
    echo $row['comment'].' ';
    echo $row['date'].'<br>';
    }
    }
    elseif(!empty($_POST["delnum"]) && !empty($_POST["delpassword"]) && $row['id']==$delnum){
    echo "!!パスワードが違います。入力し直して下さい。!!";
    }
    }
    }
    //編集するべきものを受け取った場合
    //編集する際に編集番号を送り、空でないときに送信されたものが新規投稿ではなく編集するもの
    if(!empty($_POST["displayedinum"]) && !empty($_POST["name"]) && !empty($_POST["com"])){
    //各投稿内容を受信し変数に代入
    $displayedinum=$_POST["displayedinum"];
    $nametoedit=$_POST["name"];
    $comtoedit=$_POST["com"];
    
    //displaynumに指定されている番号を編集
    $id = $displayedinum; //変更する投稿番号
    $sql = 'UPDATE tbm5 SET name=:name,comment=:comment WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $nametoedit, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comtoedit, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    //表示
    $sql = 'SELECT id,name,comment,date FROM tbm5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
    echo $row['id'].' ';
    echo $row['name'].' ';
    echo $row['comment'].' ';
    echo $row['date'].'<br>';
    }
    }
?>
</body>
</html>
    
