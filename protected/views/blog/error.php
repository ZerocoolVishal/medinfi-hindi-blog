<?php
    $this->layoutData = $pageData['layoutData'];
?>
<link href="<?php echo HOST_NAME.Yii::app()->baseUrl.CSS_BASE_PATH.ERROR_PAGE_CSS ?>" type="text/css" rel="stylesheet"/>
<div class="row PEle"  id="p_row" >
	<div class="col-sm-6 col-xs-12" id="p_h1">
		<div class="row" >
			<div class="col-sm-12">
				<p id="snap">Oh,Snap!</p>
			</div>
			<div id="page-not-found-heading-div" class="col-sm-12">
				<h1 id="page-not-found-heading">We can't seem to find the page you're looking for.</h1>
			</div>
			<div id="useful-link-div" class="col-sm-12">
				<p>Some useful links instead:</p>
				<ul style="list-style-type:disc">
				  <li><a class="links" href="<?php echo MEDINFI_FOLDER_BASE_URL."/" ?>">Home</a></li>
				  <li><a class="links" href="<?php echo HOST_NAME.Yii::app()->baseUrl."/"?>">Medinfi Blog</a></li>
				  <li><a class="links" href="<?php echo MEDINFI_FOLDER_BASE_URL.HEADER_DOCTOR_LINK_URL?>">Find a Doctor</a></li>
				  <li><a class="links" href="<?php echo MEDINFI_FOLDER_BASE_URL.ERROR_PAGE_HOSPITAL_LINK_URL?>">Find a Hospital</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-xs-12" id="p_img" >
		<img alt="Page not found" class="img-responsive" src="<?php echo HOST_NAME.Yii::app()->baseUrl.ERROR_IMAGE_BASE_PATH.PAGE_NOT_FOUND_IMAGE?>">
	</div>
</div>