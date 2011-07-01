<?php
    $form_data = array('fullname' => '',
                       'username' => '',
                       'email' => '',
                       'password' => '',
                       'confirm_pass' => ''
                     );

    $status_msg = '';

    if(isset($_SESSION['register']))
    {
        $form_data = $_SESSION['register']['form_data'];
        $status_msg = $_SESSION['register']['msg'];

        unset($_SESSION['register']);
    }
?>
<div class="bookmark-container">
    <h2>Register</h2>

    <form name="register" id="register" method="post" action="<?=site_url('register/submit_register')?>">
        <div class="div-row">
            <div class="field-label">
                Name
            </div>
            <div class="field-value">
                <input type="text" id="fullname" name="fullname" value="<?=$form_data['fullname']?>" >
            </div>
        </div>
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
                Email
            </div>
            <div class="field-value">
                <input type="text" id="email" name="email" value="<?=$form_data['email']?>">
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
                Confirm Password
            </div>
            <div class="field-value">
                <input type="password" id="confirm_pass" name="confirm_pass">
            </div>
        </div>
        <?php
        if($_SERVER['ENVIRONMENT'] == 'dev' || $_SERVER['ENVIRONMENT'] == 'beta')
        {
        ?>
        <div class="div-row">
            <div class="field-label">
                Invite Code
            </div>
            <div class="field-value">
                <input type="text" id="invite_code" name="invite_code" value="">
            </div>
        </div>
        <div class="div-row">
            <div class="field-label">
               
            </div>
            <div class="field-value">
                <a href="<?=site_url('register/request_invite')?>">Request an Invite Code</a>
            </div>
        </div>
        <?php
        }
        ?>
        <div class="div-row">
            <div class="field-label">
                <input type="submit" class="submit-form" value="Register">
            </div>
            <div class="field-value" id="register-status">
                <?=$status_msg?>
            </div>
        </div>
    </form>
</div>