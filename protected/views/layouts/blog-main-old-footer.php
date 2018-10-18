<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta charset="utf-8">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link href="<?php echo Yii::app()->baseUrl."/css/v7.1/bootstrap-lightest.min.css";?>" type="text/css" rel="stylesheet"/>
	<link href="<?php echo Yii::app()->baseUrl."/css/v7.1/template.css";?>" type="text/css" rel="stylesheet"/>
	<script>
		var baseUrl = <?php echo '"'.$this->layoutData['projectBaseUrl'].'"' ?>;
	</script>
	<script src="<?php echo Yii::app()->baseUrl.JS_BASE_PATH.TEMPLATE_JS?>"></script>
</head>
<body>
<div id="page-wrap">
	<div id="header">
		<div id="bootstrap-container" class="container" >
			<div id="header-logo-row" class="row" >
				<div id="logo" class="col-lg-2 col-md-2 col-sm-3 col-xs-6" >
					<span id="mobile-navigation-button" ><!--&#9776;-->
						<div class="mobile-navigation-div"></div>
						<div class="mobile-navigation-div"></div>
						<div class="mobile-navigation-div"></div>
					</span>
					<a id="logo-anchor" href="<?php echo Yii::app()->baseUrl."/"?>">
						<img id="medinfi-logo" src="<?php echo Yii::app()->baseUrl."/images/template/logo.png"?>" alt="Medinfi Logo"/>
					</a>
				</div>
				<div id="white-space" class="col-lg-8 col-md-8 col-sm-6 col-xs-2">
				</div>
				<div id="android-download-top" class="col-lg-2 col-md-2 col-sm-3 col-xs-4" >
					<a id="get-app-anchor" href="https://play.google.com/store/apps/details?id=com.medinfi" target="_blank">
						<span id="get-app-text">
							GET APP
						</span>
						<img id="android-top" src="<?php echo Yii::app()->baseUrl."/images/template/get-app.png"?>" alt=""/>
					</a>
				</div>
					 
			</div><!--header-logo-row-->
			<div id="blog-search-row" class="row" >
				<div id="txt-search-bar" class="col-lg-5 col-md-5 col-sm-4 col-xs-12">
					<div id="txt-search-bar-row" class="row" >
						<!--<div id="blog-txt" class="col-lg-2 col-md-2 col-sm-1 col-xs-2">
							<span>Blog</span>
						</div>-->
						
						<div id="blog-search-bar" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!--<div class="row">-->
							<!--<div id="blog-search-text-button" class="col-xs-12">-->
								<?php
									$searchBoxHtml = '<input id="blog-search" type="text" class="form-control"  placeholder="Search the blog" ';
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
							<!--</div>-->
						</div>	
							<div id="search-border-div" class="col-xs-12">
								<!--<div id="search-border-div-2" ></div>-->
								<img id="search-border" src="<?php echo Yii::app()->baseUrl."/images/template/custom-long-search-border.png"?>" alt=""/>
							
							<!--</div>-->
						</div>
					</div>
				</div>
				<!--<div id="mobile-nav-div" class="mobile-nav">
					<div id="label-row-div">
						<label id="nav-bar-label">
							<?php echo MEDINFI_MOBILE_NAV_TEXT ?>
						</label>
						<a id = "nav-close" href="javascript:void(0)" class="closebtn">&times;</a>
					</div>
					<a href="#">HEALTH CONDITIONS</a>
					<a href="#">HEALTHY LIVING</a>
					<a href="#">TRENDING TOPICS</a>
					<a href="#">NUTRITION & DIET</a>
					<a href="#">WOMEN'S HEALTH</a>
					<a href="#">CHILD HEALTH</a>
					<div class="mobile-nav-separator"></div>
					<a href="#">
						<img id="doctor-default-img" src="<?php echo Yii::app()->baseUrl.TEMPLATE_IMAGE_BASE_PATH.FIND_DOCTORS_IMAGE?>" alt=""/>
						<span>FIND DOCTORS</span>
					</a>
					<div class="mobile-nav-separator"></div>
				</div>-->
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
							if($categories[$index][2] == HEADER_DOCTOR_LINK_SLUG){
								$mobileNavigationHtml = $mobileNavigationHtml.'
								<div class="mobile-nav-separator"></div>
									<a href="'.$categories[$index][3].'">
										<img id="doctor-default-img" src="'.Yii::app()->baseUrl.TEMPLATE_IMAGE_BASE_PATH.FIND_DOCTORS_IMAGE.'" alt=""/>
										<span>'.strtoupper($categories[$index][1]).'</span>
									</a>
								<div class="mobile-nav-separator"></div>';
								
								$categoryLinkDivHtml=$categoryLinkDivHtml.'
								<div id="doctor-link" class="col-sm-1 category-link-div">
									<a id="category-'.$categoryTagId.'" class="category-link-anchor" href="'.$categories[$index][3].'">
												'.strtoupper($categories[$index][1]).'
									</a>
								</div>';
							}
							else if(!empty($this->layoutData['selectedTerm']) && ($categories[$index][2]==$this->layoutData['selectedTerm']['selectedTermSlug'])){
								$selectedTerm = $this->layoutData['selectedTerm'];
								$mobileNavigationHtml = $mobileNavigationHtml.'
								<a href="'.$categories[$index][3].'">
									'.strtoupper($categories[$index][1]).'
								</a>';
								$categoryLinkDivHtml=$categoryLinkDivHtml.'
								<div id="category-link-div-'.$categoryTagId.'" class="col-sm-1 category-link-div selected-outer-div">
									<div class="selected-inner-div">
										<a id="category-'.$categoryTagId.'" class="category-link-anchor" href="'.$categories[$index][3].'">
											'.strtoupper($categories[$index][1]).'
										</a>
									</div>
								</div>';
							}
							else{
								$mobileNavigationHtml = $mobileNavigationHtml.'
								<a href="'.$categories[$index][3].'">
									'.strtoupper($categories[$index][1]).'
								</a>';
								$categoryLinkDivHtml=$categoryLinkDivHtml.'
								<div id="category-link-div-'.$categoryTagId.'" class="col-xs-1 category-link-div">
									<a id="category-'.$categoryTagId.'" class="category-link-anchor" href="'.$categories[$index][3].'">
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
							<!--<div class="col-sm-1 category-link-div">
								<a id="category-1" class="category-link-anchor">
									CHILD HEALTH
								</a>
							</div>
							<div class="col-sm-1 category-link-div">
								<a id="category-2" class="category-link-anchor">
									WOMEN'S HEALTH
								</a>
							</div>
							<div class="col-sm-1 category-link-div">
								<a id="category-3" class="category-link-anchor">
									HEALTH CONDITIONS
								</a>
							</div>
							<div class="col-sm-1 category-link-div">
								<a id="category-4" class="category-link-anchor">
									NUTRITION & DIET
								</a>
							</div>
							<div class="col-sm-1 category-link-div">
								<a id="category-5" class="category-link-anchor">
									HEALTHY LIVING
								</a>
							</div>
							<div class="col-sm-1 category-link-div">
								<a id="category-6" class="category-link-anchor">
									TRENDING TOPICS
								</a>
							</div>
							<div id="doctor-link" class="col-sm-1 category-link-div">
								<a id="category-7" class="category-link-anchor">
									FIND DOCTORS
								</a>
							</div>-->
					
					<?php /*if(!empty($this->layoutData['categories'])){
						$categories = $this->layoutData['categories'];
						$categoryLinkDivHtml = '<div id="category-links-div" class="col-lg-7 col-md-7 col-sm-12 col-xs-12">';
						for($index = 0;$index < sizeof($categories);$index++){
							$categoryTagId = $index + 1;
							if($index == 0 || $index == 3){
								$groupId = ($index == 0 ? 1 : 2);
								$categoryLinkDivHtml = $categoryLinkDivHtml.'
									<div id="category-links-group-'.$groupId.'" class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="row">';
							}
							 
											if(!empty($this->layoutData['selectedTerm']) && ($categories[$index][2]==$this->layoutData['selectedTerm']['selectedTermSlug'])){
												$selectedTerm = $this->layoutData['selectedTerm'];
												$categoryLinkDivHtml=$categoryLinkDivHtml.'
												<div class="col-xs-4 category-link-div">
													<div class="selected-outer-div">
														<div class="selected-inner-div">
															<a id="category-'.$categoryTagId.'" class="category-link-anchor" href="'.Yii::app()->baseUrl."/category/".$categories[$index][2].'/">
																'.strtoupper($categories[$index][1]).'
															</a>
														</div>
													</div>
												</div>';
											}
											else{
												$categoryLinkDivHtml=$categoryLinkDivHtml.'
												<div class="col-xs-4 category-link-div">
													<a id="category-'.$categoryTagId.'" class="category-link-anchor" href="'.Yii::app()->baseUrl."/category/".$categories[$index][2].'/">
																'.strtoupper($categories[$index][1]).'
													</a>
												</div>';
											}
							if($index==2 || $index==5){
								$categoryLinkDivHtml = $categoryLinkDivHtml.'
										</div>
									</div>';
								
							}
							
						}
						$categoryLinkDivHtml = $categoryLinkDivHtml.'
							</div>';
						echo $categoryLinkDivHtml;
					}*/
				?>
				
							
				
				<!--<div id="category-links-div" class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
					<div id="category-links-group-1" class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
						<div class="row">
							<div class="col-xs-4 category-link-div">
								<a id="category-1" class="category-link-anchor">
									CHILD HEALTH
								</a>
							</div>
							<div class="col-xs-4 category-link-div">
								<a id="category-2" class="category-link-anchor">
									WOMEN'S HEALTH
								</a>
							</div>
							<div class="col-xs-4 category-link-div">
								<a id="category-3" class="category-link-anchor">
									HEALTH CONDITIONS
								</a>
							</div>
						</div>
					</div>
					<div id="category-links-group-2" class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
						<div class="row">
							<div class="col-xs-4 category-link-div">
								<a id="category-4" class="category-link-anchor">
									NUTRITION & DIET
								</a>
							</div>
							<div class="col-xs-4 category-link-div">
								<a id="category-5" class="category-link-anchor">
									HEALTHY LIVING
								</a>
							</div>
							<div class="col-xs-4 category-link-div">
								<a id="category-6" class="category-link-anchor">
									TRENDING TOPICS
								</a>
							</div>
						</div>
					</div>
					<div id="category-links-group-3" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
						<div class="row1">
							<div class="category-link-div">
								<a id="category-7" class="category-link-anchor">
									FIND DOCTORS
								</a>
							</div>
						</div>
					</div>
				</div>--><!--category-links-div-->
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
				<a id="get-app-anchor-bottom" href="https://play.google.com/store/apps/details?id=com.medinfi" target="_blank">
					<img id="android-download-bottom" src="<?php echo Yii::app()->baseUrl."/images/template/google-play.png"?>" alt=""/>
				</a>
			</div>
		</div>
		<div id="link-div" class="row">
			<div id="link-bootstrap" class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
				<div id="link-inner-div">
					<a class="footer_links" href="<?php echo MEDINFI_FOLDER_BASE_URL."/privacypolicy"?>">
						<span >Privacy Policy. &nbsp;</span>
					</a>
					<a class="footer_links" href="<?php echo MEDINFI_FOLDER_BASE_URL."/termscondition"?>">
						<span > Terms of Use. &nbsp;</span>
					</a>
					<a class="footer_links" href="<?php echo MEDINFI_FOLDER_BASE_URL."/save-feedback"?>">
						<span >Feedback. &nbsp;</span>
					</a>
					<a class="footer_links" href="<?php echo MEDINFI_FOLDER_BASE_URL."/press"?>">
						<span>Press. &nbsp;</span>
					</a>
					<a class="footer_links" href="<?php echo Yii::app()->baseUrl."/"?>">
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
	</div>
</div><!--footer-->

<?php echo $mobileNavigationHtml ?>
<!--<div id="mobile-nav-div" class="mobile-nav">-->
<!--	<div id="label-row-div">-->
<!--		<label id="nav-bar-label">-->
<!--			<?php echo MEDINFI_MOBILE_NAV_TEXT ?>-->
<!--		</label>-->
<!--		<a id = "nav-close" href="javascript:void(0)" class="closebtn">&times;</a>-->
<!--	</div>-->
<!--	<a href="#">HEALTH CONDITIONS</a>-->
<!--	<a href="#">HEALTHY LIVING</a>-->
<!--	<a href="#">TRENDING TOPICS</a>-->
<!--	<a href="#">NUTRITION & DIET</a>-->
<!--	<a href="#">WOMEN'S HEALTH</a>-->
<!--	<a href="#">CHILD HEALTH</a>-->
<!--	<div class="mobile-nav-separator"></div>-->
<!--	<a href="#">-->
<!--		<img id="doctor-default-img" src="<?php echo Yii::app()->baseUrl.TEMPLATE_IMAGE_BASE_PATH.FIND_DOCTORS_IMAGE?>" alt=""/>-->
<!--		<span>FIND DOCTORS</span>-->
<!--	</a>-->
<!--	<div class="mobile-nav-separator"></div>-->
<!--</div>-->
</body>
</html>
