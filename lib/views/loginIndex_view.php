<?php
    $form_data = array('username' => '',
                       'password' => ''
                     );

    $status_msg = '';

    if(isset($_SESSION['login']))
    {
        $form_data = $_SESSION['login']['form_data'];
        $status_msg = $_SESSION['login']['msg'];

        unset($_SESSION['login']);
    }
?>
<div class="right-col">
    <div class="bookmark-container">
        <h2>Login</h2>

        <form name="login" id="login" method="post" action="<?=site_url('login/process_login')?>">
            <div class="div-row">
                <div class="field-label">
                    Username
                </div>
                <div class="field-value">
                    <input type="text" id="username" name="username" value="<?=$form_data['username']?>">
                </div>
                <div class="field-status">

                </div>
            </div>
            <div class="div-row">
                <div class="field-label">
                    Password
                </div>
                <div class="field-value">
                    <input type="password" id="password" name="password">
                </div>
            </div>
            <div class="div-row">
                <div class="field-label">
                    <input type="submit" class="submit-form" value="Login">
                </div>
                <div class="field-value" id="register-status">
                    <?=$status_msg?>
                </div>
            </div>
        </form>
    </div>
</div>