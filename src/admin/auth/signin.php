<?php




    require('../../dbconnect.php');

    //$users 全体のデータを格納
    $users = $dbh -> query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC); 

    // //メアドのバリエーション
    // if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    //     echo '正しいEメールアドレスを指定してください。';
    //     }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        //$user 個人のデータを格納
        $stmt = $dbh->prepare("SELECT * from users where email = :email");
        $stmt ->bindValue(":email", $_REQUEST["email"]);
        $stmt ->execute();
        $user = $stmt->fetch();

        //パスワードの確認
        if ($_POST['password'] == $user['password']) {
            session_start();               //sessionの管理を開始する支持
            $_SESSION['id'] = $user["id"]; //sessionIDを持ってくる
            header('Location: ../index.php'); //管理者画面に飛ばす    分からない！！！！！
            exit;
            }
        else{
            // 認証失敗: エラーメッセージをセット
            $message = 'メールアドレスまたはパスワードが間違っています。';
        }
    }


?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>POSSE ログイン</title>
<!-- スタイルシート読み込み -->
<link rel="stylesheet" href="./../assets/styles/common.css">
<link rel="stylesheet" href="./../admin.css">
<!-- Google Fonts読み込み -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
<header>
    <img src="../assets/img/logo.svg" alt="">
    <a href="../admin/auth/signout.php"><button>ログアウト</button></a>
</header>
<div class="wrapper">
    <main>
    <div class="container">
        <h1 class="mb-4">ログイン</h1>
        <p style="color: red;"></p>
        <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="email form-control" id="email" required="required">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">パスワード</label>
            <input type="password" name="password" id="password" class="form-control" required="required">
        </div>
        <button type="submit" disabled class="btn submit">ログイン</button>
        </form>
    </div>
    </main>
</div>
<script>
    const EMAIL_REGEX = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/
    const submitButton = document.querySelector('.btn.submit')
    const emailInput = document.querySelector('.email')
    inputDoms = Array.from(document.querySelectorAll('.form-control'))
    inputDoms.forEach(inpuDom => {
    inpuDom.addEventListener('input', event => {
        const isFilled = inputDoms.filter(d => d.value).length === inputDoms.length
        submitButton.disabled = !(isFilled && EMAIL_REGEX.test(emailInput.value))
    })
    })
</script>
</body>

</html>