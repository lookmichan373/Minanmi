<!DOCTYPE html>
<meta charset="UTF-8">
<title>掲示板サンプル</title>
<h1>掲示板</h1>
<section>
    <h2>投稿完了</h2>
    <button onclick="location.href='Bboard.php'">戻る</button>
</section>
<?php
 $id = NULL;
 $name = $_POST['name'];
 $contents = $_POST['contents'];
 //データベース接続
 $dsn = 'mysql:dbname=tb230232db;host=localhost';
    $user = 'tb-230232';
    $password = 
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
 $sql = $pdo -> prepare("INSERT INTO post (id, name, contents) VALUES (:id, :name, :contents)");
 $sql -> bindParam(':id', $id, PDO::PARAM_INT);
 $sql -> bindParam(':name', $name, PDO::PARAM_STR);
 $sql -> bindParam(':contents', $contents, PDO::PARAM_STR);
 $sql -> execute();
?>
<?php
 $dsn = 'mysql:dbname=tb230232db;host=localhost';
    $user = 'tb-230232';
    $password =
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
 $sql = "CREATE TABLE IF NOT EXISTS post (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(32) NOT NULL,
  contents TEXT
 )";
 $stmt = $pdo->query($sql);
 $sql = 'SHOW TABLES';
 $stmt = $pdo->prepare("SELECT * FROM post");
 $stmt->execute();
?>


<!DOCTYPE html>
<meta charset="UTF-8">
<title>掲示板</title>
<h1>掲示板</h1>
<section>
    <h2>新規投稿</h2>
    <form action="send.php" method="post">
        名前 : <input type="text" name="name" value=""><br>
        投稿内容: <input type="text" name="contents" value=""><br>
        <button type="submit">投稿</button>
    </form>
</section>
<section>
 <h2>投稿内容一覧</h2>
  <?php if ($stmt) foreach($stmt as $loop):?>
   <div>No：<?php echo $loop['id']?></div>
   <div>名前：<?php echo $loop['name']?></div>
   <div>投稿内容：<?php echo $loop['contents']?></div>
   <div>------------------------------------------</div>
  <?php endforeach;?>
</section>
<form action="" method="POST"> 
    <p>新規投稿</p>
        <input type="text" name="name" placeholder="名前"><br>
        <input type="text" name="comment" placeholder="コメント"><br>
        <input type="text" name="form_password" placeholder="パスワード"><br>
        <input type="submit" name="submit"><br>
   <p>削除</p>
        <input type="text" name="deletenum" placeholder="削除"><br>
        <input type="text" name="delete_password" placeholder="パスワード"><br>
        <input type="submit" name="submit"><br>
   <p>編集</p>
        <input type="text" name="editnum" placeholder="編集"><br>
        <input type="text" name="edit_name" placeholder="名前"><br>
        <input type="text" name="edit_comment" placeholder="コメント"><br>
        <input type="text" name="edit_password" placeholder="パスワード"><br>
        <input type="submit" name="submit"><br>
    </form>
    