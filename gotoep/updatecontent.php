<?php

include("../include/config.php");

if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {
    header('Location: index.php');
} else {

    $loginName = $_SESSION['userName'];
    $loginId = $_SESSION['userId'];
    $updateId = $_GET['id'];
    $power = $_SESSION['adminType'];

    /* %%%%%%%%%%%%% START CODE SUBMIT %%%%%%%%%%%% */

    if( isset($_POST['submit']) ){

        if(isset($_POST["course_op"]) && !empty($_POST["course_op"])){
            $course_option = $_POST["course_op"];
        } else {
            $course_error = '<b class="text-danger text-center">Пожалуйста, выберите курс или введите новый.</b>';
        }

        // Description
        if( isset($_POST['editor']) && !empty($_POST['editor']) ){
            $lectureContent = $_POST['editor'];
        } else {
            $message_Content = '<b class="text-danger text-center">Пожалуйста, заполните поле с содержанием.</b>';
        }     

        // Name
        if( isset($_POST['name']) && !empty($_POST['name'])){
            if(preg_match('/^[А-Яа-яЁё\s]+$/u',$_POST['name'])){
                $name = mysqli_real_escape_string($connection,$_POST['name']);
            } else {
                $message_name = '<b class="text-danger text-center">Пожалуйста, введите корректное название лекции.</b>';
            }
        } else {
            $message_name = '<b class="text-danger text-center">Пожалуйста, заполните поле названия лекции.</b>';
        }

        if( ( isset($name) && !empty($name) ) && ( isset($course_option) && !empty($course_option) ) && ( isset($lectureContent) && !empty($lectureContent) ) ) {
            $insert_query = "UPDATE `content` SET
            content = '$lectureContent', 
            courseId = '$course_option', 
            lectureName = '$name'
            WHERE id= '$updateId' ";

            if(mysqli_query($connection, $insert_query)){
                header('Location: content.php?back=2');
            } else {
                $submit_message = '<div class="alert alert-danger">
                    <strong>Внимание!</strong>
                    Не удалось сохранить изменения, попробуйте позже.
                </div>';
            }
        }
    }

    /* %%%%%%%%%%%%% END CODE SUBMIT %%%%%%%%%%%% */

    $alertMessage = " ";

    // Get Data
    if(isset($_GET['id'])){
        $updateId = $_GET['id'];
        if( $power == 'yes' ) {
            $query = "SELECT * FROM `content` WHERE id=$updateId ";
            $result = mysqli_query($connection,$query);

            if(mysqli_num_rows($result) > 0){
                while( $row = mysqli_fetch_assoc($result) ){
                    $content_Name = $row["lectureName"];
                    $contentFull = $row["content"];
                    $course_Id = $row["courseId"];
                }
            }
        } else header('Location: content.php?back=1');
    } else header('Location: content.php?back=1');

    include('header.php');
    
?>

<div id="vertical-nav">
			<div class="container clearfix">
				<nav>
					<ul>
						<li><a href="home.php"><i class="icon-home2"></i>Главная</a></li>
                        <li><a href="categorie.php"><i class="icon-book2"></i>Категории</a></li>
						<li><a href="courses.php"><i class="icon-book3"></i>Курсы</a></li>
						<li class="current"><a href="content.php"><i class="icon-line-content-left"></i>Контент</a> </li>
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
            <h1>Добро пожаловать, <strong><?php if(isset($loginName)) echo $loginName; ?></strong></h1>
        </div>
        <div id="page-menu-wrap">
            <div class="container clearfix"></div>
        </div>
    </section>

    <!-- Контент -->
    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">
                <div class="postcontent nobottommargin">
                    <?php
                        echo $alertMessage; 
                        if(isset($update_status)) echo $update_status;

                        if(isset($message_name) || isset($submit_message) || isset($message_Content) || isset($course_error)){
                            echo "<div class='alert alert-danger'>";
                            echo "Пожалуйста, заполните форму внимательно и корректно<br>";
                            echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
                            </div>";    
                        }
                    ?>
                 
                    <h3>Редактировать содержание курса</h3>

                    <form action="" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="nameId1">Название лекции</label>
                            <input type="text" id="nameId1" value="<?php if(isset($content_Name)) echo $content_Name; ?>" placeholder="Название лекции" name="name" class="form-control" title="Только буквы и пробелы" pattern="[A-Za-z/\s]+">
                            <?php if(isset($message_name)){ echo $message_name; } ?>
                        </div>

                        <div class="form-group">                    
                            <label for="contentsel">Выбор курса</label>
                            <select class="form-control" name="course_op" id="contentsel">
                            <?php 
                                $query = "SELECT * FROM `course`";
                                $result = mysqli_query($connection, $query);
                                if(mysqli_num_rows($result) > 0){
                                    while( $row = mysqli_fetch_assoc($result) ){
                            ?>
                                <option <?php if($row['id'] == $course_Id) { ?> selected <?php } ?> value="<?php echo $row['id']; ?>"> <?php echo $row['name']; ?> </option>
                            <?php } } ?>
                            </select>
                            <?php if(isset($course_error)) echo $course_error; ?>
                        </div>

                        <textarea class="ckeditor" name="editor"><?= $contentFull ?></textarea>
                        <?php if(isset($message_Content)) echo $message_Content; ?>

                        <div class="form-group">
                            <button name="submit" class="btn btn-block btn-success" type="submit">Сохранить</button>
                        </div>
                    </form>

                </div><!-- .postcontent end -->
            </div>
        </div>
    </section>

    <script src="ckeditor/ckeditor.js" type="text/javascript"></script>

<?php include('footer.php'); } ?>
