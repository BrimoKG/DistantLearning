<?php

include("../include/config.php");

    if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {

        header('Location: index.php');
    } else {
        $loginName = $_SESSION['userName'];

include('header.php');

?>

        <!-- Обертка документа
    ============================================= -->
    <div id="wrapper" class="clearfix">

        <div id="vertical-nav">
            <div class="container clearfix">

                <nav>
                    <ul>
                        <li><a href="home.php"><i class="icon-home2"></i>Главная</a></li>

                        <li><a href="categorie.php"><i class="icon-book2"></i>Категории</a></li>

                        <li <?php if(isset($_GET['courseId'])) { ?> class="current" <?php } ?>><a href="courses.php"><i class="icon-book3"></i>Курсы</a></li>

                        <li <?php if(isset($_GET['id'])) { ?> class="current" <?php } ?> ><a href="content.php"><i class="icon-line-content-left"></i>Материалы</a> </li>

                        <li><a href="blog.php"><i class="icon-blogger"></i>Блог</a></li>

                        <li <?php if(isset($_GET['libId'])) { ?> class="current" <?php } ?> ><a href="library.php"><i class="icon-line-align-center"></i>Библиотека</a></li>

                        <li <?php if(isset($_GET['instructorId'])) { ?> class="current" <?php } ?> ><a href="instructors.php"><i class="icon-guest"></i>Преподаватели</a></li>

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
                <h1>Добро пожаловать, <strong><?php if(isset($loginName)) echo $loginName; ?></strong></h1>
            </div>

            <div id="page-menu-wrap">

                <div class="container clearfix">

                </div>

            </div>

        </section><!-- конец #page-title -->

        <!-- Подменю страницы
        ============================================= -->

        <!-- Контент
        ============================================= -->
        <section id="content">

            <div class="content-wrap">

                <div class="container clearfix">
                <!-- ========================================== -->

                <div class="postcontent nobottommargin">


            <h3>Материалы</h3>

<!-- :::::::::::::::::::::: Content.php ::::::::::::::::::::::: -->

    <?php
    if(isset($_GET['id']) || isset($_GET['courseId']) || isset($_GET['instructorId']) || isset($_GET['libId'])){

        if(isset($_GET['id'])){
        
        $content_Id = $_GET['id'];

        $query = "SELECT * FROM `content` WHERE id= '$content_Id'";

        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) > 0){
        
         while( $row = mysqli_fetch_assoc($result) ){

                echo $row['content'];
                
            }
    
    }else {

        echo '<h1>Материалы не найдены!</h1>';

       } } 

       //:::::::::::::::::::::: Course.php :::::::::::::::::

      

        if(isset($_GET['courseId'])){
        
        $content_Id = $_GET['courseId'];

        $query = "SELECT * FROM `course` WHERE id= '$content_Id'";

        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) > 0){
        
         while( $row = mysqli_fetch_assoc($result) ){

                echo $row['description'];
                
            }
    
    }else {

        echo '<h1>Материалы не найдены!</h1>';

       } }
       //:::::::::::::::::::::: instructor.php :::::::::::::::::

        if(isset($_GET['instructorId'])){
        
        $content_Id = $_GET['instructorId'];

        $query = "SELECT * FROM `teacher` WHERE id= '$content_Id'";

        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) > 0){
        
         while( $row = mysqli_fetch_assoc($result) ){

                echo $row['description'];
                
            }
    
    }else {

        echo '<h1>Информация о преподавателе не найдена!</h1>';

       } }

       //:::::::::::::::::::::: library.php :::::::::::::::::

        if(isset($_GET['libId'])){
        
        $content_Id = $_GET['libId'];

        $query = "SELECT * FROM `library` WHERE id= '$content_Id'";

        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) > 0){
        
         while( $row = mysqli_fetch_assoc($result) ){

                echo "<h5>Название</h5>";
                echo $row['name'];
                echo "<br><br><h5>Описание</h5>";
                echo $row['description'];
                
            }
    
    }else {

        echo '<h1>Материалы библиотеки не найдены!</h1>';

       } }


}

?>
   
                    </div><!-- конец .postcontent -->


                </div>

            </div>

        </section><!-- конец #content -->

<?php 
// закрываем соединение с MySQL
    mysqli_close($connection);
include('footer.php'); 

}
?>