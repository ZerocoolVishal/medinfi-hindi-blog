<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <!--Font Awesome CSS CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link rel="manifest" href="<?php echo HOST_NAME . Yii::app()->baseUrl . MANIFEST ?>"/>

    <script src="https://code.jquery.com/jquery-2.1.1.min.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title><?php
        $layoutData = $this->layoutData;
        echo $this->layoutData['pageTitleValue'] ?></title>
    <?php
    if (!empty($this->layoutData['pageDescription'])) {
        echo '
			<meta name="description" content="' . $this->layoutData['pageDescription'] . '"/>';
    }
    if (!empty($this->layoutData['paginationSeoUrls'])) {
        $paginationSeoUrls = $this->layoutData['paginationSeoUrls'];
        if (!empty($paginationSeoUrls['prevUrl'])) {
            echo '
				<link rel="prev" href="' . $paginationSeoUrls['prevUrl'] . '"/>';
        }
        if (!empty($paginationSeoUrls['nextUrl'])) {
            echo '
				<link rel="next" href="' . $paginationSeoUrls['nextUrl'] . '"/>';
        }

    }
    if (!empty($layoutData['postFbImageUrl'])) {
        echo '
			<meta property="og:image" content="' . $layoutData['postFbImageUrl'] . '"/>
			';
    }
    ?>
    <link rel="canonical" href="<?php echo $this->layoutData['pageCanonicalUrl'] ?>"/>


    <link href="<?php echo HOST_NAME . Yii::app()->baseUrl . CSS_BASE_PATH . BOOTSTRAP_CSS; ?>" type="text/css"
          rel="stylesheet"/>
    <link href="<?php echo HOST_NAME . Yii::app()->baseUrl . CSS_BASE_PATH . TEMPLATE_CSS; ?>" type="text/css"
          rel="stylesheet"/>

    <script>
        var baseUrl = <?php echo '"' . $this->layoutData['projectBaseUrl'] . '"' ?>;
        var websiteBaseUrl = <?php echo '"' . MEDINFI_FOLDER_BASE_URL . '"' ?>;
        var pageName = <?php echo '"' . $layoutData['pageName'] . '"' ?>;

        //variable created for ever cookies
        var everCookiePath1 = <?php echo "'" . HOST_NAME . Yii::app()->createUrl('/ever-cookie-png') . "'";?>;
        var everCookiePath2 = <?php echo "'" . HOST_NAME . Yii::app()->createUrl('/ever-cookie-etag') . "'";?>;
        var everCookiePath3 = <?php echo "'" . HOST_NAME . Yii::app()->createUrl('/ever-cookie-cache') . "'";?>;
        var basePath1 = <?php echo "'" . HOST_NAME . Yii::app()->baseUrl . "'";?>;
        var createSessionPath = <?php echo "'" . HOST_NAME . Yii::app()->createUrl('/createUserSession') . "'";?>;
        var updateSessionPath = <?php echo "'" . HOST_NAME . Yii::app()->createUrl('/updateUserSession') . "'";?>;
        var pName =<?php echo '"' . $layoutData['pageName'] . '"' ?>;
        var permissionAllowWeb = 'Not Available';
        var identifiedLocation = '';
        var identifiedCity = '';
        var location_detected = '';

    </script>
    <script src="<?php echo HOST_NAME . Yii::app()->baseUrl . JS_BASE_PATH . TEMPLATE_JS ?>"></script>
    <script src="<?php echo HOST_NAME . Yii::app()->baseUrl . JS_BASE_PATH . BROWSER_NOTIFICATION_JS ?>"></script>

    <link rel="shortcut icon"
          href="<?php echo HOST_NAME . Yii::app()->baseUrl . TEMPLATE_IMAGE_BASE_PATH . MEDINFI_FAVICON_IMAGE ?>">

    <style>

        #page-row {
                padding-top: 60px;
        }

        @media (max-width: 768px) {
            .header+.container {
                padding-top: 40px!important;
            }
        }

        .dropdown {
            position: relative;
            display: inline-block;
            margin-top: 15px;
        }

        @media only screen and (max-width: 768px) {
			.dropdown {
				margin-top: 5px;	
				margin-left: 30px;
        	}

            #page-row {
                padding-top: 30px;
            }
		}

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            padding: 2px 6px;
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropbtn {
            padding: 20px;
            font-size: 15px;
        }

        #Login-Button {
            width: 150px;
        }

        #blog-search-row {
            margin-top: 10px;
        }

        .category-link-div  {
            text-align: center;
            padding: 20px;
        }

        .selected-inner-div {
            display: block;
        }
    

    </style>
</head>
<body>

<!--The below code was used for testing-->
<!--<textarea id="token" rows="6" cols="40">test</textarea>
<button onClick="sendNotification()">Send Notification</button>-->

<div id="page-wrap">
    <div id="header" class="header">

        <div id="bootstrap-container" class="container">

            <div id="header-logo-row" class="row" >
				<div id="logo" class="col-lg-9 col-md-8 col-sm-6" >
					
					<span id="mobile-navigation-button" onclick="showMobileNav()" ><!--&#9776;-->
						<div class="mobile-navigation-div"></div>
						<div class="mobile-navigation-div"></div>
						<div class="mobile-navigation-div"></div>
					</span>
							
					<a id="logo-anchor" href="<?php echo MEDINFI_FOLDER_BASE_URL."/"?>" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Home',eventLabel: 'Home'});">
						<img id="medinfi-logo" src="<?php echo HOST_NAME.Yii::app()->baseUrl.TEMPLATE_IMAGE_BASE_PATH.MEDINFI_LOGO?>" alt="Medinfi Logo"/>
					</a>

					<div class="dropdown">
                        <span class="dropbtn">
                            <b>LANGUAGE: <span style="color: #ea235b;"><?=LANGUAGE?></span> <i class="fas fa-sort-down dropdown-ico" style="font-size: 25px;"></i></b>
                        </span>
                        <div class="dropdown-content">
                            <p><a href="<?= ENGLISH_BLOG_URL ?>">English<a></p>
                            <p style="font-size:18px;"><a href="<?= HINDI_BLOG_URL ?>">हिंदी<a></p>
                        </div>
                    </div>
				</div>

				<div id="android-login" class="col-lg-3 col-md-4 col-sm-6">

				<div id="Login-Button">
                   <?php if(isset(Yii::app()->session['userId'])) { ?>
                     <a style="text-decoration: none; color: black !important;" id="login" onclick="ga('send', {hitType: 'event',eventCategory: <?php echo "'".$this->pageName."'"?>,eventAction: 'Login/Logout',eventLabel: 'Logout'});"  href="<?php
                       // if(!Yii::app()->user->isGuest && Yii::app()->user->role<3){
                       //     echo HOST_NAME.Yii::app()->baseUrl.LOGOUT_URL;
                       // }
                       // else{
                        echo HOST_NAME.Yii::app()->baseUrl.LOGOUT_URL;
                       // }
                        ?>">LOGOUT</a>
                        <?php } else { ?>
                     <a id="login" style="text-decoration: none; color: black !important;" onclick="ga('send', {hitType: 'event',eventCategory: <?php echo "'".$this->pageName."'"?>,eventAction: 'Login/Logout',eventLabel: 'Login'});"  href="<?php echo HOST_NAME.Yii::app()->baseUrl.LOGIN_URL; ?>">LOGIN</a>
                        <?php }?>
                </div><!--Login-Button ends -->
				</div>
			</div><!--header-logo-row-->


            <div id="blog-search-row" class="row">
                
                <?php
                if (!empty($this->layoutData['categories'])) {
                    $categories = $this->layoutData['categories'];
                    $categoryLinkDivHtml = '';
                    $mobileNavigationHtml = '<div id="mobile-nav-div" class="mobile-nav">
					<div id="label-row-div">
						<label id="nav-bar-label">
							' . MEDINFI_MOBILE_NAV_TEXT . '
						</label>
						<a id = "nav-close" href="javascript:void(0)" onclick="hideMobileNav()" class="close-button">&times;</a>
					</div>';

                    for ($index = 0; $index < sizeof($categories); $index++) {
                        $categoryTagId = $index + 1;

                        if (!empty($this->layoutData['selectedTerm']) && ($categories[$index][2] == $this->layoutData['selectedTerm']['selectedTermSlug'])) {
                            $selectedTerm = $this->layoutData['selectedTerm'];
                            $mobileNavigationHtml = $mobileNavigationHtml . '
								<a href="' . $categories[$index][3] . '" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\'' . $layoutData['pageName'] . '\', eventAction: \'Category link in nav bar\',eventLabel: \'' . $categories[$index][2] . '\'});">
									' . strtoupper($categories[$index][1]) . '
								</a>';
                            $categoryLinkDivHtml = $categoryLinkDivHtml . '
								<div id="category-link-div-' . $categoryTagId . '" class="col-sm-1 col-md-2 col-lg-2 category-link-div selected-outer-div">
									<div class="selected-inner-div">
										<a id="category-' . $categoryTagId . '" style="font-size: 17px;" class="category-link-anchor" href="' . $categories[$index][3] . '" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\'' . $layoutData['pageName'] . '\', eventAction: \'Category link in header\',eventLabel: \'' . $categories[$index][2] . '\'});">
											' . strtoupper($categories[$index][1]) . '
										</a>
									</div>
								</div>';
                        } else {
                            $mobileNavigationHtml = $mobileNavigationHtml . '
								<a href="' . $categories[$index][3] . '" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\'' . $layoutData['pageName'] . '\', eventAction: \'Category link in nav bar\',eventLabel: \'' . $categories[$index][2] . '\'});">
									' . strtoupper($categories[$index][1]) . '
								</a>';
                            $categoryLinkDivHtml = $categoryLinkDivHtml . '
								<div id="category-link-div-' . $categoryTagId . '" class="col-sm-1 col-md-2 col-lg-2 category-link-div">
									<a id="category-' . $categoryTagId . '" style="font-size: 17px;" class="category-link-anchor" href="' . $categories[$index][3] . '" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\'' . $layoutData['pageName'] . '\', eventAction: \'Category link in header\',eventLabel: \'' . $categories[$index][2] . '\'});">
												' . strtoupper($categories[$index][1]) . '
									</a>
								</div>';
                        }
                    }
                    $mobileNavigationHtml = $mobileNavigationHtml . '
						</div>';
                    echo $categoryLinkDivHtml;
                }
                ?>
            </div><!--blog-search-row-->
            <div id="header-border-line">
            </div>
        </div><!--bootstrap-container-->
    </div>
    <div id="page" class="container">
        <?php echo $content; ?>
    </div><!-- page -->
</div><!--page-wrap-->

<div id="footer">
    <div id="bootstrap-container-bottom" class="container">
        <div id="banner-div" class="row">
            <div class="col-lg-12">
                <a id="get-app-anchor-bottom" href="https://play.google.com/store/apps/details?id=com.medinfi"
                   target="_blank"
                   onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Download App Bottom',eventLabel: 'Download App Bottom'});">
                    <img id="android-download-bottom"
                         src="<?php echo HOST_NAME . Yii::app()->baseUrl . TEMPLATE_IMAGE_BASE_PATH . FOOTER_ANDROID_IMAGE ?>"
                         alt=""/>
                    <div>
                        <label id="footer-label-1" class="footer-label">Available on Android</label>
                        <br/>
                        <label id="footer-label-2" class="footer-label">Download from Play Store</label>
                    </div>
                </a>
            </div>
        </div>
        <div id="link-div" class="row">
            <div id="link-bootstrap" class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                <div id="link-inner-div">
                    <a class="footer_links" href="<?php echo MEDINFI_FOLDER_BASE_URL . "/privacypolicy" ?>"
                       onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Privacy Policy',eventLabel: 'Privacy Policy'});">
                        <span>Privacy Policy. &nbsp;</span>
                    </a>
                    <a class="footer_links" href="<?php echo MEDINFI_FOLDER_BASE_URL . "/termscondition" ?>"
                       onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Terms Of Use',eventLabel: 'Terms Of Use'});">
                        <span> Terms of Use. &nbsp;</span>
                    </a>
                    <a class="footer_links" href="<?php echo MEDINFI_FOLDER_BASE_URL . "/save-feedback" ?>"
                       onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Feedback',eventLabel: 'Feedback'});">
                        <span>Feedback. &nbsp;</span>
                    </a>
                    <a class="footer_links" href="<?php echo MEDINFI_FOLDER_BASE_URL . "/press" ?>"
                       onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Press',eventLabel: 'Press'});">
                        <span>Press. &nbsp;</span>
                    </a>
                    <a class="footer_links" href="<?php echo HOST_NAME . Yii::app()->baseUrl . "/" ?>"
                       onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Blog',eventLabel: 'Blog'});">
                        <span>Blog</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-1 col-xs-0 ">
            </div>
            <div id="copyright" class="col-lg-4 col-md-4 col-sm-5 col-xs-12 ">
                &copy; 2014-<?php echo date('Y'); ?> Medinfi <sup>TM</sup> Healthcare Pvt Ltd
            </div>
        </div>
        <div id="bottom-border" class="row">

        </div>
    </div>
</div><!--footer-->

<?php
if (isset($mobileNavigationHtml)) {
    echo $mobileNavigationHtml;
}
?>
<a id='med1Doctor' href="/med1_doctor" rel="nofollow">Med1 Doctor</a>
<script type="text/javascript">
    document.getElementById('med1Doctor').style.display = "none";
    document.getElementById('med1Doctor').setAttribute("aria-hidden", "true");
</script>

<script type="application/javascript">

    function showMobileNav(e) {

        document.getElementById("mobile-nav-div").style.width = "260px";
        document.getElementById("mobile-nav-div").style.borderRight = "#D9D9D9 solid thin";
        document.getElementById("mobile-nav-div").style.overflow = "auto";
        document.body.style.overflow = "hidden";
        document.getElementById("page-wrap").style.backgroundColor = "#ffffff";
        document.getElementById("page-wrap").style.opacity = "0.3";
        document.getElementById("page-wrap").style.pointerEvents = "none";
        document.getElementById("footer").style.backgroundColor = "#ffffff";
        document.getElementById("footer").style.opacity = "0.3";
        document.getElementById("footer").style.pointerEvents = "none";
        ga("send", {
            hitType: "event",
            eventCategory: pageName,
            eventAction: "Open Nav Bar",
            eventLabel: "Open Nav Bar"
        })
    }

    function hideMobileNav(e) {

        document.body.style.overflow = "auto";
        document.getElementById("mobile-nav-div").style.width = "0";
        document.getElementById("mobile-nav-div").style.borderRight = "0px";
        document.getElementById("page-wrap").style.backgroundColor = "#ffffff";
        document.getElementById("page-wrap").style.opacity = "1.0";
        document.getElementById("page-wrap").style.pointerEvents = "auto";
        document.getElementById("footer").style.backgroundColor = "#ffffff";
        document.getElementById("footer").style.opacity = "1.0";
        document.getElementById("footer").style.pointerEvents = "auto";
        ga("send", {
            hitType: "event",
            eventCategory: pageName,
            eventAction: "Close Nav Bar",
            eventLabel: "Close Nav Bar"
        })
    }
</script>

</body>

<!-- ever cookies and jquery JS -->
<script src="<?php echo HOST_NAME . Yii::app()->baseUrl . JS_BASE_PATH . BELOW_FOLD_JS ?>"></script>
<script src="<?php echo HOST_NAME . Yii::app()->baseUrl . JS_BASE_PATH . ANALYTICS_JS ?>"></script>

</html>

