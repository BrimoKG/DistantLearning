<?php
include("../include/config.php");

if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {
    header('Location: index.php');
} else {

    $loginName = $_SESSION['userName'];
    $loginId = $_SESSION['userId'];
    $power = $_SESSION['adminType'];
    $alertMessage = " ";

    if (isset($_POST['submit'])) {
        if ($power == 'yes') {
            if (isset($_POST['fullname']) && !empty($_POST['fullname'])) {
                if (preg_match('/^[A-Za-z\s]+$/', $_POST['fullname'])) {
                    $name = mysqli_real_escape_string($connection, $_POST['fullname']);
                } else {
                    $message_name = '<b class="text-danger text-center">Пожалуйста, введите корректное имя</b>';
                }
            } else {
                $message_name = '<b class="text-danger text-center">Пожалуйста, заполните поле имени</b>';
            }

            if (isset($name) && !empty($name)) {
                $insert_query = "INSERT INTO `categories` (categorie) VALUES ('$name')";
                if (mysqli_query($connection, $insert_query)) {
                    header('Location: categorie.php#end');
                } else {
                    $submit_message = '<div class="alert alert-danger">
                        <strong>Внимание!</strong> Не удалось отправить данные. Попробуйте позже.
                    </div>';
                }
            }
        } else {
            $alertMessage = "<div class='alert alert-danger'> 
                <p>Вы не обладаете достаточными правами администратора для добавления категории. <strong>СПАСИБО.</strong></p><br>       
                <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
                </div>";    
        }
    }

    if (isset($_GET['sucess'])) {
        $alertMessage = "<div class='alert alert-success'> 
        <p>Запись успешно удалена.</p><br>       
        <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
        </div>";
    }

    if (isset($_GET['delid'])) {
        $delCatecorie = $_GET['delid'];
        if ($power == 'yes') {
            $alertMessage = "<div class='alert alert-danger'> 
                <p>Вы уверены, что хотите удалить эту запись? Это действие необратимо!</p><br>
                <form action='".htmlspecialchars($_SERVER['PHP_SELF'])."?id=$delCatecorie' method='post'>
                    <input type='submit' class='btn btn-danger btn-sm' name='confirm-delete' value='Да, удалить!'>
                    <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Нет, спасибо!</a> 
                </form>
            </div>";
        } else {
            $alertMessage = "<div class='alert alert-danger'> 
            <p>Вы не обладаете достаточными правами администратора для удаления записей. <strong>СПАСИБО.</strong></p><br>       
            <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
            </div>";
        }
    }

    if (isset($_GET['back'])) {
        $back = $_GET['back'];
        if ($back != 2) {
            $update_status = "<div class='alert alert-danger'> 
            <p>Вы не обладаете достаточными правами администратора. Вы можете обновлять только свои записи. <strong>СПАСИБО.</strong></p><br>       
            <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
            </div>";
        } else {
            $update_status = "<div class='alert alert-success'> 
            <p>Запись успешно обновлена.</p><br>       
            <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
            </div>";
        }
    }

    if (isset($_POST['confirm-delete'])) {
        $id = $_GET['id'];
        $query = "DELETE FROM `categories` WHERE id='$id'";
        $result = mysqli_query($connection, $query);
        if ($result) {
            header("Location: categorie.php?sucess");
        } else {
            echo "Ошибка: ".$query."<br>".mysqli_error($conn);
        }
    }

    include('header.php');
    ?>

<div id="vertical-nav">
			<div class="container clearfix">
				<nav>
					<ul>
						<li><a href="home.php"><i class="icon-home2"></i>Главная</a></li>
                        <li class="current"><a href="categorie.php"><i class="icon-book2"></i>Категории</a></li>
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

    <!-- Заголовок страницы -->
    <section id="page-title">
        <div class="container clearfix">
            <h1>Добро пожаловать, <strong><?php if(isset($loginName)) echo $loginName; ?></strong></h1>
        </div>
        <div id="page-menu-wrap"><div class="container clearfix"></div></div>
    </section>

    <!-- Контент -->
    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">
                <div class="postcontent nobottommargin">
                    
                    <?php
                        echo $alertMessage; 
                        if (isset($update_status)) echo $update_status;

                        if (isset($message_name) || isset($submit_message)) {
                            echo "<div class='alert alert-danger'>";
                            echo "Пожалуйста, внимательно и правильно заполните форму<br>";
                            echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a></div>";    
                        }
                    ?>

                    <h3>Добавить категории курса</h3>

                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="CourseId1">Категория курса</label>
                            <input type="text" id="CourseId1" placeholder="Полное имя" name="fullname" class="form-control" title="Только буквы и пробел" pattern="[A-Za-z/\s]+">
                            <?php if(isset($message_name)){ echo $message_name; } ?>
                        </div>
                        
                        <div class="form-group">
                            <button name="submit" class="btn btn-block btn-success" type="submit">Отправить</button>
                        </div>
                    </form>

                    <!-- Таблица -->
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>ID</th>
                            <th>Категории курсов</th>
                            <th>Редактировать</th>
                            <th>Удалить</th>
                        </tr>
                        <?php
                            $query = "SELECT * FROM `categories`";
                            $result = mysqli_query($connection, $query);

                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_assoc($result)){
                                    echo "<tr>";
                                    echo "<td>".$row["id"]."</td> <td>".$row["categorie"]."</td>";
                                    echo '<td><a href="updatecategorie.php?id='.$row['id'].'" type="button" class="btn btn-primary btn-sm"><span class="icon-edit"></span></a></td>';
                                    echo '<td><a href="categorie.php?delid='.$row['id'].'" type="button" class="btn btn-danger btn-sm"><span class="icon-trash2"></span></a></td>';
                                    echo "</tr>";  
                                }
                            } else {
                                echo "<div class='alert alert-danger'>У вас нет курсов.<a class='close' data-dismiss='alert'>&times</a></div>";
                            }

                            mysqli_close($connection);
                        ?>
                        <tr>
                            <td colspan="4" id="end"><div class="text-center"><a href="categorie.php" type="button" class="btn btn-sm btn-success"><span class="icon-plus"></span></a></div></td>
                        </tr>
                    </table>

                </div><!-- .postcontent end -->
            </div>
        </div>
    </section><!-- #content end -->

<?php include('footer.php'); 
}
?>
