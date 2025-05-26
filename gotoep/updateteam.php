<?php
    //********* Обновление участника команды
include("../include/config.php");

	if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {

        header('Location: index.php');
    } else {

        $memberId = $_GET['id'];
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

            //Проверка квалификации
            if( isset($_POST['qualification']) && !empty($_POST['qualification'])){
            	
            	if(preg_match('/^[А-Яа-яЁё\s]+$/u',$_POST['qualification'])){
            		$qualification = mysqli_real_escape_string($connection,$_POST['qualification']);
            	}else{
            		$message_q = '<b class="text-danger text-center">Пожалуйста, введите корректную квалификацию.</b>';
            	}

            }else{
            	$message_q = '<b class="text-danger text-center">Пожалуйста, заполните поле квалификации.</b>';
            }

            //Проверка фотографии
            if( isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"]) ){
                $target_dir = "images/team/";
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
                    $message_picture =  '<b class="text-danger">Извините, файл слишком большой.</b>';
                    $uploadOk = 0;
                }
            
                // Проверка формата файла
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    $message_picture =  '<b class="text-danger">Разрешены только JPG, JPEG, PNG и GIF файлы</b>';
                    $uploadOk = 0;
                }
            
                // Если нет ошибок - загружаем файл
                if ($uploadOk != 0) {
                    $temp = explode(".", $_FILES["profilePic"]["name"]);
                    $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                    if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_dir . $newfilename)) {
                        
                    } else {
                        $message_picture =  '<b class="text-danger">Ошибка при загрузке файла</b>';
                    }
                }

            }else{
                $newfilename =  $_POST['picValue'];
                $del = 'no';
            }

            if( ( isset($name) && !empty($name) )  && ( isset($newfilename) && !empty($newfilename) ) && ( isset($qualification) && !empty($qualification) )  ){

                    $insert_query = "UPDATE `team` SET
                     name ='$name',  
                     image = '$newfilename', 
                     qualification = '$qualification' 
                     WHERE id = '$memberId'";

                    if(mysqli_query($connection, $insert_query)){
                        
                        if($del == 'yes'){
                        $base_directory = "images/team/";
                        if(unlink($base_directory.$_POST['picValue']))
                        $delVar = " ";
                    }
                       
                        header('Location: team.php?back=2');
                    }else{
                        $submit_message = '<div class="alert alert-danger">
                            <strong>Предупреждение!</strong>
                            В настоящее время отправка невозможна, попробуйте позже
                        </div>';
                    }
                } // конец условия 
            }//кнопка отправки


    if(isset($_GET['id'])){

        $memberId = $_GET['id'];
        if( $power == 'yes' ) {

           $query = "SELECT * FROM `team` WHERE id=$memberId ";

            $result = mysqli_query($connection,$query);

            if(mysqli_num_rows($result) > 0){
                while( $row = mysqli_fetch_assoc($result) ){
                    $memberPic = $row["image"];
                    $memberName = $row["name"];
                    $memberQualification = $row["qualification"];
                }
            }
        }else header('Location: team.php?back=1');    

    } else header('Location: team.php?back=1');
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
                        <li class="current"><a href="team.php"><i class="icon-users"></i>Команда</a></li>
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
                    if(isset($message_name) || isset($message_picture) || isset($submit_message) || isset($message_q) ){
                        echo "<div class='alert alert-danger'>";
                        echo "Пожалуйста, заполните форму внимательно и корректно<br>";
                        echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
                        </div>";    
                    }
                ?>
                 
                <h3>Обновить участника</h3>

                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fullnameId1">Полное имя</label>
                        <input type="text" id="fullnameId1" placeholder="Полное имя" value="<?php if(isset($memberName)) echo $memberName; ?>" name="fullname" class="form-control" title="Только буквы и пробелы" pattern="[A-Za-z/\s]+">
                        <?php if(isset($message_name)){ echo $message_name; } ?>
                    </div>

                    <div class="form-group">
                        <img src="images/team/<?php if(isset($memberPic)) echo $memberPic; ?>" width="100px" height="100px">
                    </div>

                    <div class="form-group">
                        <label class="btn btn-success" for="my-file-selector">
                            <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                            Фотография профиля
                        </label>
                        <span class='label label-success' id="upload-file-info"></span>
                        <?php if(isset($message_picture)){ echo $message_picture; } ?>
                    </div>

                    <div class="form-group">
                        <label for="qualificationid1">Квалификация</label>
                        <input type="text" id="qualificationid1" placeholder="Квалификация" value="<?php if($memberQualification) echo $memberQualification; ?>" name="qualification" class="form-control">
                        <?php if(isset($message_q)){ echo $message_q; } ?>
                    </div>

                    <input type="hidden" value="<?php if(isset($memberPic)) echo $memberPic; ?>" name="picValue"/>
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