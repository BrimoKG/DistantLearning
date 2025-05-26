<?php

    include("../include/config.php");

	if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {

        header('Location: index.php');
    } else{

        $courseId1 = $_GET["id"];
        $loginName = $_SESSION['userName'];
        $loginId = $_SESSION['userId'];
        $power = $_SESSION['adminType'];

        /* %%%%%%%%%%%%% НАЧАЛО ОБРАБОТКИ ФОРМЫ %%%%%%%%%%%% */

          if( isset($_POST['submit']) ){

            // Условие для описания
            if( isset($_POST['description']) && !empty($_POST['description']) ){
                
               
                    $description = mysqli_real_escape_string($connection,$_POST['description']);
               
            }else{
                $message_des = '<b class="text-danger text-center">Пожалуйста, заполните поле Описание.</b>';
            } 

            // Условие для категории
            if(isset($_POST["categorie_op"]) && !empty($_POST["categorie_op"])){

                $categorie_option = $_POST["categorie_op"];
            } else {
                $categorie_error = '<b class="text-danger text-center">Пожалуйста, выберите категорию или введите новую.</b>';
            }

            // Условие для книги
            if(isset($_POST["book_op"]) && !empty($_POST["book_op"])){

                    $book_option = $_POST["book_op"];
            } else {
                $book_error = '<b class="text-danger text-center">Пожалуйста, выберите книгу или введите новую.</b>';
            }

            //Условие для инструктора
            if(isset($_POST["ins_op"]) && !empty($_POST["ins_op"])){

                    $instructor_option = $_POST["ins_op"];
            } else {
                $instructor_error = '<b class="text-danger text-center">Пожалуйста, выберите инструктора или введите информацию о новом.</b>';
            }

            //Условие для имени
            if( isset($_POST['fullname']) && !empty($_POST['fullname'])){
                
                if(preg_match('/^[А-Яа-яЁё\s]+$/u',$_POST['fullname'])){
                  $name = mysqli_real_escape_string($connection,$_POST['fullname']);
                }else{
                  $message_name = '<b class="text-danger text-center">Пожалуйста, введите корректное имя</b>';
                }

              }else{
                  $message_name = '<b class="text-danger text-center">Пожалуйста, заполните поле Имя</b>';
            }

            // Условие для изображения
            if( isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"]) ){
                $target_dir = "images/courses/";
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
                    $message_picture =  '<b class="text-danger">Извините, разрешены только JPG, JPEG, PNG и GIF файлы</b>';
                    $uploadOk = 0;
                }
            
                // Проверка ошибок загрузки
                if ($uploadOk != 0) {
                    $temp = explode(".", $_FILES["profilePic"]["name"]);
                    $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                    if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_dir . $newfilename)) {
                        
                    } else {
                        $message_picture =  '<b class="text-danger">Извините, произошла ошибка при загрузке файла';
                    }
                }

            }else{
                $newfilename = $_POST["picValue"];
                $del = 'no';
            }
            if( ( isset($name) && !empty($name) ) && (isset($book_option) && !empty($book_option)) && (isset($instructor_option) && !empty($instructor_option)) && (isset($categorie_option) && !empty($categorie_option)) && (isset($description) && !empty($description)) && ( isset($newfilename) && !empty($newfilename) ) ){


                    $insert_query = "UPDATE `course` SET
                    name = '$name', 
                    cover = '$newfilename',
                    description = '$description',
                    categorieId = '$categorie_option', 
                    instructorId = '$instructor_option',
                    bookId = '$book_option'
                    WHERE id = '$courseId1' ";


                    if(mysqli_query($connection, $insert_query)){
                        
                        if($del == 'yes'){
                        $base_directory = "images/courses/";
                        if(unlink($base_directory.$_POST['picValue']))
                        $delVar = " ";
                    }

                       
                        header('Location: courses.php?back=2');
                    }else{
                        $submit_message = '<div class="alert alert-danger">
                            <strong>Ошибка!</strong>
                            Не удалось отправить данные, попробуйте позже
                        </div>';
                    }        
                }
            } // конец условия if 


	   /* %%%%%%%%%%%%% КОНЕЦ ОБРАБОТКИ ФОРМЫ %%%%%%%%%%% */
    if(isset($_GET['id'])){

        $courseId1 = $_GET["id"];

        if( $power == 'yes') {

           $query = "SELECT * FROM `course` WHERE id='$courseId1' ";

            $result = mysqli_query($connection,$query);

            if(mysqli_num_rows($result) > 0){
                  while( $row = mysqli_fetch_assoc($result) ){

                $coursePic = $row["cover"];
                $coursename = $row["name"];
                $courseDescription = $row["description"];
                $courseInstr = $row['instructorId'];
                $coueseCategorie = $row['categorieId'];
                $coursBook = $row['bookId'];
            
             }
            }
        }else header('Location: courses.php?back=1');    

    } else header('Location: courses.php?back=1');


    include('header.php');
    
?>

<div id="vertical-nav">
			<div class="container clearfix">
				<nav>
					<ul>
						<li><a href="home.php"><i class="icon-home2"></i>Главная</a></li>
                        <li><a href="categorie.php"><i class="icon-book2"></i>Категории</a></li>
						<li class="current"><a href="courses.php"><i class="icon-book3"></i>Курсы</a></li>
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

		<!-- Подменю страницы -->

		<!-- Контент -->
		<section id="content">

			<div class="content-wrap">

				<div class="container clearfix">
				<!-- ========================================== -->

				<div class="postcontent nobottommargin">

                    

                <?php

                       if(isset($message_name) || isset($message_picture) || isset($submit_message) || isset($message_des) || isset($categorie_error) || isset($instructor_error) || isset($book_error) ){
                            echo "<div class='alert alert-danger'>";
                            
                            echo "Пожалуйста, заполните форму внимательно и правильно<br>";

                            echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
                            </div>";    

                        }

                 ?>
                 
						<h3>Обновить курс</h3>

                        <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nameID">Название курса</label>
                        <input type="text" id="nameID" placeholder="Полное название" value="<?php if(isset($coursename)) echo $coursename; ?>" name="fullname" class="form-control" title="Только буквы и пробелы" pattern="[A-Za-z/\s]+">
                        <?php if(isset($message_name)){ echo $message_name; } ?>
                    </div>


                    <div class="form-group">                    
                        <label> Выбор книги</label>
                        <select class="form-control"  name="book_op">
                    <?php 
                             $query = "SELECT * FROM `library`";

                        $result = mysqli_query($connection, $query);

                        if(mysqli_num_rows($result) > 0){
        
                        //Получаем данные 
                        //Выводим данные
                        while( $row = mysqli_fetch_assoc($result) ){
                    ?>

                        <option <?php if($row['id'] == $coursBook) { ?> selected <?php } ?> value="<?php echo $row['id']; ?>" > <?php echo $row['name']; ?>  </option>

                        <?php       
                            } }
                        ?>

                        </select>
                    <?php if(isset($book_error)) echo $book_error; ?>
                </div>


                    <div class="form-group">                    
                        <label> Выбор категории</label>
                        <select class="form-control"  name="categorie_op">
                    <?php 
                             $query = "SELECT * FROM `categories`";

                        $result = mysqli_query($connection, $query);

                        if(mysqli_num_rows($result) > 0){
        
                        //Получаем данные 
                        //Выводим данные
                        while( $row = mysqli_fetch_assoc($result) ){
                    ?>

                        <option <?php if($row['id'] == $coueseCategorie) { ?> selected <?php } ?> value="<?php echo $row['id']; ?>" > <?php echo $row['categorie']; ?>  </option>

                        <?php       
                            } }
                        ?>

                        </select>
                    <?php if(isset($categorie_error)) echo $categorie_error; ?>
                </div>

                <div class="form-group">                    
                        <label>Выбор инструктора</label>
                        <select class="form-control"  name="ins_op">
                    <?php 
                             $query = "SELECT * FROM `teacher`";

                        $result = mysqli_query($connection, $query);

                        if(mysqli_num_rows($result) > 0){
        
                        //Получаем данные 
                        //Выводим данные
                        while( $row = mysqli_fetch_assoc($result) ){
                    ?>

                        <option <?php if($row['id'] == $courseInstr) { ?> selected <?php } ?> value="<?php echo $row['id']; ?>" > <?php echo $row['name']; ?>  </option>

                        <?php       
                            } }
                        ?>

                        </select>
                    <?php if(isset($instructor_error)) echo $instructor_error; ?>
                </div>

                    <div class="form-group">
                     <img src="images/courses/<?php if(isset($coursePic)) echo $coursePic; ?>" width="100px" height="100px">   
                    </div>
                    <div class="form-group">
                        <label class="btn btn-success" for="my-file-selector">
                            <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                           Изменить обложку
                        </label>
                        <span class='label label-success' id="upload-file-info"></span>
                        <?php if(isset($message_picture)){ echo $message_picture; } ?>
                    </div>
                    <input type="hidden" value="<?php if(isset($coursePic)) echo $coursePic; ?>" name="picValue" />


                    <div class="form-group">
                        <label for="descriptionId1">Описание</label>
                        <textarea id="descriptionId1" class="form-control" 
                         name="description"><?php if(isset($courseDescription)) echo $courseDescription; ?></textarea>
                    </div>
                    <?php if(isset($message_des)){ echo $message_des; } ?>
                    <div class="form-group">
                        <button name="submit" class="btn btn-block btn-success" type="submit">Отправить</button>
                    </div>
                </form>
                        
	
                	</div><!-- конец .postcontent -->

				</div>

			</div>

		</section><!-- конец #content -->

<?php include('footer.php'); 

}

?>