<?php
    // *************Обновление книги
include("../include/config.php");

	if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {

        header('Location: index.php');
    } else {

        $loginName = $_SESSION['userName'];
        $loginId = $_SESSION['userId'];
        $bookId = $_GET["id"];
        $power = $_SESSION['adminType'];

        /* %%%%%%%%%%%%% НАЧАЛО ОБРАБОТКИ ФОРМЫ %%%%%%%%%%%% */

        if( isset($_POST['submit']) ){

            //Проверка названия
            if( isset($_POST['fullname']) && !empty($_POST['fullname'])){
        
                if(preg_match('/^[А-Яа-яЁё\s]+$/u',$_POST['fullname'])){
                  $name = mysqli_real_escape_string($connection,$_POST['fullname']);
                }else{
                  $message_name = '<b class="text-danger text-center">Пожалуйста, введите корректное название</b>';
                }

            }else{
                $message_name = '<b class="text-danger text-center">Пожалуйста, заполните поле названия</b>';
            }

            //Проверка категории
            if(isset($_POST["categorie_op"]) && !empty($_POST["categorie_op"])){

                    $categorie_option = $_POST["categorie_op"];
            } else {
                $categorie_error = '<b class="text-danger text-center">Пожалуйста, выберите категорию или добавьте новую.</b>';
            }

            // Проверка описания
            if( isset($_POST['description']) && !empty($_POST['description']) ){
            	
            	if(preg_match('/^[A-Za-z.\s]+$/',$_POST['description'])){
            		$description = mysqli_real_escape_string($connection,$_POST['description']);
            	}else{

            		$message_des = '<b class="text-danger text-center">Пожалуйста, введите корректное описание.</b>';
            	}

            }else{
            	$message_des = '<b class="text-danger text-center">Пожалуйста, заполните поле описания.</b>';
            }    

            if (isset($_FILES["file1"]["name"]) && !empty($_FILES["file1"]["name"] ) )  {

                $allowedExts = array("pdf");
                $temp = explode(".", $_FILES["file1"]["name"]);
                $extension = end($temp);
                
                if (($_FILES["file1"]["type"] == "application/pdf") && in_array($extension, $allowedExts))
                {
                    if ($_FILES["file1"]["error"] > 0)
                    {
                        $file_error = "Код ошибки: " . $_FILES["file1"]["error"];
                    }else{

                        $target_dir = "books/"; 
                        $delfile = 'yes';

                        $fileName = $_FILES["file1"]["name"]; // имя файла
                        $fileTmpLoc = $_FILES["file1"]["tmp_name"]; // временное имя файла
                        $fileType = $_FILES["file1"]["type"];
                        $fileSize =$_FILES["file1"]["size"]; // размер файла в байтах
                        // *******************Новый код начинается здесь

                        $temp = explode(".", $_FILES["file1"]["name"]);
                         $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                        if (move_uploaded_file($_FILES["file1"]["tmp_name"], $target_dir . $newfilename)) {
                        
                        } else {
                            $file_error =  '<b class="text-danger">Извините, произошла ошибка при загрузке файла.';
                        }

                        //********************Конец кода
                    } 
                }else{
                    $file_error = '<b class="text-danger">Файл не в формате PDF.</b>';   
                }   
            }else{
                $newfilename = $_POST['pdfValue'];
                $delfile = 'no';

            } // конец else 


            // Обложка книги

            if( isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"]) ){
                $target_dir = "images/library/";
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
                    $newfilename1 = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                    if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_dir . $newfilename1)) {
                        
                    } else {
                        $message_picture =  '<b class="text-danger">Извините, произошла ошибка при загрузке файла';
                    }
                }

            }else{
                $newfilename1 =  $_POST['picValue'];
                $del = 'no';
            } 


            if( ( isset($name) && !empty($name) )  && ( isset($newfilename) && !empty($newfilename) ) && (isset($categorie_option) && !empty($categorie_option)) && ( isset($description) && !empty($description) ) && ( isset($newfilename1) && !empty($newfilename1) ) ){

                    $insert_query = "UPDATE `library` SET
                    name = '$name', 
                    categorieId = '$categorie_option',  
                    description = '$description', 
                    book = '$newfilename',
                    image = '$newfilename1' 
                    WHERE id = '$bookId' ";

                    if(mysqli_query($connection, $insert_query)){
                        
                        if($del == 'yes'){
                        $base_directory = "images/library/";
                        if(unlink($base_directory.$_POST['picValue']))
                        $delVar = " ";
                    }

                    if($delfile == 'yes'){
                        $base_directory = "books/";
                        if(unlink($base_directory.$_POST['pdfValue']))
                        $delVar = " ";
                    }
                       
                        header('Location: library.php?back=2');
                    }else{
                        $submit_message = '<div class="alert alert-danger">
                            <strong>Предупреждение!</strong>
                            В настоящее время отправка невозможна, попробуйте позже
                        </div>';
                    }
        

        }else{
            $submit_message = '<div class="alert alert-danger">
                            <strong>Предупреждение!</strong>
                            В настоящее время отправка невозможна, попробуйте позже
                        </div>';
        }


} // конец обработки формы

	   /* %%%%%%%%%%%%% КОНЕЦ ОБРАБОТКИ ФОРМЫ %%%%%%%%%%%% */


if(isset($_GET['id'])){

        $bookId = $_GET["id"];

        if( $power == 'yes') {

           $query = "SELECT * FROM `library` WHERE id='$bookId' ";

            $result = mysqli_query($connection,$query);

            if(mysqli_num_rows($result) > 0){
                  while( $row = mysqli_fetch_assoc($result) ){

                $bookName = $row["name"];
                $bookDescription = $row["description"];
                $bookCategorie = $row["categorieId"];
                $bookPdf = $row["book"];
                $coverPic = $row["image"];
            
             }
            }
        }else header('Location: library.php?back=1');    

    } else header('Location: library.php?back=1');


    
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
						<li class="current"><a href="library.php"><i class="icon-line-align-center"></i>Библиотека</a></li>
						<li><a href="instructors.php"><i class="icon-guest"></i>Преподаватели</a></li>
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
				<!-- ========================================== -->

				<div class="postcontent nobottommargin">

                    

                <?php

                        if(isset($message_name) || isset($message_picture) || isset($submit_message) || isset($message_des) || isset($categorie_error) || isset($file_error) ){

                            echo "<div class='alert alert-danger'>";
                            
                            echo "Пожалуйста, заполните форму внимательно и корректно<br>";
                            
                            echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
                            </div>";    

                        }

                 ?>
                 
						<h3>Обновить книгу</h3>

                        <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fullnameId">Название книги</label>
                        <input type="text" id="fullnameId" value="<?php if(isset($bookName)) echo $bookName; ?>" placeholder="Название книги" name="fullname" class="form-control" title="Только буквы и пробелы" pattern="[A-Za-z/\s]+">
                        <?php if(isset($message_name)){ echo $message_name; } ?>
                    </div>

                    <div class="form-group">
                    <img src="images/library/<?php if(isset($coverPic)) echo $coverPic; ?>" width="100px" height="100px">
                    </div>

                    <div class="form-group">
                        <label class="btn btn-success" for="my-file-selector">
                            <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info1').html($(this).val());">
                            Обновить обложку
                        </label>
                        <span class='label label-success' id="upload-file-info1"></span>
                        <?php if(isset($message_picture)){ echo $message_picture; } ?>
                    </div>
                 
                <div class="form-group">                    
                        <label>Выбор категории</label>
                        <select class="form-control"  name="categorie_op">
                    <?php 
                             $query = "SELECT * FROM `categories`";

                        $result = mysqli_query($connection, $query);

                        if(mysqli_num_rows($result) > 0){
        
                        //Есть данные 
                        //выводим данные
                        while( $row = mysqli_fetch_assoc($result) ){
                    ?>

                        <option <?php if($row['id'] == $bookCategorie) { ?> selected <?php } ?> value="<?php echo $row['id']; ?>" > <?php echo $row['categorie']; ?>  </option>

                        <?php       
                            } }
                        ?>

                        </select>
                    <?php if(isset($categorie_error)) echo $categorie_error; ?>
                </div>
                <h3>Книга</h3>
                <?php echo '<a href="books/book.php?name='.$bookPdf. '" type= "button" class="btn btn-success btn-sm">
                <span class="icon-eye-open"></span></a>'; ?>
                <br>
                <br>
                    <div class="form-group">
                        <label class="btn btn-success" for="my-file-selector1">
                            <input id="my-file-selector1"  name="file1" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                            Обновить книгу
                        </label>
                        <span class='label label-success' id="upload-file-info"></span>
                        <?php if(isset($file_error)){ echo $file_error; } ?>

                        <progress id="progressBar" value="0" max="100" style="width: 300px;"></progress>
                        <h3 id="status"></h3>
                        <p id="loaded_n_total"></p>
                    
                    </div>
                    <div class="form-group">
                		<label for="descriptionId">Описание</label>
                		<textarea id="descriptionId" class="form-control" 
                		 name="description"><?php if(isset($bookDescription)) echo $bookDescription;?></textarea>
             		</div>
             		<?php if(isset($message_des)){ echo $message_des; } ?>

                    <input type="hidden" name="pdfValue" value="<?php if(isset($bookPdf))  echo $bookPdf; ?>">

                    <input type="hidden" value="<?php if(isset($coverPic)) echo $coverPic; ?>" name="picValue"/>

                    <div class="form-group">
                        <button name="submit" class="btn btn-block btn-success" onclick="uploadFile()" type="submit">Обновить</button>
                    </div>
                </form>

					</div><!-- .postcontent end -->


				</div>

			</div>

		</section><!-- #content end -->

<?php include('footer.php'); 
}

?>

<script>
        
    function _(el){
        return document.getElementById(el);
    }

    function uploadFile(){
        var file = _("my-file-selector").files[0];
    //  alter(file.name+" | "+file.size+" | "+file.type);

        var formdata = new FormData();
        formdata.append("my-file-selector", file);
        var ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", progressHandler, false);
        ajax.addEventListener("load", completeHandler, false);
        ajax.addEventListener("error", errorHandler, false);
        ajax.addEventListener("abort", abortHandler, false);

        ajax.open("POST", "library.php");

        ajax.send(formdata);

    }

    function progressHandler(event){
        _("loaded_n_total").innerHTML= "Загружено "+event.loaded+" байт из "+event.total;

        var percent = (event.loaded / event.total) * 100;
        _("progressBar").value= Math.round(percent);
        _("status").innerHTML= Math.round(percent)+"% загружено... Пожалуйста, подождите";
    }

    function completeHandler(event){
        
        _("status").innerHTML= event.target.responseText;
        _("progressBar").value= 0;
        
    }

    function errorHandler(event){
        
        _("status").innerHTML= "Ошибка загрузки";
        
    }

    function abortHandler(event){
        
        _("status").innerHTML= "Загрузка отменена";
        
    }
    </script>