<?php include("header.php"); ?>

<!-- Заголовок страницы -->
<section id="page-title">
    <div class="container clearfix">
        <h1>Команда</h1>
        <span>Исключительная команда</span>
    </div>
</section><!-- конец #page-title -->

<!-- Подменю страницы -->
<div id="page-menu">
    <div id="page-menu-wrap">
    </div>
</div><!-- конец #page-menu -->

<!-- Контент -->
<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
            
            <div class="row">
                <div class="col-md-6 bottommargin">
                <?php 
                    $query = "SELECT * FROM `team` WHERE name = 'Кануте Абдурахим'";
                    $result = mysqli_query($connection, $query);

                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            $ChairmanPic = $row['image'];
                        }
                    }
                ?>

                    <div class="team team-list clearfix">
                        <div class="team-image">
                            <img src="gotoep/images/team/<?php if(isset($ChairmanPic)) echo $ChairmanPic; ?>" alt="Кануте Абдурахим">
                        </div>
                        <div class="team-desc">
                            <div class="team-title"><h4>Кануте Абдурахим</h4><span>Программист</span></div>
                            <div class="team-content">
                                <p>Я инженер-программист с страстью к преподаванию, а также специалист по безопасности. Я увлечен всем, что связано с цифровыми технологиями, наслаждаюсь программированием и вызовами успешного цифрового опыта.</p>
                            </div>    
                        </div>
                    </div>
                </div>

                <?php 
                    $query = "SELECT * FROM `team` WHERE name = 'Адаму Маруфату'";
                    $result = mysqli_query($connection, $query);

                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            $ceoPic = $row['image'];
                        }
                    }
                ?>

                <div class="col-md-6 bottommargin">
                    <div class="team team-list clearfix">
                        <div class="team-image">
                            <img src="gotoep/images/team/<?php if(isset($ceoPic)) echo $ceoPic; ?>" alt="Адаму Маруфату">
                        </div>
                        <div class="team-desc">
                            <div class="team-title"><h4>Адаму Маруфату</h4><span>Программист</span></div>
                            
                            <div class="team-content">
                                <p>Я графический дизайнер<br>Работаю с Adobe Photoshop 3 года, с Illustrator и After Effects - 2 года. Также являюсь инструктором по графике в Доня.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clear"></div>

            <div class="fancy-title title-border title-center">
                <h3>Члены команды</h3>
            </div>

            <div id="oc-team" class="owl-carousel team-carousel bottommargin carousel-widget" data-margin="30" data-pagi="false" data-items-xs="2" data-items-sm="2" data-items-lg="4">
                <?php 
                    $query = "SELECT * FROM `team` WHERE name != 'Muhammad Saim' AND name != 'Chaudhry Faheem Irfan' ";
                    $result = mysqli_query($connection, $query);

                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            $memberPic = $row['image'];
                            $memberName = $row['name'];
                            $memberQ = $row['qualification'];

                            echo '<div class="oc-item">
                                    <div class="team">
                                        <div class="team-image">
                                            <img src="gotoep/images/team/'.$memberPic.'" alt="Exceptional">
                                        </div>
                                        <div class="team-desc">
                                            <div class="team-title"><h4>'.$memberName.'</h4><span>'.$memberQ.'</span></div>
                                        </div>
                                    </div>
                                </div>';
                        }
                    }
                ?>
            </div>
            
            <div class="clear"></div>
        </div>
    </div>
</section><!-- конец #content -->

<?php include("footer.php"); ?>