<!DOCTYPE HTML>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
        <link href="http://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,400,400italic,700,700italic,900,900italic" rel="stylesheet" type="text/css" >
        <link href="<?=site_url('css/style.css')?>" rel="stylesheet" type="text/css">
        <link href="<?=site_url('js/tag-it/css/jquery.tagit.css')?>" type="text/css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/ext-core/3.1.0/ext-core-debug.js" type="text/javascript"></script>
        <script src="<?=site_url('js/tag-it/js/tag-it.js')?>" type="text/javascript"></script>
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
                        echo 'Welcome '.$_SESSION['loggedIn']['fullname'].' | <a href="'.site_url('account').'">Account</a> | <a href="'.site_url('login/logout').'">Logout</a>';
                    }
                    else
                    {
                        echo '<a href="register" id="register">Register</a> | <a href="login" id="login">Login</a>';
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