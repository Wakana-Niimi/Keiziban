<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
        <?php
            //データベース接続
            $dsn ='データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            //テーブル作る
            $sql = "CREATE TABLE IF NOT EXISTS tbmission_5"
               . " ("
               . "id INT AUTO_INCREMENT PRIMARY KEY,"
               . "name char(32),"
               . "comment TEXT,"//最後「,」忘れずに！
               . "date DATETIME"
               .");";
               $stmt = $pdo->query($sql);
            //編集ボタン
            if(!empty($_POST["edit_num"])){
                $id = $_POST["edit_num"];
                $sql ='SELECT * FROM tbmission_5 WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id',$id,PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchAll();
                    foreach($results as $row){
                        $edit_num = $row['id'];
                        $edit_name = $row['name'];
                        $edit_com = $row['comment'];
                    }
            }
        ?>
        <form action = "" method = "post">
            <input type = "hidden" name = "edit_num2" value = "<?php echo $edit_num;?>">
            <input type = "text" name = "name" placeholder = "名前" value = "<?php echo $edit_name;?>">
            <input type = "text" name = "comment" placeholder = "コメント" value = "<?php echo $edit_com;?>">
            <input type = "submit" name = "submit">
            <br>
            <input type = "number" name = "del_num" placeholder = "削除番号">
            <input type = "submit" name = "delete" value = "削除">
            <br>
            <input type = "number" name = "edit_num" placeholder = "編集番号">
            <input type = "submit" name = "edit" value = "編集">
        </form>
        <?php
            //送信ボタン
            if(!empty($_POST["name"]) && !empty($_POST["comment"])){
                //編集(編集番号入ってたら)
                if(!empty($_POST["edit_num2"])){
                    $id = $_POST["edit_num2"];
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $date = date("Y/m/d H:i:s");
                    $sql = 'UPDATE tbmission_5 SET name=:name,comment=:comment,date=:date WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name',$name,PDO::PARAM_STR);
                    $stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
                    $stmt->bindParam(':date',$date,PDO::PARAM_STR);
                    $stmt->bindParam(':id',$id,PDO::PARAM_INT);
                    $stmt->execute();
                //新規(編集番号入ってなかったら)
                }else{
                    $sql = $pdo -> prepare("INSERT INTO tbmission_5(name,comment,date) VALUES (:name,:comment,:date)");
                    $sql -> bindParam(':name',$name, PDO::PARAM_STR);                                                           
                    $sql -> bindParam(':comment',$comment, PDO::PARAM_STR);
                    $sql -> bindParam(':date',$date, PDO::PARAM_STR);
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $date = date("Y/m/d H:i:s");
                    $sql -> execute();
                }
            }
            //削除ボタン
            if(!empty($_POST["del_num"])){
                $id = $_POST["del_num"];
                $sql = 'delete from tbmission_5 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id',$id,PDO::PARAM_INT);
                $stmt->execute();
            }
            //データ表示
            $sql ='SELECT * FROM tbmission_5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['date'].'<br>';
                echo "<hr>";
            }
        ?>
    </body>
</html>