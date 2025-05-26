<?php include("header.php"); ?>

		<!-- Заголовок страницы
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>Улучшите свой путь обучения, скоро <strong>появятся новые курсы!</strong></h1>
				<span>Доня: Знание - сила</span>
			</div>

		</section><!-- конец #page-title -->

		<!-- Подменю страницы
		============================================= -->
		<div id="page-menu">

			<div id="page-menu-wrap">

			</div>

		</div><!-- конец #page-menu -->

		<section id="content">

			<div class="content-wrap">

				<div class="container clearfix">

					<div class="bottommargin clearfix">

						<div class="row">
							
<?php				        
		
		$query = "SELECT * FROM `course`";

        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) > 0){
        
                        //Данные получены
                        //Выводим данные
         while( $row = mysqli_fetch_assoc($result) ){
         		$courseId = $row["id"];
         	 	$coursePic = $row["cover"];
                $coursename = $row["name"];
                $courseDescription = $row["description"];

                echo '<div class="col-sm-6 col-md-3">
							<div class="thumbnail image_fade">
							  <img data-src="holder.js/300x200" alt="Изображение" src="gotoep/images/courses/'.$coursePic.'" style="display: block; border: 2px solid #555;">
							  <div class="caption">
							  	
								<h5>'.$coursename.'</h5>
								<p>'.$courseDescription.'</p>
								<a href="lecture.php?id='.$courseId.'" class="btn btn-success btn-lg btn-block" role="button"><strong>Перейти к курсу</strong></a>
							  </div>
							</div>
						  </div>';
         }
     }else{echo '<div class="section notopmargin notopborder">
					<div class="container clearfix">
						<div class="heading-block center nomargin">
							<h3>Курсы пока недоступны</h3>
							</div>
						</div>
					</div>';}
?>
	
						  
					</div>
				</div>		
			</div>
		</div>
	</section>

<?php include("footer.php"); ?>