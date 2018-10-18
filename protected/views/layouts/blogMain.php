<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <link rel="manifest" href="<?php echo HOST_NAME.Yii::app()->baseUrl.MANIFEST?>"/>

    <script src="https://code.jquery.com/jquery-2.1.1.min.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta charset="utf-8">
	<title><?php
	    $layoutData = $this->layoutData;
	    echo $this->layoutData['pageTitleValue'] ?></title>
	<?php
		if(!empty($this->layoutData['pageDescription'])){
			echo '
			<meta name="description" content="'.$this->layoutData['pageDescription'].'"/>';
		}
		if(!empty($this->layoutData['paginationSeoUrls'])){
			$paginationSeoUrls = $this->layoutData['paginationSeoUrls'];
			if(!empty($paginationSeoUrls['prevUrl'])){
				echo '
				<link rel="prev" href="'.$paginationSeoUrls['prevUrl'].'"/>';
			}
			if(!empty($paginationSeoUrls['nextUrl'])){
				echo '
				<link rel="next" href="'.$paginationSeoUrls['nextUrl'].'"/>';
			}

		}
		if(!empty($layoutData['postFbImageUrl'])){
			echo '
			<meta property="og:image" content="'.$layoutData['postFbImageUrl'].'"/>
			';
		}
	?>
	<link rel="canonical" href="<?php echo $this->layoutData['pageCanonicalUrl']?>"/>


	<link href="<?php echo HOST_NAME.Yii::app()->baseUrl.CSS_BASE_PATH.BOOTSTRAP_CSS;?>" type="text/css" rel="stylesheet"/>
	<link href="<?php echo HOST_NAME.Yii::app()->baseUrl.CSS_BASE_PATH.TEMPLATE_CSS;?>" type="text/css" rel="stylesheet"/>

	<script>
		var baseUrl = <?php echo '"'.$this->layoutData['projectBaseUrl'].'"' ?>;
		var websiteBaseUrl = <?php echo '"'.MEDINFI_FOLDER_BASE_URL.'"' ?>;
		var pageName = <?php echo '"'.$layoutData['pageName'].'"' ?>;

        //variable created for ever cookies
		var everCookiePath1 = <?php echo "'".HOST_NAME.Yii::app()->createUrl('/ever-cookie-png')."'";?>;
        var everCookiePath2 = <?php echo "'".HOST_NAME.Yii::app()->createUrl('/ever-cookie-etag')."'";?>;
        var everCookiePath3 = <?php echo "'".HOST_NAME.Yii::app()->createUrl('/ever-cookie-cache')."'";?>;
        var basePath1 = <?php echo "'".HOST_NAME.Yii::app()->baseUrl."'";?>;
        var createSessionPath = <?php echo "'".HOST_NAME.Yii::app()->createUrl('/createUserSession')."'";?>;
        var updateSessionPath = <?php echo "'".HOST_NAME.Yii::app()->createUrl('/updateUserSession')."'";?>;
        var pName=<?php echo '"'.$layoutData['pageName'].'"' ?>;
        var permissionAllowWeb = 'Not Available';
        var identifiedLocation = '';
        var identifiedCity = '';
        var location_detected = '';

	</script>
	<script src="<?php echo HOST_NAME.Yii::app()->baseUrl.JS_BASE_PATH.TEMPLATE_JS?>"></script>
	<script src="<?php echo HOST_NAME.Yii::app()->baseUrl.JS_BASE_PATH.BROWSER_NOTIFICATION_JS?>"></script>

	<link rel="shortcut icon" href="<?php echo HOST_NAME.Yii::app()->baseUrl.TEMPLATE_IMAGE_BASE_PATH.MEDINFI_FAVICON_IMAGE ?>">

</head>
<body>
<!--The below code was used for testing-->
<!--<textarea id="token" rows="6" cols="40">test</textarea>
<button onClick="sendNotification()">Send Notification</button>-->
<div id="page-wrap">
	<div id="header" class="header">
		<div id="bootstrap-container" class="container" >
			<div id="header-logo-row" class="row" >
				<div id="logo" class="col-lg-2 col-md-2 col-sm-3 col-xs-6" >
					<?php
						if(!empty($this->layoutData['categories'])){
							echo '
								<span id="mobile-navigation-button" ><!--&#9776;-->
									<div class="mobile-navigation-div"></div>
									<div class="mobile-navigation-div"></div>
									<div class="mobile-navigation-div"></div>
								</span>
								';
						}?>
					<a id="logo-anchor" href="<?php echo MEDINFI_FOLDER_BASE_URL."/"?>" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Home',eventLabel: 'Home'});">
						<img id="medinfi-logo" src="<?php echo HOST_NAME.Yii::app()->baseUrl.TEMPLATE_IMAGE_BASE_PATH.MEDINFI_LOGO?>" alt="Medinfi Logo"/>
					</a>
				</div>
				<div id="white-space" class="col-lg-7 col-md-6 col-sm-4 col-xs-1">
				</div>
				<div id="android-login" class="col-lg-3 col-md-4 col-sm-5 col-xs-5">
				<div id="android-download-top">
					<a rel="nofollow" id="get-app-anchor" href="https://play.google.com/store/apps/details?id=com.medinfi" target="_blank" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Download App Top',eventLabel: 'Download App Top'});">
						<span id="get-app-text">
							GET APP
						</span>
						<img id="android-top" src="<?php echo HOST_NAME.Yii::app()->baseUrl.TEMPLATE_IMAGE_BASE_PATH.HEADER_ANDROID_IMAGE?>" alt=""/>
					</a>
				</div>

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
			<div id="blog-search-row" class="row" >
				<div id="txt-search-bar" class="col-lg-5 col-md-5 col-sm-4 col-xs-12">
					<div id="txt-search-bar-row" class="row" >
						
						<div id="blog-search-bar" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<?php
									$searchBoxHtml = '<input id="blog-search" type="text" class="form-control"  placeholder="Search the blog"';
									if(!empty($this->layoutData['searchInput'])){
										$searchBoxHtml = $searchBoxHtml.'value = "'.$this->layoutData['searchInput'].'"';
									}
									$searchBoxHtml = $searchBoxHtml.'/>
									';
									echo $searchBoxHtml;
								?>
								<div id="search-glass-div">
									<img id="search-glass" src="<?php echo SEARCH_GLASS_ENCODED_IMAGE ?>" alt=""/>
								</div>
						</div>	
						<div id="search-border-div" class="col-xs-12">
							<img id="search-border" src="<?php echo HOST_NAME.Yii::app()->baseUrl.TEMPLATE_IMAGE_BASE_PATH.SEARCH_BOX_BORDER?>" alt=""/>
						</div>
						<ul id="blog-auto-suggest-ul"></ul>
					</div>					
				</div>
				<?php
				if(!empty($this->layoutData['categories'])){
						$categories = $this->layoutData['categories'];
						$categoryLinkDivHtml = '';
						$mobileNavigationHtml = '<div id="mobile-nav-div" class="mobile-nav">
					<div id="label-row-div">
						<label id="nav-bar-label">
							'.MEDINFI_MOBILE_NAV_TEXT.'
						</label>
						<a id = "nav-close" href="javascript:void(0)" class="close-button">&times;</a>
					</div>';
						
						for($index = 0;$index < sizeof($categories);$index++){
							$categoryTagId = $index + 1;
							
							if(!empty($this->layoutData['selectedTerm']) && ($categories[$index][2]==$this->layoutData['selectedTerm']['selectedTermSlug'])){
								$selectedTerm = $this->layoutData['selectedTerm'];
								$mobileNavigationHtml = $mobileNavigationHtml.'
								<a href="'.$categories[$index][3].'" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Category link in nav bar\',eventLabel: \''.$categories[$index][2].'\'});">
									'.strtoupper($categories[$index][1]).'
								</a>';
								$categoryLinkDivHtml=$categoryLinkDivHtml.'
								<div id="category-link-div-'.$categoryTagId.'" class="col-sm-1 category-link-div selected-outer-div">
									<div class="selected-inner-div">
										<a id="category-'.$categoryTagId.'" class="category-link-anchor" href="'.$categories[$index][3].'" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Category link in header\',eventLabel: \''.$categories[$index][2].'\'});">
											'.strtoupper($categories[$index][1]).'
										</a>
									</div>
								</div>';
							}
							else{
								$mobileNavigationHtml = $mobileNavigationHtml.'
								<a href="'.$categories[$index][3].'" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Category link in nav bar\',eventLabel: \''.$categories[$index][2].'\'});">
									'.strtoupper($categories[$index][1]).'
								</a>';
								$categoryLinkDivHtml=$categoryLinkDivHtml.'
								<div id="category-link-div-'.$categoryTagId.'" class="col-xs-1 category-link-div">
									<a id="category-'.$categoryTagId.'" class="category-link-anchor" href="'.$categories[$index][3].'" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Category link in header\',eventLabel: \''.$categories[$index][2].'\'});">
												'.strtoupper($categories[$index][1]).'
									</a>
								</div>';
							}							
						}
						$mobileNavigationHtml = $mobileNavigationHtml.'
						</div>';
						echo $categoryLinkDivHtml;
					}
					?>
			</div><!--blog-search-row-->
			<div id="header-border-line">
			</div>
		</div><!--bootstrap-container-->
	</div>	
	<div id="page" class="container" >
		<?php echo $content; ?>
	</div><!-- page -->
</div><!--page-wrap-->

<div id="footer" >
	<div id="bootstrap-container-bottom" class="container">
		<div id="banner-div" class="row">
			<div class="col-lg-12">
				<a id="get-app-anchor-bottom" href="https://play.google.com/store/apps/details?id=com.medinfi" target="_blank" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Download App Bottom',eventLabel: 'Download App Bottom'});">
					<img id="android-download-bottom" src="<?php echo HOST_NAME.Yii::app()->baseUrl.TEMPLATE_IMAGE_BASE_PATH.FOOTER_ANDROID_IMAGE ?>" alt=""/>
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
					<a class="footer_links" href="<?php echo MEDINFI_FOLDER_BASE_URL."/privacypolicy"?>" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Privacy Policy',eventLabel: 'Privacy Policy'});">
						<span >Privacy Policy. &nbsp;</span>
					</a>
					<a class="footer_links" href="<?php echo MEDINFI_FOLDER_BASE_URL."/termscondition"?>" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Terms Of Use',eventLabel: 'Terms Of Use'});">
						<span > Terms of Use. &nbsp;</span>
					</a>
					<a class="footer_links" href="<?php echo MEDINFI_FOLDER_BASE_URL."/save-feedback"?>" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Feedback',eventLabel: 'Feedback'});">
						<span >Feedback. &nbsp;</span>
					</a>
					<a class="footer_links" href="<?php echo MEDINFI_FOLDER_BASE_URL."/press"?>" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Press',eventLabel: 'Press'});">
						<span>Press. &nbsp;</span>
					</a>
					<a class="footer_links" href="<?php echo HOST_NAME.Yii::app()->baseUrl."/"?>" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Blog',eventLabel: 'Blog'});">
						<span >Blog</span>
					</a>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-1 col-xs-0 ">
			</div>
			<div id="copyright" class="col-lg-4 col-md-4 col-sm-5 col-xs-12 ">
				&copy; 2014-<?php echo date('Y');?> Medinfi <sup>TM</sup> Healthcare Pvt Ltd
			</div>
		</div>
		<div id="bottom-border" class="row">
			
		</div>
	</div>
</div><!--footer-->

<?php
	if(isset($mobileNavigationHtml)){
		echo $mobileNavigationHtml;
	}
?>
  <a id='med1Doctor' href="/med1_doctor" rel="nofollow" >Med1 Doctor</a>
  <script type="text/javascript">
   document.getElementById('med1Doctor').style.display = "none";
   document.getElementById('med1Doctor').setAttribute("aria-hidden", "true");
   </script>

</body>

       <!-- ever cookies and jquery JS -->
	<script src="<?php echo HOST_NAME.Yii::app()->baseUrl.JS_BASE_PATH.BELOW_FOLD_JS?>"></script>
	<script src="<?php echo HOST_NAME.Yii::app()->baseUrl.JS_BASE_PATH.ANALYTICS_JS?>"></script>

</html>

