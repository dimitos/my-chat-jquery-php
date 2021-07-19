$(document).ready(function () {

    // открываем чат
    let chat = 'close';
    $('#btn-chat').on('click', function (e) {
        $('.popup-chat').addClass('open')
        e.preventDefault();
        if(chat === 'close'){
            // делаем загрузку последних сообщений
            getLastMessages();
            chat = 'open';
        }
    })
    // закрываем попап
    $('.chat__close').on('click', function (e) {
        $('.popup-chat').removeClass('open');
        e.preventDefault();
    })
    $('.chat__body').on('click', function (e) {
        if (!e.target.closest('.chat__content')) {$('.popup-chat').removeClass('open');}
        e.preventDefault();
    })

    /**
     * При нажатии кнопки ОТПРАВИТЬ СООБЩЕНИЕ отправляем сообщение
     */
    $('#btnChatSubmit').on('click', (e) => {
        const textValue = $('#txtChatMessage').val().trim(); // убираем лишние пробелы
        if (textValue !== '') {
            $.ajax({
                method: "POST",
                url: "source/some.php",
                data: {action: 2, message: textValue,}
            });
        }
        $('#txtChatMessage').val(''); // обнуляем textarea
        $('#txtChatMessage').css({'height': 'auto'});
        $(".chat__main").scrollTop($(".chat__main")[0].scrollHeight); //прокручиваем скролл вниз
        e.preventDefault();
    })

    /**
     * Функция - запрос на подгрузку первой части сообщений
     */
    function getLastMessages() {
        $.ajax({
            method: "POST",
            url: "source/some.php",
            data: {action: 1},
            success: function (data) {
                if (data !== ''){
                    if ($('.chat__main-wr').length > 0) {
                        $('.chat__main-wr').append(data);
                    }
                    $(".chat__main").scrollTop($(".chat__main")[0].scrollHeight);  // прокручиваем скролл вниз

                    // подключаем возможность удаления
                    $('.chat__panel').off('click');
                    $('.chat__item-delete').off('click');
                    deleteMessage();
                }
            }
        })
    }

    /**
     * Функция делает запрос на обновление базы вставляет новое сообщение в DOM
     */
    function queryUpdateDB(){
        const id = $('.chat__item').eq($('.chat__item').length - 1).data('id') // последний нижний выведенный ID
        $.ajax({
            method: "POST",
            url: "source/some.php",
            data: { action: 3, id: id},
            success: function(data) {
                if(data !== '') {
                    $('.chat__main-wr').append(data);
                    $(".chat__main").scrollTop($(".chat__main")[0].scrollHeight);

                    // подключаем возможность удаления
                    $('.chat__panel').off('click');
                    $('.chat__item-delete').off('click');
                    deleteMessage();
                }
            }
        })
    }

    /**
     * Функция делает запрос на подгрузку старых сообщений и отрисовывает их вверху
     */
    function getOldMessages() {
        const heightScroll = $('.chat__main').scrollTop(); // координата высоты скролла окна
        if (heightScroll === 0 && $('.no-msg').length == 0) {
            const heightBlock = $('.chat__main-wr').height();  // высота документа экрана
            const topMessageId = $('.chat__item').eq(0).data('id'); // получаем id верхнего сообщения
            $('#show-more-trigger').css({'display': 'block'});
            setTimeout(function () {
                $.ajax({
                    method: "POST",
                    url: "source/some.php",
                    data: {action: 4, id: topMessageId},
                    success: function (data) {
                        if (data !== ''){
                            $('.chat__main-wr').prepend(data);
                            const newHeightBlock = $('.chat__main-wr').height();
                            $('.chat__main').scrollTop(newHeightBlock - heightBlock);
                            if ($('.no-msg').length > 0) {
                                $('#show-more-trigger').css({'display': 'none'});
                            }
                            // подключаем возможность удаления
                            $('.chat__panel').off('click');
                            $('.chat__item-delete').off('click');
                            deleteMessage();
                        }
                    }
                })
            }, 100);
        }
    }

    /**
     * Функция делает запрос на удаление сообщений
     */
    function deleteMessage() {
        $('.chat__panel').on('click', function () {
            let massageItem = $(this).parent().parent(); // находим chat__item
            const messageId = massageItem.data('id');  // находим chat__item id
            const delMsg = $(this).parent().prev();  // находим кнопку удаления
            const textMsg = $(this).find('.message-text');
            delMsg.addClass('active');

            delMsg.on('click', function (){
                // послать запрос на удаление
                $.ajax({
                    method: "POST",
                    url: "source/some.php",
                    data: {action: 5, id: messageId},
                    success: function () {
                        // очистить сообщение в дом
                        textMsg.empty();
                        textMsg.css({'color': 'red'});
                        textMsg.append('<p>Сообщение удалено</p>')
                    }
                })
            })
        })
    }

    setInterval(function () {queryUpdateDB();}, 3000)  // делаем запросы на обновления
    $('.chat__main').on('scroll', getOldMessages); // подгрузка при прокрутке вверх
    // закрываем кнопку УДАЛИТЬ сообщения
    $('.chat__body').on('click', function (e) {
        if (!e.target.closest('.chat__panel') && $('.chat__item-delete.active').length > 0) {
            $('.chat__item-delete').removeClass('active');
        } else if (e.target.closest('.chat__panel') && $('.chat__item-delete.active').length > 1) {
            $('.chat__item-delete').removeClass('active');
        }

    })

    // автоматическое увеличение высоты textarea
    if(document.querySelector('.input-sm')) {
        document.querySelector('.input-sm').addEventListener('keyup', function(){
            if(this.scrollTop > 0){this.style.height = this.scrollHeight + "px";}
        });
    }
})