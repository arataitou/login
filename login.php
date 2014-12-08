<?php

require_once('config.php');
require_once('functions.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    //CSRF対策
    setToken();
} else {
    checkToken();

    $email = $_POST['email'];
    $password = $_POST['password'];

    $dbh = connectDb();

    $err = array();

    if (!emailExists($email, $dbh)) {
        $err['email'] = 'このメールアドレスは登録されていません';
    }
        
    //メールアドレスのけいしきが不正
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err['email'] = 'メールアドレスの形式が正しくないです';
    }
    //メールアドレスが空
    if ($email == '') {
        $err['email'] = 'メールアドレスを入力してください';
    }
    //メールアドレスとパスワードが正しくない
    $me = getUser($email, $password, $dbh);
    if (!$me) {
        $err['password'] = 'パスワードとメアドが一致しません';
    }
    //パスワードが空
    if ($password == '') {
        $err['password'] = 'パスワードを入力してください';
    }

    if (empty($err)) {
        session_regenerate_id(true);//セッションハイジャック対策(決まり文句)
        $_SESSION['me'] = $me;
        header('Location: '.SITE_URL);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン画面</title>
</head>
<body>
<h1>ログイン</h1>
<form action="" method="POST">
    <p>メールアドレス:<input type="text" name="email" value="<?php echo h($email); ?>"><?php echo h($err['email']); ?></p>
    <p>パスワード:<input type="password" name="password" value=""><?php echo h($err['password']); ?></p>
    <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
    <p><input type="submit" value="ログイン"><a href="signup.php">新規登録</a></p>
</form>
</body>
</html>
