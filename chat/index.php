<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="source/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>

<section id="form-enter">
    <div class="enter">
            <div class="container">
                <div class="container__wr">
                    <div class="row">
                        <h2 class="chat__title">My company chat</h2>
                        <div class="col">
                            <div class="col__wr">
                                <button class="btn" type="submit" id="btn-chat">Open chat</button>
                            </div>
                        </div>
                        <div class="col">
                            <div class="col__wr">
                                <a href="../logout.php" class="btn-exit" type="submit" id="btn-chat">Exit</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<div class="chat popup-chat" id="popup-chat">
    <div class="chat__body">
        <div class="chat__content">
            <div class="chat__close">X</div>
            <div class="chat__header">
                <div class="chat__title">Чат нашей компании</div>
            </div>
            <div class="chat__main">
                <div id="show-more-trigger">
                    <img src="source/img/ajax-loader.gif" alt="ajax-loader">
                </div>
                <div class="chat__main-wr">
                    <!--лента сообщений-->
                </div>
            </div>
            <div class="chat__footer">
                <form class="form-msg">
                    <div class="input-group">
                        <div class="helper">
                            <a class="helper__link" href="Michelf">
                                <img class="helper__img" src="source/img/icon-q.svg">
                            </a>
                        </div>
                        <label for="txtChatMessage">
                            <textarea class="form-control input-sm" id="txtChatMessage" placeholder="Введите сообщение" type="text"></textarea>
                        </label>
                        <div class="input-group-btn">
                            <button class="btn-sm" id="btnChatSubmit" type="submit">
                                <span class="btn-span">Отправить</span>
                                <img class="btn-span-img" src="source/img/paper-plane.svg">
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="source/script.js"></script>
</body>
</html>
