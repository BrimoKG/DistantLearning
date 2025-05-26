<?php
    include("../include/config.php");

    if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {
        header('Location: index.php');
    } else {

        $loginName = $_SESSION['userName'];
        $loginId = $_SESSION['userId'];
        $power = $_SESSION['adminType'];

        /* %%%%%%%%%%%%% НАЧАЛО ОБРАБОТКИ ФОРМЫ %%%%%%%%%%%% */

        if( isset($_POST['submit']) ){

            //Проверка заголовка
            if( isset($_POST['title']) && !empty($_POST['title'])){
                $title = mysqli_real_escape_string($connection,$_POST['title']);
            }else{
                $message_title = '<b class="text-danger text-center">Пожалуйста, заполните поле заголовка</b>';
            }

            //Проверка выбора типа
            if(isset($_POST["contentsel"]) && !empty($_POST["contentsel"])){
                $option = $_POST["contentsel"];
            } else {
                $message_option = '<b class="text-danger text-center">Пожалуйста, выберите тип контента.</b>';
            }

            // Проверка содержания
            if( isset($_POST['content']) && !empty($_POST['content']) ){
                if(preg_match('/^[A-Za-z.\s]+$/',$_POST['content'])){
                    $content = mysqli_real_escape_string($connection,$_POST['content']);
                }else{
                    $message_con = '<b class="text-danger text-center">Пожалуйста, введите корректное содержание поста.</b>';
                }
            }else{
                $message_con = '<b class="text-danger text-center">Пожалуйста, заполните поле содержания.</b>';
            } 

            // Проверка изображения/ссылки
            if( (isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"])) || (isset($_POST["link"]) && !empty($_POST["link"]))){

                if(isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"])){
                    $target_dir = "images/blog/";
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
                } else { // если изображение не загружено
                    $newfilename = $_POST["link"];
                }
            }else{ // если ни изображение, ни ссылка не указаны
                $message_picture =  '<b class="text-danger">Пожалуйста, загрузите изображение ИЛИ укажите ссылку на видео</b>';
            }

            // Сегодняшняя дата
            $postDate = date("F d, Y");

            // Проверка всех условий
            if( ( isset($title) && !empty($title) ) && ( isset($newfilename) && !empty($newfilename) ) ){
                $insert_query = "INSERT INTO `blog` (postContent, postDate, admin, title, status, post) VALUES ('$content','$postDate','$loginId','$title','$option','$newfilename')";

                if(mysqli_query($connection, $insert_query)){
                    header('Location: blog.php#end');
                }else{
                    $submit_message = '<div class="alert alert-danger">
                        <strong>Предупреждение!</strong>
                        В настоящее время отправка невозможна, попробуйте позже
                    </div>';
                }
            }
        }//Кнопка отправки

        /* %%%%%%%%%%%%% КОНЕЦ ОБРАБОТКИ ФОРМЫ %%%%%%%%%%%% */

        $alertMessage = " ";

        if(isset($_GET['sucess'])){
            $alertMessage = "<div class='alert alert-success'> 
            <p>Запись успешно удалена.</p><br>       
            <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a> 
            </div>";
        }    

        if(isset($_GET['delid'])){ 
            $delpost = $_GET['delid'];
            $deladmin = $_GET['admin'];

            if( $power == 'yes' || $deladmin == $loginId){
                $alertMessage = "<div class='alert alert-danger'> 
                            <p>Вы уверены, что хотите удалить эту запись? Это действие нельзя отменить!</p><br>
                            <form action='".htmlspecialchars($_SERVER['PHP_SELF'])."?id=$delpost' method='post'>
                            <input type='submit' class='btn btn-danger btn-sm'
                           name='confirm-delete' value='Да, удалить!'>
                            <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Нет, отменить!</a>
                        </form>
                </div>";
            }else{
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

        // Подтверждение удаления
        if(isset($_POST['confirm-delete'])){
            $id = $_GET['id'];

            // Удаление файла из папки
            $query2 = "SELECT * FROM `blog` WHERE id='$id' ";
            $result2 = mysqli_query($connection, $query2);

            if(mysqli_num_rows($result2) > 0){
                while( $row2 = mysqli_fetch_assoc($result2) ){
                    if($row2['status'] == 'image'){
                        $base_directory = "images/blog/";
                        if(unlink($base_directory.$row2['post']))
                            $delVar = " ";  
                    }
                }
            }

            // Удаление из базы данных
            $query = "DELETE FROM `blog` WHERE id='$id'";
            $result = mysqli_query($connection,$query);
            
            if($result){
                header("Location: blog.php?sucess=1");
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
						<li class="current"><a href="blog.php"><i class="icon-blogger"></i>Блог</a></li>
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
    </section><!-- конец #page-title -->

    <!-- Контент -->
    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">
                <div class="postcontent nobottommargin">

                <?php
                    echo $alertMessage; 
                    if(isset($update_status)) echo $update_status;

                    if(isset($message_title) || isset($message_option) || isset($message_picture) || isset($submit_message) || isset($message_con) ){
                        echo "<div class='alert alert-danger'>";
                        echo "Пожалуйста, заполните форму внимательно и корректно<br>";
                        echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
                        </div>";    
                    }
                ?>
                 
                <h3>Добавить запись</h3>

                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="titleID1">Заголовок</label>
                        <input type="text" id="titleID1" placeholder="Заголовок" name="title" class="form-control">
                        <?php if(isset($message_title)){ echo $message_title; } ?>
                    </div>

                    <div class="form-group">                    
                        <label>Тип контента</label>
                        <select class="form-control" name="contentsel" id="contentsel" onchange="showinput()">
                            <option value="">Выберите тип</option>
                            <?php 
                                $select = ["video","image"];
                                foreach ($select as $value) {
                            ?>
                            <option value="<?php echo $value; ?>"><?php echo $value == 'video' ? 'Видео' : 'Изображение'; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if(isset($message_option)) echo $message_option; ?>

                    <div id="data"></div>
                    
                    <div class="form-group">
                        <label for="contentID1">Содержание</label>
                        <textarea id="contentID1" class="form-control" name="content"></textarea>
                    </div>
                    <?php if(isset($message_con)) echo $message_con; ?>   
                    <div class="form-group">
                        <button name="submit" class="btn btn-block btn-success" type="submit">Отправить</button>
                    </div>
                </form>

                <!-- Таблица для отображения записей -->
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>ID</th>
                        <th>Контент</th>
                        <th>Заголовок</th>
                        <th>Содержание</th>
                        <th>Дата публикации</th>
                        <th>Редактировать</th>
                        <th>Удалить</th>
                    </tr>
                    <?php
                    $query = "SELECT * FROM `blog`";
                    $result = mysqli_query($connection, $query);

                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<tr>";
                            echo "<td>".$row["id"]."</td>"; 
                            if($row["status"]=='image'){
                                echo "<td><img src=images/blog/".$row["post"]." width='80px' height='80px'> </td>"; 
                            }else{ ?>
                                <td width="80" height="80"> 
                                    <iframe width="80px" height="80px" src="https://www.youtube.com/embed/<?php echo $row['post']; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </td>
                            <?php }   

                            echo "<td>".$row["title"]."</td> <td> ".$row["postContent"]."</td><td> ".$row["postDate"]."</td>";

                            echo '<td><a href="updateblog.php?id='.$row['id'].'&admin='.$row['admin']. '" type= "button" class="btn btn-primary btn-sm">
                            <span class="icon-edit"></span></a></td>';
                            
                            echo '<td><a href="blog.php?delid='.$row['id'].'&admin='.$row['admin']. '" type= "button" class="btn btn-danger btn-sm">
                            <span class="icon-trash2"></span></a></td>';

                            echo "</tr>";  
                        }
                    } else {
                        echo "<tr><td colspan='7'><div class='alert alert-danger'>У вас нет записей.<a class='close' data-dismiss='alert'>&times</a></div></td></tr>";
                    }
                    
                    mysqli_close($connection);
                    ?>
                    <tr>
                        <td colspan="7" id="end"><div class="text-center"><a href="blog.php" type="button" class="btn btn-sm btn-success"><span class="icon-plus"></span></a></div></td>
                    </tr>
                </table>

                </div><!-- .postcontent end -->
            </div>
        </div>
    </section><!-- #content end -->

<script>
function showinput(){
    var select = document.getElementById('contentsel');
    select = select.value;
    if(select=='video') {
        document.getElementById('data').innerHTML =  
        `<div class="form-group">
            <label for="linkID1">Ссылка на видео</label>
            <input type="url" id="linkID1" placeholder="Ссылка" name="link" class="form-control">
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
        document.getElementById('data').innerHTML = ``;
    }
} 
</script>

<?php include('footer.php'); 
}
?>