<?php
$dsn = 'mysql:host=db;dbname=posse;charset=utf8'; //dbは、データベースの略
$user = 'root';
$password = 'root';         //変数dsn,user,passwordに情報を格納する
// データベースに接続する
try{                            
    $dbh = new PDO($dsn, $user, $password);  //PODは、データベースを扱うためのPHPの拡張ライブラリ
    // echo 'Connection success!';
} catch (PDOException $e){      //PDOExceptionクラスでエラーをキャッチする
    // echo 'Connection failed: ' . $e->getMessage(); //後半は発生した例外からエラーメッセージを取得
}
// データを取得する

// // // SQL ステートメント
// $sql = 'SELECT id, content FROM questions'; //変数sqlに、aテーブル内のb列を洗濯するsql文を代入する。

// // テーブル内のレコードを順番に出力
// foreach ($dbh->query($sql) as $row) {  //$dbh(データベース)に対して、$sqlを代入する 　そして、各行を$rawに格納する（？） 何がforeach?
//   echo $row['id'];                     //以下、各列を出力
//   echo $row['content'];
// }
