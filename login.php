<?php
session_start();
include('include/config.php');

$message_mail = $message_pass = $message_found = '';

if(isset($_POST['submit'])) {
    // Email Condition
    if(isset($_POST['email']) && !empty($_POST['email'])) {
        $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
        if(preg_match($pattern, $_POST['email'])) {
            $email = $_POST['email'];
        } else {
            $message_mail = '<b class="text-danger text-center">Пожалуйста, введите корректный email</b>';
        }
    } else {
        $message_mail = '<b class="text-danger text-center">Пожалуйста, заполните поле email</b>';
    }

    // Password Condition
    if(isset($_POST['password']) && !empty($_POST['password'])) {
        if(strlen($_POST['password']) < 6) {
            $message_pass = '<b class="text-danger text-center">Пароль должен содержать не менее 6 символов</b>';
        } else {
            $password = $_POST['password'];
        }
    } else {
        $message_pass = '<b class="text-danger text-center">Пожалуйста, заполните поле пароля</b>';
    }

    // Authentication
    if((isset($email) && !empty($email)) && (isset($password) && !empty($password))) {
        $email = mysqli_real_escape_string($connection, $email);
        $password = mysqli_real_escape_string($connection, $password);
        
        $stmt = $connection->prepare("SELECT id, name, user_mail, password FROM users WHERE user_mail = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if(password_verify($password, $user['password'])) {
                $_SESSION['userId'] = $user['id'];
                $_SESSION['userName'] = $user['name'];
                $_SESSION['userMail'] = $user['user_mail'];
                header('Location: home.php');
                exit();
            } else {
                $message_found = '<div class="text-danger text-center"><strong>Ошибка!</strong> Email или пароль не найдены</div>';
            }
        } else {
            $message_found = '<div class="text-danger text-center"><strong>Ошибка!</strong> Email или пароль не найдены</div>';
        }
        $stmt->close();
    }
}

// Display registration success message if redirected from registration
$registration_success = isset($_GET['registration']) && $_GET['registration'] === 'success';
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
    <title>Вход | Donya</title>
</head>

<body class="stretched">
    <div id="wrapper" class="clearfix">
        <section id="content">
            <div class="content-wrap nopadding">
                <div class="section nopadding nomargin" style="width: 100%; height: 100%; position: absolute; left: 0; top: 0; background: url('images/background_donya.png') center center no-repeat; background-size: cover;"></div>

                <div class="section nobg full-screen nopadding nomargin">
                    <div class="container vertical-middle divcenter clearfix">
                        <div class="row center">
                            <a href="index.php"><img height="100px" src="images/logo_donya_f.png" alt="Donya"></a>
                        </div>

                        <div class="panel panel-default divcenter noradius noborder" style="max-width: 400px; background-color: rgba(255,255,255,0.93);">
                            <div class="panel-body" style="padding: 40px;">
                                <form id="login-form" name="login-form" class="nobottommargin" action="" method="post">
                                    <h3>Вход в аккаунт</h3>

                                    <?php if ($registration_success): ?>
                                        <div class="alert alert-success text-center">
                                            Регистрация прошла успешно! Теперь вы можете войти.
                                        </div>
                                    <?php endif; ?>

                                    <div class="col_full">
                                        <label for="login-form-email">Email:</label>
                                        <input type="email" id="login-form-username" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" class="form-control not-dark" />
                                    </div>

                                    <div class="col_full">
                                        <label for="login-form-password">Пароль:</label>
                                        <input type="password" id="login-form-password" name="password" value="" class="form-control not-dark" />
                                    </div>

                                    <div class="col_full nobottommargin center">
                                        <button class="button button-3d button-black nomargin" id="login-form-submit" name="submit" value="login">Войти</button>
                                    </div>
                                </form>

                                <div class="line line-sm"></div>

                                <div class="alert-danger">
                                    <?php 
                                    if(isset($message_pass) || isset($message_mail) || isset($message_found)) { 
                                        if(isset($message_mail))
                                            echo "$message_mail <br>";
                                        if(isset($message_pass)) 
                                            echo "$message_pass <br>";
                                        if(isset($message_found)) 
                                            echo "$message_found";
                                    }
                                    ?>
                                </div>

                                <div class="center" style="margin-top: 15px;">
                                    <small>Еще нет аккаунта? <a href="register.php">Зарегистрироваться</a></small>
                                </div>
                            </div>
                        </div>

                        <div class="row center dark"><small>© 2025 Все права защищены Donya.</small></div>
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
    </div>
</body>
</html>