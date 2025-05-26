<?php
include('include/config.php');

$errors = [];
$name = $user_mail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $user_mail = trim($_POST['user_mail'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $profilePic = $_POST['profilePic'] ?? '';
    
    // Validation
    if (empty($name)) {
        $errors[] = 'Имя обязательно';
    }
    
    if (empty($user_mail)) {
        $errors[] = 'Email обязателен';
    } elseif (!filter_var($user_mail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Неверный формат email';
    }
    if( isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"]) ){
                    $target_dir = "images/admin/";
                    $target_file = $target_dir . basename($_FILES["profilePic"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                    $check = getimagesize($_FILES["profilePic"]["tmp_name"]);
                    if($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $message_picture  = '<b class="text-danger">Файл не является изображением</b>';
                        $uploadOk = 0;
                    }
                    if ($_FILES["profilePic"]["size"] > 5000000) {
                        $message_picture =  '<b class="text-danger">Извините, файл слишком большой</b>';
                        $uploadOk = 0;
                    }
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif" ) {
                        $message_picture =  '<b class="text-danger">Допустимы только JPG, JPEG, PNG и GIF файлы</b>';
                        $uploadOk = 0;
                    }
                    if ($uploadOk != 0) {
                        $temp = explode(".", $_FILES["profilePic"]["name"]);
                        $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                        if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_dir . $newfilename)) {
                            
                        } else {
                            $message_picture =  '<b class="text-danger">Ошибка загрузки файла</b>';
                        }
                    }
                }else{
                    $message_picture =  '<b class="text-danger">Пожалуйста, выберите аватар</b>';
                }
    
    if (empty($password)) {
        $errors[] = 'Пароль обязателен';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Пароль должен быть не менее 8 символов';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Пароли не совпадают';
    }
    
    // Check if email exists
    if (empty($errors)) {
        $stmt = $connection->prepare("SELECT id FROM users WHERE user_mail = ?");
        $stmt->bind_param('s', $user_mail);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = 'Email уже зарегистрирован';
        }
        $stmt->close();
    }
    
    // Register user
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $connection->prepare("INSERT INTO users (name, user_mail, password, profilePic) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $name, $user_mail, $hashed_password, $profilePic);
        
        if ($stmt->execute()) {
            header('Location: login.php?registration=success');
            exit();
        } else {
            $errors[] = 'Ошибка регистрации. Попробуйте снова.';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="SemiColonWeb" />
    <link rel="icon" type="image/png" href="images/tab.png" sizes="16x16">
    <link rel="icon" type="image/png" href="images/tab1.png" sizes="32x32">

    <!-- Stylesheets -->
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="stylesheet" href="css/dark.css" type="text/css" />
    <link rel="stylesheet" href="css/font-icons.css" type="text/css" />
    <link rel="stylesheet" href="css/animate.css" type="text/css" />
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css" />
    <link rel="stylesheet" href="css/responsive.css" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Регистрация | Donya</title>
    <style>
        /* Fix background in place */
        .section.nopadding.nomargin.background-fixed {
            position: fixed;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            z-index: -1;
            pointer-events: none;
        }
        
        /* Make only the panel scrollable */
        .scrollable-panel {
            max-height: 65vh;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            padding: 40px;
        }
        
        /* Ensure logo stays fixed at top */
        .logo-container {
            position: relative;
            z-index: 1;
            padding-top: 20px;
        }
        
        /* Footer positioning */
        .footer-fixed {
            position: relative;
            z-index: 1;
            margin-top: 20px;
        }
        
        /* Panel container adjustments */
        .panel-container {
            margin: 20px auto;
            max-width: 400px;
            background-color: rgba(255,255,255,0.93);
            border-radius: 0;
            border: none;
        }
    </style>
</head>

<body class="stretched">
    <div id="wrapper" class="clearfix">
        <section id="content">
            <div class="content-wrap nopadding">
                <!-- Fixed Background -->
                <div class="section nopadding nomargin background-fixed" style="background: url('images/background_donya.png') center center no-repeat; background-size: cover;"></div>

                <div class="section nobg full-screen nopadding nomargin">
                    <div class="container vertical-middle divcenter clearfix">
                        <!-- Logo Container -->
                        <div class="logo-container">
                            <div class="row center">
                                <a href="index.php"><img height="100px" src="images/logo_donya_f.png" alt="Donya"></a>
                            </div>
                        </div>

                        <!-- Scrollable Panel -->
                        <div class="panel panel-default panel-container divcenter noradius noborder">
                            <div class="panel-body scrollable-panel">
                                <form id="register-form" name="register-form" class="nobottommargin" action="" method="post">
                                    <h3>Регистрация аккаунта</h3>

                                    <div class="col_full">
                                        <label for="register-form-name">Имя:</label>
                                        <input type="text" id="register-form-name" name="name" value="<?php echo htmlspecialchars($name); ?>" class="form-control not-dark" required />
                                    </div>

                                    <div class="col_full">
                                        <label for="register-form-email">Email:</label>
                                        <input type="email" id="register-form-email" name="user_mail" value="<?php echo htmlspecialchars($user_mail); ?>" class="form-control not-dark" required />
                                    </div>

                                    <div class="form-group">
                <label class="btn btn-success" for="my-file-selector">
                    <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                    Аватар
                </label>
                <span class='label label-success' id="upload-file-info"></span>
                <?php if(isset($message_picture)){ echo $message_picture; } ?>
            </div>

                                    <div class="col_full">
                                        <label for="register-form-password">Пароль:</label>
                                        <input type="password" id="register-form-password" name="password" class="form-control not-dark" required />
                                        <small class="text-muted">Минимум 8 символов</small>
                                    </div>

                                    <div class="col_full">
                                        <label for="register-form-confirm-password">Подтвердите пароль:</label>
                                        <input type="password" id="register-form-confirm-password" name="confirm_password" class="form-control not-dark" required />
                                    </div>

                                    <div class="col_full nobottommargin center">
                                        <button class="button button-3d button-black nomargin" id="register-form-submit" name="submit" value="register">Зарегистрироваться</button>
                                    </div>
                                </form>

                                <div class="line line-sm"></div>

                                <?php 
                                if(isset($errors) && !empty($errors)) { 
                                    echo '<div class="alert-danger">';
                                    foreach($errors as $error) {
                                        echo "$error<br>";
                                    }
                                    echo '</div>';
                                }
                                ?>

                                <div class="center">
                                    <small>Уже есть аккаунт? <a href="login.php">Войти</a></small>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="row center dark footer-fixed">
                            <small>© 2025 Все права защищены Donya.</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Go To Top -->
        <div id="gotoTop" class="icon-angle-up"></div>

        <!-- JavaScripts -->
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/plugins.js"></script>
        <script type="text/javascript" src="js/functions.js"></script>
        
        <script>
        document.getElementById('register-form').addEventListener('submit', function(e) {
            const password = document.getElementById('register-form-password').value;
            const confirmPassword = document.getElementById('register-form-confirm-password').value;
            
            if (password.length < 8) {
                alert('Пароль должен быть не менее 8 символов');
                e.preventDefault();
                return false;
            }
            
            if (password !== confirmPassword) {
                alert('Пароли не совпадают');
                e.preventDefault();
                return false;
            }
            
            return true;
        });
        </script>
    </div>
</body>
</html>