<?php
/**
 * Created by PhpStorm.
 * User: vishal
 * Date: 22/10/18
 * Time: 6:49 PM
 */
?>


<div id="Login-Button">
    <?php if (isset(Yii::app()->session['userId'])) { ?>
        <a style="text-decoration: none; color: black !important;" id="login"
           onclick="ga('send', {hitType: 'event',eventCategory: <?php echo "'" . $this->pageName . "'" ?>,eventAction: 'Login/Logout',eventLabel: 'Logout'});"
           href="<?php
           // if(!Yii::app()->user->isGuest && Yii::app()->user->role<3){
           //     echo HOST_NAME.Yii::app()->baseUrl.LOGOUT_URL;
           // }
           // else{
           echo HOST_NAME . Yii::app()->baseUrl . LOGOUT_URL;
           // }
           ?>">LOGOUT</a>
    <?php } else { ?>
        <a id="login" style="text-decoration: none; color: black !important;"
           onclick="ga('send', {hitType: 'event',eventCategory: <?php echo "'" . $this->pageName . "'" ?>,eventAction: 'Login/Logout',eventLabel: 'Login'});"
           href="<?php echo HOST_NAME . Yii::app()->baseUrl . LOGIN_URL; ?>">LOGIN</a>
    <?php } ?>
</div><!--Login-Button ends -->

<!--Blog Language selector-->
<div style="margin-top: 15px" class="dropdown col-lg-7 col-md-7 col-sm-3 col-xs-6">
    <span class="dropbtn"><b>Language: <?= LANGUAGE ?></b> <i class="fas fa-sort-down"></i></span>
    <div class="dropdown-content">
        <p><a href="<?= ENGLISH_BLOG_URL ?>">English<a></p>
        <p><a href="<?= HINDI_BLOG_URL ?>">हिंदी<a></p>
    </div>
</div>