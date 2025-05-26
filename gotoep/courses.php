<?php
    include("../include/config.php");

    if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {
        header('Location: index.php');
    } else {
        $loginName = $_SESSION['userName'];
        $loginId = $_SESSION['userId'];
        $power = $_SESSION['adminType'];
        $alertMessage = " ";

        /* %%%%%%%%%%%%% НАЧАЛО КОДА ОТПРАВКИ %%%%%%%%%%%% */

        if(isset($_POST['submit'])) {
            if($power == 'yes') { //*************************
                // Условие для имени
                if(isset($_POST['fullname']) && !empty($_POST['fullname'])) {
                    if(preg_match('/^[А-Яа-яЁё\s]+$/u',$_POST['fullname'])) {
                        $name = mysqli_real_escape_string($connection,$_POST['fullname']);
                    } else {
                        $message_name = '<b class="text-danger text-center">Пожалуйста, введите корректное имя.</b>';
                    }
                } else {
                    $message_name = '<b class="text-danger text-center">Пожалуйста, заполните поле Имя</b>';
                }

                // Условие для категории
                if(isset($_POST["categorie_op"]) && !empty($_POST["categorie_op"])) {
                    $categorie_option = $_POST["categorie_op"];
                } else {
                    $categorie_error = '<b class="text-danger text-center">Пожалуйста, выберите категорию ИЛИ добавьте новую категорию курса.</b>';
                }

                // Условие для книги
                if(isset($_POST["book_op"]) && !empty($_POST["book_op"])) {
                    $book_option = $_POST["book_op"];
                } else {
                    $book_error = '<b class="text-danger text-center">Пожалуйста, выберите книгу ИЛИ добавьте новую книгу.</b>';
                }

                // Условие для инструктора
                if(isset($_POST["ins_op"]) && !empty($_POST["ins_op"])) {
                    $instructor_option = $_POST["ins_op"];
                } else {
                    $instructor_error = '<b class="text-danger text-center">Пожалуйста, выберите инструктора ИЛИ добавьте информацию об инструкторе.</b>';
                }

                // Условие для описания
                if(isset($_POST['description']) && !empty($_POST['description'])) {
                    $description = mysqli_real_escape_string($connection,$_POST['description']);
                } else {
                    $message_des = '<b class="text-danger text-center">Пожалуйста, заполните поле Описание.</b>';
                } 

                // Условие для изображения
                if(isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"])) {
                    $target_dir = "images/courses/";
                    $target_file = $target_dir . basename($_FILES["profilePic"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                    
                    // Проверка, является ли файл изображением
                    $check = getimagesize($_FILES["profilePic"]["tmp_name"]);
                    if($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $message_picture = '<b class="text-danger">Файл не является изображением</b>';
                        $uploadOk = 0;
                    }
                    
                    // Проверка размера файла
                    if($_FILES["profilePic"]["size"] > 5000000) {
                        $message_picture = '<b class="text-danger">Извините, ваш файл слишком большой.</b>';
                        $uploadOk = 0;
                    }
                    
                    // Разрешенные форматы файлов
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        $message_picture = '<b class="text-danger">Извините, разрешены только JPG, JPEG, PNG и GIF файлы</b>';
                        $uploadOk = 0;
                    }
                    
                    // Проверка на ошибки загрузки
                    if($uploadOk != 0) {
                        $temp = explode(".", $_FILES["profilePic"]["name"]);
                        $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                        if(move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_dir . $newfilename)) {
                            // Файл успешно загружен
                        } else {
                            $message_picture = '<b class="text-danger">Извините, произошла ошибка при загрузке файла';
                        }
                    }
                } else {
                    $message_picture = '<b class="text-danger">Пожалуйста, выберите изображение профиля</b>';
                }

                if((isset($name) && !empty($name)) && (isset($book_option) && !empty($book_option)) && (isset($instructor_option) && !empty($instructor_option)) && (isset($categorie_option) && !empty($categorie_option)) && (isset($description) && !empty($description)) && (isset($newfilename) && !empty($newfilename))) {
                    $insert_query = "INSERT INTO `course` (name, cover, description, categorieId, instructorId, bookId) VALUES ('$name','$newfilename','$description','$categorie_option','$instructor_option','$book_option')";

                    if(mysqli_query($connection, $insert_query)) {
                        header('Location: courses.php#end');
                    } else {
                        $submit_message = '<div class="alert alert-danger">
                            <strong>Предупреждение!</strong>
                            В настоящее время отправка невозможна, попробуйте позже
                        </div>';
                    }    
                }
            } else {
                $alertMessage = "<div class='alert alert-danger'> 
                    <p>Вы не являетесь администратором. У вас нет прав на удаление записей.<strong>СПАСИБО.</strong></p><br>       
                    <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
                </div>";    
            } // *******************************
        } // конец if 

        /* %%%%%%%%%%%%% КОНЕЦ КОДА ОТПРАВКИ %%%%%%%%%%%% */

        if(isset($_GET['success'])) {
            $alertMessage = "<div class='alert alert-success'> 
                <p>Запись успешно удалена.</p><br>       
                <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
            </div>";
        }

        if(isset($_GET['delid'])) { 
            $delcourse = $_GET['delid'];

            if($power == 'yes') {
                $alertMessage = "<div class='alert alert-danger'> 
                    <p>Вы уверены, что хотите удалить эту запись? Отменить действие будет невозможно!</p><br>
                    <form action='".htmlspecialchars($_SERVER['PHP_SELF'])."?id=$delcourse' method='post'>
                        <input type='submit' class='btn btn-danger btn-sm' name='confirm-delete' value='Да, удалить!'>
                        <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Ой, нет, спасибо!</a> 
                    </form>
                </div>";
            } else {
                $alertMessage = "<div class='alert alert-danger'> 
                    <p>Вы не являетесь администратором. У вас нет прав на удаление записей.<strong>СПАСИБО.</strong></p><br>       
                    <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
                </div>";
            }
        }

        // Возврат из обновления
        if(isset($_GET['back'])) {
            $back = $_GET['back'];

            if($back!=2) {
                $update_status = "<div class='alert alert-danger'> 
                    <p>Вы не являетесь администратором. Вы можете обновлять только свои записи.<strong>СПАСИБО.</strong></p><br>       
                    <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
                </div>";
            } else {
                $update_status = "<div class='alert alert-success'> 
                    <p>Запись успешно обновлена.</p><br>       
                    <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
                </div>";
            }
        } 

        // Подтверждение удаления
        if(isset($_POST['confirm-delete'])) {
            $id = $_GET['id'];

            // Удаление файла из папки
            $query2 = "SELECT * FROM `course` WHERE id='$id' ";
            $result2 = mysqli_query($connection, $query2);

            if(mysqli_num_rows($result2) > 0) {
                while($row2 = mysqli_fetch_assoc($result2)) {
                    $base_directory = "images/courses/";
                    if(unlink($base_directory.$row2['cover'])) {
                        $delVar = " ";  
                    }
                }
            }
 
            // Удаление из базы данных
            $query = "DELETE FROM `course` WHERE id='$id'";
            $result = mysqli_query($connection,$query);
            
            if($result) {
                // перенаправление
                header("Location: courses.php?success=1");
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
                <!-- Меню страницы -->
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

                        if(isset($message_name) || isset($message_picture) || isset($submit_message) || isset($message_des) || isset($categorie_error) || isset($instructor_error) || isset($book_error)) {
                            echo "<div class='alert alert-danger'>";
                            echo "Пожалуйста, заполните форму внимательно и корректно<br>";
                            echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
                            </div>";    
                        }
                    ?>
                 
                    <h3>Добавить курс</h3>

                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nameID">Название курса</label>
                            <input type="text" id="nameID" placeholder="Полное название" name="fullname" class="form-control" title="Только буквы и пробелы" pattern="[A-Za-z/\s]+">
                            <?php if(isset($message_name)) { echo $message_name; } ?>
                        </div>

                        <div class="form-group">                    
                            <label>Выбор книги</label>
                            <select class="form-control" name="book_op">
                                <option value="">Выберите вариант</option>
                                <?php 
                                    $query = "SELECT * FROM `library`";
                                    $result = mysqli_query($connection, $query);

                                    if(mysqli_num_rows($result) > 0) {
                                        while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                <?php       
                                        } 
                                    }
                                ?>
                            </select>
                            <?php if(isset($book_error)) echo $book_error; ?>
                        </div>

                        <div class="form-group">                    
                            <label>Выбор категории</label>
                            <select class="form-control" name="categorie_op">
                                <option value="">Выберите вариант</option>
                                <?php 
                                    $query = "SELECT * FROM `categories`";
                                    $result = mysqli_query($connection, $query);

                                    if(mysqli_num_rows($result) > 0) {
                                        while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['categorie']; ?></option>
                                <?php       
                                        } 
                                    }
                                ?>
                            </select>
                            <?php if(isset($categorie_error)) echo $categorie_error; ?>
                        </div>

                        <div class="form-group">                    
                            <label>Выбор инструктора</label>
                            <select class="form-control" name="ins_op">
                                <option value="">Выберите вариант</option>
                                <?php 
                                    $query = "SELECT * FROM `teacher`";
                                    $result = mysqli_query($connection, $query);

                                    if(mysqli_num_rows($result) > 0) {
                                        while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                <?php       
                                        } 
                                    }
                                ?>
                            </select>
                            <?php if(isset($instructor_error)) echo $instructor_error; ?>
                        </div>

                        <div class="form-group">
                            <label class="btn btn-success" for="my-file-selector">
                                <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                                Изображение профиля
                            </label>
                            <span class='label label-success' id="upload-file-info"></span>
                            <?php if(isset($message_picture)) { echo $message_picture; } ?>
                        </div>

                        <div class="form-group">
                            <label for="descriptionId1">Описание</label>
                            <textarea id="descriptionId1" class="form-control" name="description"></textarea>
                        </div>
                        <?php if(isset($message_des)) { echo $message_des; } ?>

                        <div class="form-group">
                            <button name="submit" class="btn btn-block btn-success" type="submit">Отправить</button>
                        </div>
                    </form>
                        
                    <!-- ТАБЛИЦА ДЛЯ ОТОБРАЖЕНИЯ ДАННЫХ -->
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>ID</th>
                            <th>Обложка</th>
                            <th>Название</th>
                            <th>Описание</th>
                            <th>Редактировать</th>
                            <th>Удалить</th>
                        </tr>
                        <?php
                            $query = "SELECT * FROM `course`";
                            $result = mysqli_query($connection, $query);

                            if(mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>".$row["id"]."</td> <td><img src=images/courses/".$row["cover"]." width='80px' height='80px'> </td> <td>".$row["name"]."</td>";
                                    
                                    echo '<td><a href="view.php?courseId='.$row['id']. '" type= "button" class="btn btn-primary btn-sm">
                                        <span class="icon-eye-open"></span></a></td>';

                                    echo '<td><a href="updatecourses.php?id='.$row['id']. '" type= "button" class="btn btn-primary btn-sm">
                                        <span class="icon-edit"></span></a></td>';
                                    
                                    echo '<td><a href="courses.php?delid='.$row['id']. '" type= "button" class="btn btn-danger btn-sm">
                                        <span class="icon-trash2"></span></a></td>';

                                    echo "<tr>";  
                                }
                            } else {
                                echo "<div class='alert alert-danger'>У вас нет курсов.<a class='close' data-dismiss='alert'>&times</a></div>";
                            }
                            
                            // закрытие соединения с MySQL
                            mysqli_close($connection);
                        ?>

                        <tr>
                            <td colspan="6" id="end"><div class="text-center"><a href="courses.php" type="button" class="btn btn-sm btn-success"><span class="icon-plus"></span></a></div></td>
                        </tr>
                    </table>
                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%-->

                </div><!-- .postcontent end -->
            </div>
        </div>
    </section><!-- конец #content -->

<?php include('footer.php'); 
}
?>