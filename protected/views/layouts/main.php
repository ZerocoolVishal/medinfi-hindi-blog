<!DOCTYPE HTML>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo HOST_NAME.Yii::app()->baseUrl.CSS_BASE_PATH.MAIN_CSS?>" type="text/css" rel="stylesheet"/>
        <link href="<?php echo HOST_NAME.Yii::app()->baseUrl.CSS_BASE_PATH.MENU_STYLE_CSS?>" type="text/css" rel="stylesheet"/>
        <link href="<?php echo HOST_NAME.Yii::app()->baseUrl.CSS_BASE_PATH.STYLE_CSS?>" type="text/css" rel="stylesheet"/>

        <!-- <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/magnific-popup.css"></link> -->
        <!-- <script src="<?php echo Yii::app()->baseUrl ?>/js/jquery-1.11.2.min.js" type="text/javascript"></script> -->
        <!-- <script src=<?php echo Yii::app()->baseUrl."/js/jquery.magnific-popup.js";?> type="text/javascript"></script> -->

        <title><?php echo $this->pageTitle;?></title>
        <meta name="description" content="<?php echo $this->pageDescription;?>">

    </head>

    <body>
        <div id="container">

            <div id="header-wrapper">
                <div id="header">

                    <div class="top-contemail"></div>
                    <!-- <div class="top-contactus"><img src="<?php //echo Yii::app()->baseUrl ?>/images/Call-icon.png"> <span>Contact us: +0123456789</span></div>
                    <div class="top-contactus"><img src="<?php //echo Yii::app()->baseUrl ?>/images/Email.png"> <span>info@medicheck.com</span></div> -->


                    <?php
                    $error=Yii::app()->errorHandler->error ;
                    if($error['code']!='404') {
                        if(!Yii::app()->user->isGuest){
                    ?>

                    <div class="logout">
                        <span>Welcome <?php
                            if(isset(Yii::app()->user->username) && Yii::app()->user->username!=="")
                            {
                                echo Yii::app()->user->username;
                            }
                            else
                            {
                                echo "Dear User";
                            } ?>
                        </span> |

                        <a href="<?php echo Yii::app()->createUrl('User/Logout'); ?>"> <span>Logout</span></a>

                         |
                        <a href="<?php echo Yii::app()->createUrl('User/ChangePassword'); ?>">
                        <span>Change Password</span>
                        </a>
                    </div>

                    <?php

                        }
                        else
                        {
                        }
                    }?>


                    <div class="clearfix"></div>
                    <div class="logo">


                        <?php
                        $error=Yii::app()->errorHandler->error ;

                        if(!Yii::app()->user->isGuest && $error['code']!='404')
                        {
                        ?>
                        <img alt="Medinfi Logo" width="10%" style="margin:3%" src="<?php echo Yii::app()->baseUrl ?>/images/backend/logo.jpg">
                        <br>
                        </a>
                        <?php
                            }
                            else
                            {
                        ?>

                        <img alt="Medinfi Logo" width="10%" style="margin:3%" src="<?php echo Yii::app()->baseUrl ?>/images/backend/logo.jpg">
                        <br>

                        <?php
                            }
                        ?>
                    </div>
                    <div class="clearfix"></div>




                    <?php
                    if(isset(Yii::app()->session['med_role'])) $role = Yii::app()->session['med_role'];
                    ?>
                    <div id='cssmenu'>
                        <ul>
                            <?php  global $BLOG_BACKEND_ACCESS; if(!Yii::app()->user->isGuest && (in_array($role,$BLOG_BACKEND_ACCESS))) {  ?>
                            <li class="Batch Allocation"><a href='<?php echo Yii::app()->createUrl('blogtask/blogTaskInboxView'); ?>'>Blog Task</a></li>
                            <?php  Yii::log("user role ".$role); if(!Yii::app()->user->isGuest && ($role==BLOG_AUDITOR_ROLE ||$role==ADMIN_ROLE)) {  ?>
                            <li class="User Creation"><a href='<?php echo Yii::app()->createUrl('blogtask/userCreation'); ?>'>Create User</a></li>
                            <?php } ?>
                            <!--<li class="Allocated"><a href='<?php echo Yii::app()->createUrl('Automation/ViewBatchAllocated'); ?>'>Allocated Batch</a>-->
                            <?php } ?>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="content">
                <br><br><br><br>
                <?php echo $content; ?>
                <!-- <div class="clear"></div> -->
                <br>
                <br>
                <br>
                <br>
            </div>

            <div class="footer">
                <div class="foot1"> Medinfi copyright 2018 | All Rights Reserved </div>
                    <!-- <div class="foot1"><a href="<?php //echo Yii::app()->createUrl('User/privacypolicy'); ?>">Privacy Policy</a> <br> <a href="<?php //echo Yii::app()->createUrl('User/termscondition'); ?>">Terms & Conditions</a></div>
                    <div class="foot2">Contact us <br> Medicheck copyright 2014 | All Rights Reserved</div>
                    <script src="<?php echo Yii::app()->baseUrl ?>/js/jquery-ui.js"></script>
                    <script src="<?php echo Yii::app()->baseUrl ?>/js/script.js"></script>
                    <script src="<?php echo Yii::app()->baseUrl ?>/js/jquery.magnific-popup.js"></script> -->
                </div>
            </div>
        </div>

        <script>
            $("#category").on('change', function(event) {
                if($(event.currentTarget).val() != 'Category')
                    location.href=$(event.currentTarget).val();
            })
            /*function fnCallPage(param) {
            //window.location(param);
            alert('here');
        }*/
        </script>
    </body>
</html>