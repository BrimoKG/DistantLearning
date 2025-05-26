<?php

    include("../include/config.php");

	if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {

        header('Location: index.php');
    } else {

        $loginName = $_SESSION['userName'];
        $loginId = $_SESSION['userId'];
        $postId = $_GET["id"];
        $power = $_SESSION['adminType'];

        /* %%%%%%%%%%%%% НАЧАЛО КОДА ДЛЯ ОТПРАВКИ %%%%%%%%%%%% */

          if( isset($_POST['submit']) ){

            $adminPost = $_POST['adminPost'];

            // Условие для заголовка
            if( isset($_POST['title']) && !empty($_POST['title'])){
        
                  $title = mysqli_real_escape_string($connection,$_POST['title']);
               
            }else{
                  $message_title = '<b class="text-danger text-center">Пожалуйста, заполните поле названия</b>';
            }

            // Условие для выбора
            if(isset($_POST["contentsel"]) && !empty($_POST["contentsel"])){

                    $option = $_POST["contentsel"];
            } else {
                $option = $_POST['valueHide1'];
            }

            // Условие для содержимого
            if( isset($_POST['content']) && !empty($_POST['content']) ){
                
                if(preg_match('/^[A-Za-z.\s]+$/',$_POST['content'])){
                    $content = mysqli_real_escape_string($connection,$_POST['content']);
                }else{

                    $message_con = '<b class="text-danger text-center">Пожалуйста, введите корректное содержимое поста.</b>';
                }

            }else{
                $message_con = '<b class="text-danger text-center">Пожалуйста, заполните поле содержимого поста.</b>';
            } // конец описания

            if( (isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"])) || (isset($_POST["link"]) && !empty($_POST["link"]))){

                if(isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"])){

                    $target_dir = "images/blog/";
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
            
                    // Разрешенные форматы файлов
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif" ) {
                        $message_picture =  '<b class="text-danger">Извините, разрешены только файлы JPG, JPEG, PNG и GIF</b>';
                        $uploadOk = 0;
                    }

                    // Проверка на ошибки
                    if ($uploadOk != 0) {
                        $temp = explode(".", $_FILES["profilePic"]["name"]);
                        $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                        if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_dir . $newfilename)) {
                            
                        } else {
                            $message_picture =  '<b class="text-danger">Извините, произошла ошибка при загрузке файла';
                        }
                    }

                } else { // если изображение не загружено

                    $newfilename = $_POST["link"];
                }

            }else{ // основной IF если ничего не загружено
                $newfilename =  $_POST['valueHide'];
                $del = 'no';
            }

            $postDate = date("F d, Y");

            if( ( isset($title) && !empty($title) ) && ( isset($newfilename) && !empty($newfilename) ) ){

                $insert_query = "UPDATE `blog` SET 
                postContent = '$content', 
                postDate = '$postDate', 
                admin = '$adminPost', 
                title = '$title', 
                status = '$option', 
                post = '$newfilename'
                WHERE id ='$postId' " ;

                if(mysqli_query($connection, $insert_query)){
                   
                    if($option == 'image'){    
                       if($del == 'yes'){
                             $base_directory = "images/blog/";
                            if(unlink($base_directory.$_POST['valueHide']))
                            $delVar = " ";
                        }
                    }    
                    header('Location: blog.php?back=2');
                }else{
                    $submit_message = '<div class="alert alert-danger">
                        <strong>Внимание!</strong>
                        В настоящее время вы не можете отправить данные, попробуйте позже
                    </div>';
                }
            } // конец if 
    }//кнопка отправки

	   /* %%%%%%%%%%%%% КОНЕЦ КОДА ДЛЯ ОТПРАВКИ %%%%%%%%%%%% */

    if(isset($_GET['id'])){

        $postId = $_GET["id"];
        $postAdmin = $_GET["admin"];

        if( $power == 'yes' || $loginId == $postAdmin) {

           $query = "SELECT * FROM `blog` WHERE id='$postId' ";

            $result = mysqli_query($connection,$query);

                if(mysqli_num_rows($result) > 0){
                      while( $row = mysqli_fetch_assoc($result) ){

                    $adminPost = $row['admin']; 
                    $status =  $row["status"];
                    $post = $row["post"];
                    $title = $row["title"]; 
                    $content = $row["postContent"];                            
                 }
            }
        }else header('Location: blog.php?back=1');    

    } else header('Location: blog.php?back=1');

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
						<li class="current"><a href="blog.php"><i class="icon-blogger"></i>Блог</a></li>
						<li><a href="library.php"><i class="icon-line-align-center"></i>Библиотека</a></li>
						<li><a href="instructors.php"><i class="icon-guest"></i>Преподаватели</a></li>
                        <li><a href="team.php"><i class="icon-users"></i>Команда</a></li>
                        <li><a href="logout.php"><i class="icon-line-power"></i>Выход</a></li>    
					</ul>
				</nav>

			</div>
		</div>


				<!-- Заголовок страницы
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

		<!-- Подменю страницы
		============================================= -->

		<!-- Контент
		============================================= -->
		<section id="content">

			<div class="content-wrap">

				<div class="container clearfix">
				<!-- ========================================== -->

				<div class="postcontent nobottommargin">

                    

                <?php
   
                        if(isset($message_title) || isset($message_option) || isset($message_picture) || isset($submit_message) || isset($message_con) ){
                            echo "<div class='alert alert-danger'>";
                            

                            echo "Пожалуйста, заполните форму внимательно и корректно<br>";

                            echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
                            </div>";    

                        }

                 ?>
                 
						<h3>Обновить пост</h3>

                        <form action="" method="post" enctype="multipart/form-data">
                        
                    <div class="form-group">
                        <label for="titleId1">Заголовок</label>
                        <input type="text" id="titleId1" value="<?php echo $title; ?>" placeholder="Заголовок" name="title" class="form-control" >
                        <?php if(isset($message_title)){ echo $message_title; } ?>
                    </div>

                   
                <div class="form-group">                    
                        <label for="contentsel">Тип контента</label>
                        <select class="form-control"  name="contentsel" id="contentsel" onchange="showinput()">
                        <option value="">Выберите вариант</option>
                    <?php 
                             $select = ["video","image"];
                             foreach ($select as $value) {
                    ?>

                    <option value="<?php echo $value; ?>" > <?php echo $value; ?>  </option>

                    <?php       
                        }
                    ?>

                </select>
            </div>
            <?php if(isset($message_option)) echo $message_option; ?>

                    <div id="data">
    

                    </div>
                   
                   <?php

                   if($status=='image'){ ?> 


                    <img src="images/blog/<?php echo $post; ?>" width="80px" height="80px">
                 <?php  }else{ ?>

                   <table><tr><td width="100px" height="100"><iframe width="80px" height="80px" src="https://www.youtube.com/embed/<?php echo $row['post']; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></td></tr></table>

                  <?php }

                   ?> 
                   

                    <div class="form-group">
                        <label for="contentId1">Содержимое поста</label>
                        <textarea id="contentId1" class="form-control" 
                         name="content"><?php echo $content; ?></textarea>
                    </div>
                     <?php if(isset($message_con)) echo $message_con; ?>   

                    <input type="hidden" value="<?php if(isset($status)) echo $status; ?>" name="valueHide1" />                    
                    <input type="hidden" value="<?php if(isset($adminPost)) echo $adminPost; ?>" name="adminPost" />

                    <input type="hidden" value="<?php if(isset($post)) echo $post; ?>" name="valueHide" />
                     
                    <div class="form-group">
                        <button name="submit" class="btn btn-block btn-success" type="submit">Отправить</button>
                    </div>
                </form>
                        
				

					</div><!-- .postcontent end -->


				</div>

			</div>

		</section><!-- #content end -->


<script>
function showinput(){
    var select = document.getElementById('contentsel');
    select = select.value;
        if(select=='video')
        {
            document.getElementById('data').innerHTML =  
            `<div class="form-group">
                        <label for="link">Ссылка на видео</label>
                        <input type="text" placeholder="Ссылка" name="link" class="form-control">
                    </div>
                    <?php if(isset($message_picture)){ echo $message_picture; } ?>`;
        } else if(select=='image'){
            document.getElementById('data').innerHTML =  
            `<div class="form-group">
                        <label class="btn btn-success" for="my-file-selector">
                            <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                            Изображение
                        </label>
                        <span class='label label-success' id="upload-file-info"></span>
                        <?php if(isset($message_picture)){ echo $message_picture; } ?>
                    </div>`;
        } else {
            document.getElementById('data').innerHTML =  
            ``;
        }

    } 
</script>
<?php include('footer.php'); 

}
?>