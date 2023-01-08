<!DOCTYPE html>

<html lang="ja">

<head>

    <meta charset="UTF-8">

    <title>簡易掲示板</title>

</head>

<body>
    <h1>おすすめの観光地は？</h1>
<!--ここから書く-->
    <?php
    //テーブルを作成する
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = "CREATE TABLE IF NOT EXISTS tb001"
    //「 IF NOT EXISTS 」は「もしまだこのテーブルが存在しないなら」
    ." ("
        //id ・自動で登録されているナンバリング。
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
        //name ・名前を入れる。文字列、半角英数で32文字
    . "name char(32),"
        //コメントを入れる。文字列、長めの文章も入る。
    . "comment TEXT,"
        //時間
    ."created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,"
        //パスワード
    ."password char(32)"
    .");";
    $stmt = $pdo->query($sql);

    ?>    

    <?php

    

    //----------------------削除フォーム処理-----------------------------------------------
    if (!empty ($_POST["delete"]) && !empty ($_POST["password2"])){

        // DB接続設定
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        $id = (int)$_POST["delete"];
        $delete_password = $_POST ["password2"];
        $sql = 'delete from tb001 where id=:id AND password=:password2';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':password2', $delete_password, PDO::PARAM_INT);
        $stmt->execute();

    }


    //-------------------編集番号指定用フォーム処理-----------------------
    if (!empty ($_POST["edit"]) && !empty ($_POST["password3"])){
            // DB接続設定
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        //この絞り込み部分を「WHERE句」と言います。SELECT文の他、UPDATE文やDELETE文でも使われます
        $id = (int)$_POST["edit"] ; // idがこの値のデータだけを抽出したい、とする
        $edit_password = $_POST["password3"];
        $sql = 'SELECT * FROM tb001 WHERE id=:id AND password=:password3';    
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->bindParam(':password3', $edit_password, PDO::PARAM_INT);
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
        foreach ($results as $row){
        } 

    }




    ?>


   <!--入力フォーム-->
    【入力フォーム】
    <form action = "#" method = "post">
        <p>氏名記入：<input type = "text" name = "name" placeholder = "名前" value = "<?php if(!empty($row['name'])){echo $row['name'];}?>"></p>
        <p>観光地名：<input type = "text" name = "comment" placeholder = "コメント" value = "<?php if(!empty($row['comment'])){echo $row['comment'];}?>"></p>
        <p>パスワード：<input type = "password" name = "password1" placeholder = "パスワード" >
           <input type = "hidden" name ="mode" value =  "<?php if(!empty($row['id'])){echo $row['id'];} ?>">
           <input type = "submit" name = "submit1" >
        </p>
    </form>
    
    <!--削除フォーム-->
    【削除フォーム】
    <form action = "#" method = "post">
        <p></p>
        <p>削除番号：<input type = "number" name = "delete" placeholder = "削除対象番号"></p>
        <p>パスワード：<input type = "password" name = "password2" placeholder = "パスワード">
           <input type = "submit" name = "submit2" value = "削除">
        </p>
    </form>
    
    <!--編集番号指定用フォーム-->
    【編集フォーム】
    <form action = "#" method = "post">
        <p></p>
        <p>編集番号：<input type = "number" name = "edit" placeholder = "編集対象番号"></p>
        <p>パスワード：<input type = "password" name = "password3" placeholder = "パスワード">
           <input type = "submit" name = "submit3" value = "編集">
        </p>
    </form>


    <?php
    //---------------------編集モード---------------------------------
    if (!empty ($_POST["mode"])){

        $id = $_POST["mode"]; //変更する投稿番号
        $name = $_POST["name"];
        $comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
        $sql = 'UPDATE tb001 SET name=:name,comment=:comment WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();            
    }

    //-------------------------新規投稿モード---------------------------------
    if (!empty ($_POST["name"]) and !empty ($_POST["comment"]) and !empty ($_POST["password1"]) and empty ($_POST["mode"])){
        // DB接続設定
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        $sql = $pdo -> prepare("INSERT INTO tb001 (name, comment, created_at, password) VALUES (:name, :comment, NOW(), :password)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':password', $password, PDO::PARAM_STR);
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $password = $_POST["password1"];
        $sql -> execute();
    }
    ?>


    <h2>投稿内容</h2>
    <?php
        // DB接続設定
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        $sql = 'SELECT * FROM tb001';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
         //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['created_at'].'<br>';
            echo "<hr>";
        }
    ?>

    

</body>
</html>