<?php
class BlogController extends Controller{
    /**
     *Developer - Harsh Balyan
     *Date - 27-Jan-2017
     */
    public $pageName = "Medinfi Blog";
    public $pageTitleValue = BLOG_DEFAULT_PAGE_TITLE;
	public $pageDescription = BLOG_DEFAULT_PAGE_DESCRIPTION;
	public $layoutData = array();
	public $projectBaseUrl = "";

    /**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex(){
		Yii::log("Started index");

        $url = HTTP.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        Yii::app()->session['BLOGURL'] = $url ;
        Yii::app()->session['BLOGLOGIN_LOGOUT'] = "false";

        $this->layout = BLOG_MAIN_TEMPLATE;
		$showViewAll = false;
		$totalNumberOfPosts = 0;
		$termId = NULL;
		$processedRecentPostList = array();
		$selectedView = DEFAULT_VIEW;
		$pageData = array();
		$pageData['selectedTerm'] = array();
		$termType = isset($_GET['term_type']) ? $_GET['term_type'] : VALUE_NOT_SET;
		$termSlug = isset($_GET['term_slug']) ? $_GET['term_slug'] : VALUE_NOT_SET;
		$pageNo = isset($_GET['page_no']) ? $_GET['page_no'] : VALUE_NOT_SET;
		$searchInput = isset($_GET['s']) ? $_GET['s'] : (isset($_GET['search_input']) ? $_GET['search_input'] : VALUE_NOT_SET);
		$pageType = BLOG_LIST_PAGE;
		$offset = DEFAULT_POST_OFFSET;
		$limit = DEFAULT_POST_LIMIT;
		$pageTitleValue = BLOG_DEFAULT_PAGE_TITLE;
		$pageDescription = BLOG_DEFAULT_PAGE_DESCRIPTION;
		$isPsuedoSearchListPage = isset($_GET['search_input']) ? true : false;
		$isValidException = false;
		$redirectUrl = '';
		$trackSource = isset($_GET['track_source']) ? $_GET['track_source'] : VALUE_NOT_SET;
		Yii::log( "from get request ".$trackSource);
		$isTracking =0;
		//$latitude=isset($_REQUEST['latitude'])?$_REQUEST['latitude']:"";
        //$longitude=isset($_REQUEST['longitude'])?$_REQUEST['longitude']:"";
        //$locDetails=MyUtils::getAddressFromLatLon($latitude,$longitude);

        if($termType == TERM_CATEGORY){
        $redirectUrl = BlogUtility::getRedirectUrl();
        $redirectUrl = str_ireplace("/category","",$redirectUrl);
        $this->redirect($redirectUrl,true,301);
        }

		try{

			/*Added by jiji on 27-04-2017
			This is storing the track details in session, because, we have to pass those redirection info to the client side. So from the source url, this api will store the track info in session and after redirection, it will takethe same from session and clear the session */
			if(trim($trackSource)==VALUE_NOT_SET) {
				Yii::log("inside NEWS_LETTER_TRACKING if ..");
				if(isset(Yii::app()->session['track_source'])) {
					$trackSource = Yii::app()->session['track_source'];
					unset(Yii::app()->session['track_source']);
				}
			}
			else{
				Yii::log("else inside NEWS_LETTER_TRACKING ...");
				if($trackSource==NEWS_LETTER_TRACKING) {
					Yii::app()->session['track_source'] = $trackSource;
					$isTracking =1;
				}

			}


			$termType = strtolower($termType);
			$termSlug = strtolower($termSlug);

			$pageData['layoutData']['projectBaseUrl'] = "https://".$_SERVER['HTTP_HOST'].Yii::app()->baseUrl;

			//Get the categories
			$categories = BlogUtility::getCategories();
			if(!empty($categories)){
				$pageData['layoutData']['categories'] = $categories;
			}

			//Setup the offset as per the page number
			if ($pageNo !=VALUE_NOT_SET){
				$offset = ($pageNo - 1) * LIST_PAGE_POST_LIMIT;
			}

			$pageText = " Page ".$pageNo;

			//Configuring the parameters as per the requested page
			if($termType == VALUE_NOT_SET && $pageNo == VALUE_NOT_SET && $searchInput == VALUE_NOT_SET){ //Blog Home Page
				$pageType = BLOG_HOME_PAGE;
				$limit = HOME_PAGE_POST_LIMIT;
				$selectedView = HOME_PAGE_VIEW;
				$pageName = BLOG_HOME_PAGE;
			}
			else if($termType == VALUE_NOT_SET && $pageNo != VALUE_NOT_SET && $searchInput == VALUE_NOT_SET){ //Blog List Page
				$pageType = BLOG_LIST_PAGE;
				$limit = LIST_PAGE_POST_LIMIT;
				$selectedView = LIST_PAGE_VIEW;
				$pageTitleValue = BLOG_DEFAULT_PAGE_TITLE.$pageText;
				$pageDescription = BLOG_DEFAULT_PAGE_DESCRIPTION.$pageText;
				$pageName = BLOG_LIST_PAGE.' '.$pageNo;
			}
			/*
			else if($termType !=VALUE_NOT_SET && $termType == TERM_CATEGORY && $pageNo == VALUE_NOT_SET){ //Category Home Page
				$pageType = CATEGORY_HOME_PAGE;
				$limit = HOME_PAGE_POST_LIMIT;
				$selectedView = HOME_PAGE_VIEW;
				$termInfo= BlogUtility::getTermInfoFromName(TERM_CATEGORY,$termSlug,$categories);
				if(!empty($termInfo)){
					$termId = $termInfo['termId'];
					$termName = $termInfo['termName'];
					$termSlug = $termInfo['termSlug'];
					$pageData ['layoutData']['selectedTerm'] = array('selectedTermId' => $termId,'selectedTermName' => $termName,'selectedTermSlug' => $termSlug,);
				}
				else{
					$isValidException = true;
					throw new CHttpException(404,$pageType.' - The specified category does not exist.');
				}
				//$pageTitleValue = $termName.CATEGORY_TITLE_TEXT;
				//$pageDescription = str_replace(CATEGORY_DESCRIPTION_MARKER,$termName,CATEGORY_DESCRIPTION_TEXT);
				$pageName = CATEGORY_HOME_PAGE.' - '.BlogUtility::processTermName($termName);
				$pageTitleValue = $GLOBALS['meta_title'][$termSlug];
				$pageDescription = $GLOBALS['meta_description'][$termSlug];

			}
			else if($termType !=VALUE_NOT_SET && $termType == TERM_CATEGORY && $pageNo != VALUE_NOT_SET){ //Category List Page
				$pageType = CATEGORY_LIST_PAGE;
				$limit = LIST_PAGE_POST_LIMIT;
				$selectedView = LIST_PAGE_VIEW;
				$termInfo= BlogUtility::getTermInfoFromName(TERM_CATEGORY,$termSlug,$categories);
				if(!empty($termInfo)){
					$termId = $termInfo['termId'];
					$termName = $termInfo['termName'];
					$termSlug = $termInfo['termSlug'];
					$pageData ['layoutData']['selectedTerm'] = array('selectedTermId' => $termId,'selectedTermName' => $termName,'selectedTermSlug' => $termSlug,);
				}
				else{
					$isValidException = true;
					throw new CHttpException(404,$pageType.' - The specified category does not exist.');
				}
				$pageTitleValue = $termName.CATEGORY_TITLE_TEXT.$pageText;
				$pageDescription = str_replace(CATEGORY_DESCRIPTION_MARKER,$termName,CATEGORY_DESCRIPTION_TEXT).$pageText;
				$pageName = CATEGORY_LIST_PAGE.' '.$pageNo.' - '.BlogUtility::processTermName($termName);
			}
			*/
			else if($termType !=VALUE_NOT_SET && $termType == TERM_TAG && $pageNo == VALUE_NOT_SET){ //Tag List Page 1 without page no.
				$pageType = TAG_LIST_PAGE;
				$selectedView = LIST_PAGE_VIEW;
				$limit = LIST_PAGE_POST_LIMIT;
				$pageNo = DEFAULT_PAGE_NUMBER;
				$termInfo = BlogUtility::getTermInfoFromName(TERM_TAG,$termSlug,NULL);
				if(!empty($termInfo)){
					$termId = $termInfo['termId'];
					$termName = $termInfo['termName'];
					$termSlug = $termInfo['termSlug'];
					$pageData ['layoutData']['selectedTerm'] = array('selectedTermId' => $termId,'selectedTermName' => $termName,'selectedTermSlug' => $termSlug,);
				}
				else{
					$isValidException = true;
					throw new CHttpException(404,$pageType.' - The specified tag does not exist.');
				}
				$pageTitleValue = $termName.TAG_TITLE_TEXT;
				$pageDescription = str_replace(TAG_DESCRIPTION_MARKER,$termName,TAG_DESCRIPTION_TEXT);
				$pageName = TAG_LIST_PAGE.' '.$pageNo.' - '.BlogUtility::processTermName($termName);
			}
			else if($termType !=VALUE_NOT_SET && $termType == TERM_TAG && $pageNo != VALUE_NOT_SET){ //Tag List Page with page no.
				$pageType = TAG_LIST_PAGE;
				$selectedView = LIST_PAGE_VIEW;
				$limit = LIST_PAGE_POST_LIMIT;
				$termInfo = BlogUtility::getTermInfoFromName(TERM_TAG,$termSlug,NULL);
				if(!empty($termInfo)){
					$termId = $termInfo['termId'];
					$termName = $termInfo['termName'];
					$termSlug = $termInfo['termSlug'];
					$pageData ['layoutData']['selectedTerm'] = array('selectedTermId' => $termId,'selectedTermName' => $termName,'selectedTermSlug' => $termSlug,);
				}
				else{
					$isValidException = true;
					throw new CHttpException(404,$pageType.' - The specified tag does not exist.');
				}
				$pageTitleValue = $termName.TAG_TITLE_TEXT.$pageText;
				$pageDescription = str_replace(TAG_DESCRIPTION_MARKER,$termName,TAG_DESCRIPTION_TEXT).$pageText;
				$pageName = TAG_LIST_PAGE.' '.$pageNo.' - '.BlogUtility::processTermName($termName);
			}
			else if($searchInput !=VALUE_NOT_SET && $termType == VALUE_NOT_SET && $pageNo != VALUE_NOT_SET && !$isPsuedoSearchListPage){ //Search List Page with page no.
				$pageType = SEARCH_LIST_PAGE;
				$selectedView = LIST_PAGE_VIEW;
				$limit = LIST_PAGE_POST_LIMIT;
				$pageTitleValue = str_replace(SEARCH_TITLE_MARKER,$searchInput,SEARCH_TITLE_TEXT).$pageText;
				$pageDescription = SEARCH_DESCRIPTION_TEXT;
				$pageName = SEARCH_LIST_PAGE.' '.$pageNo.' - '.BlogUtility::processTermName($searchInput);
			}
			else if($searchInput !=VALUE_NOT_SET && $termType == VALUE_NOT_SET && $pageNo == VALUE_NOT_SET && !$isPsuedoSearchListPage){ //Search List Page without page no.
				$pageType = SEARCH_LIST_PAGE;
				$selectedView = LIST_PAGE_VIEW;
				$limit = LIST_PAGE_POST_LIMIT;
				$pageNo = DEFAULT_PAGE_NUMBER;
				$pageTitleValue = str_replace(SEARCH_TITLE_MARKER,$searchInput,SEARCH_TITLE_TEXT);
				$pageDescription = SEARCH_DESCRIPTION_TEXT;
				$pageName = SEARCH_LIST_PAGE.' '.$pageNo.' - '.BlogUtility::processTermName($searchInput);
			}
			else if($searchInput !=VALUE_NOT_SET && $termType == VALUE_NOT_SET && $pageNo != VALUE_NOT_SET && $isPsuedoSearchListPage){ //Psuedo Search List Page with page no.
				$pageType = PSEUDO_SEARCH_LIST_PAGE;
				$selectedView = LIST_PAGE_VIEW;
				$limit = LIST_PAGE_POST_LIMIT;
				$pageTitleValue = str_replace(SEARCH_TITLE_MARKER,$searchInput,SEARCH_TITLE_TEXT).$pageText;
				$pageDescription = SEARCH_DESCRIPTION_TEXT;
				$pageName = PSEUDO_SEARCH_LIST_PAGE.' '.$pageNo.' - '.BlogUtility::processTermName($searchInput);
			}
			else if($searchInput !=VALUE_NOT_SET && $termType == VALUE_NOT_SET && $pageNo == VALUE_NOT_SET && $isPsuedoSearchListPage){ //Psuedo Search List Page without page no.
				$pageType = PSEUDO_SEARCH_LIST_PAGE;
				$selectedView = LIST_PAGE_VIEW;
				$limit = LIST_PAGE_POST_LIMIT;
				$pageNo = DEFAULT_PAGE_NUMBER;
				$pageTitleValue = str_replace(SEARCH_TITLE_MARKER,$searchInput,SEARCH_TITLE_TEXT);
				$pageDescription = SEARCH_DESCRIPTION_TEXT;
				$pageName = PSEUDO_SEARCH_LIST_PAGE.' '.$pageNo.' - '.BlogUtility::processTermName($searchInput);
			}
			$redirectUrl = BlogUtility::getRedirectUrl();
			$requestUrl = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			Yii::log("Request URL ".$requestUrl);
			Yii::log("Redirect URL ".$redirectUrl);

			if($redirectUrl != $requestUrl){
				Yii::log(" reuest and redirect are not equal 111 ".$isTracking);
				if($isTracking ==1) {
					Yii::log("inside else if 301 for category list 113.... ".$redirectUrl." - ".$requestUrl);
					$urlArray = explode("?", $requestUrl);
					$this->redirect($urlArray[0]."/",true,301);
				}
				else{
					$this->redirect($redirectUrl,true,301);
				}


			}


			$pageData['layoutData']['trackSource'] = $trackSource;
			//Get the Page Title and Description
			$pageData['layoutData']['pageName'] = $pageName;
			$pageData['layoutData']['pageTitleValue'] = $pageTitleValue;
			$pageData['layoutData']['pageDescription'] = $pageDescription;

			//Get the canonical URL
			$pageData['layoutData']['pageCanonicalUrl'] = BlogUtility::getCanonicalUrl($pageType,$searchInput,$pageNo);

			//Fetch the list of posts as per the requested page.
			if($pageType == SEARCH_LIST_PAGE || $pageType == PSEUDO_SEARCH_LIST_PAGE){
				$pageData['layoutData']['searchInput'] = $searchInput;
				$recentPostList = BlogUtility::getPostsBySearch($searchInput,$offset,$limit);
				$totalNumberOfPosts = BlogUtility::getPostsBySearchCount($searchInput,$offset,$limit);
			}
			else{
				$recentPostList = BlogUtility::getPostList($termId,$termType,DEFAULT_ID,$offset,$limit);
				$totalNumberOfPosts = BlogUtility::getPostListCount($termId,$termType,DEFAULT_ID,$offset,$limit);
			}


			if(!empty($recentPostList)){
				//Check whether a view all option is required in the blog/category home pages
				$pageData['showViewAll'] = false;
				if($totalNumberOfPosts > HOME_PAGE_POST_LIMIT){
					$showViewAll = true;
					$pageData['showViewAll'] = $showViewAll;
				}
				$processedRecentPostList = BlogUtility::processRecentPostList($recentPostList,$pageType);
				$pageData['recentPostList'] = $processedRecentPostList;
				if($pageType == BLOG_LIST_PAGE || $pageType == CATEGORY_LIST_PAGE || $pageType == TAG_LIST_PAGE || $pageType == SEARCH_LIST_PAGE || $pageType == PSEUDO_SEARCH_LIST_PAGE){
					$name = NULL;
					if($pageType == CATEGORY_LIST_PAGE || $pageType == TAG_LIST_PAGE){
						$name = $termName;
					}
					else if($pageType == SEARCH_LIST_PAGE || $pageType == PSEUDO_SEARCH_LIST_PAGE) {
						$name = $searchInput;
						$pageData['searchInput'] = $searchInput;
					}
					//Get the post count heading for list pages
					$pageData['listHeading'] = BlogUtility::getListHeading($totalNumberOfPosts,$pageType,$name);

					//Get the base pagination URL
					$paginationBaseUrl = BlogUtility::getPaginationBaseUrl($pageType, $termType, $termSlug, $searchInput);

					//Get the URL suffix to be used in the pagination URLs
					$pageUrlSuffix = "/";
					if($pageType == SEARCH_LIST_PAGE) {
						$pageUrlSuffix = $pageUrlSuffix."?s=".$searchInput;
					}

					//Get the pagination elements
					$paginationElements = BlogUtility::getPaginationElements($pageType,$pageNo,$totalNumberOfPosts,$paginationBaseUrl,$pageUrlSuffix);
					if(empty($paginationElements)){
						Yii::log(" We have only one page!!! No pagination.");
					}
					/*else{
						// Uncomment this else condition as it is only used for logging.
						$str="";
						foreach($paginationElements as $elements){
							$str = $str.$elements['pageNo']."/";
							Yii::log("Page Url is ".$elements['pageUrl']);
						}
						Yii::log("Current >>> ".$pageNo." / Pagination strip >>> ".$str);
					}*/
					$pageData['paginationElements'] = $paginationElements;
					$pageData['currentPage'] = $pageNo;
					$paginatonSeoUrls = BlogUtility::getNextPrevUrls($pageType, $termType, $termSlug, $searchInput,$pageNo,$totalNumberOfPosts);
					$pageData['layoutData']['paginationSeoUrls'] = $paginatonSeoUrls;
				}
			}
			//Yii::log("Page type is ".$pageType);
		}
		catch(Exception $ex) {
			Yii::log('Failed to list posts','info','application.actionIndex');
			Yii::log("exception ".$ex->getMessage());
			if($isValidException){
				throw new CHttpException(404,$pageType.' - 404 happened!!!');
			}
			else{
				MedinfiExceptionNotifier::notifyException($ex);
			}
		}

		//Render the view as per the requested page.
		if(!empty($pageData)){
			$this->render($selectedView,array("pageData" =>$pageData));
		}
		else{
            $this->render($selectedView);
        }
	}


	/* Nitish Jha
	   19-07-2017
	   Blog url shortening
    */

	public function actionGetBlogPostDetails(){

	  		$url = HTTP.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            Yii::app()->session['BLOGURL'] = $url ;
            Yii::app()->session['BLOGLOGIN_LOGOUT'] = "false";

	        $this->layout = BLOG_MAIN_TEMPLATE;
            $blogPostCategorySlug = isset($_GET['category_slug']) ? $_GET['category_slug'] : VALUE_NOT_SET;
    		$blogPostYear = isset($_GET['year']) ? $_GET['year'] : VALUE_NOT_SET;
    		$blogPostMonth = isset($_GET['month']) ? $_GET['month'] : VALUE_NOT_SET;
    		$blogPostDay = isset($_GET['day']) ? $_GET['day'] : VALUE_NOT_SET;
    		$postSlug = isset($_GET['post_slug']) ? $_GET['post_slug'] : VALUE_NOT_SET;
    		$unsubscribeEmail = isset($_GET['unsubscribe']) ? $_GET['unsubscribe'] : VALUE_NOT_SET;
    		$trackSource = isset($_GET['track_source']) ? $_GET['track_source'] : VALUE_NOT_SET;

    		$pageNo = isset($_GET['page_no']) ? $_GET['page_no'] : VALUE_NOT_SET;
    		$isTracking =0;
    		$searchInput = VALUE_NOT_SET;
    		$offset = DEFAULT_POST_OFFSET;
            $limit = DEFAULT_POST_LIMIT;

    		$isValidException = false;
    		$redirectUrl = '';
    		$doRedirection = false;
    		$postDetailFromOtherSourcesFlag = false;


        if($postSlug == 'scoliosis­-causes-symptoms-facts' || $postSlug =='scoliosis%c2%ad-causes-symptoms-facts'){
        	$postSlug = 'scoliosis-causes-symptoms-facts';
        }
    		try{
    			/*Added by jiji on 27-04-2017
    			This is storing the track details in session, because, we have to pass those redirection info to the client side. So from the source url, this api will store the track info in session and after redirection, it will take the same from session and clear the session */
    			if(trim($trackSource) == VALUE_NOT_SET) {
    				Yii::log("inside NEWS_LETTER_TRACKING if ..");
    				if(isset(Yii::app()->session['track_source'])) {
    					$trackSource = Yii::app()->session['track_source'];
    					unset(Yii::app()->session['track_source']);
    				}
    			}
    			else{
    				Yii::log("else inside NEWS_LETTER_TRACKING ...");
    				Yii::app()->session['track_source'] = $trackSource;
    				$isTracking =1;
    			}

    			$pageData['layoutData']['projectBaseUrl'] = "https://".$_SERVER['HTTP_HOST'].Yii::app()->baseUrl;

                //Get the categories
    			$categories = BlogUtility::getCategories();
    			if(!empty($categories)){
                    $pageData['layoutData']['categories'] = $categories;
                }
    			$termInfo= BlogUtility::getTermInfoFromName(TERM_CATEGORY,$postSlug,$categories);
                if(!empty($termInfo)){
                    Yii::log("inside this check".json_encode($termInfo));
                    //Setup the offset as per the page number
                    if ($pageNo !=VALUE_NOT_SET){
                        $offset = ($pageNo - 1) * LIST_PAGE_POST_LIMIT;
                    }

                    $pageText = " Page ".$pageNo;

                    $termId = $termInfo['termId'];
                    $termName = $termInfo['termName'];
                    $termSlug = $termInfo['termSlug'];
                    $pageData ['layoutData']['selectedTerm'] = array('selectedTermId' => $termId,'selectedTermName' => $termName,'selectedTermSlug' => $termSlug,);

                    if($pageNo != VALUE_NOT_SET){
                    $pageType = CATEGORY_LIST_PAGE;
                    $limit = LIST_PAGE_POST_LIMIT;
                    $selectedView = LIST_PAGE_VIEW;

                   //$pageTitleValue = $termName.CATEGORY_TITLE_TEXT.$pageText;
                   //$pageDescription = str_replace(CATEGORY_DESCRIPTION_MARKER,$termName,CATEGORY_DESCRIPTION_TEXT).$pageText;
                   $pageName = CATEGORY_LIST_PAGE.' '.$pageNo.' - '.BlogUtility::processTermName($termName);
                   $pageTitleValue = $GLOBALS['meta_title'][$termSlug].$pageText;
                   $pageDescription = $GLOBALS['meta_description'][$termSlug].$pageText;

                    }
                    else{
                    $pageType = CATEGORY_HOME_PAGE;
                    $limit = HOME_PAGE_POST_LIMIT;
                    $selectedView = HOME_PAGE_VIEW;

                    //$pageTitleValue = $termName.CATEGORY_TITLE_TEXT;
                    //$pageDescription = str_replace(CATEGORY_DESCRIPTION_MARKER,$termName,CATEGORY_DESCRIPTION_TEXT);
                    $pageName = CATEGORY_HOME_PAGE.' - '.BlogUtility::processTermName($termName);
                    $pageTitleValue = $GLOBALS['meta_title'][$termSlug];
                    $pageDescription = $GLOBALS['meta_description'][$termSlug];
                    }


    				//Get tags
    				//$categoryTags = BlogUtility::getPopularTags($termId);
    				$pageData['categoryTags'] = "";

                    $redirectUrl = BlogUtility::getRedirectUrl();
                    $requestUrl = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    Yii::log("Request URL ".$requestUrl);

                    if($redirectUrl != $requestUrl){
                        Yii::log(" reuest and redirect are not equal 111 ".$isTracking);
                        if($isTracking ==1) {
                            Yii::log("inside else if 301 for category list 113.... ".$redirectUrl." - ".$requestUrl);
                            $urlArray = explode("?", $requestUrl);
                            $this->redirect($urlArray[0]."/",true,301);
                        }
                        else{
                            $this->redirect($redirectUrl,true,301);
                        }
                    }

                    $pageData['layoutData']['trackSource'] = $trackSource;
                    //Get the Page Title and Description
                    $pageData['layoutData']['pageName'] = $pageName;
                    $pageData['layoutData']['pageTitleValue'] = $pageTitleValue;
                    $pageData['layoutData']['pageDescription'] = $pageDescription;

                    //Get the canonical URL
                    $pageData['layoutData']['pageCanonicalUrl'] = BlogUtility::getCanonicalUrl($pageType,$searchInput,$pageNo);

                    $termType = 'category';

                    //Fetch the list of posts as per the requested page.
                    $recentPostList = BlogUtility::getPostList($termId,$termType,DEFAULT_ID,$offset,$limit);
                    $totalNumberOfPosts = BlogUtility::getPostListCount($termId,$termType,DEFAULT_ID,$offset,$limit);


                    if(!empty($recentPostList)){
                        //Check whether a view all option is required in the blog/category home pages
                        $pageData['showViewAll'] = false;
                        if($totalNumberOfPosts > HOME_PAGE_POST_LIMIT){
                            $showViewAll = true;
                            $pageData['showViewAll'] = $showViewAll;
                        }
                        $processedRecentPostList = BlogUtility::processRecentPostList($recentPostList,$pageType);
                        $pageData['recentPostList'] = $processedRecentPostList;
                        if($pageType == BLOG_LIST_PAGE || $pageType == CATEGORY_LIST_PAGE || $pageType == TAG_LIST_PAGE || $pageType == SEARCH_LIST_PAGE || $pageType == PSEUDO_SEARCH_LIST_PAGE){
                            $name = NULL;
                            if($pageType == CATEGORY_LIST_PAGE || $pageType == TAG_LIST_PAGE){
                                $name = $termName;
                            }
                            else if($pageType == SEARCH_LIST_PAGE || $pageType == PSEUDO_SEARCH_LIST_PAGE) {
                                $name = $searchInput;
                                $pageData['searchInput'] = $searchInput;
                            }
                            //Get the post count heading for list pages
                            $pageData['listHeading'] = BlogUtility::getListHeading($totalNumberOfPosts,$pageType,$name);

                            //Get the base pagination URL
                            $paginationBaseUrl = BlogUtility::getPaginationBaseUrl($pageType, $termType, $termSlug, $searchInput);

                            //Get the URL suffix to be used in the pagination URLs
                            $pageUrlSuffix = "/";
                            if($pageType == SEARCH_LIST_PAGE) {
                                $pageUrlSuffix = $pageUrlSuffix."?s=".$searchInput;
                            }

                            //Get the pagination elements
                            $paginationElements = BlogUtility::getPaginationElements($pageType,$pageNo,$totalNumberOfPosts,$paginationBaseUrl,$pageUrlSuffix);
                            if(empty($paginationElements)){
                                Yii::log(" We have only one page!!! No pagination.");
                            }

                            $pageData['paginationElements'] = $paginationElements;
                            $pageData['currentPage'] = $pageNo;
                            $paginatonSeoUrls = BlogUtility::getNextPrevUrls($pageType, $termType, $termSlug, $searchInput,$pageNo,$totalNumberOfPosts);
                            $pageData['layoutData']['paginationSeoUrls'] = $paginatonSeoUrls;
                        }
                    }

                            if(!empty($pageData)){
                    			$this->render($selectedView,array("pageData" =>$pageData));
                    			return;
                    		}

                }


    			$pageType = BLOG_POST_PAGE;
    			$pageTitleValue = BLOG_DEFAULT_PAGE_TITLE;
    			$pageDescription = BLOG_DEFAULT_PAGE_DESCRIPTION;

    			//Yii::log("Post slug is ".$postSlug);

    			//Get the categories
    			/* $categories = BlogUtility::getCategories();
    			if(!empty($categories)){
    				$pageData['layoutData']['categories'] = $categories;
    			} */

    			//Get the post details
    			$postDetails = BlogUtility::getPostDetails($postSlug);
    			if(empty($postDetails)){
    				$postDetails = BlogUtility::getPostDetailsFromOtherSources($postSlug);
    				$postDetailFromOtherSourcesFlag = true;
    			}
    			if(!empty($postDetails)){
    			$postCategorySlug = $postDetails['postCategorySlug'];
    				if((!empty($blogPostCategorySlug && $blogPostYear && $blogPostMonth && $blogPostDay)) || $postDetailFromOtherSourcesFlag){
    					$redirectUrl = $postDetails['postCanonicalUrl'];
    					$doRedirection = true;
    				}
    				else if(BlogUtility::needWwwOrTrailingSlash()){
    					$redirectUrl = $postDetails['postCanonicalUrl'];
    					$doRedirection = true;
    				}
    				else{
    					$pageData ['postDetails'] = $postDetails;
    				}
    			}
    			else{
    				$isValidException = true;
    				throw new CHttpException(404,$pageType.' - The specified post and published_on date combination does not exist');
    			}

    			if($doRedirection){
    				Yii::log("About to redirect to ".$redirectUrl);
    				$this->redirect($redirectUrl,true,301);
    			}
    			else{
    				$postId = $pageData['postDetails']['id'];
    				$pageData ['layoutData']['postId'] = $postId;
    				$postTagList = BlogUtility::getTagsForPost($postId);
    				if(!empty($postTagList)){
    					$pageData ['postTagList'] = $postTagList;
    				}
    				$pageTitleValue = $postDetails['postTitle'].POST_TITLE_TEXT;
    				$pageDescription = BlogUtility::getPostMetaDescription($postId);
    				$pageName = BLOG_POST_PAGE.' - '.BlogUtility::processTermName($postDetails['postTitle']);
    				/* This is for fetching the blog comments which are approved, When we will do user login , we can pass the user email is the email of the user who is already login*/
    				$postComments = BlogUtility::getPostComments($postId,$userEmail="");
    				$pageData ['commentCount'] = count($postComments);
    				$pageData ['postComments'] = $postComments;
    				//Set the category to which the post belongs to.
    				$categoryInfo = BlogUtility::getTermInfoFromName(TERM_CATEGORY,$postCategorySlug,$categories);


    				if(!empty($categoryInfo)){
    					$categoryId = $categoryInfo['termId'];
    					$categoryName = $categoryInfo['termName'];
    					$categorySlug = $categoryInfo['termSlug'];
    					$pageData ['layoutData']['selectedTerm'] = array('selectedTermId' => $categoryId, 'selectedTermName' => $categoryName, 'selectedTermSlug' => $categorySlug, 'selectedTermUnsubscribe'=>$unsubscribeEmail,'trackSource'=>$trackSource);
    				}
    				else{
    					$categoryId = NULL;
    					//$isValidException = true;
    					throw new CHttpException(404,$pageType.' - The specified category does not exist');
    				}

			        //Get the 3 most popular posts for the category to which the post belongs to.
    				$popularPostList = BlogUtility::getPopularPostList($categoryId,$postId);
    				$processedPopularPostList = BlogUtility::processPopularPostList($popularPostList,CATEGORY_LIST_PAGE);
    				$pageData ['popularPostList'] = $processedPopularPostList;

			        //Get the 3 most recent posts for the category to which the post belongs to.
    				$recentPostList = BlogUtility::getPostList($categoryId,TERM_CATEGORY,$postId,POST_DETAIL_PAGE_POST_OFFSET,POST_DETAIL_PAGE_POST_LIMIT);
    				$processedPostList = BlogUtility::processRecentPostList($recentPostList,CATEGORY_LIST_PAGE);
    				$pageData ['recentPostList'] = $processedPostList;
    				$pageData ['layoutData']['pageTitleValue'] = $pageTitleValue;
    				$pageData ['layoutData']['pageDescription'] = $pageDescription;
    				$pageData ['layoutData']['pageName'] = $pageName;
    				//Get the canonical URL
    				//$pageData['layoutData']['pageCanonicalUrl'] = BlogUtility::getCanonicalUrl($pageType,null,null);
    				$pageData['layoutData']['pageCanonicalUrl'] = $pageData['postDetails']['postCanonicalUrl'];
    				$pageData['layoutData']['pageShareUrl'] = $pageData['postDetails']['postShareUrl'];
    				$pageData['layoutData']['postFbImageUrl'] = $pageData['postDetails']['postImage'];
    				Yii::log ($pageData['layoutData']['postFbImageUrl']);


    				try {
                           Yii::log("ABOVE MY CODE ..");
                                 if(!isset($_SESSION))
                                    {
                                        session_start();
                                    }
                                $session_id = session_id();

    		                    $userId = "";
    		                    if(isset(Yii::app()->session['userId'])){
    		                          $userId =  Yii::app()->session['userId'];
    		                          $userLogin = "TRUE";
    		                    }
    		                    else{
    		                    $userId = "";
    		                      $userLogin = "FALSE";
    		                    }
    		                    $locality = Yii::app()->session['locality'];
                                $sublocality = Yii::app()->session['sublocality'];
                                $fullAddress = Yii::app()->session['fullAddress'];
                                $server_processtime = Yii::app()->session['get_address_server_processtime'];

                               if($locality !=""){
                                    $fetchLocation = "YES";
                                }else{
                                   $fetchLocation = "NO";
                                }


                                //check for readmore option to show or not to show
                                if($locality =="Pune" && $userId==""){
                                    $readMoreOption = "TRUE";
                                }
                                else {
                                    $readMoreOption = "FALSE";
                                }

                                $pageData['userCurrentLocation'] = array('userLogin'=> $userLogin,'fetchLocation'=> $fetchLocation,'userId' => $userId, 'userLocality' => $locality, 'userSubLocality' => $sublocality, 'userFullAddress' => $fullAddress, 'serverProcessTime'=>$server_processtime, 'readMoreOption' => $readMoreOption);


                             //check for category and session to show survey form or not
                             if($categoryInfo['termSlug'] == "womens-health" || $categoryInfo['termSlug'] == "health-conditions"){
                             $primeSurvey = PrimeSurvey::model()->findByAttributes(array('session_id'=>$session_id));
                             if($primeSurvey){
                             $pageData ['primeSurveyDetail']['isSurveyEnable'] = "FALSE";
                             }
                             else{
                             $pageData ['primeSurveyDetail']['isSurveyEnable'] = "TRUE";
                             }
                             }
                             else{
                             $pageData ['primeSurveyDetail']['isSurveyEnable'] = "FALSE";
                             }

                             $pageData ['primeSurveyDetail']['sessionId'] = $session_id;

                             Yii::log("my session ID". $pageData ['primeSurveyDetail']['sessionId'] );
                             Yii::log("my session ID". $pageData ['primeSurveyDetail']['isSurveyEnable'] );
                             Yii::log("Session variable stored successful,Status : Lat/Long : ".$locality."/".$sublocality."/".$fullAddress);
                    		}
                    		catch(Exception $ex) {
                                Yii::log('Failed to get Prime Survey details','info','application.actionGetBlogPostDetails');
                    		}

    			}

    			//Check block ads
    			$block = BlogUtility::checkIfBlockAds($postId);
                    $pageData['additional']['block'] = $block['hideAds'];
                    $pageData['additional']['imgClickLink'] = $block['link'];
                   // Yii::log("Block: ". $block);

                    if($block)
                    {
                        $author= BlogUtility::getAuthorName($postId);
                        $pageData['additional']['author'] = $author;
                        Yii::log("AUTHOR: ". $author);
                    }

    		}
		catch(Exception $ex) {
			Yii::log('Failed to get blog post details','info','application.actionGetBlogPostDetails');
			Yii::log("exception ".$ex->getMessage());
			if($isValidException){
				throw new CHttpException(404,$pageType.' - 404 happened!!!');
			}
			else{
				MedinfiExceptionNotifier::notifyException($ex);
			}
		}
    		if(!empty($pageData)){
    			$this->render(POST_DETAIL_PAGE_VIEW,array("pageData" =>$pageData));
    		}
    		else{
                $this->render(POST_DETAIL_PAGE_VIEW);
            }

	}

	public function actionGetAutoSuggestions(){
		$searchInput=isset($_GET['term'])?$_GET['term']:"";
		$offset = AUTO_SUGGESTION_OFFSET;
		$limit = AUTO_SUGGESTION_LIMIT;
		$processedRecentPostList = array();
		Yii::log("Inside get auto-suggestions >>>> ".$searchInput);
		try{
			if(!empty("searchInput")){
				$searchPostList = BlogUtility::getPostsBySearch($searchInput,$offset,$limit);
				$pageType = AUTO_SUGGESTION_LIST;
				$processedRecentPostList = array();
				if(!empty($searchPostList)){
					$processedRecentPostList = BlogUtility::processRecentPostList($searchPostList,$pageType);
				}
			}
		}
		catch(Exception $ex) {
			Yii::log('Failed to get auto-suggestions','info','application.actionGetAutoSuggestions');
			Yii::log("exception ".$ex->getMessage());
            MedinfiExceptionNotifier::notifyException($ex);
		}
		BlogUtility::jencode($processedRecentPostList);

	}
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError(){

	    $url = HTTP.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        Yii::app()->session['BLOGURL'] = $url ;
        Yii::app()->session['BLOGLOGIN_LOGOUT'] = "false";

		$this->layout = BLOG_MAIN_TEMPLATE;
		$pageType = BLOG_ERROR_PAGE;
		$pageName = BLOG_ERROR_PAGE;
		$pageData = array();
		$pageData['layoutData']['projectBaseUrl'] = "https://".$_SERVER['HTTP_HOST'].Yii::app()->baseUrl;
		$categories = BlogUtility::getCategories();
		if(!empty($categories)){
			$pageData['layoutData']['categories'] = $categories;
		}

		$pageData['layoutData']['pageTitleValue'] = ERROR_PAGE_TITLE;
		$pageData['layoutData']['pageCanonicalUrl'] = BlogUtility::getCanonicalUrl($pageType,null,null);
		$pageData['layoutData']['pageName'] = $pageName;
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', array("pageData" => $pageData));
		}
	}

    /*Created By Jiji on Feb 2017
    	This is for inserting a comment to a particular post */

    public function actionAddCommentToPost(){
    	Yii::log('Begin inserting blog comment', 'info', 'application.actionInsertBlogComment');
		$res = array();
		$commentInfo = array();
		$postId = isset($_REQUEST['postId']) ? $_REQUEST['postId'] : '';
		$comment = isset($_REQUEST['comment']) ? $_REQUEST['comment'] : '';
		$commenterName = isset($_REQUEST['commenterName']) ? $_REQUEST['commenterName'] : '';
		$commenterEmail = isset($_REQUEST['commenterEmail']) ? $_REQUEST['commenterEmail'] : '';
		$parentCommentId = isset($_REQUEST['parentCommentId']) ? $_REQUEST['parentCommentId'] : 0;
		Yii::log($postId."/".$comment."/".$commenterName."/".$commenterEmail."/".$parentCommentId);
		try{
			$commentInfo['postId'] = $postId;
			$commentInfo['comment'] = $comment;
			$commentInfo['commenterName'] = $commenterName;
			$commentInfo['commenterEmail'] = $commenterEmail;
			$commentInfo['parentCommentId'] = $parentCommentId;
			$commentStatus = BlogUtility::insertBlogComment($commentInfo);
			$res['commentStatus'] = $commentStatus;
			$res['status'] = 'success';
			$res['desc'] = 'Blog comment inserted successfully';
		}catch(Exception $ex) {
			Yii::log('Fail to insert blog comment','info','application.actionInsertBlogComment');
			$res['status']="Fail";
			$res['posts'] ="";
			$res['desc']="Fail to insert blog comment";
			Yii::log("exception ".$ex->getMessage());
            MedinfiExceptionNotifier::notifyException($ex);
		}

		BlogUtility::jencode($res);
    }
     /* Gopi Subscription Email Notification */
    public function actionBlogSubscriptionEmail(){
      		$email = isset($_POST['Email']) ? $_POST['Email'] : "";
      		$message;
      		$subject;
             try{
                   $subject = "Welcome to Medinfi";
                   $message='<span style="font-weight: bold;">Dear User, </span><br><br>';
                   $message.='Thank you for subscribing to Medinfi Newsletter.<br><br>';
                   $message.="Medinfi’s vision is to offer well research yet easy to understand information so that you can stay well informed and take right healthcare decisions for yourself, family, and friends.<br><br>";
                   $message.="We hope you enjoy reading our articles and share it with your family and friends.<br><br>";
                   $message.="Happy reading!!<br><br>";
                   $message.='<span style="font-weight: bold;">Thank You Again,<br> Team Medinfi</span><br><br>';
                   $message.='Disclaimer: This is an auto-generated email. Kindly do not reply to this message.<br><br>';
                   $message.='<div style="min-width:316px; font-size:10px;"><a href="'.HOST_NAME.'/medinfi/" style="color:#226EBF;text-decoration:none;">Medinfi.com</a>| <a href="'.HOST_NAME.'/medinfi/termscondition/" style="color:#226EBF;text-decoration:none;">Terms of Use</a>| <a href="'.HOST_NAME.'/medinfi/privacypolicy/" style="color:#226EBF;text-decoration:none;" >Privacy Policy</a>| <a href="'.HOST_NAME.'/blog/bipolar-disorder/?unsubscribe='.$email.'" style="color:#226EBF;text-decoration:none;" >Unsubscribe</a>|<a href="'.HOST_NAME.'/blog/" style="color:#226EBF;text-decoration:none;" >Blog</a> </div>';
                   $message .='</body></html>';
                   SendEmail::sendYiiMail($email,"no-reply@medinfi.com", 'Medinfi', $replyto='', $subject, $message,$cc='',$bcc='');
      		}catch(Exception $ex){
                   MedinfiExceptionNotifier::notifyException($ex);
      		}
      		 return;
    }
        /* Nitish Jha
           Blog Notification */
    public function actionBlogNotificationSubscription(){
      		$category_Id= isset($_REQUEST['categoryId']) ? $_REQUEST['categoryId'] : '';
      		$subscription_Plan= isset($_REQUEST['subscriptionPlan']) ? $_REQUEST['subscriptionPlan'] : '';
      		$email = isset($_REQUEST['subscriptionEmail']) ? $_REQUEST['subscriptionEmail']: '';
      		try{

      		   $emailBlog = BlogSubscription::model()->findByAttributes(array('recipient'=>$email));

      		   if($emailBlog){

      		        $emailBlog->category_id= $category_Id;
      		        $emailBlog->subscription_plan= $subscription_Plan;
      		        /* Added for Is_Subscribed state after unsubscription */
      		        $emailBlog->Is_subscribed= 1;
      		        $emailBlog->update();
      		      //  $res['blogSubscription'] = $blogSubscription;
                    $res['status'] = 'success';
                    $res['desc'] = 'Blog Notification Subscription inserted successfully';
      		   }else{

                   $blogNotificationSubscription['categoryID']= $category_Id;
                   $blogNotificationSubscription['subscription_plan'] = $subscription_Plan;
                   $blogNotificationSubscription['email_Id']= $email;
                   $blogSubscription = BlogUtility::insertBlogSubscription($blogNotificationSubscription);
                   $res['blogSubscription'] = $blogSubscription;
                   $res['status'] = 'success';
                   $res['desc'] = 'Blog Notification Subscription inserted successfully';
                   }
      		}catch(Exception $ex){

      		       Yii::log("exception ".$ex->getMessage());
                   MedinfiExceptionNotifier::notifyException($ex);
      		 }


      BlogUtility::jencode($res);
    }

    /*
        Browser Notification Subscribtion
        Nivetha Yadhavan
        29-01-2018
    */
    public function actionBrowserNotificationSubscription(){

        $category_Id= isset($_REQUEST['categoryId']) ? $_REQUEST['categoryId'] : '';
        $subscription_Plan= 'Daily';
        $recipient = isset($_REQUEST['recipient']) ? $_REQUEST['recipient']: '';
        Yii::log("Browser notification".$recipient);
        $res = array();
        try{

            $subscription = BlogSubscription::model()->findByAttributes(array('recipient'=>$recipient));

            if($subscription && $subscription->Is_subscribed==1){
                $res['blogSubscription'] = 1;
                $res['status'] = 'Success';
                $res['desc'] = 'Browser already subscribed for notification';
            }
            else{
                $new_subscription=new BlogSubscription();

                $new_subscription->recipient=$recipient;
                $new_subscription->subscription_plan=$subscription_Plan;
                $new_subscription->category_id=$category_Id;
                $new_subscription->Is_subscribed=1;
                $new_subscription->Is_first_time=0;
                $new_subscription->subscribed_date=date('Y-m-d H:i:s');
                $new_subscription->subscription_type='BROWSER';

                if($new_subscription->save()){
                   $res['blogSubscription'] = 1;
                   $res['status'] = 'Success';
                   $res['desc'] = 'Browser Notification Subscription inserted successfully';
                }
                else
                {
                    $res['blogSubscription'] = 0;
                    $res['status'] = 'Fail';
                    $res['desc'] = 'Browser Notification Subscription insertion failed';
                }
            }

        }
        catch(Exception $ex){
             Yii::log("exception ".$ex->getMessage());
             MedinfiExceptionNotifier::notifyException($ex);
        }

        BlogUtility::jencode($res);
    }

    /*
        Browser Notification Subscribtion
        Nivetha Yadhavan
        29-01-2018
    */

    public function actionGetBrowserNotificationMessage(){

        $payload = file_get_contents('php://input');
        $data = json_decode($payload);
        $recipient=$data->id;
        //$res=array();
        Yii::log("Get Browser Notification Message: ".$recipient);



        try{
            $strquery="Select * from med_notification where recipient='".$recipient."' and status='Sent' ORDER BY created_on DESC";
            $notif=Yii::app()->db->createCommand($strquery)->queryAll();
            $nid=$notif[0]['id'];
            $message=$notif[0]['content'];
            Yii::log("**");
            if($message)
            {
                Yii::log("--");
                $content=json_decode($message);
                $blogId=$content->blogId;
                $postDetails = BlogUtility::getPostDetails($blogId,true);
                if(!empty($postDetails)) {
                    $url = HTTP.$_SERVER['HTTP_HOST'].Yii::app()->baseUrl."/".$postDetails['postSlug']."/?track_source=browser_notification";
                }
                $model = Notification::model()->findByPk($nid);
                $model->status ='Delivered';
                $model->delivered_on = date('Y-m-d H:i:s');
                //echo '------------- status is updating  -----------';
                $model->update();
                $res['status'] = 'success';
                $res['message'] = $content->m;
                $res['title'] = $content->title;
                $res['url']=$url;
            }

        }catch(Exception $ex){
            Yii::log("exception ".$ex->getMessage());
            MedinfiExceptionNotifier::notifyException($ex);
        }

        //$res['status'] = 'success';
        //$res['content'] = 'content';
        BlogUtility::jencode($res);
    }

    /*
      Blog Notification Unsubscribe
      Nitish Jha
      13-04-2017
    */
    public function actionBlogNotificationUnsubscription(){

            $email = isset($_REQUEST['unsubscribeEmail']) ? $_REQUEST['unsubscribeEmail']: '';
            try{
            $emailBlog = BlogSubscription::model()->findByAttributes(array('recipient'=>$email));
            if($emailBlog){

                $emailBlog->Is_subscribed= 0;
                $emailBlog->Is_first_time= 0;
                $emailBlog->update();
                //  $res['blogSubscription'] = $blogSubscription;
                $res['status'] = 'success';
                $res['desc'] = 'Blog Notification User Unsubscribed successfully';
               }
            }catch(Exception $ex){
                Yii::log("exception ".$ex->getMessage());
                MedinfiExceptionNotifier::notifyException($ex);
            }
      BlogUtility::jencode($res);
    }

     /* Abhi 23-08-2017
        This API is used to track medinfi prime survey */
        public function actionPrimeSurvey(){

          		$session_id = isset($_REQUEST['session_id']) ? $_REQUEST['session_id'] : '';
          		$blog_category = isset($_REQUEST['blog_category']) ? $_REQUEST['blog_category'] : '';
          		$survey_question = isset($_REQUEST['survey_question']) ? $_REQUEST['survey_question'] : '';
          		$survey_answers = isset($_REQUEST['survey_answers']) ? $_REQUEST['survey_answers'] : '';


          		try{
          		   if($session_id != "" && $survey_question != "" && $survey_answers != ""){
                         $primeSurvey = new PrimeSurvey;

          		        $primeSurvey->session_id = $session_id;
          		        $primeSurvey->ip_address = BlogUtility::getUserIP();
          		        $primeSurvey->blog_category = $blog_category;
          		        $primeSurvey->survey_question = $survey_question;
          		        $primeSurvey->survey_answers = $survey_answers;
          		        $primeSurvey->submitted_on = date('Y-m-d H:i:s');

                         if($primeSurvey->save()){
                         $res['status'] = 'success';
                         $res['desc'] = 'Prime survey inserted successfully';
                          }
                          else {
                          $res['status'] = 'fail';
                          $res['desc'] = 'Something went wrong. Please try again';
                          }

          		   }else{
                       $res['status'] = 'fail';
                       $res['desc'] = 'Submit all the required details';
                       }
          		}catch(Exception $ex){

          		       Yii::log("exception ".$ex->getMessage());
                       MedinfiExceptionNotifier::notifyException($ex);
          		 }

          BlogUtility::jencode($res);
        }

     public function actionGetAddressFromLatLon(){
             Yii::log("Inside Adress from lat lon ");
            $latitude = isset($_REQUEST['latitude']) ? $_REQUEST['latitude'] : '';
            $longitude = isset($_REQUEST['longitude']) ? $_REQUEST['longitude'] : '';
            if($latitude !="" && $longitude!=""){
            $geolocation = $latitude.','.$longitude;
			$server_starttime = round(microtime(true) * 1000);
            $geolocation = $latitude.','.$longitude;
            $request='https://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&key='.GOOGLE_API_KEY;
            $file_contents = file_get_contents($request);
            $json_decode = json_decode($file_contents,true);
			//Add more checks try catch
            $status=$json_decode['status'];
            //Yii::log("GAPI Status ".$status);
			$locality="";
            $sublocality="";
			$fullAddress="";
			if($status=='OK'){
				//$result=$json_decode['results'][0];
				$results=$json_decode['results'];
				//$address_components=$result['address_components'];
                //$fullAddress=$result['formatted_address'];
				//  Yii::log("full address ".json_encode($fullAddress));
				foreach($results as $result){
				    //$fullAddress=$result['formatted_address'];
				    $address_components=$result['address_components'];
                    foreach($address_components as $item){ //foreach element in $arr
                            for($typeIndex=0;$typeIndex<sizeOf($item['types']);$typeIndex++){
                                    if($item['types'][$typeIndex]=="locality"){
                                            $locality = $item['long_name'];
                                            //Yii::log("loc ".json_encode($locality));
                                    }
                                    if($item['types'][$typeIndex]=="sublocality_level_1"){
                                            $sublocality = $item['long_name'];
                                            //Yii::log("sub 1 ".json_encode($sublocality));
                                    }
                                    else if($item['types'][$typeIndex]=="sublocality_level_2"){
                                            $sublocality = $item['long_name'];
                                            //Yii::log("sub 2".json_encode($sublocality));
                                    }
                                    else if($item['types'][$typeIndex]=="sublocality_level_3"){
                                            $sublocality = $item['long_name'];
                                            //Yii::log("sub 3".json_encode($sublocality));
                                    }
                                }
                            }
							if(array_key_exists ( 'formatted_address' , $result ) && !empty($result['formatted_address'])){
							    $fullAddress=$result['formatted_address'];
								break;
							}


                            /*if($fullAddress!=null){
                                break;
                            }*/
                    }
			}
			else{
				Yii::log("GAPI request not successful,Status : ".$status." Lat/Long : ".$latitude."/".$longitude);
			}
             if(!isset($_SESSION))
               {
                 session_start();
               }
                    $server_endtime = round(microtime(true) * 1000);
                    $server_processtime=$server_endtime-$server_starttime;
                    Yii::log("GAPI request successful,Status : ".$status." Lat/Long : ".$locality."/".$sublocality."/".$fullAddress);
                    // Set session variables
                    Yii::app()->session['locality'] = $locality;
                    Yii::app()->session['sublocality'] = $sublocality;
                    Yii::app()->session['fullAddress'] = $fullAddress;
                    Yii::app()->session['lat'] = $latitude;
                    Yii::app()->session['lon'] = $longitude;
                    Yii::app()->session['get_address_server_processtime'] = $server_processtime;

                    $userCurrentLocation =  array('locality' => $locality,
                                  'sublocality' => $sublocality,
                                  'fullAddress' => $fullAddress,
                                  'get_address_server_processtime' => $server_processtime);
                    BlogUtility::jencode($userCurrentLocation);
           }
           else{
               $userCurrentLocation =  array('locality' => "",
                                                'sublocality' => "",
                                                'fullAddress' => "",
                                                'get_address_server_processtime' => "");
                                  BlogUtility::jencode($userCurrentLocation);
           }
     }
         public function actionLogin(){
        		Yii::log("Started login");
                Yii::app()->session['BLOGLOGIN_LOGOUT'] = "true";
                $this->redirect(MEDINFI_FOLDER_BASE_URL.LOGIN_URL);
                }

         public function actionLogout(){
                Yii::log("Started logout");
                Yii::app()->session['BLOGLOGIN_LOGOUT'] = "true";
                $this->redirect(MEDINFI_FOLDER_BASE_URL.LOGOUT_URL);
                }


                 /*
                      Get Category Tags
                      Abhi
                      03-04-2018
                    */
                    public function actionGetCategoryTags(){

                            $categoryId = isset($_REQUEST['categoryId']) ? $_REQUEST['categoryId']: '';
                            $categoryTags = "";
                            try{
                            $categoryTags = BlogUtility::getPopularTags($categoryId);
                            }catch(Exception $ex){
                                Yii::log("exception ".$ex->getMessage());
                                MedinfiExceptionNotifier::notifyException($ex);
                            }
                      BlogUtility::jencode($categoryTags);
                    }


}

?>
