<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>

<script>
    window.onload = function () {
        var socket = new WebSocket("ws://localhost:8080");
        var infomessage = document.querySelector("#infomessage");
        console.log(socket)

        socket.onerror = function (event) {
            status.innerHTML = "ошибка " + event.message;
        };
        socket.onopen = function () {
            infomessage.innerHTML = "<span style='color:green'>cоединение установлено</span><br>";
        };

        socket.onclose = function (event) {
            if (event.wasClean) {
                infomessage.innerHTML = "<span style='color:red'>cоединение закрыто</span><br>";
            } else {
                infomessage.innerHTML = "<span style='color:red'>соединения закрыто с ошибкой</span>";
            }
            infomessage.innerHTML += '<br>код: ' + event.code + ' причина: ' + event.reason;
        };

<?php foreach ($products as $product): ?>    // перебор массива с продуктами

            document.forms["messages-<?= $product['id'] ?>"].onsubmit = function () {  // ловим отправку формы 
                let message<?= $product['id'] ?> = {// формируем сообщение
                    product_id: this.product_id.value                              // получаем значение поля product_id
                }

                socket.send(JSON.stringify(message<?= $product['id'] ?>));           // отправляем сообщение
                return false;
            }

            socket.onmessage = function (event) {
                // на вход получаем строку - event.data вида price | id
                var arr = event.data.split(' | ');   // Разделяем строку на price и id
                var status = document.querySelector("#status_" + arr[1]);  // получаем div эллемента в который будем записывать изменения цены
                status.innerHTML = arr[0] + " руб.";  // записываем изменения в div
            }

<?php endforeach; ?>

    }
</script>
<!-- Для запуска сервера запустите в консоле "yii socket/start-socket" -->
<div class="container">
    <h1>Продукты на каждый день</h1>
    <div id="infomessage"></div>
<?php foreach ($products as $product): ?>
        <form action="" name="messages-<?= $product['id'] ?>">
            <h3><?= $product['name']?></h3>
            <h4><div id="status_<?= $product['id'] ?>"><?= $product['price'] ?> руб.</div></h4>
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <div class="row"><input type="submit" value="Повысить +10"></div>
        </form>
    <hr>
<?php endforeach; ?>
</div>