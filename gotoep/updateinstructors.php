<?php
    //************ Код обновления
include("../include/config.php");

	if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {

        header('Location: index.php');
    } else {

        $instructorId = $_GET['id'];
        $loginName = $_SESSION['userName'];
        $loginId = $_SESSION['userId'];
        $power = $_SESSION['adminType'];

        /* %%%%%%%%%%%%% НАЧАЛО ОБРАБОТКИ ФОРМЫ %%%%%%%%%%%% */

        if( isset($_POST['submit']) ){

            //Проверка имени
            if( isset($_POST['fullname']) && !empty($_POST['fullname'])){
        
                if(preg_match('/^[А-Яа-яЁё\s]+$/u',$_POST['fullname'])){
                  $name = mysqli_real_escape_string($connection,$_POST['fullname']);
                }else{
                  $message_name = '<b class="text-danger text-center">Пожалуйста, введите корректное имя.</b>';
                }

            }else{
                $message_name = '<b class="text-danger text-center">Пожалуйста, заполните поле имени.</b>';
            }

            //Проверка email
            if( isset($_POST['email']) && !empty($_POST['email']) ){
                $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";

                if(preg_match($pattern,$_POST['email'])){
                  $cmail = mysqli_real_escape_string($connection,$_POST['email']);  
                  
                  $query = "SELECT * FROM `teacher` WHERE id != '$instructorId' AND mail='$cmail' ";
                  $result = mysqli_query($connection, $query);
                    if(mysqli_num_rows($result) > 0){
                        $message_email = '<b class="text-danger text-center">Этот email уже существует, попробуйте другой.</b>';
                    }else{
                        $email = mysqli_real_escape_string($connection,$_POST['email']);                    
                    }
                }else{
                    $message_email = '<b class="text-danger text-center">Пожалуйста, введите корректный email.</b>';
                }
            }else{
                $message_email = '<b class="text-danger text-center">Пожалуйста, заполните поле email.</b>';
            }

            //Проверка телефона
            if( isset($_POST['phone']) && !empty($_POST['phone'])){
                
                 $pattern = "/^(\+7|8)?[\s\-]?\(?\d{3}\)?[\s\-]?\d{3}[\s\-]?\d{2}[\s\-]?\d{2}$/";
                if(preg_match($pattern,$_POST['phone'])){

                	$phone = mysqli_real_escape_string($connection,$_POST['phone']);
                }else{
                		$message_ph = '<b class="text-danger text-center">Пожалуйста, введите корректный номер телефона.</b>';
                }
 				
            }else{
                    $message_ph = '<b class="text-danger text-center">Пожалуйста, заполните поле телефона.</b>';
            } 

            // Проверка описания
            if( isset($_POST['description']) && !empty($_POST['description']) ){
            	
            		$description = mysqli_real_escape_string($connection,$_POST['description']);
            	

            }else{
            	$message_des = '<b class="text-danger text-center">Пожалуйста, заполните поле описания.</b>';
            }     

            // Проверка квалификации
            if( isset($_POST['qualification']) && !empty($_POST['qualification'])){            
            	if(preg_match('/^[A-Za-z\s]+$/',$_POST['qualification'])){
            		$qualification = mysqli_real_escape_string($connection,$_POST['qualification']);
            	}else{

            		$message_q = '<b class="text-danger text-center">Пожалуйста, введите корректную квалификацию.</b>';
            	}
            }else{
            	$message_q = '<b class="text-danger text-center">Пожалуйста, заполните поле квалификации.</b>';
            }


            if( isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"]) ){
                $target_dir = "images/instructor/";
                $del = 'yes';
                $target_file = $target_dir . basename($_FILES["profilePic"]["name"]);
                $uploadOk = 1;
                $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

                // Проверка, является ли файл изображением
                $check = getimagesize($_FILES["profilePic"]["tmp_name"]);
                if($check !== false) {
                    
                    $uploadOk = 1;
                } else {
                    $message_picture  = '<b class="text-danger">Файл не является изображением</b>';
                    $uploadOk = 0;
                }
                
                // Проверка размера файла
                if ($_FILES["profilePic"]["size"] > 5000000) {
                    $message_picture =  '<b class="text-danger">Извините, ваш файл слишком большой.</b>';
                    $uploadOk = 0;
                }
            
                // Проверка формата файла
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    $message_picture =  '<b class="text-danger">Извините, разрешены только JPG, JPEG, PNG и GIF файлы</b>';
                    $uploadOk = 0;
                }
            
                // Если нет ошибок - загружаем файл
                if ($uploadOk != 0) {
                    $temp = explode(".", $_FILES["profilePic"]["name"]);
                    $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                    if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_dir . $newfilename)) {
                        
                    } else {
                        $message_picture =  '<b class="text-danger">Извините, произошла ошибка при загрузке файла';
                    }
                }

            }else{
                $newfilename =  $_POST['picValue'];
                $del = 'no';
            }
                        if( ( isset($name) && !empty($name) ) && ( isset($email) && !empty($email) ) && ( isset($newfilename) && !empty($newfilename) ) && ( isset($phone) && !empty($phone) ) && ( isset($description) && !empty($description) ) && ( isset($qualification) && !empty($qualification) )  ){

                    $insert_query = "UPDATE `teacher` set
                     name ='$name', 
                     mail ='$cmail', 
                     phone = '$phone', 
                     image = '$newfilename', 
                     qualification = '$qualification', 
                     description =  '$description'
                     WHERE id = '$instructorId'";

                    if(mysqli_query($connection, $insert_query)){
                        
                        if($del == 'yes'){
                        $base_directory = "images/instructor/";
                        if(unlink($base_directory.$_POST['picValue']))
                        $delVar = " ";
                    }
                       
                        header('Location: instructors.php?back=2');
                    }else{
                        $submit_message = '<div class="alert alert-danger">
                            <strong>Предупреждение!</strong>
                            В настоящее время отправка невозможна, попробуйте позже
                        </div>';
                    }
                } // конец условия 
            }//кнопка отправки


    if(isset($_GET['id'])){

        $instructorId = $_GET['id'];
        if( $power == 'yes' ) {

           $query = "SELECT * FROM `teacher` WHERE id=$instructorId ";

            $result = mysqli_query($connection,$query);

            if(mysqli_num_rows($result) > 0){
                  while( $row = mysqli_fetch_assoc($result) ){

                $teacherPic = $row["image"];
                $teacherName = $row["name"];
                $teacherMail = $row["mail"];
                $teacherPhone = $row["phone"];
                $teacherdes = $row["description"];
                $teacherqualification = $row["qualification"];

            
             }
            }
        }else header('Location: instructors.php?back=1');    

    } else header('Location: instructors.php?back=1');
    include('header.php');
?>

<!-- Обертка документа -->
<div id="wrapper" class="clearfix">

    <div id="vertical-nav">
        <div class="container clearfix">

            <nav>
                <ul>
                    <li><a href="home.php"><i class="icon-home2"></i>Главная</a></li>
                    <li><a href="categorie.php"><i class="icon-book2"></i>Категории</a></li>
                    <li><a href="courses.php"><i class="icon-book3"></i>Курсы</a></li>
                    <li><a href="content.php"><i class="icon-line-content-left"></i>Контент</a></li>
                    <li><a href="blog.php"><i class="icon-blogger"></i>Блог</a></li>
                    <li><a href="library.php"><i class="icon-line-align-center"></i>Библиотека</a></li>
                    <li class="current"><a href="instructors.php"><i class="icon-guest"></i>Преподаватели</a></li>
                    <li><a href="team.php"><i class="icon-users"></i>Команда</a></li>
                    <li><a href="logout.php"><i class="icon-line-power"></i>Выход</a></li>    
                </ul>
            </nav>

        </div>
    </div>

    <!-- Заголовок страницы -->
    <section id="page-title">
        <div class="container clearfix">
            <h1>Добро пожаловать <strong><?php if(isset($loginName)) echo $loginName; ?></strong></h1>
        </div>

        <div id="page-menu-wrap">
            <div class="container clearfix">
            </div>
        </div>
    </section><!-- конец #page-title -->

    <!-- Контент -->
    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">
                <div class="postcontent nobottommargin">

                <?php
                    if(isset($message_name) || isset($message_picture) || isset($message_picture) || isset($submit_message) || isset($message_q) || isset($message_des) || isset($message_ph)){
                        echo "<div class='alert alert-danger'>";
                        echo "Пожалуйста, заполните форму внимательно и корректно<br>";
                        echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
                        </div>";    
                    }
                ?>
                 
                <h3>Обновить данные преподавателя</h3>

                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fullnameId1">Полное имя</label>
                        <input type="text" id="fullnameId1" placeholder="Полное имя" value="<?php if(isset($teacherName)) echo $teacherName; ?>" name="fullname" class="form-control" title="Только буквы и пробелы" pattern="[A-Za-z/\s]+">
                        <?php if(isset($message_name)){ echo $message_name; } ?>
                    </div>

                    <div class="form-group">
                        <label for="emailId1">Email</label>
                        <input type="email" id="emailId1" placeholder="Email" value="<?php if(isset($teacherMail)) echo $teacherMail; ?>" name="email" class="form-control" title="someone@example.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                        <?php if(isset($message_email)){ echo $message_email; } ?>
                    </div>

                    <div class="form-group">
                        <img src="images/instructor/<?php if(isset($teacherPic)) echo $teacherPic; ?>" width="100px" height="100px">
                    </div>

                    <div class="form-group">
                        <label class="btn btn-success" for="my-file-selector">
                            <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                            Фото профиля
                        </label>
                        <span class='label label-success' id="upload-file-info"></span>
                        <?php if(isset($message_picture)){ echo $message_picture; } ?>
                    </div>

                    <div class="form-group">
                        <label for="qualificationid1">Квалификация</label>
                        <input type="tex" id="qualificationid1" placeholder="Квалификация" value="<?php if($teacherqualification) echo $teacherqualification; ?>" name="qualification" class="form-control">
                        <?php if(isset($message_q)){ echo $message_q; } ?>
                    </div>

                    <div class="form-group">
                        <label for="phoneId1">Телефон</label>
                        <input type="text" id="phoneId1" placeholder="Телефон" value="<?php if(isset($teacherPhone)) echo $teacherPhone; ?>" name="phone" class="form-control">
                        <?php if(isset($message_ph)){ echo $message_ph; } ?>
                    </div>

                    <div class="form-group">
                        <label for="descriptionId1">Описание</label>
                        <textarea id="descriptionId1" placeholder="Описание" class="form-control" name="description"><?php if(isset($teacherdes)) echo $teacherdes; ?></textarea>
                    </div>
                    <?php if(isset($message_des)){ echo $message_des; } ?>
                    <input type="hidden" value="<?php if(isset($teacherPic)) echo $teacherPic; ?>" name="picValue"/>
                    <div class="form-group">
                        <button name="submit" class="btn btn-block btn-success" type="submit">Обновить</button>
                    </div>
                </form>

                </div><!-- .postcontent end -->
            </div>
        </div>
    </section><!-- #content end -->

<?php include('footer.php'); 
}
?>