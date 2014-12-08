<?php

require_once('config.php');
require_once('functions.php');

session_start();

if (empty($_SESSION['me'])) {
    header('Location: '.SITE_URL.'login.php');
}

$me = $_SESSION['me'];

$dbh = connectDb();

$users = array();

$sql = "select * from users order by created desc";
foreach ($dbh->query($sql) as $row) {
    array_push($users, $row);
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ホーム画面</title>
</head>
<body>
<div>
<p>
Logged in as <?php echo h($me['name']); ?>(<?php echo h($me['email']); ?>)<a href="logout.php">[logout]</a>
</p>
</div>
<h1>ユーザー一覧</h1>
<ul>
<?php foreach ($users as $user): ?>
<li><a href="profile.php?id=<?php echo h($user['id'])?>"><?php echo h($user['name']); ?></a></li>
<?php endforeach; ?>
</ul>
</body>
</html>
