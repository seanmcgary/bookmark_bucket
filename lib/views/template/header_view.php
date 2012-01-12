<!DOCTYPE HTML>
<html>
    <head>
        <link href="http://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,400,400italic,700,700italic,900,900italic" rel="stylesheet" type="text/css" >
        <!--<link href="<?=site_url('js/simplemodal/css/basic.css')?>" rel="stylesheet" type="text/css">-->
        <link href="<?=site_url('css/stylesheets/screen.css')?>" rel="stylesheet" type="text/css">
        <link href="<?=site_url('js/tag-it/css/jquery.tagit.css')?>" type="text/css" rel="stylesheet">
        <link href="<?=site_url('js/jquery-checkbox/jquery.checkbox.css')?>" type="text/css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/ext-core/3.1.0/ext-core-debug.js" type="text/javascript"></script>
        <script src="<?=site_url('js/simplemodal/js/jquery.simplemodal.js')?>" type="text/javascript"></script>
        <script src="<?=site_url('js/jquery-checkbox/jquery.checkbox.js')?>" type="text/javascript"></script>
        <script src="<?=site_url('js/dropdown_select.js')?>" type="text/javascript"></script>
        <?php
            $account = '';

            if(array_key_exists('ANALYTICS', $_SERVER))
            {
                if($_SERVER['ANALYTICS'] == 'dev')
                {
                    $account = 'UA-24994790-2';
                }

                if($_SERVER['ANALYTICS'] == 'production')
                {
                    $account = 'UA-24994790-1';
                }
            }
        ?>
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '<?=$account?>']);
            _gaq.push(['_trackPageview']);

            (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>
        <?php
            foreach($css as $styles)
            {
                echo '<link href="'.$styles.'" type="text/css" rel="stylesheet">';
            }

            foreach($javascript as $js)
            {
                echo '<script src="'.$js.'" type="text/javascript"></script>';
            }
          ?>
        <script type="text/javascript">
            var submit_new_bookmark = '<?=site_url('bookmark/add_bookmark')?>';
            var suggest_link_title = '<?=site_url('bookmark/suggest_title')?>';
            var is_valid_url = '<?=site_url('bookmark/is_valid_url')?>';
            var log_click = '<?=site_url('bookmark/log_click')?>';
            var bookmark_existing = '<?=site_url('bookmark/bookmark_existing')?>';
        </script>
    </head>
    <body>
        <div class="header">
            <div class="header-container">
                <div class="logo">
                    <a href="<?=site_url('')?>">BookmarkBucket</a>
                </div>
                <div class="right">
                <?php
                    if(isset($_SESSION['loggedIn']))
                    {
                        echo '<div class="user-data">';
                        echo 'Welcome, '.$_SESSION['loggedIn']['username'].' | <a href="'.site_url('account').'">Account</a> | <a href="'.site_url('login/logout').'">Logout</a>';
                        echo '</div>
                             <div class="bookmark_actions">
                                <input type="button" class="button btn-green" value="Add Bookmark" id="create_new_bookmark">
                                <input type="button" class="button btn-green" value="Add Bucket">
                             </div>';
                    }
                    else
                    {
                        echo '<div class="user-data"><a href="register" id="register">Register</a> | <a href="login" id="login">Login</a></div>';
                    }
                ?>
                </div>
            </div>
        </div>
        <!--<div class="navigation">
            <div class="navigation-container">
                <ul id="nav-list">
                    
                </ul>
            </div>
        </div>  -->
        <div class="container">
