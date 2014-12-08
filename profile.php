<?php

require_once('config.php');
require_once('functions.php');

session_start();

if (empty($_SESSION['me'])) {
    header('Location: '.SITE_URL.'login.php');
    exit;
}

$me = $_SESSION['me'];

$dbh = connectDb();

$sql = "select * from users where id = :id limit 1";
$stmt = $dbh->prepare($sql);
$stmt->execute(array(":id" => (int)$_GET['id']));
$user = $stmt->fetch();

if (!$user) {
    echo "no such user!!";
    exit;
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザープロフィール</title>
</head>
<body>
<div>
<p>
Logged in as <?php echo h($me['name']); ?>(<?php echo h($me['email']); ?>)<a href="logout.php">[logout]</a>
</p>
</div>
<h1>ユーザープロフィール</h1>
<p>お名前:<?php echo h($user['name']); ?></p>
<p>メールアドレス:<?php echo h($user['email']); ?></p>
<p><a href="index.php">一覧へ</a></p>
</body>
</html>
