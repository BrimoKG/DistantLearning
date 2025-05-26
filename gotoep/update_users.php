<?php
//**************** Admin Update
include("../include/config.php");


    if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {

        header('Location: index.php');
    } else{
        /* %%%%%%%%%%%%% START CODE SUBMIT %%%%%%%%%%%% */

        $UserId = $_GET['id'];
        $loginName = $_SESSION['userName'];
        $loginId = $_SESSION['userId'];
        $power = $_SESSION['userType'];

        if( isset($_POST['submit']) ){

            if(isset($_POST["user_op"]) && !empty($_POST["user_op"])){

                $userType = $_POST["user_op"];
            } else {
                $userError = '<b class="text-danger text-center">Пожалуйста, выберите тип ползователь.</b>';
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

            //Email Condition
            if( isset($_POST['email']) && !empty($_POST['email']) ){
                
                $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
                if(preg_match($pattern,$_POST['email'])){
                    
                    $cemail = mysqli_real_escape_string($connection,$_POST['email']);  
              
                    $query = "SELECT * FROM `users` WHERE user_mail='$cemail' ";
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
                $del = 'yes';

                $target_file = $target_dir . basename($_FILES["profilePic"]["name"]);
                $uploadOk = 1;
                $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["profilePic"]["tmp_name"]);
                if($check !== false) {
                    
                    $uploadOk = 1;
                } else {
                    $message_picture  = '<b class="text-danger">Файл не является изображением</b>';
                    $uploadOk = 0;
                }
                
                // Check file size
                if ($_FILES["profilePic"]["size"] > 5000000) {
                    $message_picture =  '<b class="text-danger">Извините, файл слишком большой</b>';
                    $uploadOk = 0;
                }
                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    $message_picture =  '<b class="text-danger">Допустимы только JPG, JPEG, PNG и GIF файлы</b>';
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk != 0) {
                    $temp = explode(".", $_FILES["profilePic"]["name"]);
                    $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                    if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_dir . $newfilename)) {

                        
                    } else {
                        $message_picture =  '<b class="text-danger">Ошибка загрузки файла</b>';
                    }
                }

            }else{
                    $newfilename =  $_POST['picValue'];
                    $del = 'no';
            }

            // Submission
            if( ( isset($name) && !empty($name) ) && ( isset($userType) && !empty($userType) ) && ( isset($email) && !empty($email) ) && ( isset($password) && !empty($password) ) && ( isset($newfilename) && !empty($newfilename) ) ){

                $check_email = "SELECT * FROM 'users' WHERE id != '$UserId' AND user_mail = '$email'";

                $check_res = mysqli_query($connection, $check_email);
                if(mysqli_num_rows($check_res) > 0){
                    $message_email = '<b class="text-danger text-center">Этот email уже существует</b>';
                }else{

                            $update_query = "UPDATE 'users' SET  
                            name='$name',
                            user_mail='$email',  
                            password='$password', 
                            profilePic='$newfilename',
                            type = '$userType'
                            WHERE id='$UserId'" ;


                            if(mysqli_query($connection, $update_query)){
                                
                                if($del == 'yes'){
                                $base_directory = "images/users/";
                                if(unlink($base_directory.$_POST['picValue']))
                                $delVar = " ";
                            }

                                header('Location: home.php?back=2');
                            }else{
                                $submit_message = '<div class="alert alert-danger">
                                    <strong>Ошибка!</strong>
                                    Не удалось обновить данные, попробуйте позже
                                </div>';
                            }            
                }
            } 
        }//submit button

    
    if(isset($_GET['id'])){

        if( $power == 'yes' || $loginId==$UserId) {

           $query = "SELECT * FROM 'users' WHERE id='$UserId' ";

            $result = mysqli_query($connection,$query);

            if(mysqli_num_rows($result) > 0){
                  while( $row = mysqli_fetch_assoc($result) ){

                $userPic = $row["profilePic"];
                $userName = $row["name"];
                $userMail = $row["user_mail"];
                $userType = $row["type"];
            
             }
            }
        }else header('Location: home.php?back=1');    

    } else header('Location: home.php?back=1');

    
    include('header.php');
    
?>

<div id="vertical-nav">
			<div class="container clearfix">
				<nav>
					<ul>
						<li><a href="home.php"><i class="icon-home2"></i>Главная</a></li>
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

                <!-- Page Title
        ============================================= -->
        <section id="page-title">

            <div class="container clearfix">
                <h1>Добро пожаловать <strong><?php if(isset($loginName)) echo $loginName; ?></strong></h1>
            </div>

            <div id="page-menu-wrap">

                <div class="container clearfix">

                    

                </div>

            </div>

        </section><!-- #page-title end -->

        <!-- Page Sub Menu
        ============================================= -->

        <!-- Content
        ============================================= -->
        <section id="content">

            <div class="content-wrap">

                <div class="container clearfix">
                <!-- ========================================== -->

                <div class="postcontent nobottommargin">

                    

                <?php

              

                        if(isset($message_name) || isset($message_picture) || isset($message_pass) || isset($message_c_pass) || isset($submit_message) || isset($userError)){
                           echo "<div class='alert alert-danger'>";
                            
                            echo "Пожалуйста, заполните форму внимательно и правильно<br>";
                            
                            echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
                            </div>";    

                        }

                 ?>
                 
                        <h3>Обновить ползователь</h3>

                        <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nameId">Полное имя</label>
                        <input type="text" id="nameId" placeholder="Полное имя" value="<?php if(isset($userName)) echo $userName; ?>" name="fullname" class="form-control" title="Только буквы и пробелы" pattern="[A-Za-z/\s]+">
                        <?php if(isset($message_name)){ echo $message_name; } ?>
                    </div>

                    <div class="form-group">                    
                       <label>Тип ползователь</label>
                        <select class="form-control"  name="user_op">
                        <option value="">Выберите опцию</option>
                        
                        <option <?php if($userType == 'yes') { ?> selected <?php } ?> value="yes" >Главный ползователь</option>
                        
                        <option <?php if($userType == 'no') { ?> selected <?php } ?> value="no">Обычный ползователь</option>

                        </select>
                    <?php if(isset($userError)) echo $userError; ?>
                    </div>

                    <div class="form-group">
                        <label for="emailId">Email</label>
                        <input type="email" id="emailId" placeholder="Email" value="<?php 
                        if(isset($userMail)) echo $userMail; ?>" name="email" class="form-control" title="ivan@example.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                        <?php if(isset($message_email)){ echo $message_email; } ?>
                    </div>
                    <div class="form-group">
                            <img src="images/admin/<?php if(isset($userPic)) echo $userPic; ?>" width="100 px" height="100 px">
                    </div>
                    <div class="form-group">       
                        <label class="btn btn-success" for="my-file-selector">
                            <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                           Изменить аватар
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
                        <label for="passwordid2">Подтвердите пароль</label>
                        <input type="password" id="passwordid2" placeholder="Подтвердите пароль" name="c_password" class="form-control" required minlength="6">
                        <?php if(isset($message_c_pass)){ echo $message_c_pass; } ?>
                    </div>

                    <input type="hidden" value="<?php if(isset($userPic)) echo $userPic; ?>" name="picValue" />
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