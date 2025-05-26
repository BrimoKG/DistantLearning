<?php
    //**************** Файл администрирования команды
include("../include/config.php");

	if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {

        header('Location: index.php');
    } else {

        $loginName = $_SESSION['userName'];
        $loginId = $_SESSION['userId'];
        $power = $_SESSION['adminType'];
        $alertMessage = " ";
        
        /* %%%%%%%%%%%%% НАЧАЛО ОБРАБОТКИ ФОРМЫ %%%%%%%%%%%% */

        if( isset($_POST['submit']) ){

            if($power == 'yes'){ //*************************

                //Проверка имени
                if( isset($_POST['fullname']) && !empty($_POST['fullname'])){
        
                    if(preg_match('/^[А-Яа-яЁё\s\'-]+$/u',$_POST['fullname'])){
                      $name = mysqli_real_escape_string($connection,$_POST['fullname']);
                    }else{
                      $message_name = '<b class="text-danger text-center">Пожалуйста, введите корректное имя.</b>';
                    }
                }else{
                    $message_name = '<b class="text-danger text-center">Пожалуйста, заполните поле имени.</b>';
                }

                //Проверка квалификации
                if(isset($_POST['qualification']) && !empty($_POST['qualification'])){
            	
                	if(preg_match('/^[A-Za-zА-Яа-яЁё\s]+$/u',$_POST['qualification'])){
                		$qualification = mysqli_real_escape_string($connection,$_POST['qualification']);
                	}else{

                		$message_q = '<b class="text-danger text-center">Пожалуйста, введите корректную квалификацию.</b>';
                	}
                }else{
            	   $message_q = '<b class="text-danger text-center">Пожалуйста, заполните поле квалификации.</b>';
                }


                if( isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"]) ){
                
                    $target_dir = "images/team/";
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
                    $message_picture =  '<b class="text-danger">Пожалуйста, выберите фотографию</b>';
                }


                if( ( isset($name) && !empty($name) )  && ( isset($newfilename) && !empty($newfilename) ) && ( isset($qualification) && !empty($qualification) )  ){


                    $insert_query = "INSERT INTO `team` (name, image, qualification) VALUES ('$name','$newfilename','$qualification')";

                    if(mysqli_query($connection, $insert_query)){                        
                       
                        header('Location: team.php#end');
                    }else{
                        $submit_message = '<div class="alert alert-danger">
                            <strong>Предупреждение!</strong>
                            В настоящее время отправка невозможна, попробуйте позже
                        </div>';
                    }

                } // конец условия 

            }else{
    
                 $alertMessage = "<div class='alert alert-danger'> 
                    <p>Вы не являетесь администратором. У вас нет прав удалять записи. <strong>СПАСИБО.</strong> </p><br>       
                    <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
                    </div>";    
            } // *******************************

        }//кнопка отправки

	   /* %%%%%%%%%%%%% КОНЕЦ ОБРАБОТКИ ФОРМЫ %%%%%%%%%%%% */


    if(isset($_GET['sucess'])){
        $alertMessage = "<div class='alert alert-success'> 
        <p>Запись успешно удалена.</p><br>       
        <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
        </div>";
    }

    if(isset($_GET['delid'])){ 

        $deluser = $_GET['delid'];

        if($power == 'yes'){
                                   
            $alertMessage = "<div class='alert alert-danger'> 
                <p>Вы уверены, что хотите удалить эту запись? Это действие нельзя отменить!</p><br>
                    <form action='".htmlspecialchars($_SERVER['PHP_SELF'])."?id=$deluser' method='post'>
                       <input type='submit' class='btn btn-danger btn-sm'
                       name='confirm-delete' value='Да, удалить!'>
                       <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Нет, отменить!</a>                         
                    </form>
            </div>";
        } else {
            $alertMessage = "<div class='alert alert-danger'> 
            <p>Вы не являетесь администратором. У вас нет прав удалять записи. <strong>СПАСИБО.</strong> </p><br>       
            <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
            </div>";
        }
    }


    // Возврат из обновления
    if(isset($_GET['back'])){

        $back = $_GET['back'];

        if($back!=2){
                $update_status = "<div class='alert alert-danger'> 
        <p>Вы не являетесь администратором. Вы можете обновлять только свои записи.<strong>СПАСИБО.</strong> </p><br>       
        <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
        </div>";
        }else{

            $update_status = "<div class='alert alert-success'> 
        <p>Запись успешно обновлена.</p><br>       
        <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
        </div>";
        }

    } 


    // подтверждение удаления
    if(isset($_POST['confirm-delete'])){

        $id = $_GET['id'];

        // Удаление файла из папки
        $query2 = "SELECT * FROM `team` WHERE id='$id' ";

        $result2 = mysqli_query($connection, $query2);

        if(mysqli_num_rows($result2) > 0){
        
                        //Есть данные 
                        //выводим данные
             while( $row2 = mysqli_fetch_assoc($result2) ){
                    
                    $base_directory = "images/team/";
                    if(unlink($base_directory.$row2['image']))
                        $delVar = " ";
                      
             }
        }
 
        // новый запрос к базе данных 
        $query = "DELETE FROM `team` WHERE id='$id'";
        $result = mysqli_query($connection,$query);
        
        if($result){
            // перенаправление
            header("Location: team.php?sucess=1");
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
				<!-- ========================================== -->

				<div class="postcontent nobottommargin">
                
                <?php

                    echo $alertMessage; 
                    if(isset($update_status)) echo $update_status;

                        if(isset($message_name) || isset($message_picture) || isset($submit_message)  || isset($message_q) ){
                            echo "<div class='alert alert-danger'>";
                            
                            echo "Пожалуйста, заполните форму внимательно и корректно<br>";

                            echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
                            </div>";    

                        }

                 ?>
                 
						<h3>Добавить участника</h3>

                        <form action="" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="fullnameId1">Полное имя</label>
                        <input type="text" id="fullnameId1" placeholder="Полное имя" name="fullname" class="form-control" title="Только буквы и пробелы" pattern="[A-Za-z/\s]+">
                        <?php if(isset($message_name)){ echo $message_name; } ?>
                    </div>
                    <div class="form-group">
                        <label class="btn btn-success" for="my-file-selector">
                            <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                            Изменить фотографию
                        </label>
                        <span class='label label-success' id="upload-file-info"></span>
                        <?php if(isset($message_picture)){ echo $message_picture; } ?>
                    </div>
                    <div class="form-group">
                        <label for="qualificationId1">Квалификация</label>
                        <input type="tex" id="qualificationId1" placeholder="Квалификация" name="qualification" class="form-control">
                        <?php if(isset($message_q)){ echo $message_q; } ?>
                    </div>
                    <div class="form-group">
                        <button name="submit" class="btn btn-block btn-success" type="submit">Отправить</button>
                    </div>
                </form>
                        					

<!--%%%%%%%%%%%%%%%% ТАБЛИЦА ДЛЯ ОТОБРАЖЕНИЯ %%%%%%%%%%%%%%%%% -->
    
    
    <table class="table table-striped table-bordered">
    <tr>
        <th>ID</th>
        <th>Фото</th>
        <th>Имя</th>
        <th>Квалификация</th>
        <th>Редактировать</th>
        <th>Удалить</th>
    </tr>
    <?php

        $query = "SELECT * FROM `team`";

        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) > 0){
        
                        //Есть данные 
                        //выводим данные
         while( $row = mysqli_fetch_assoc($result) ){
                echo "<tr>";
echo "<td>".$row["id"]."</td> <td><img src=images/team/".$row["image"]." width='80px' height='80px'> </td> <td>".$row["name"]."</td><td>".$row["qualification"]."</td>";

                echo '<td><a href="updateteam.php?id='.$row['id']. '" type= "button" class="btn btn-primary btn-sm">
                <span class="icon-edit"></span></a></td>';
                
                echo '<td><a href="team.php?delid='.$row['id']. '" type= "button" class="btn btn-danger btn-sm">
                <span class="icon-trash2"></span></a></td>';

                echo "<tr>";  
            }
    } else {
        echo "<div class='alert alert-danger'>Нет участников команды<a class='close' data-dismiss='alert'>&times</a></div>";
    }
    
    // закрываем соединение с mysql 
        mysqli_close($connection);
    ?>

    <tr>
        <td colspan="9" id="end"><div class="text-center"><a href="team.php" type="button" class="btn btn-sm btn-success"><span class="icon-plus"></span></a></div></td>
    </tr>
</table>

<!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
    					</div><!-- .postcontent end -->


				</div>

			</div>

		</section><!-- #content end -->

<?php include('footer.php'); 
}
?>