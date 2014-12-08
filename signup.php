<?php

require_once('config.php');
require_once('functions.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    //CSRF対策
    setToken();
} else {
    checkToken();

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $dbh = connectDb();

    $err = array();

    //名前が空？
    if ($name == '') {
        $err['name'] = 'お名前を入力してください';
    }
    //メールアドレスが正しい？
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err['email'] = 'メールアドレスの形式が正しくないです。';
    }
    if (emailExists($email, $dbh)) {
        $err['email'] = 'このメールアドレスは既に登録されています。';
    }
    //メールアドレスが空？
    if ($email == '') {
        $err['email'] = 'emailを入力してください';
    }

    //パスワードが空？
    if ($password == '') {
        $err['password'] = 'パスワードを入力してください';
    }

    //登録処理
    if (empty($err)) {
        $sql = "insert into users
                (name, email, password, created, modified)
                values
                (:name, :email, :password, now(), now())";
        $stmt = $dbh->prepare($sql);
        $params = array(
            ":name" => $name,
            ":email" => $email,
            ":password" => getSha1Password($password)
        );
        $stmt->execute($params);
        header('Location: '.SITE_URL.'login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規ユーザー登録画面</title>
</head>
<body>
<h1>サインアップ</h1>
<form action="" method="POST">
<p>名前:<input type="text" name="name" value="<?php echo h($name); ?>"><?php echo h($err['name']); ?></p>
    <p>メールアドレス:<input type="text" name="email" value="<?php echo h($email); ?>"><?php echo h($err['email']); ?></p>
    <p>パスワード:<input type="password" name="password" value=""><?php echo h($err['password']); ?></p>
    <input type="hidden" name="token" value="<?php echo h($_SESSION['token']) ?>">
    <p><input type="submit" value="新規登録"><a href="login.php">戻る</a></p>
</form>
</body>
</html>
