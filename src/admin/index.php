<?php

    // セッションを開始
    session_start();

    // もしログインしていない場合はログインページにリダイレクト
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php'); // ログインページのURLに変更してください
        exit(); // リダイレクト後にスクリプトの実行を終了するために必要
    };

    //dbconnect.phpを読み込み、dbに接続
    require('../dbconnect.php');


     //テーブルquestionsを変数questionsに格納 データベースに接続し、SQL文でデータを取得し、連想配列の形で返している。
    $questions = $dbh -> query("SELECT * FROM questions")->fetchAll(PDO::FETCH_ASSOC); 
    
    //削除機能の実装
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
        $dbh->beginTransaction();
    
        // 削除する問題の画像ファイル名を取得
        $sql = "SELECT image FROM questions WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(":id", $_POST["id"]);
        $stmt->execute();
        $question = $stmt->fetch();                                  //fetchできてない！！！！！
        $image_name = $question['image'];
        // 画像ファイルが存在する場合、削除する
        if ($image_name) {
            $image_path = '/../assets/img/quiz/' . $image_name;
            if (file_exists($image_path)) {
            unlink($image_path);
            }
        }
    
        // 問題と選択肢をデータベースから削除
        $sql = "DELETE FROM choices WHERE question_id = :question_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(":question_id", $_POST["id"]);
        $stmt->execute();
    
        $sql = "DELETE FROM questions WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(":id", $_POST["id"]);
        $stmt->execute();
    }
    catch(PDOException $e){
        //失敗した場合、処理を戻してエラーメッセージを出力
        $dbh->rollback();
        error_log($e->getMessage());

?>
<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>POSSE 管理画面ダッシュボード</title>
<!-- スタイルシート読み込み -->
<link rel="stylesheet" href="./assets/styles/common.css">
<link rel="stylesheet" href="./styles/foundation/_reset.scss">
<link rel="stylesheet" href="./admin.css">
<!-- Google Fonts読み込み -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="../assets/scripts/common.js" defer></script>
</head>

<body>
    <header>
        <img src="../assets/img/logo.svg" alt="">
        <a href="../admin/auth/signout.php"><button>ログアウト</button></a>
    </header>
    <div class="wrapper">
        <aside>
            <nav>
                <ul>
                    <li><a href="">ユーザー招待</a></li>
                    <li><a href="./">問題一覧</a></li>
                    <li><a href="./questions/create.php">問題作成</a></li>
                </ul>
            </nav>
        </aside>
        <main>
            <div class="container">
                <h1 class="mb-4">問題一覧</h1>
                <p></p>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>問題</th>
                            <th></th>
                        </tr>
                    </thead>
                    <?php for ($i = 0; $i < count($questions); $i++){ ?>
                        <tbody>
                        <tr>
                            <td><?= $questions[$i]["id"]; ?></td>
                            <td>
                                <a href="./questions/edit.php?id=<?= htmlspecialchars($questions[$i]["id"]) ?>"><?= $questions[$i]["content"]?></a>
                            </td>
                        <td>
                            <form method="POST">
                                    <input type="hidden" value="<?= $question[$i]["id"] ?>" name="id">
                                    <input type="submit" value="削除" class="submit">
                            </form>
                        </td>
                        </tr>
                    </tbody>
                    <?php } ?>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
