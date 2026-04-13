<?php 
ob_start();
include 'includes/header.html'; ?>
<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-center mb-4">Контакты и форма обратной связи</h1>
                <?php
if ($_POST['submit']) {
                    $name = trim($_POST['name']);
                    $email = trim($_POST['email']);
                    $message = trim($_POST['message']);
                    if (!empty($name) && !empty($email) && !empty($message)) {
                        $log_entry = date('Y-m-d H:i:s') . " | Имя: " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . " | Email: " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . " | Сообщение: " . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "\n";
$log_file = 'logs/contacts_log.txt';
$log_dir = dirname(__FILE__) . '/logs';
if (!is_dir($log_dir)) {
    mkdir($log_dir, 0777, true);
}
$file = fopen($log_file, 'a');
if (!$file) {
    error_log("Cannot open $log_file for append");
}
if ($file) {
    fwrite($file, $log_entry);
    fclose($file);
    
    
    $download_filename = 'test_data_' . date('Y-m-d_H-i-s') . '.txt';
    $test_file_path = __DIR__ . '/' . $download_filename;
file_put_contents($test_file_path, $log_entry);

// Отправка email
$to = 'info@muzei62.ru';
$subject = 'Новое сообщение с сайта музея: ' . $name;
$body = "Новое сообщение от посетителя сайта.\n\nДата отправки: " . date('Y-m-d H:i:s') . "\n\nИмя: " . $name . "\nEmail: " . $email . "\n\nСообщение:\n" . $message;
$headers = "From: no-reply@muzei62.ru\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();
$mail_sent = @mail($to, $subject, $body, $headers);
if (!$mail_sent) {
    error_log("Contact form mail failed for " . $email . " at " . date('Y-m-d H:i:s'));
}

    
    $success_msg = '
<strong>Успех!</strong> Сообщение сохранено и отправлено на email музея. 
        <a href="' . $download_filename . '" download class="btn btn-success btn-sm ms-2">Скачать тестовый файл</a>
    </div>';
    echo $success_msg;
} else {
    echo '<div class="alert alert-danger">Ошибка: не удалось открыть файл для записи. Проверьте права доступа.</div>';
}
                    } else {
                        echo '<div class="alert alert-danger">Заполните все поля.</div>';
                    }
                }
                ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Имя</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Сообщение</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Отправить</button>
                </form>
                <div class="mt-5 p-4 bg-light rounded">
                    <h3>Адрес</h3>
                    <p>ул. Соборная гора, 4, Рязань, 390000<br>Тел: +7 (4912) 25-51-74<br>Email: info@muzei62.ru</p>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.html'; ?>
