<?php

	//**************** Файл "Свяжитесь с нами"
    if(isset($_POST['submit'])){        
            
            $subject = "Помогите нам";
            
            // Переменная статуса
            $Status_var = "";

            if( isset($_POST['name']) && !empty($_POST['name'])){
        
        		if(preg_match('/^[A-Za-z\s]+$/',$_POST['name'])){

          			$name = trim($_POST['name']);

        		}else{
          			$name_error = '<b class="text-danger text-center">Пожалуйста, введите корректное имя</b>';
        		}

      		}else{
          		$name_error = '<b class="text-danger text-center">Пожалуйста, заполните поле "Имя"</b>';
		    }//условие для имени
        
            if(isset($_POST['phone']) && !empty($_POST['phone'])){
                	
                    if(preg_match("/^([0-9]+)$/",$_POST['phone'])){
                    
                        $phone = trim($_POST['phone']);

                    }else{

                        $phone_error = '<b class="text-danger text-center">Пожалуйста, вводите только цифры</b>';
                    }

            }else{     
                    $phone = "Пользователь не указал";
            }// условие для телефона

            if(isset($_POST['email']) && !empty($_POST['email'])){

        		if(preg_match("/^[^@ ]+@[^@ ]+\.[^@ \.]+$/",$_POST['email'])){

            		    $email = trim($_POST['email']);
        		}else{
     	           $mail_error = '<b class="text-danger text-center">Пожалуйста, введите действительный Email</b>';
 	       		}
 	       	}else{
 	       		$mail_error = '<b class="text-danger text-center">Пожалуйста, заполните поле "Email"</b>';
 	       	}// условие для почты

 	       	if( isset($_POST['msg']) && !empty($_POST['msg']) ){
            	
            		$user_msg = trim($_POST['msg']);
            	
            }else{
            	$message_error = '<b class="text-danger text-center">Пожалуйста, заполните поле "Сообщение".</b>';
            } // конец условия для сообщения


        if( ( isset($name) && !empty($name) ) && ( isset($email) && !empty($email) ) && ( isset($user_msg) && !empty($user_msg) ) && ( isset($phone) && !empty($phone) ) ) {

            // подготовка тела письма 
            $message = "Email:".$email."\n";
            $message .= "Имя:".$name."\n";
            $message .= "Телефон:".$phone."\n";
            $message .= "Сообщение:".$user_msg."\n";
            
            $to = "donya@gmail.com"; //Почта программистов
            
            if(mail($to,$subject,$message)){
                
                $success= "<div class='alert alert-sucess'> Спасибо за ваш отзыв. <br>Мы здесь, чтобы оправдать ваши ожидания.<a class='close' data-dismiss='alert'>&times</a></div>";

            } else {
                $send_error = '<b class="text-danger text-center">Не удалось отправить письмо. Пожалуйста, проверьте ваш Email, Имя и заполните поле "Сообщение". </b>';
            } 
            
            $header  = "donya@gmail.com";
            $subject = "Спасибо, $name";
            $message = "Мы здесь, чтобы оправдать ваши ожидания. \nСпасибо за ваш ОТЗЫВ от Donya.\n CEO - Donya";
        
            $to = $email; //Почта пользователя
            
            if(mail($to,$subject,$message,$header)){

                $success= "<div class='alert alert-sucess'> Спасибо за ваш отзыв <br>Мы здесь, чтобы оправдать ваши ожидания.<a class='close' data-dismiss='alert'>&times</a></div>";

            } else {
                $send_error = '<b class="text-danger text-center">Не удалось отправить письмо. Пожалуйста, проверьте ваш Email, Имя и заполните поле "Сообщение". </b>';
            }  
        }  
        
        
    } // Конец условия Submit    

	
	include("header.php");

	
?>
		<!-- ======================Заголовок страницы======================== -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>Контакты</h1>
				<span>Свяжитесь с нами</span>
				
			</div>

		</section><!-- конец #page-title -->

		<!-- Подменю страницы
		============================================= -->
		<div id="page-menu">

			<div id="page-menu-wrap">

			</div>

		</div><!-- конец #page-menu -->

		<!-- Контент
		============================================= -->
		<section id="content">

			<div class="content-wrap">

				<div class="container clearfix">

					<!-- Основное содержимое
					============================================= -->
					<div class="postcontent nobottommargin">

						<?php 

							if(isset($message_error) || isset($name_error) || isset($phone_error) || isset($mail_error) || isset($send_error) ){

								echo "<div class='alert alert-danger'>";
                            
                            	echo "Пожалуйста, внимательно и правильно заполните форму<br>";

                            	echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Отмена</a>
                            	</div>";
							}else if(isset($success)){

								if(isset($success)) echo $success;
							}

						?>

						<h3>Отправьте нам письмо</h3>

							<form class="nobottommargin" id="template-contactform" name="template-contactform" action="" method="post">

								<div class="form-process"></div>

								<div class="col_one_third">
									<label for="template-contactform-name">Имя <small>*</small></label>
									<input type="text" placeholder="Имя" id="template-contactform-name" name="name" class="sm-form-control required" />
								<?php if(isset($name_error)) echo $name_error; ?>
								</div>

								<div class="col_one_third">
									<label for="template-contactform-email">Email <small>*</small></label>
									<input type="email" id="template-contactform-email" placeholder="Email" name="email" class="required email sm-form-control" />
									<?php if(isset($mail_error)) echo $mail_error; ?>
								</div>

								<div class="col_one_third col_last">
									<label for="template-contactform-phone">Телефон</label>
									<input type="text" id="template-contactform-phone" placeholder="Только цифры" name="phone" value="" class="sm-form-control" />
									<?php if(isset($phone_error)) echo $phone_error; ?>
								</div>

								<div class="clear"></div>
								<div class="clear"></div>

								<div class="col_full">
									<label for="template-contactform-message">Сообщение <small>*</small></label>
									<textarea placeholder="Сообщение" class="required sm-form-control" id="template-contactform-message" name="msg" rows="6" cols="30"></textarea>
									<?php if(isset($message_error)) echo $message_error; ?>
								</div>
								
								<div class="col_full">
									<button class="button button-3d nomargin" type="submit" id="template-contactform-submit" name="submit" value="submit">Отправить сообщение</button>
								</div>

							</form>
						
					</div><!-- конец .postcontent -->

					<!-- Боковая панель
					============================================= -->
					<div class="sidebar col_last nobottommargin">

						<address>
							<strong>Офис:</strong><br>
							Москва, Россия<br>
						</address>

						<abbr title="Номер телефона"><strong>Телефон:</strong></abbr> +7(987)-123-45-67<br>
						<abbr title="Email адрес"><strong>Email:</strong></abbr>donya@mail.ru

					
						<div class="widget noborder notoppadding">

							<a target="_blank" href="https://www.facebook.com/" class="social-icon si-small si-dark si-facebook">
								<i class="icon-facebook"></i>
								<i class="icon-facebook"></i>
							</a>

							<a target="_blank" href="https://twitter.com/" class="social-icon si-small si-dark si-twitter">
								<i class="icon-twitter"></i>
								<i class="icon-twitter"></i>
							</a>

							<a target="_blank" href="https://www.youtube.com/" class="social-icon si-small si-dark si-youtube">
								<i class="icon-youtube-play"></i>
								<i class="icon-youtube-play"></i>
							</a>

							
							<a target="_blank" href="https://github.com/brimokg" class="social-icon si-small si-dark si-github">
								<i class="icon-github"></i>
								<i class="icon-github"></i>
							</a>

						</div>

					</div><!-- конец .sidebar -->

				</div>

			</div>

		</section><!-- конец #content -->

<?php include("footer.php"); ?>