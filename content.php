<?php include("header.php"); ?>

<!-- Подменю страницы
		============================================= -->
		<div id="page-menu">

			<div id="page-menu-wrap">

			</div>

		</div><!-- конец #page-menu -->


		<section id="content">

			<div class="content-wrap">

				<div class="container clearfix">

					<!-- Основное содержимое
					============================================= -->
					<div class="nobottommargin clearfix">

					<?php

					if(isset($_GET['id'])){
        
        			$content_Id = $_GET['id'];

        			$query = "SELECT * FROM `content` WHERE id= '$content_Id'";

        			$result = mysqli_query($connection, $query);

        			if(mysqli_num_rows($result) > 0){
        
         				while( $row = mysqli_fetch_assoc($result) ){
                			
                			$lecureContent = $row['content'];
                			$lectureName = $row['lectureName'];

            			}
    
    				}else {

        				echo '<h1>Материал не найден!</h1>';

        				$lecureContent = " ";
                		$lectureName = " ";


       				} 
       			}else {
                    echo '<script type="text/javascript">
                        window.location = "course.php"
                    </script>';
                }

			?>

					<div class="panel panel-default">
  						<div class="panel-heading">
    						<h3 class="panel-title"><?php echo $lectureName; ?></h3>
  						</div>
  						<div class="panel-body">
    						<?php echo $lecureContent; ?>
  						</div>
					</div>

</div>
</div>
</div>
</section>

<?php include("footer.php"); ?>