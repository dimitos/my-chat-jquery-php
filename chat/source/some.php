<?php
session_start();
$userId = $_SESSION['userId'];
$userName = $_SESSION['userName'];
$userRole = $_SESSION['userRole'];
$userLoginDate = $_SESSION['userLoginDate'];

// подключение к бд
require_once dirname(dirname(dirname(__DIR__))) . '/db_config.php';
$dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=UTF8", $username, $password);

// подключение конвертора MarkDown
require_once dirname(__DIR__) . '/Michelf/Markdown.inc.php';
use Michelf\Markdown;

/**
 * Функция возвращает необходимый формат даты и времени
 * @param $date {string} дата в формате '2021.06.22 8:21:41'
 * @return string {string} дата в формате '22.06.2021 в 8:21'
 */
function getConvertDate($date){
    $date = explode(" ", $date);
    $date[0] = implode( '.', array_reverse(explode("-", $date[0])));
    $date[1] = implode( ':', array_slice(explode(":", $date[1]), 0, -1));
    return $date[0] . ' в ' . $date[1];
}

/**
 * Функция возвращает необходимый формат  времени
 * @param $date {string} дата в формате '2021.06.22 8:21:41'
 * @return string {string} дата в формате '8:21'
 */
function getConvertTime($date){
    $date = explode(" ", $date);
    $date[1] = implode( ':', array_slice(explode(":", $date[1]), 0, -1));
    return $date[1];
}

/**
 * Запрос на первую отрисовку последних сообщений сообщений
 */
if ($_POST['action'] == 1) {
    $sql = "SELECT `id`, `user_id`, `user_name`, `message`, `date`
            FROM `messages`
            WHERE UNIX_TIMESTAMP(`date`) >= UNIX_TIMESTAMP('{$userLoginDate}') AND `status` = 1
            ORDER BY `id` DESC LIMIT 30";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll();
    $result = array_reverse($result);

    $block = '';

    if (count($result) > 0) {
        if (count($result) < 30) {
            $block = '<p class="no-msg">Добро пожаловать в чат нашей компании!</p>
                      <p class="no-msg">Здесь информация для нового пользователя.</p>
                      <p class="no-msg">Дата регистрации: ' . getConvertDate($userLoginDate) . '</p>
                      <div class="delimiter__line mt-15"></div>';
        }
        $date = $result[0]['date'];

        // добавляем удаление сообщения админу
        $deleteMessage = '';
        if ($userRole == '1') {
            $deleteMessage = '<div class="chat__item-delete">Удалить</div>';
        }
        foreach ($result as $row) {
            // проверяем дату для разбиения по дням
            $dateNewMsg = explode(" ", $row['date'])[0];
            if ($date < $dateNewMsg){
                $dateNew = implode( '.', array_reverse(explode("-", $dateNewMsg)));
                $block = $block .
                    '<div class="delimiter">
                        <div class="delimiter__line"></div>
                        <div class="delimiter__data">' . $dateNew . '</div>
                        <div class="delimiter__line"></div>
                    </div>';
                $date = $dateNewMsg;
            }

            // проверяем свой/чужой
            $myMessage = 'pull-left';
            if ((int)$row['user_id'] == $userId) {
                $myMessage = 'pull-right';
            }

            $block = $block . '<div class="chat__item" data-id="' . $row['id'] . '">' . $deleteMessage . '           
            <div class="chat-body ' . $myMessage . '">
                <div class="chat__panel">
                    <div class="chat__panel-body">
                        <h4 class="message-author">' . $row['user_name'] . '</h4>
                        <div class="message-text">' . Markdown::defaultTransform($row['message']) . '</div>
                    </div>
                </div>
                <h6 class="message-data">' . getConvertTime($row['date']) . '</h6>
            </div>
        </div>';
        }
    } else {
        $block = '<p class="no-msg">Добро пожаловать в чат нашей компании!</p>
                  <p class="no-msg">Здесь информация для нового пользователя.</p>
                  <p class="no-msg">Дата регистрации: ' . getConvertDate($userLoginDate) . '</p>
                  <div class="delimiter__line mt-15"></div>';
    }
    echo $block;
}

/**
 * Добавление нового сообщения в БД
 */
if ($_POST['action'] == 2) {

    $data = [
        'user_id' => $userId,
        'user_name' => $userName,
        'message' => $_POST['message']
    ];
    $sql = "INSERT INTO `messages` (`user_id`, `user_name`, `message`) VALUES (:user_id, :user_name, :message)";
    $statement = $dbh->prepare($sql);
    $result_sql = $statement->execute($data);
}

/**
 * Запрос на отрисовку нового сообщения в БД
 */
if ($_POST['action'] == 3) {
    $id = $_POST['id']; // id последнего сообщения
    $sql = "
        SELECT `id`, `user_id`, `user_name`, `message`, `date` 
        FROM `messages` 
        WHERE `status` = 1 AND `id` > {$id}";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    if(count($result) !== 0){
        $myMessage = 'pull-left';
        if ((int)$result[0]['user_id'] == $userId) {
            $myMessage = 'pull-right';
        }
        $deleteMessage = '';
        if ($userRole === '1') {
            $deleteMessage = '<div class="chat__item-delete">Удалить</div>';
        }
        ?>
       <div class="chat__item" data-id="<?php echo $result[0]['id']; ?>">
           <?php echo $deleteMessage; ?>
            <div class="chat-body <?php echo $myMessage; ?>">
                <div class="chat__panel">
                    <div class="chat__panel-body">
                        <h4 class="message-author"><?php echo $result[0]['user_name']; ?></h4>
                        <div class="message-text"><?php echo Markdown::defaultTransform($result[0]['message']); ?></div>
                    </div>
                </div>
                <h6 class="message-data"><?php echo getConvertTime($result[0]['date']); ?></h6>
            </div>
        </div>
        <?php
    }
}

/**
 * Запрос на подгрузку старых сообщений
 */
if ($_POST['action'] == 4) {

    $id = $_POST['id'];
    // подготавливаем запрос, в values закидываем не сами данные а метки
    $sql = "
        SELECT `id`, `user_id`, `user_name`, `message`, `date` 
        FROM `messages`
        WHERE `id` < {$id} AND UNIX_TIMESTAMP(`date`) >= UNIX_TIMESTAMP('{$userLoginDate}') AND `status` = 1
        ORDER BY `id` DESC LIMIT 40";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    $result = array_reverse($result);

    $block = '';
    if (count($result) > 0) {
        if (count($result) < 40) {
            $block = '<p class="no-msg">Добро пожаловать в чат нашей компании!</p>
                      <p class="no-msg">Здесь информация для нового пользователя.</p>
                      <p class="no-msg">Дата регистрации: ' . getConvertDate($userLoginDate) . '</p>
                      <div class="delimiter__line mt-15"></div>';
        }

        $date = $result[0]['date'];
        $deleteMessage = '';
        if ($userRole === '1') {
            $deleteMessage = '<div class="chat__item-delete">Удалить</div>';
        }
        foreach ($result as $row) {
            // проверяем дату для разбиения по дням
            $dateNewMsg = explode(" ", $row['date'])[0];
            if ($date < $dateNewMsg){
                $dateNew = implode( '.', array_reverse(explode("-", $dateNewMsg)));
                $block = $block .
                    '<div class="delimiter">
                        <div class="delimiter__line"></div>
                        <div class="delimiter__data">' . $dateNew . '</div>
                        <div class="delimiter__line"></div>
                    </div>';
                $date = $dateNewMsg;
            }

            // проверяем свой/чужой
            $myMessage = 'pull-left';
            if ((int)$row['user_id'] == $userId) {
                $myMessage = 'pull-right';
            }
            $block = $block . '<div class="chat__item" data-id="' . $row['id'] . '">' . $deleteMessage . '
            <div class="chat-body ' . $myMessage . '">
                <div class="chat__panel">
                    <div class="chat__panel-body">
                        <h4 class="message-author">' . $row['user_name'] . '</h4>
                        <div class="message-text">' . Markdown::defaultTransform($row['message']) . '</div>
                    </div>
                </div>
                <h6 class="message-data">' . getConvertTime($row['date']) . '</h6>
            </div>
        </div>';
        }
    } else {
        $block = '<p class="no-msg">Добро пожаловать в чат нашей компании!</p>
                  <p class="no-msg">Здесь информация для нового пользователя.</p>
                  <p class="no-msg">Дата регистрации: ' . getConvertDate($userLoginDate) . '</p>
                  <div class="delimiter__line mt-15"></div>';
    }

    echo $block;
}

/**
 * Запрос на удаление сообщения
 */
if ($_POST['action'] == 5) {
    $id = (int)$_POST['id'];
    // подготавливаем запрос, в values закидываем не сами данные а метки
    $sql = "UPDATE `messages` SET `status` = 0 WHERE `id` = {$id}";
    $sth = $dbh->prepare($sql);
    $sth->execute();
}









