<?php
require ('../../dbconnect.php');

// パラメータが存在しない場合のエラーチェック
if (!isset($_REQUEST["id"])) {
    // エラーハンドリングやリダイレクト処理を追加してください
    exit("Error: ID parameter is missing.");
}

//配列questionsを定義
$sql = "SELECT * FROM questions WHERE id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":id", $_REQUEST["id"]);
$stmt->execute();
$questions = $stmt->fetch();  //一個だけなのでfetch

//配列choicesを定義
$sql = "SELECT * FROM choices WHERE question_id = :question_id";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":question_id", $_REQUEST["id"]);
$stmt->execute();
$choices = $stmt->fetchAll(); //複数個なのでfetchall

// $image_name = $question["image"];

//sql処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try{
        //トランザクションを開始
        $dbh->beginTransaction();

         //questionテーブルの更新
        $stmt = $dbh->prepare('UPDATE questions SET content = :content,  supplement = :supplement  WHERE id = :id');
        $stmt->bindValue(':content', $_POST['content']);
        $stmt->bindValue(':supplement', $_POST['supplement']);
        $stmt->execute();

        $lastInsertId = $dbh->lastInsertId();

        //choiceテーブルの更新
        $stmt = $dbh->prepare('UPDATE choices SET name = :name, valid = :valid, question_id = :question_id  WHERE id = :id');
        for ($i = 0; $i < count($_POST["choices"]); $i++) {
            print_r($_POST["correctChoice"]);
            $stmt->execute([
                "name" => $_POST["choices"][$i],
                "valid" => (int)$_POST['correctChoice'] === $i + 1 ? 1 : 0,
                "question_id" => $lastInsertId
            ]);
        }
        
        //画像の処理
        $image_name = uniqid(mt_rand(), true) . '.' . substr(strrchr($_FILES['image']['name'], '.'), 1);
        $image_path = '../../assets/img/quiz' . $image_name;
        print_r($image_path);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        $stmt = $dbh->prepare('UPDATE questions set image = :image where id = :id');
        $stmt->bindValue('image', $image_name);
        $stmt->execute();
        
        //全て成功したらトランザクション処理を確定す津
        $dbh->commit();
    }

    catch(PDOException $e){
        //失敗した場合、処理を戻してエラーメッセージを出力
        $dbh->rollback();
        error_log($e->getMessage());
    }
}
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
<link rel="stylesheet" href="../admin.css">
<!-- Google Fonts読み込み -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
<div class="wrapper">
    <main>
    <div class="container">
        <h1 class="mb-4">問題編集</h1>
        <form class="question-form" method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="question" class="form-label">問題文:</label>
            <input type="text" name="content" id="question" class="form-control required" value="<?= $questions["content"] ?>" placeholder="問題文を入力してください" />
        </div>
        <div class="mb-4">
            <label class="form-label">選択肢:</label>
            <div class="form-choices-container">
            <?php foreach ($choices as $key => $choice) { ?>
                <input type="text" name="choices[]" class="required form-control mb-2" placeholder="選択肢を入力してください" value=<?= $choice["name"] ?>>
                <input type="hidden" name="choice_ids[]" value="<?= $choice["id"] ?>">
            <?php } ?>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label">正解の選択肢</label>
            <div class="form-check-container">
            <?php foreach ($choices as $key => $choice) { ?>
                <div class="form-check">
                <input class="form-check-input" type="radio" name="correctChoice" id="correctChoice<?= $key ?>" value="<?= $choice["id"] ?>" <?= $choice["valid"] === 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="correctChoice1">
                    選択肢<?= $key + 1 ?>
                </label>
                </div>
            <?php } ?>
            </div>
        </div>
        <div class="mb-4">
            <label for="question" class="form-label">問題の画像</label>
            <input type="file" name="image" id="image" class="form-control" />
        </div>
        <div class="mb-4">
            <label for="question" class="form-label">補足:</label>
            <input type="text" name="supplement" id="supplement" class="form-control" placeholder="補足を入力してください" value="<?= $questions["supplement"] ?>" />
        </div>
        <input type="hidden" name="question_id" value="<?= $questions["id"] ?>">
        <button type="submit" class="btn submit">更新</button>
        </form>
    </div>
    </main>
</div>
<script>
    const submitButton = document.querySelector('.btn.submit')
    const inputDoms = Array.from(document.querySelectorAll('.required'))
    inputDoms.forEach(inputDom => {
    inputDom.addEventListener('input', event => {
        const isFilled = inputDoms.filter(d => d.value).length === inputDoms.length
        submitButton.disabled = !isFilled
    })
    })
</script>
</body>

</html>
