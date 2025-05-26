<footer id="footer" class="dark">

            <div id="copyrights">

                <div class="container clearfix">

                    <div class="col_half">
                        <br>
                        Авторские права © 2025 Все права защищены Donya<br>
                        
                    </div>

                    <div class="col_half col_last tright">
                        <div class="fright clearfix">
                            <a target="_blank" href="https://www.facebook.com/" class="social-icon si-small si-borderless si-facebook">
                                <i class="icon-facebook"></i>
                                <i class="icon-facebook"></i>
                            </a>

                            <a target="_blank" href="https://www.youtube.com/" class="social-icon si-small si-borderless si-youtube">
                                <i class="icon-youtube-play"></i>
                                <i class="icon-youtube-play"></i>
                            </a>

                            <a target="_blank" href="https://github.com/brimokg" class="social-icon si-small si-borderless si-github">
                                <i class="icon-github"></i>
                                <i class="icon-github"></i>
                            </a>

                            
                        </div>

                        <div class="clear"></div>

                        <i class="icon-envelope2"></i> abdourahim.xy@mail.ru <span class="middot">·</span> <i class="icon-headphones"></i> 1234-1234567  <span class="middot"></span>
                    </div>

            </div></div>
            
        </footer> <div id="gotoTop" class="icon-angle-up"></div>

    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/plugins.js"></script>

    <script type="text/javascript" src="../js/functions.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            $( '#vertical-nav nav ul li:has(ul)' ).addClass('sub-menu');
            $( '#vertical-nav nav ul li:has(ul) > a' ).append( ' <i class="icon-angle-down"></i>' );

            $( '#vertical-nav nav ul li:has(ul) > a' ).click( function(e){
                var element = $(this);
                $( '#vertical-nav nav ul li' ).not(element.parents()).removeClass('active');
                element.parent().children('ul').slideToggle( function(){
                    $(this).find('ul').hide();
                    $(this).find('li.active').removeClass('active');
                });
                $( '#vertical-nav nav ul li > ul' ).not(element.parent().children('ul')).not(element.parents('ul')).slideUp();
                element.parent('li:has(ul)').toggleClass('active');
                e.preventDefault();
            });
        });
        

    </script>

</body>
</html>