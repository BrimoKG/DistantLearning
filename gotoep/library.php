<?php
    //**************** Файл администрирования библиотеки
include("../include/config.php");

	if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {

        header('Location: index.php');
    } else {

        $loginName = $_SESSION['userName'];
        $loginId = $_SESSION['userId'];
        $power = $power = $_SESSION['adminType'];
        $alertMessage = " ";

        /* %%%%%%%%%%%%% НАЧАЛО ОБРАБОТКИ ФОРМЫ %%%%%%%%%%%% */

        if( isset($_POST['submit']) ){

            if($power == 'yes'){ //***********************
                
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
                	
                		$description = mysqli_real_escape_string($connection,$_POST['description']);
                	

                }else{
                	$message_des = '<b class="text-danger text-center">Пожалуйста, заполните поле описания.</b>';
                }   

                // Проверка PDF файла  

                if ( isset($_FILES["file1"]["name"]) && !empty($_FILES["file1"]["name"] ) )  {

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
                            $fileName = $_FILES["file1"]["name"]; // имя файла
                            $fileTmpLoc = $_FILES["file1"]["tmp_name"]; // временное имя файла
                            $fileType = $_FILES["file1"]["type"];
                            $fileSize =$_FILES["file1"]["size"]; // размер файла в байтах
                            // *******************Новый код начинается здесь

                            $temp = explode(".", $_FILES["file1"]["name"]);
                             $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                            if (move_uploaded_file($_FILES["file1"]["tmp_name"], $target_dir . $newfilename)) {
                            
                            } else {
                                $file_error =  '<b class="text-danger">Извините, произошла ошибка при загрузке файла';
                            }
                                //********************Конец кода
                        } 
                    }else{
                        $file_error = '<b class="text-danger">Файл не в формате PDF.</b>';   
                    }
                }
                else{
                    $file_error = '<b class="text-danger">Пожалуйста, выберите файл.</b>';
                } 


                // Обложка книги
                if( isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"]) ){
                    $target_dir = "images/library/";

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
                    if ($_FILES["profilePic"]["size"] > 50000000) {
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
                    $message_picture =  '<b class="text-danger">Пожалуйста, выберите обложку книги</b>';
                } 
                if( ( isset($name) && !empty($name) )  && ( isset($newfilename) && !empty($newfilename) ) && (isset($categorie_option) && !empty($categorie_option)) && ( isset($description) && !empty($description) ) && ( isset($newfilename1) && !empty($newfilename1) ) ){

                    $insert_query = "INSERT INTO `library` (name, categorieId, description, book, image) VALUES ('$name', $categorie_option, '$description','$newfilename','$newfilename1')";

                    if(mysqli_query($connection, $insert_query)){                        
                       
                        header('Location: library.php#end');
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
            }else{
    
                 $alertMessage = "<div class='alert alert-danger'> 
                    <p>Вы не являетесь администратором. У вас нет прав удалять записи. <strong>СПАСИБО.</strong> </p><br>       
                    <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
                    </div>";    
            } // *******************************

        } // конец обработки формы

	
       /* %%%%%%%%%%%%% КОНЕЦ ОБРАБОТКИ ФОРМЫ %%%%%%%%%%%% */
	

    if(isset($_GET['sucess'])){
        $alertMessage = "<div class='alert alert-success'> 
        <p>Запись успешно удалена.</p><br>       
        <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
        </div>";
    }

    if(isset($_GET['delid'])){ 

        $delBook = $_GET['delid'];

        if($power == 'yes'){
           
                           
            $alertMessage = "<div class='alert alert-danger'> 
                <p>Вы уверены, что хотите удалить эту запись? Это действие нельзя отменить!</p><br>
                    <form action='".htmlspecialchars($_SERVER['PHP_SELF'])."?id=$delBook' method='post'>
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


    // возврат из обновления
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

        $idBook = $_GET['id'];

        // Удаление файла из папки
        $query2 = "SELECT * FROM `library` WHERE id='$idBook' ";

        $result2 = mysqli_query($connection, $query2);

        if(mysqli_num_rows($result2) > 0){
        
                        //Есть данные 
                        //выводим данные
             while( $row2 = mysqli_fetch_assoc($result2) ){
                    
                    $base_directory = "images/library/";
                    if(unlink($base_directory.$row2['image']))
                        $delVar = " ";

                    $base_directory = "books/";
                    if(unlink($base_directory.$row2['book']))
                        $delVar = " ";  
             }
        }
 
        // новый запрос к базе данных 
        $query = "DELETE FROM `library` WHERE id='$idBook'";
        $result = mysqli_query($connection,$query);
        
        if($result){
            // перенаправление
            header("Location: library.php?sucess=1");
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

                    echo $alertMessage; 
                    if(isset($update_status)) echo $update_status;

                        if(isset($message_name) || isset($file_error) || isset($submit_message) || isset($message_des) || isset($categorie_error) || isset($message_picture) ){
                            echo "<div class='alert alert-danger'>";
                            
                            echo "Пожалуйста, заполните форму внимательно и корректно<br>";
                            
                            echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
                            </div>";    

                        }

                 ?>
                 
						<h3>Добавить книгу</h3>

                        <form method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="fullnameId">Название книги</label>
                        <input type="text" id="fullnameId" placeholder="Название книги" name="fullname" class="form-control" title="Только буквы и пробелы" pattern="[A-Za-z/\s]+">
                        <?php if(isset($message_name)){ echo $message_name; } ?>
                    </div>
                 
                    <div class="form-group">
                        <label class="btn btn-success" for="my-file-selector">
                            <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info1').html($(this).val());">
                            Обложка книги
                        </label>
                        <span class='label label-success' id="upload-file-info1"></span>
                        <?php if(isset($message_picture)){ echo $message_picture; } ?>
                    </div>

                <div class="form-group">                    
                        <label>Выбор категории</label>
                        <select class="form-control"  name="categorie_op">
                        <option value="">Выберите категорию</option>
                    <?php 
                             $query = "SELECT * FROM `categories`";

                        $result = mysqli_query($connection, $query);

                        if(mysqli_num_rows($result) > 0){
        
                        //Есть данные 
                        //выводим данные
                        while( $row = mysqli_fetch_assoc($result) ){
                    ?>

                        <option value="<?php echo $row['id']; ?>" > <?php echo $row['categorie']; ?>  </option>

                        <?php       
                            } }
                        ?>

                        </select>
                    <?php if(isset($categorie_error)) echo $categorie_error; ?>
                </div>

                    <div class="form-group">
                        <label class="btn btn-success" for="my-file-selector1">
                            <input id="my-file-selector1"  name="file1" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                            Загрузить книгу
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
                		 name="description"></textarea>
             		</div>
             		<?php if(isset($message_des)){ echo $message_des; } ?>
                    <div class="form-group">
                        <button name="submit" class="btn btn-block btn-success" onclick="uploadFile()" type="submit">Отправить</button>
                    </div>
                </form>
                        
							

							
						

<!--%%%%%%%%%%%%%%%% ТАБЛИЦА ДЛЯ ОТОБРАЖЕНИЯ %%%%%%%%%%%%%%%%% -->
    
    
    <table class="table table-striped table-bordered">
    <tr>
        <th>ID</th>
        <th>Обложка</th>
        <th>Просмотр</th>
        <th>Книга</th>
        <th>Редактировать</th>
        <th>Удалить</th>
    </tr>
    <?php

        $query = "SELECT * FROM `library`";

        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) > 0){
        
                        //Есть данные 
                        //выводим данные
         while( $row = mysqli_fetch_assoc($result) ){
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";

                echo "<td><img src=images/library/".$row["image"]." width='80px' height='80px'></td>";
                
                echo '<td><a href="view.php?libId='.$row['id']. '" type= "button" class="btn btn-primary btn-sm">
                <span class="icon-eye-open"></span></a></td>';             

                echo '<td><a href="books/book.php?name='.$row['book']. '" type= "button" class="btn btn-primary btn-sm">
                <span class="icon-eye-open"></span></a></td>';

                echo '<td><a href="updatelibrary.php?id='.$row['id']. '" type= "button" class="btn btn-primary btn-sm">
                <span class="icon-edit"></span></a></td>';
                
                echo '<td><a href="library.php?delid='.$row['id']. '" type= "button" class="btn btn-danger btn-sm">
                <span class="icon-trash2"></span></a></td>';

                echo "<tr>";  
            }
    } else {
        echo "<div class='alert alert-danger'>Нет записей<a class='close' data-dismiss='alert'>&times</a></div>";
    }
    
    // закрываем соединение с mysql 
        mysqli_close($connection);
    ?>

    <tr>
        <td colspan="6" id="end"><div class="text-center"><a href="library.php" type="button" class="btn btn-sm btn-success"><span class="icon-plus"></span></a></div></td>
    </tr>
</table>

<!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->
  
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