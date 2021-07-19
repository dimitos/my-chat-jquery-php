<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../source/style.css">
</head>
<body>

<div class="chat popup-chat open" id="popup-chat">
    <div class="chat__body">
        <div class="chat__content">
            <div class="chat__header">
                <div class="chat__title">Markdown</div>
            </div>
            <div class="chat__main">
                <div class="chat__main-wr">
                    <h1>#Заголовок первого уровня</h1>
                    <h2>##Заголовок второго уровня</h2>
                    <h3>###Заголовок третьего уровня</h3>
                    <h4>####Заголовок четвертого уровня</h4>
                    <h5>#####Заголовок пятого уровня</h5>
                    <h6>######Заголовок шестого уровня</h6>
                    <br>
                    <b>**Жирный текст**</b>
                    <i>*Текст курсивом*</i>
                    <br>
                    <h3>Списки</h3>
                    <div>* Generic list item</div>
                    <div>* Generic list item</div>
                    <div>* Generic list item</div>
                    <div>1. Numbered list item</div>
                    <div>2. Numbered list item</div>
                    <div>3. Numbered list item</div>
                    <br>
                    <h3>Цитаты</h3>
                    <p>Впереди через пробел ставится ">"</p>
                    <p>
                        > Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit. Ab aperiam culpa delectus dolorem eaque enim
                        est et explicabo facilis harum itaque iusto minima nam nisi
                        non officia, porro possimus praesentium quaerat
                    </p>
                    <blockquote>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Ab aperiam culpa delectus dolorem eaque enim
                            est et explicabo facilis harum itaque iusto minima nam nisi
                            non officia, porro possimus praesentium quaerat
                        </p>
                    </blockquote>

                    <br>

                    <h3>Линия</h3>
                    <p> ***  или ---</p>
                    <br>
                    <h3>Встроенный код</h3>
                    <br>
                    <p>`Встроенный код` с обратными кавычками</p>
                    <br>
                    <br>
                    <p>```</p>
                    <p>Встроенный код с;</p>
                    <p>обратными кавычками;</p>
                    <p>```</p>
                    <br>
                    <p>[Helper](https://commonmark.org/help/ "Откройте в соседнем окне")</p>
                    <p>пишется так:  \[Helper](https://commonmark.org/help/ "Откройте в соседнем окне")</p>
                    <br>
                    <p>[https://commonmark.org/help/](https://commonmark.org/help/ "Необязательный заголовок ссылки")</p>
                    <p>пишется так:  \[https://commonmark.org/help/](https://commonmark.org/help/ "Необязательный заголовок ссылки")</p>
                    <br>
                    <p>
                        перенос строки - два пробела в конце
                    </p>
                    <hr>
                    <a href="https://commonmark.org/help" title="Откройте в соседнем окне">https://commonmark.org/help</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
