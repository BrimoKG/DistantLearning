<?php
   //**************** Admin File
include("../include/config.php");

    if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {
        header('Location: index.php');
    }else{
        $loginName = $_SESSION['userName'];
        $loginId = $_SESSION['userId'];
        $power = $_SESSION['adminType'];
        $alertMessage = " ";
        
        /* %%%%%%%%%%%%% START CODE SUBMIT %%%%%%%%%%%% */
        if( isset($_POST['submit']) ){
            
            if($power == 'yes'){ //*************************
                if(isset($_POST["admin_op"]) && !empty($_POST["admin_op"])){
                    $admin_type = $_POST["admin_op"];
                } else {
                    $admin_error = '<b class="text-danger text-center">Пожалуйста, выберите тип администратора.</b>';
                }       

                //Name Condition
                if( isset($_POST['fullname']) && !empty($_POST['fullname'])){
                    if(preg_match('/^[А-Яа-яЁё\s]+$/u',$_POST['fullname'])){
                      $name = mysqli_real_escape_string($connection,$_POST['fullname']);
                    }else{
                      $message_name = '<b class="text-danger text-center">Пожалуйста, введите корректное имя</b>';
                    }
                  }else{
                      $message_name = '<b class="text-danger text-center">Пожалуйста, заполните поле имени</b>';
                }

                if( isset($_POST['email']) && !empty($_POST['email']) ){
                    $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
                        if(preg_match($pattern,$_POST['email'])){
                          $cemail = mysqli_real_escape_string($connection,$_POST['email']);  
                          
                          $query = "SELECT * FROM `admin` WHERE admin_mail='$cemail' ";
                          $result = mysqli_query($connection, $query);
                          if(mysqli_num_rows($result) > 0){
                                $message_email = '<b class="text-danger text-center">Этот email уже существует</b>';
                          }else{
                            $email = mysqli_real_escape_string($connection,$_POST['email']);
                          }
                        }else{
                          $message_email = '<b class="text-danger text-center">Пожалуйста, введите корректный email</b>';
                        }
                }else{
                      $message_email = '<b class="text-danger text-center">Пожалуйста, заполните поле email</b>';
                }

                if( !isset($_POST['password']) && empty($_POST['password'])){
                    $message_pass = '<b class="text-danger text-center">Пожалуйста, заполните поле пароля</b>';
                }
                
                //Password Condition
                if(isset($_POST['c_password']) && !empty($_POST['c_password'])){
                    if($_POST['c_password'] != $_POST['password']){
                        $message_c_pass = '<b class="text-danger text-center">Пароли должны совпадать</b>';
                      }else{
                          if(strlen($_POST['password']) < 6){
                                $message_pass = '<b class="text-danger text-center">Пароль должен содержать не менее 6 символов</b>';
                            }else{
                                $password = md5(mysqli_real_escape_string($connection,$_POST['password']));
                            }
                        }
                }else{
                    $message_c_pass = '<b class="text-danger text-center">Пожалуйста, подтвердите пароль</b>';
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

                if( ( isset($name) && !empty($name) ) && ( isset($admin_type) && !empty($admin_type) ) && 
                    ( isset($email) && !empty($email) ) && ( isset($password) && !empty($password) ) && 
                    ( isset($newfilename) && !empty($newfilename) ) ){

                    $check_email = "SELECT * FROM `admin` WHERE admin_mail = '$email'";
                    $check_res = mysqli_query($connection, $check_email);
                    if(mysqli_num_rows($check_res) > 0){
                        $message_email = '<b class="text-danger text-center">Этот email уже существует</b>';
                    }else{
                        $insert_query = "INSERT INTO `admin` (name, admin_mail, password, profilePic, type) 
                                       VALUES ('$name','$email','$password','$newfilename','$admin_type')";
                        if(mysqli_query($connection, $insert_query)){
                            header('Location: home.php#end');
                        }else{
                            $submit_message = '<div class="alert alert-danger">
                                <strong>Ошибка!</strong>
                                Не удалось зарегистрироваться, попробуйте позже
                            </div>';
                        }
                    }       
                }
            }else{
                 $alertMessage = "<div class='alert alert-danger'> 
                    <p>Вы не главный администратор и не можете удалять других администраторов. <strong>СПАСИБО.</strong> </p><br>       
                    <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
                    </div>";    
            }
        }

        if(isset($_GET['sucess'])){
            $alertMessage = "<div class='alert alert-success'> 
            <p>Запись <strong>удалена</strong> успешно.</p><br>       
            <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
            </div>";
        }

        if(isset($_GET['delid'])){ 
            $deluser = $_GET['delid'];
            if($power == 'yes'){
               if ($deluser != 1) {
                    $alertMessage = "<div class='alert alert-danger'> 
                        <p>Вы уверены, что хотите удалить этого администратора? Отменить действие будет невозможно!</p><br>
                            <form action='".htmlspecialchars($_SERVER['PHP_SELF'])."?id=$deluser' method='post'>
                               <input type='submit' class='btn btn-danger btn-sm'
                               name='confirm-delete' value='Да, удалить!'>
                               <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Нет, отмена!</a> 
                            </form>
                        </div>";
                } else {
                    $alertMessage = "<div class='alert alert-danger'> 
                    <p>Вы не можете удалить себя <strong>СПАСИБО.</strong> </p><br>       
                    <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
                    </div>";
                }        
            }else{
                $alertMessage = "<div class='alert alert-danger'> 
                <p>Вы не главный администратор и не можете удалять других администраторов. <strong>СПАСИБО.</strong> </p><br>       
                <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
                </div>";
            }
        }

        if(isset($_GET['back'])){
            $back = $_GET['back'];
            if($back!=2){
                    $update_status = "<div class='alert alert-danger'> 
            <p>Вы не главный администратор. Вы можете редактировать только свою запись.<strong>СПАСИБО.</strong> </p><br>       
            <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
            </div>";
            }else{
                $update_status = "<div class='alert alert-success'> 
            <p>Запись успешно обновлена.</p><br>       
            <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
            </div>";
            }
        } 

        if(isset($_POST['confirm-delete'])){
            $id = $_GET['id'];
            $query2 = "SELECT * FROM `admin` WHERE id='$id' ";
            $result2 = mysqli_query($connection, $query2);
            if(mysqli_num_rows($result2) > 0){
                while( $row2 = mysqli_fetch_assoc($result2) ){
                    $base_directory = "images/admin/";
                    if(unlink($base_directory.$row2['profilePic']))
                        $delVar = " ";  
                }
            }
            $query = "DELETE FROM `admin` WHERE id='$id'";
            $result = mysqli_query($connection,$query);
            if($result){
                header("Location: home.php?sucess=1");
            } else {
                echo "Ошибка".$query."<br>".mysqli_error($conn);
            }
        }
    include('header.php');    
?>

<div id="vertical-nav">
			<div class="container clearfix">
				<nav>
					<ul>
						<li class="current"><a href="home.php"><i class="icon-home2"></i>Главная</a></li>
                        <li><a href="categorie.php"><i class="icon-book2"></i>Категории</a></li>
						<li><a href="courses.php"><i class="icon-book3"></i>Курсы</a></li>
						<li><a href="content.php"><i class="icon-line-content-left"></i>Контент</a> </li>
						<li><a href="blog.php"><i class="icon-blogger"></i>Блог</a></li>
						<li><a href="library.php"><i class="icon-line-align-center"></i>Библиотека</a></li>
						<li><a href="instructors.php"><i class="icon-guest"></i>Преподаватели</a></li>
                        <li><a href="team.php"><i class="icon-users"></i>Команда</a></li>
                        <li><a href="logout.php"><i class="icon-line-power"></i>Выход</a></li>    
					</ul>
				</nav>

			</div>
		</div>
    
<!DOCTYPE html>
<html dir="ltr" lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="SemiColonWeb" />
    <link rel="icon" type="image/png" href="images/tab.png" sizes="16x16">
    <link rel="icon" type="image/png" href="images/tab1.png" sizes="32x32">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="../style.css" type="text/css" />
    <link rel="stylesheet" href="../css/dark.css" type="text/css" />
    <link rel="stylesheet" href="../css/font-icons.css" type="text/css" />
    <link rel="stylesheet" href="../css/animate.css" type="text/css" />
    <link rel="stylesheet" href="../css/magnific-popup.css" type="text/css" />
    <link rel="stylesheet" href="../css/responsive.css" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Donya</title>
</head>



        <section id="page-title" style="margin-padding: 0px;">
            <div class="container clearfix">
                <h1>Добро пожаловать <strong><?php if(isset($loginName)) echo $loginName; ?></strong></h1>
            </div>
            <div id="page-menu-wrap"></div>
        </section>

        <section id="content">
            <div class="content-wrap">
                <div class="container clearfix">
                    <div class="postcontent nobottommargin">
                        <?php
                            echo $alertMessage; 
                            if(isset($update_status)) echo $update_status;

                            if(isset($message_name) || isset($message_picture) || isset($message_pass) || isset($message_c_pass) || isset($submit_message) || isset($admin_error)){
                                echo "<div class='alert alert-danger'>";
                                echo "Пожалуйста, заполните форму внимательно и правильно<br>";
                                echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
                                </div>";    
                            }
                        ?>
                         
                        <h3>Добавить администратора</h3>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nameId">Полное имя</label>
                                <input type="text" id="nameId" placeholder="Полное имя" name="fullname" class="form-control" title="Только буквы и пробелы" pattern="[A-Za-z/\s]+">
                                <?php if(isset($message_name)){ echo $message_name; } ?>
                            </div>

                            <div class="form-group">                    
                               <label>Тип администратора</label>
                                <select class="form-control" name="admin_op">
                                <option value="">Выберите опцию</option>
                                <option value="yes">Главный администратор</option>
                                <option value="no">Обычный администратор</option>
                                </select>
                                <?php if(isset($admin_error)) echo $admin_error; ?>
                            </div>

                            <div class="form-group">
                                <label for="emailId">Email</label>
                                <input type="email" id="emailId" placeholder="Email" name="email" class="form-control" title="someone@example.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                                <?php if(isset($message_email)){ echo $message_email; } ?>
                            </div>
                            <div class="form-group">
                                <label class="btn btn-success" for="my-file-selector">
                                    <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                                    Аватар
                                </label>
                                <span class='label label-success' id="upload-file-info"></span>
                                <?php if(isset($message_picture)){ echo $message_picture; } ?>
                            </div>
                            <div class="form-group">
                                <label for="passwordId1">Пароль</label>
                                <input type="password" id="passwordId1" placeholder="Пароль" name="password" class="form-control" required minlength="6">
                                <?php if(isset($message_pass)){ echo $message_pass; } ?>
                            </div>
                            <div class="form-group">
                                <label for="passwordId2">Подтвердите пароль</label>
                                <input id="passwordId2" type="password" placeholder="Подтвердите пароль" name="c_password" class="form-control" required minlength="6">
                                <?php if(isset($message_c_pass)){ echo $message_c_pass; } ?>
                            </div>
                            <div class="form-group">
                                <button name="submit" class="btn btn-block btn-success" type="submit">Отправить</button>
                            </div>
                        </form>
                        
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>ID</th>
                                <th>Фото</th>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Редактировать</th>
                                <th>Удалить</th>
                            </tr>
                            <?php
                                $query = "SELECT * FROM `admin`";
                                $result = mysqli_query($connection, $query);
                                if(mysqli_num_rows($result) > 0){
                                    while( $row = mysqli_fetch_assoc($result) ){
                                        echo "<tr>";
                                        echo "<td>".$row["id"]."</td> <td><img src=images/admin/".$row["profilePic"]." width='80px' height='80px'> </td> <td>".$row["name"]."</td> <td> ".$row["admin_mail"]."</td>";
                                        echo '<td><a href="updateadmin.php?id='.$row['id']. '" type= "button" class="btn btn-primary btn-sm">
                                        <span class="icon-edit"></span></a></td>';
                                        echo '<td><a href="home.php?delid='.$row['id']. '" type= "button" class="btn btn-danger btn-sm">
                                        <span class="icon-trash2"></span></a></td>';
                                        echo "<tr>";  
                                    }
                                } else {
                                    echo "<div class='alert alert-danger'>Нет администраторов<a class='close' data-dismiss='alert'>&times</a></div>";
                                }
                                mysqli_close($connection);
                            ?>
                            <tr>
                                <td colspan="6" id="end"><div class="text-center"><a href="home.php" type="button" class="btn btn-sm btn-success"><span class="icon-plus"></span></a></div></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php include('footer.php'); } ?>