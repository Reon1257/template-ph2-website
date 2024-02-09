<?php

//ログアウト処理
session_start(); //セッション開始
$_SESSION = array();//セッション変数を空の配列に設定して、すべてのセッションデータをクリア
session_destroy();//サーバー側のセッションを破壊
header('Location: ../../index.php');//画面を飛ばす
exit;
