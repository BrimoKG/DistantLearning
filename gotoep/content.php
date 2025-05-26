<?php
include("../include/config.php");

if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {
    header('Location: index.php');
} else {
    $loginName = $_SESSION['userName'];
    $loginId = $_SESSION['userId'];
    $power = $_SESSION['adminType'];
    $alertMessage = " ";

    /* %%%%%%%%%%%%% НАЧАЛО ОБРАБОТКИ ФОРМЫ %%%%%%%%%%%% */
    if(isset($_POST['submit'])) {
        if($power == 'yes') { //*********************
            if(isset($_POST["course_op"]) && !empty($_POST["course_op"])) {
                $course_option = $_POST["course_op"];
            } else {
                $course_error = '<b class="text-danger text-center">Пожалуйста, выберите курс или добавьте новый.</b>';
            }
            
            if(isset($_POST['editor']) && !empty($_POST['editor'])) {
                $lectureContent = $_POST['editor'];
            } else {
                $message_Content = '<b class="text-danger text-center">Пожалуйста, заполните содержимое.</b>';
            }

            if(isset($_POST['name']) && !empty($_POST['name'])) {
                if(preg_match('/^[А-Яа-яЁё\s]+$/u',$_POST['name'])) {
                    $name = mysqli_real_escape_string($connection,$_POST['name']);
                } else {
                    $message_name = '<b class="text-danger text-center">Пожалуйста, введите корректное имя.</b>';
                }
            } else {
                $message_name = '<b class="text-danger text-center">Пожалуйста, заполните поле Название.</b>';
            }

            if((isset($name) && !empty($name)) && (isset($course_option) && !empty($course_option)) && (isset($lectureContent) && !empty($lectureContent))) {
                $insert_query = "INSERT INTO `content` (content, courseId, lectureName) VALUES ('$lectureContent','$course_option','$name')";

                if(mysqli_query($connection, $insert_query)) {
                    header('Location: content.php#end');
                } else {
                    $submit_message = '<div class="alert alert-danger">
                        <strong>Ошибка!</strong>
                        Не удалось отправить данные, попробуйте позже
                    </div>';
                }
            }
        } else {
            $alertMessage = "<div class='alert alert-danger'> 
                <p>Вы не являетесь администратором. У вас нет прав на добавление контента. <strong>СПАСИБО.</strong></p><br>       
                <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
                </div>";    
        }
    }
    /* %%%%%%%%%%%%% КОНЕЦ ОБРАБОТКИ ФОРМЫ %%%%%%%%%%%% */

    if(isset($_GET['sucess'])) {
        $alertMessage = "<div class='alert alert-success'> 
            <p>Запись успешно удалена.</p><br>       
            <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
            </div>";
    }  

    if(isset($_GET['delid'])) { 
        $deluser = $_GET['delid'];

        if($power == 'yes') {
            $alertMessage = "<div class='alert alert-danger'> 
                <p>Вы уверены, что хотите удалить эту запись? Отменить действие будет невозможно!</p><br>
                <form action='".htmlspecialchars($_SERVER['PHP_SELF'])."?id=$deluser' method='post'>
                    <input type='submit' class='btn btn-danger btn-sm'
                    name='confirm-delete' value='Да, удалить!'>
                    <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Нет, отмена!</a>                    
                </form>
            </div>";
        } else {
            $alertMessage = "<div class='alert alert-danger'> 
                <p>Вы не являетесь администратором. У вас нет прав на удаление записей. <strong>СПАСИБО.</strong></p><br>       
                <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
                </div>";
        }
    }

    // Возврат после обновления
    if(isset($_GET['back'])) {
        $back = $_GET['back'];

        if($back!=2) {
            $update_status = "<div class='alert alert-danger'> 
                <p>Вы не являетесь администратором. Вы можете обновлять только свои записи. <strong>СПАСИБО.</strong></p><br>       
                <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
                </div>";
        } else {
            $update_status = "<div class='alert alert-success'> 
                <p>Обновление прошло успешно.</p><br>       
                <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
                </div>";
        }
    }

    // Подтверждение удаления
    if(isset($_POST['confirm-delete'])) {
        $id = $_GET['id'];
 
        $query = "DELETE FROM `content` WHERE id='$id'";
        $result = mysqli_query($connection,$query);
        
        if($result) {
            header("Location: content.php?sucess=1");
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
            <h1>Добро пожаловать <strong><?php if(isset($loginName)) echo $loginName; ?></strong></h1>
        </div>
        <div id="page-menu-wrap">
            <div class="container clearfix">
            </div>
        </div>
    </section><!-- #page-title end -->

    <!-- Контент -->
    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">
                <div class="postcontent nobottommargin">
                    <?php
                        echo $alertMessage; 
                        if(isset($update_status)) echo $update_status;

                        if(isset($message_name) || isset($submit_message) || isset($message_Content) || isset($course_error)) {
                            echo "<div class='alert alert-danger'>";
                            echo "Пожалуйста, заполните форму внимательно и правильно<br>";
                            echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
                            </div>";    
                        }
                    ?>
                     
                    <h3>Добавить контент курса</h3>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nameId1">Название лекции</label>
                            <input type="text" id="nameId1" placeholder="Название лекции" name="name" class="form-control" title="Только буквы и пробелы" pattern="[A-Za-z/\s]+">
                            <?php if(isset($message_name)){ echo $message_name; } ?>
                        </div>

                        <div class="form-group">                    
                            <label>Выбор курса</label>
                            <select class="form-control" name="course_op">
                                <?php 
                                $query = "SELECT * FROM `course`";
                                $result = mysqli_query($connection, $query);

                                if(mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <option value="">Выберите курс</option>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                <?php       
                                    }
                                }
                                ?>
                            </select>
                            <?php if(isset($course_error)) echo $course_error; ?>
                        </div>
                        
                        <textarea class="ckeditor" name="editor"></textarea>
                        <?php if(isset($message_Content)) echo $message_Content; ?>
                        
                        <div class="form-group">
                            <button name="submit" class="btn btn-block btn-success" type="submit">Отправить</button>
                        </div>
                    </form>
                 		
                    <!-- ТАБЛИЦА С КОНТЕНТОМ -->
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>ID</th>
                            <th>Контент</th>
                            <th>Название лекции</th>
                            <th>Название курса</th>
                            <th>Просмотр</th>
                            <th>Редактировать</th>
                            <th>Удалить</th>
                        </tr>
                        <?php
                        $query = "SELECT * FROM `content`";
                        $result = mysqli_query($connection, $query);

                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                $temp = $row['courseId'];
                                $query2 = "SELECT * FROM `course` WHERE id ='$temp' ";
                                $result2 = mysqli_query($connection, $query2);

                                if(mysqli_num_rows($result2) > 0) {
                                    while($row2 = mysqli_fetch_assoc($result2)) {
                                        $courseName = $row2['name']; 
                                    }
                                } else {
                                    $courseName='Нет названия курса';
                                }

                                echo "<tr>";
                                echo "<td>".$row["id"]."</td>";
                                echo "<td>".$row["lectureName"]."</td>"; 
                                echo "<td>".$courseName."</td>";
                                echo '<td><a href="view.php?id='.$row['id'].'" type="button" class="btn btn-primary btn-sm">
                                    <span class="icon-eye-open"></span></a></td>';
                                echo '<td><a href="updatecontent.php?id='.$row['id'].'" type="button" class="btn btn-primary btn-sm">
                                    <span class="icon-edit"></span></a></td>';
                                echo '<td><a href="content.php?delid='.$row['id'].'" type="button" class="btn btn-danger btn-sm">
                                    <span class="icon-trash2"></span></a></td>';
                                echo "</tr>";  
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Нет доступного контента.<a class='close' data-dismiss='alert'>&times</a></div>";
                        }
                        
                        mysqli_close($connection);
                        ?>
                        <tr>
                            <td colspan="7" id="end"><div class="text-center"><a href="content.php" type="button" class="btn btn-sm btn-success"><span class="icon-plus"></span></a></div></td>
                        </tr>
                    </table>
                </div><!-- .postcontent end -->
            </div>
        </div>
    </section><!-- #content end -->
    <script src="ckeditor/ckeditor.js" type="text/javascript"></script>

<?php 
include('footer.php'); 
}
?>