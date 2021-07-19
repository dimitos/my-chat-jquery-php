<?php
session_start();

require_once dirname(__DIR__) . '/db_config.php';
$dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=UTF8", $username, $password);
if (isset($_POST['login']) && $_POST['password']){
    $login = filter_var($_POST['login'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['password']);
}

$sql = "SELECT * FROM `users` WHERE `login` = '{$login}' AND `password` = {$pass}";
$sth = $dbh->prepare($sql);
$sth->execute();
$user = $sth->fetchAll();

if(count($user) == 1) {
    $_SESSION['userId'] = (int)$user[0]['id'];
    $_SESSION['userName'] = $user[0]['name'];
    $_SESSION['userRole'] = $user[0]['role'];
    $_SESSION['userLoginDate'] = $user[0]['login_date'];
    header('Location: chat');
} else {
    $fmsg = 'ошибка';
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="chat/source/style.css">
</head>
<body>

<section id="form-log">
    <div class="container">
        <div class="container__wr">
            <div class="row">
                <h2 class="chat__title">Login to enter the chat</h2>
                <div class="col">
                    <div class="col__wr">
                        <form action="#" method="post">
                            <label class="input__label"> Login
                                <input class="form-control" id="login" type="text" name="login">
                            </label>
                            <label class="input__label"> Password
                                <input class="form-control" id="password" type="password" name="password">
                            </label>
                            <button class="btn" type="submit" id="enter">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>
