<?php
require(dirname(__FILE__).'/../components/WordpressFormattingUtility.php');
class BlogUtility {
	/**
     *Developer - Harsh Balyan
     *Date - 27-Jan-2017
     */
	//Function to fetch the category list to be shown in the header and mobile nav bar.
    public static function getCategories(){
        $medCategoryIds = array();
        $wpCategoryKeyValuePairs = array();
        $sortedCategories =array();
		
        $medCategories = BlogCategory::model()->findAll(array('order'=>'priority'));
        
        //Get IDs of the categories in order stored in medinfi DB.
		for ($index=0;$index < sizeof($medCategories);$index++) {
            array_push($medCategoryIds, $medCategories[$index]['wp_id']);
        }
        
        //Get category details from the WP DB.
		$getCategoryQuery= "select wtt.term_id,wt.name,wt.slug from wp_terms as wt inner join wp_term_taxonomy as wtt on wt.term_id = wtt.term_id
					where wtt.taxonomy= 'category' and count >=".MIN_CATEGORY_POST_COUNT;
        $getCategoryQueryResult = Yii::app()->wpDb->createCommand($getCategoryQuery)->queryAll();
        
        //Create a Key-Value pair for the query result.
        foreach ($getCategoryQueryResult as $category) {
			$category_name = $category['name'];
			if(strcasecmp($category['name'],'Trending Topics in Healthcare')==0) {
				$category_name = 'Trending Topics';
			}
			$categoryUrl = HOST_NAME.Yii::app()->baseUrl."/".$category['slug']."/";
            $wpCategoryKeyValuePairs[$category["term_id"]] = array($category['term_id'],$category_name,$category['slug'],$categoryUrl);
		}
        
        //Create the output array with category details according to the priority.
		foreach ($medCategoryIds as $medCategoryId) {
			if(!empty($wpCategoryKeyValuePairs[$medCategoryId])) {
                //Yii::log("Element >>> ".$wpCategoryKeyValuePairs[$medCategoryId][1]);
				array_push($sortedCategories, $wpCategoryKeyValuePairs[$medCategoryId]);
			}
		}

		//Removing Find Doctors, Ask Friend and Login

		/*$doctorLinkUrl = MEDINFI_FOLDER_BASE_URL.HEADER_DOCTOR_LINK_URL;
		array_push($sortedCategories,array(HEADER_DOCTOR_LINK_ID, HEADER_DOCTOR_LINK_NAME, HEADER_DOCTOR_LINK_SLUG, $doctorLinkUrl));
		$askAFriendLinkUrl = MEDINFI_FOLDER_BASE_URL.ASK_A_FRIEND_LINK_URL;
		array_push($sortedCategories,array(ASK_A_FRIEND_LINK_URL, ASK_A_FRIEND_LINK_NAME, ASK_A_FRIEND_LINK_SLUG, $askAFriendLinkUrl));

		if(isset(Yii::app()->session['userId'])) {
        $userID = Yii::app()->session['userId'];
        $logoutUrl = HOST_NAME.Yii::app()->baseUrl.LOGOUT_URL;
        array_push($sortedCategories,array(HEADER_DOCTOR_LINK_ID, LOGOUT, HEADER_LOGIN_LOGOUT_SLUG, $logoutUrl));

        } else {
        $loginUrl = HOST_NAME.Yii::app()->baseUrl.LOGIN_URL;
        array_push($sortedCategories,array(HEADER_DOCTOR_LINK_ID, LOGIN, HEADER_LOGIN_LOGOUT_SLUG, $loginUrl ));
         }*/

		return $sortedCategories;
    }
	
	//Function to get the ID, Name and Slug for the passed term(category/tag)
	
	public static function getTermInfoFromName($termType,$termSlug,$termList=NULL){
		$termInfo = array();
		//Yii::log("Term info Type/Name/ListSize >>>> ".$termType."/".$termSlug."/".sizeof($termList));
		if($termList == NULL){
			if($termType == TERM_TAG && $termSlug !=NULL){
				$getTagIdQuery = "select wtt.term_id,wt.slug,wt.name from wp_terms as wt inner join wp_term_taxonomy as wtt on wt.term_id = wtt.term_id
					where wtt.taxonomy= 'post_tag' and count > 0 and slug = '".$termSlug."'";
				$getTagIdQueryResult = Yii::app()->wpDb->createCommand($getTagIdQuery)->queryAll();
				if(!empty($getTagIdQueryResult)){
					$termInfo['termId'] = $getTagIdQueryResult[0]['term_id'];
					$termInfo['termName'] = $getTagIdQueryResult[0]['name'];
					$termInfo['termSlug'] = $getTagIdQueryResult[0]['slug'];
				}
			}
		}
		else {
			foreach($termList as $term){
				Yii::log("Term Element Info >>>>> ".$term[0]."/".$term[1]."/".$term[2]);
				if($term[2]==$termSlug){
					$termInfo['termId'] = $term[0];
					$termInfo['termName'] = $term[1];
					$termInfo['termSlug'] = $term[2];
					break;
				}
			}
		}
		return $termInfo;
	}
	
	/*Function to get the Post List for all the post listing
	 *Blog Home Page
	 *Category Home Page
	 *Blog List Page
	 *Category List Page
	 *Tag List Page
	 *Recent Posts on Post Detail Page
	 *Search Result Page
	 */
	
    public static function getPostList($termId=DEFAULT_ID,$termType=DEFAULT_TERM_TYPE,$postId=DEFAULT_ID,$offset=DEFAULT_POST_OFFSET,$limit=DEFAULT_POST_LIMIT){
        
        $getPostListQuery="select wp.post_title,wp.post_name as post_slug, DATE_FORMAT(wp.post_date, '%D %b %Y') as post_date, wpm1.meta_value as imgDetails,
			DATE_FORMAT(wp.post_date, '%Y/%m/%d') as post_url_date, ";
		if($termType != TERM_TAG){
			$getPostListQuery = $getPostListQuery." case
			when wt2.term_id is NOT NULL THEN wt2.slug
			ELSE wt.slug
			END AS category_slug ";
		}
		else{
			$getPostListQuery = $getPostListQuery." case
			when wt3.term_id is not NULL THEN wt3.slug
			ELSE wt2.slug
			END AS category_slug ";
		}
		$getPostListQuery = $getPostListQuery."
		from wp_posts as wp 
		inner join wp_term_relationships as wtr on wp.ID = wtr.object_id
		inner join wp_term_taxonomy as wtt on wtr.term_taxonomy_id = wtt.term_taxonomy_id
		inner join wp_terms as wt on wt.term_id = wtt.term_id
		inner join wp_postmeta as wpm on wpm.post_id = wp.ID
		inner join wp_posts as wp1 on wp1.ID = wpm.meta_value
		inner join wp_postmeta as wpm1 on wpm1.post_id = wpm.meta_value ";
		if($termType != TERM_TAG){
			$getPostListQuery = $getPostListQuery." inner join wp_postmeta as wpm2 on wp.ID = wpm2.post_id
			left join wp_terms as wt2 on wpm2.meta_value = wt2.term_id ";
		}
		else{
			$getPostListQuery = $getPostListQuery." inner join wp_term_relationships as wtr2 on wp.ID = wtr2.object_id
			inner join wp_term_taxonomy as wtt2 on wtr2.term_taxonomy_id = wtt2.term_taxonomy_id
			inner join wp_terms as wt2 on wt2.term_id = wtt2.term_id
			inner join wp_postmeta as wpm3 on wp.ID = wpm3.post_id
			left join wp_terms as wt3 on wpm3.meta_value = wt3.term_id ";
		}
		$getPostListQuery = $getPostListQuery."
		where 
		wp.post_status ='publish' and ";
		if($termType != TERM_TAG){
			$getPostListQuery = $getPostListQuery." wtt.taxonomy = 'category' and";
		}
		else{
			$getPostListQuery = $getPostListQuery." wtt.taxonomy = 'post_tag' and";
		}
            
        if($termId != DEFAULT_ID){
            $getPostListQuery=$getPostListQuery." wtt.term_id =".$termId." and" ;
        }
        $getPostListQuery=$getPostListQuery." wpm.meta_key = '_thumbnail_id' and 
            wpm1.meta_key ='_wp_attachment_metadata' and
            wp1.post_type = 'attachment' ";
		if($termType != TERM_TAG){
			$getPostListQuery = $getPostListQuery." and wtt.count >=".MIN_CATEGORY_POST_COUNT."
			and wpm2.meta_key like '_yoast_wpseo_primary_category' ";
		}
		else{
			$getPostListQuery = $getPostListQuery." and wtt2.count >=".MIN_CATEGORY_POST_COUNT."
			and wpm3.meta_key like '_yoast_wpseo_primary_category' ";
		}
		if($postId != DEFAULT_ID){
			$getPostListQuery=$getPostListQuery." and wp.ID !=".$postId;
		}
		$getPostListQuery=$getPostListQuery."
		group by wp.ID
		order by wp.post_date DESC
		LIMIT ".$offset.", ".$limit;
        Yii::log("Get Post List Query ".$getPostListQuery);
        $getPostListQueryResult = Yii::app()->wpDb->createCommand($getPostListQuery)->queryAll();
        return $getPostListQueryResult;
    }
        public static function getPopularPostList($categoryId,$postId){
            $page_view = 'PAGE_VIEW';
            $page_share = 'SHARE';
            $getpopularpages = "SELECT user_session_page.page_id, COUNT( user_session_page.page_id ) AS count_page_id,page.url as url
                                FROM user_session_page,page
                                WHERE user_session_page.page_id = page.id AND user_session_page.event_type in ('".$page_view."','".$page_share."')
                                and start_time > DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                                GROUP BY user_session_page.page_id
                                order by count_page_id desc";
            $getPopularListQueryResult = Yii::app()->analyticsDb->createCommand($getpopularpages)->queryAll();
            $popularPostList = array();
            $index = 0;
    		$urlCategoryId='';
    	    $allCategories = BlogUtility::getCategories();
            for($i=0;$i<sizeof($getPopularListQueryResult);$i++){
                $getUrl = $getPopularListQueryResult[$i]['url'];
                $getUrl1 = explode("/",$getUrl);
                $len = count($getUrl1);
                $getSlug = $getUrl1[$len-1];
       			$urlPostDetails = BlogUtility::getPostDetails($getSlug);
    			if(!empty($urlPostDetails)){
    		        $categoryInfo2 = BlogUtility::getTermInfoFromName(TERM_CATEGORY,$urlPostDetails['postCategorySlug'],$allCategories);
    				if(!empty($categoryInfo2)){
    					$urlCategoryId = $categoryInfo2['termId'];
    				}
                    if($categoryId == $urlCategoryId && $postId != $urlPostDetails['id']){
                        array_push($popularPostList, $urlPostDetails);
                        $index++;
                    }
                }
                if($index == 3){
                break;
                }
            }
            return $popularPostList;
        }
	
	/*Function to get the post list count for
	 *Showing View All link on
	 *	Category Home Page
	 *	Blog Home Page
	 *Showing Post Count and Pagination for
	 *	Blog List Page
	 *	Category List Page
	 *	Tag List Page
	 */
	public static function getPostListCount($termId=DEFAULT_ID,$termType=DEFAULT_TERM_TYPE,$postId=DEFAULT_ID,$offset=DEFAULT_POST_OFFSET,$limit=DEFAULT_POST_LIMIT){
        
        $getPostListQuery="select count(*) as count
		from wp_posts as wp 
		inner join wp_term_relationships as wtr on wp.ID = wtr.object_id
		inner join wp_term_taxonomy as wtt on wtr.term_taxonomy_id = wtt.term_taxonomy_id
		inner join wp_terms as wt on wt.term_id = wtt.term_id
		inner join wp_postmeta as wpm on wpm.post_id = wp.ID
		inner join wp_posts as wp1 on wp1.ID = wpm.meta_value
		inner join wp_postmeta as wpm1 on wpm1.post_id = wpm.meta_value ";
		if($termType != TERM_TAG){
			$getPostListQuery = $getPostListQuery." inner join wp_postmeta as wpm2 on wp.ID = wpm2.post_id
			left join wp_terms as wt2 on wpm2.meta_value = wt2.term_id ";
		}
		else{
			$getPostListQuery = $getPostListQuery." inner join wp_term_relationships as wtr2 on wp.ID = wtr2.object_id
			inner join wp_term_taxonomy as wtt2 on wtr2.term_taxonomy_id = wtt2.term_taxonomy_id
			inner join wp_terms as wt2 on wt2.term_id = wtt2.term_id
			inner join wp_postmeta as wpm3 on wp.ID = wpm3.post_id
			left join wp_terms as wt3 on wpm3.meta_value = wt3.term_id ";
		}
		$getPostListQuery = $getPostListQuery."
		where 
		wp.post_status ='publish' and ";
		if($termType != TERM_TAG){
			$getPostListQuery = $getPostListQuery." wtt.taxonomy = 'category' and";
		}
		else{
			$getPostListQuery = $getPostListQuery." wtt.taxonomy = 'post_tag' and";
		}
            
        if($termId != DEFAULT_ID){
            $getPostListQuery=$getPostListQuery." wtt.term_id =".$termId." and" ;
        }
        $getPostListQuery=$getPostListQuery." wpm.meta_key = '_thumbnail_id' and 
            wpm1.meta_key ='_wp_attachment_metadata' and
            wp1.post_type = 'attachment' ";
		if($termType != TERM_TAG){
			$getPostListQuery = $getPostListQuery." and wtt.count >=".MIN_CATEGORY_POST_COUNT."
			and wpm2.meta_key like '_yoast_wpseo_primary_category' ";
		}
		else{
			$getPostListQuery = $getPostListQuery." and wtt2.count >=".MIN_CATEGORY_POST_COUNT."
			and wpm3.meta_key like '_yoast_wpseo_primary_category' ";
		}
		if($postId != DEFAULT_ID){
			$getPostListQuery=$getPostListQuery." and wp.ID !=".$postId;
		}
		$getPostListQuery=$getPostListQuery."
		group by wp.ID
		order by wp.post_date DESC";
        //Yii::log("Get Post List Query ".$getPostListQuery);
        $getPostListQueryResult = Yii::app()->wpDb->createCommand($getPostListQuery)->queryAll();
        return count($getPostListQueryResult);
    }

        public static function processPopularPostList($postList,$pageType){
            $result = array();
            if(!empty($postList)) {
                $index = 0;
                foreach ($postList as $post) {
                    $image_type = MEDIUM_IMAGE;
                    if(($pageType == BLOG_HOME_PAGE || $pageType == CATEGORY_HOME_PAGE) && $index < NO_OF_FEATURED_POST){
                        $image_type = MEDIUM_LARGE_IMAGE;
                    }
                    $result[$index]['post_title'] = $post['postTitle'];
                    $result[$index]['post_slug'] = $post['postSlug'];
                    $result[$index]['post_date'] = $post['postDate'];
                    $result[$index]['post_img'] = $post['postImage'];
    				$result[$index]['post_url'] = BlogUtility::getPostUrl($post['postSlug']);
                    $index++;
                }
            }
            return $result;
        }

	/*Function to process the post list passed to it
	 *It fetches the post image path to be shown in UI
	 */
    public static function processRecentPostList($postList,$pageType){
        $result = array();
        if(!empty($postList)) {
            $index = 0;
            foreach ($postList as $post) {
                $image_type = MEDIUM_IMAGE;
                if(($pageType == BLOG_HOME_PAGE || $pageType == CATEGORY_HOME_PAGE) && $index < NO_OF_FEATURED_POST){
                    $image_type = MEDIUM_LARGE_IMAGE;
                }
                $result[$index]['post_title'] = $post['post_title'];
				if($pageType != AUTO_SUGGESTION_LIST){
					$result[$index]['post_slug'] = $post['post_slug'];
					$result[$index]['post_date'] = $post['post_date'];
					$result[$index]['post_img'] = BlogUtility::getPostFeaturedImage($post['imgDetails'],$image_type);
				}
				$result[$index]['post_url'] = BlogUtility::getPostUrl($post['post_slug']);
                //Yii::log("Title/Slug/Date/ImgPath/URL-Date/Category-Slug >>>> ".$result[$index]['post_title']."||".$result[$index]['post_slug']."||".$result[$index]['post_date']."||".$result[$index]['post_img']."||".$result[$index]['post_url_date']."||".$result[$index]['category_slug']);
                $index++;
            }
        }
        return $result;
    }
	
	/*Function to return the url for a blog post*/
	public static function getPostUrl($postSlug){
		return "/".$postSlug."/";
	}

	/*function to get the path of the featured image for the post*/
    public static function getPostFeaturedImage($imgData,$type) {
		$file =null;
		 if ( $imgData =@ unserialize( $imgData ) ) {
				$file = $imgData['file'];
				if ( ! empty( $imgData['sizes'] ) ) {
					$sizes = $imgData['sizes'];
					if ( ! empty( $sizes['magento-image'] ) )
					    $file = $sizes['magento-image']['file'];
					elseif ( ! empty( $sizes[$type] ) ) 
					    $file = $sizes[$type]['file'];
				}
				if ( $file !== $imgData['file'] ) {
					if ( '.' !== $path = dirname( $imgData['file'] ) )
					    $file = "$path/$file";
				}
			$file = BLOG_POST_IMAGE_PATH.$file;
			}

		return $file;
	}
	
	/*Function to get the data for pagination bar of a list pages*/
	public static function getPaginationElements($pageType,$currentPage,$totalNumberOfPosts,$paginationBaseUrl,$pageUrlSuffix){
		$paginationElements = array();
		$lastPage = BlogUtility::getLastPageNo($totalNumberOfPosts);
		Yii::log("last page is ".$lastPage);
		if($lastPage == FIRST_PAGE){
			return $paginationElements;
		}
		array_push($paginationElements,array("pageNo" => $currentPage,"pageUrl" => "NA"));
		
		if(($lastPage-$currentPage) <= 4){
			for($index = $currentPage+1; $index <= $lastPage; $index++){
				array_push($paginationElements,array("pageNo" => $index,"pageUrl" => $paginationBaseUrl.$index.$pageUrlSuffix));
			}
		}
		else{
			array_push($paginationElements,
					   array("pageNo" => $currentPage+1,"pageUrl" => $paginationBaseUrl.($currentPage+1).$pageUrlSuffix),
					   array("pageNo" => PAGINATION_ELLIPSE_VALUE,"pageUrl" => "NA"),
					   array("pageNo" => $lastPage,"pageUrl" => $paginationBaseUrl.$lastPage.$pageUrlSuffix));
		}
		
		
		if(($currentPage-1) <= (MAX_PAGINATION_ELEMENT-sizeof($paginationElements))){
			for($index = $currentPage-1; $index>0; $index--){
				$pageUrl = $paginationBaseUrl.$index.$pageUrlSuffix;
				if($index == FIRST_PAGE && ($pageType == TAG_LIST_PAGE || $pageType == SEARCH_LIST_PAGE || $pageType == PSEUDO_SEARCH_LIST_PAGE)){
					$pageUrl = substr($paginationBaseUrl,0,strpos($paginationBaseUrl,'/page/')).$pageUrlSuffix;
				}
				array_unshift($paginationElements,array("pageNo" => $index,"pageUrl" => $pageUrl));
			}
		}
		else {
			$firstPageUrl = $paginationBaseUrl.FIRST_PAGE.$pageUrlSuffix;
			if($pageType == TAG_LIST_PAGE || $pageType == SEARCH_LIST_PAGE){
				$firstPageUrl = substr($paginationBaseUrl,0,strpos($paginationBaseUrl,'/page/')).$pageUrlSuffix;
			}
			array_unshift($paginationElements,
					   array("pageNo" => FIRST_PAGE,"pageUrl" => $firstPageUrl),
					   array("pageNo" => PAGINATION_ELLIPSE_VALUE,"pageUrl" => "NA"),
					   array("pageNo" => ($currentPage-1),"pageUrl" => $paginationBaseUrl.($currentPage-1).$pageUrlSuffix));
		}
		
		
		if($currentPage != FIRST_PAGE){
			$leftPageUrl = $paginationBaseUrl.($currentPage-1).$pageUrlSuffix;
			if(($currentPage-1) == FIRST_PAGE && ($pageType == TAG_LIST_PAGE || $pageType == SEARCH_LIST_PAGE || $pageType == PSEUDO_SEARCH_LIST_PAGE)){
				$leftPageUrl = substr($paginationBaseUrl,0,strpos($paginationBaseUrl,'/page/')).$pageUrlSuffix;
			}
			array_unshift($paginationElements,array("pageNo" => LEFT_ARROW_VALUE,"pageUrl" => $leftPageUrl));
		}
		if($currentPage != $lastPage){
			array_push($paginationElements,array("pageNo" => RIGHT_ARROW_VALUE,"pageUrl" => $paginationBaseUrl.($currentPage+1).$pageUrlSuffix));
		}
		
		
		return $paginationElements;
	}
	
	/* get the base URL to be used in the pagination bar of the post list page*/
	public static function getPaginationBaseUrl($pageType, $termType, $termSlug,$searchInput){
		$paginationBaseUrl =  HOST_NAME.Yii::app()->baseUrl;
		if($pageType == BLOG_LIST_PAGE || $pageType == SEARCH_LIST_PAGE){
			$paginationBaseUrl = $paginationBaseUrl."/page/";
		}
		else if($pageType == CATEGORY_LIST_PAGE){
			$paginationBaseUrl = $paginationBaseUrl."/".$termSlug."/page/";
		}
		else if($pageType == TAG_LIST_PAGE){
			$paginationBaseUrl = $paginationBaseUrl."/".TERM_TAG."/".$termSlug."/page/";
		}
		else if($pageType == PSEUDO_SEARCH_LIST_PAGE){
			$paginationBaseUrl = $paginationBaseUrl."/search/".$searchInput."/page/";
		}
		return $paginationBaseUrl;
	}

	    /*
           Nitish Jha
           19-07-2017
           Blog URL Shortening
        */
	/*To get the Post details for the requested blog post*/
	public static function getPostDetails($postUniqueId,$isNumericId = false,$isAGuess = false) {
		$postDetails = array();
		//This hard coding is as Yii url parameter is not able to pass soft-hypen symbol in the manner stored in wp_posts table.
		//The value in below mentioned 'if condition' contains the hidden soft hyphen character afer 'scoliosis'
        /*
        Nitish Jha
        19-05-2017
        We have removed hidden soft hyphen and for redirecting we are doing the if loop
        */
		if(($isNumericId == false) && ($postUniqueId == 'scoliosis­-causes-symptoms-facts' || $postUniqueId == 'scoliosis%c2%ad-causes-symptoms-facts' )){
			$postUniqueId = 'scoliosis-causes-symptoms-facts';
		}
		//$urlPublishedOnDate = $postYear."/".$postMonth."/".$postDay;
		$query = "select distinct wp.ID,wp.post_title,DATE_FORMAT(wp.post_date, '%D %b %Y') as post_date, wp.post_content,wpm1.meta_value as imgData,wp.post_name as slug,DATE_FORMAT(wp.post_date, '%Y/%m/%d') as post_url_date,
			case
			when wt2.term_id is NOT NULL THEN wt2.slug
			ELSE wt.slug
			END AS category_slug 
			from wp_posts as wp 
			inner join wp_term_relationships as wtr on wp.ID = wtr.object_id
			inner join wp_term_taxonomy as wtt on wtr.term_taxonomy_id = wtt.term_taxonomy_id
			inner join wp_terms as wt on wt.term_id = wtt.term_id
			inner join wp_postmeta as wpm on wpm.post_id = wp.ID
			inner join wp_posts as wp1 on wp1.ID = wpm.meta_value
			inner join wp_postmeta as wpm1 on wpm1.post_id = wpm.meta_value
            inner join wp_postmeta as wpm2 on wp.ID = wpm2.post_id
			left join wp_terms as wt2 on wpm2.meta_value = wt2.term_id 
			where 
			wp.post_status ='publish' and 
            wtt.taxonomy = 'category' and
			wpm.meta_key = '_thumbnail_id' and 
            wpm1.meta_key ='_wp_attachment_metadata' and
            wp1.post_type = 'attachment' and
            wpm2.meta_key like '_yoast_wpseo_primary_category' and ";
			if($isNumericId){
				$query .= "wp.ID ='".$postUniqueId."'";
			}
			else{
				if($isAGuess){
					$query .= "wp.post_name like '".$postUniqueId."%'";
				}
				else{
					$query .= "wp.post_name ='".$postUniqueId."'";
				}
			}
            

		//Yii::log(" Query to the get the post details is >>> ".$query);
		$postUnprocessedDetails = Yii::app()->wpDb->createCommand($query)->queryAll();
		if(!empty($postUnprocessedDetails)) {
			$postDetails['id'] = $postUnprocessedDetails[0]['ID'];
			$postDetails['postTitle'] = $postUnprocessedDetails[0]['post_title'];
			$postDetails['postDate'] = $postUnprocessedDetails[0]['post_date'];
			$postDetails['postContent'] = BlogUtility::processPostHtml($postUnprocessedDetails[0]['post_content']);
			$postDetails['postUrlDate'] = $postUnprocessedDetails[0]['post_url_date'];
			$imageData = $postUnprocessedDetails[0]['imgData'];
			$postDetails['postImage'] = BlogUtility::getPostFeaturedImage($imageData,MEDIUM_LARGE_IMAGE);
			//Yii::log("Post IMAGE >>>".$postDetails['postImage']);
			
			$postDetails['postSlug'] = $postUnprocessedDetails[0]['slug'];
			$postDetails['postCategorySlug'] = $postUnprocessedDetails[0]['category_slug'];
			$postDetails['postShareUrl'] =  BlogUtility::getPostShareUrl($postDetails['postSlug']);
			$postDetails['postCanonicalUrl'] =  BlogUtility::getPostCanonicalUrl($postDetails['postSlug']);


		}
		return $postDetails;
	}
	/*public static function getPostDetails($postSlug) {
		$postDetails = array();
		//This hard coding is as Yii url parameter is not able to pass soft-hypen symbol in the manner stored in wp_posts table.
		//The value in below mentioned 'if condition' contains the hidden soft hyphen character afer 'scoliosis'
		if($postSlug == 'scoliosis­-causes-symptoms-facts'){
			$postSlug = 'scoliosis%c2%ad-causes-symptoms-facts';
		}
		$query = "select wp.ID,wp.post_title,DATE_FORMAT(wp.post_date, '%D %b %Y') as post_date, wp.post_content,wpm1.meta_value as imgData,wp.post_name as slug
			from wp_posts as wp
			inner join wp_postmeta as wpm on wpm.post_id = wp.ID
            inner join wp_posts as wp1 on wp1.ID = wpm.meta_value
            inner join wp_postmeta as wpm1 on wpm1.post_id = wpm.meta_value
			where 
			wp.post_status ='publish' and 
			 wpm.meta_key = '_thumbnail_id' and 
            wpm1.meta_key ='_wp_attachment_metadata' and
            wp1.post_type = 'attachment' and  
            wp.post_name ='".$postSlug."'";
		Yii::log(" Query to the get the post details is >>> ".$query);
		$postUnprocessedDetails = Yii::app()->wpDb->createCommand($query)->queryAll();
		if(!empty($postUnprocessedDetails)) {
			$postDetails['id'] = $postUnprocessedDetails[0]['ID'];
			$postDetails['postTitle'] = $postUnprocessedDetails[0]['post_title'];
			$postDetails['postDate'] = $postUnprocessedDetails[0]['post_date'];
			$postDetails['postContent'] = BlogUtility::processPostHtml($postUnprocessedDetails[0]['post_content']);
			
			$imageData = $postUnprocessedDetails[0]['imgData'];
			$postDetails['postImage'] = BlogUtility::getPostFeaturedImage($imageData,MEDIUM_LARGE_IMAGE);
			//Yii::log("Post IMAGE >>>".$postDetails['postImage']);
			
			$postDetails['postSlug'] = $postUnprocessedDetails[0]['slug'];
			$postDetails['postShareUrl'] =  'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}
		return $postDetails;
	}*/
	
	/*Pre-process the post HTML stored in DB to align it to be shown correctly in the UI*/
	public static function processPostHtml($postHtml){
		$processedPostHtml = wpautop($postHtml);
		while (strstr($processedPostHtml,"<img") !== false) {
            $beforeImgTagStart = substr($processedPostHtml,0,strpos($processedPostHtml,"<img"));
			$afterImgTagStart = substr($processedPostHtml,strpos($processedPostHtml,"<img"));
			$afterImgTagEnd = substr($afterImgTagStart,strpos($afterImgTagStart,"/>") + 2);
            $processedPostHtml = $beforeImgTagStart.$afterImgTagEnd;
        }
		//Yii::log("POST CONTENT >>> ".$processedPostHtml);
		return $processedPostHtml;
	}
	
	/*Funtion to get the tags for a particular post*/
	public static function getTagsForPost($postId){
		$getTagsForPost = "select wtt.term_id,wt.name,wt.slug from wp_terms as wt inner join 
			wp_term_taxonomy as wtt on wt.term_id = wtt.term_id inner join 
            wp_term_relationships as wtr on wtr.term_taxonomy_id = wtt.term_taxonomy_id inner join 
            wp_posts as wp on wp.ID = wtr.object_id
            where
			wp.post_status = 'publish' and
			wtt.taxonomy= 'post_tag' and 
            wp.id = ".$postId;
		
		$tagListQueryResult = Yii::app()->wpDb->createCommand($getTagsForPost)->queryAll();
		$tagList = array();
		if(!empty($tagListQueryResult)) {
            $index = 0;
            foreach ($tagListQueryResult as $tag) {
                $tagList[$index]['tagId'] = $tag['term_id'];
                $tagList[$index]['tagName'] = $tag['name'];
                $tagList[$index]['tagSlug'] = $tag['slug'];
				$tagList[$index]['tagUrl'] = HOST_NAME.Yii::app()->baseUrl.'/'.TERM_TAG.'/'.$tag['slug'].'/';
				//Yii::log("ID/Name/Slug/URL >>>> ".$tag['term_id']."||".$tag['name']."||".$tag['slug']."||".$tagList[$index]['tagUrl']);
                $index++;
            }
        }
		return $tagList;
	}
	
	/*Function to get the post list as per the search input*/
	public static function getPostsBySearch($searchInput,$offset,$limit,$limitResultFlag = true){
	    $searchInput = str_replace("'","\'",$searchInput);
		$keywords=explode(" ",$searchInput);
		$posts = array();
		if(!empty($searchInput)){
			$searchQuery = "select wp.post_title,wp.post_name as post_slug, DATE_FORMAT(wp.post_date, '%D %b %Y') as post_date, 	wpm1.meta_value as imgDetails,
			DATE_FORMAT(wp.post_date, '%Y/%m/%d') as post_url_date,  case
			when wt2.term_id is NOT NULL THEN wt2.slug
			ELSE wt.slug
			END AS category_slug
			from wp_posts as wp 
			inner join wp_term_relationships as wtr on wp.ID = wtr.object_id
			inner join wp_term_taxonomy as wtt on wtr.term_taxonomy_id = wtt.term_taxonomy_id
			inner join wp_terms as wt on wt.term_id = wtt.term_id
			inner join wp_postmeta as wpm on wpm.post_id = wp.ID
			inner join wp_posts as wp1 on wp1.ID = wpm.meta_value
			inner join wp_postmeta as wpm1 on wpm1.post_id = wpm.meta_value
			inner join wp_postmeta as wpm2 on wp.ID = wpm2.post_id
			left join wp_terms as wt2 on wpm2.meta_value = wt2.term_id
			where 
			wp.post_status ='publish' and
			wtt.taxonomy = 'category' and
			wpm.meta_key = '_thumbnail_id' and 
            wpm1.meta_key ='_wp_attachment_metadata' and
            wp1.post_type = 'attachment'
			and wtt.count >=".MIN_CATEGORY_POST_COUNT."
			and wpm2.meta_key like '_yoast_wpseo_primary_category' ";
			
			$titleContentFilterCondition = "";
			$titleQuery = "";
			$contentQuery = "";
			for ($index = 0; $index < sizeof($keywords); $index++) {
				if($index != 0){
					$titleContentFilterCondition = $titleContentFilterCondition." and";
					$titleQuery = $titleQuery." and";
                    $contentQuery = $contentQuery." and";
				}
			   
				$titleContentFilterCondition = $titleContentFilterCondition." concat(wp.post_title,' ',wp.post_content) LIKE '%".$keywords[$index]."%'";
				$titleQuery = $titleQuery." wp.post_title LIKE '%".$keywords[$index]."%'";
                $contentQuery = $contentQuery." wp.post_content LIKE '%".$keywords[$index]."%'";
            }
			
			$searchQuery = $searchQuery." and ".$titleContentFilterCondition ;
			$searchQuery =$searchQuery."
			GROUP BY wp.ID
			ORDER BY CASE
			WHEN wp.post_title LIKE '".$searchInput."%' THEN 0
			WHEN wp.post_title LIKE '%".$searchInput."%' THEN 1";
			if(sizeof($keywords)>1){
				$searchQuery = $searchQuery." WHEN ".$titleQuery." THEN 2 ";	
			}
			$searchQuery = $searchQuery." WHEN wp.post_content LIKE '%".$searchInput."%' THEN 4 ";
			if(sizeof($keywords)>1){
				$searchQuery = $searchQuery." WHEN ".$contentQuery." THEN 5 " ;
			}
			$searchQuery = $searchQuery." ELSE 6 END , wp.post_date DESC
			LIMIT ".$offset.", ".$limit;
			Yii::log('Search Query is >>>>>>>>>>>> '.$searchQuery);
			$posts = Yii::app()->wpDb->createCommand($searchQuery)->queryAll();
		}
		return $posts;
	}
	/*Function to get the count of search results for a particular search query*/
	public static function getPostsBySearchCount($searchInput,$offset,$limit){
		$searchInput = str_replace("'","\'",$searchInput);
		$keywords=explode(" ",$searchInput);
		$posts = array();
		if(!empty($searchInput)){
			$searchQuery = "select count(*)
			from wp_posts as wp 
			inner join wp_term_relationships as wtr on wp.ID = wtr.object_id
			inner join wp_term_taxonomy as wtt on wtr.term_taxonomy_id = wtt.term_taxonomy_id
			inner join wp_terms as wt on wt.term_id = wtt.term_id
			inner join wp_postmeta as wpm on wpm.post_id = wp.ID
			inner join wp_posts as wp1 on wp1.ID = wpm.meta_value
			inner join wp_postmeta as wpm1 on wpm1.post_id = wpm.meta_value
			inner join wp_postmeta as wpm2 on wp.ID = wpm2.post_id
			left join wp_terms as wt2 on wpm2.meta_value = wt2.term_id
			where 
			wp.post_status ='publish' and
			wtt.taxonomy = 'category' and
			wpm.meta_key = '_thumbnail_id' and 
            wpm1.meta_key ='_wp_attachment_metadata' and
            wp1.post_type = 'attachment'
			and wtt.count >=".MIN_CATEGORY_POST_COUNT."
			and wpm2.meta_key like '_yoast_wpseo_primary_category' ";
			
			$titleContentFilterCondition = "";
			$titleQuery = "";
			$contentQuery = "";
			for ($index = 0; $index < sizeof($keywords); $index++) {
				if($index != 0){
					$titleContentFilterCondition = $titleContentFilterCondition." and";
					$titleQuery = $titleQuery." and";
                    $contentQuery = $contentQuery." and";
				}
			   
				$titleContentFilterCondition = $titleContentFilterCondition." concat(wp.post_title,' ',wp.post_content) LIKE '%".$keywords[$index]."%'";
				$titleQuery = $titleQuery." wp.post_title LIKE '%".$keywords[$index]."%'";
                $contentQuery = $contentQuery." wp.post_content LIKE '%".$keywords[$index]."%'";
            }
			
			$searchQuery = $searchQuery." and ".$titleContentFilterCondition ;
			$searchQuery =$searchQuery."
			GROUP BY wp.ID
			ORDER BY CASE
			WHEN wp.post_title LIKE '".$searchInput."%' THEN 0
			WHEN wp.post_title LIKE '%".$searchInput."%' THEN 1";
			if(sizeof($keywords)>1){
				$searchQuery = $searchQuery." WHEN ".$titleQuery." THEN 2 ";	
			}
			$searchQuery = $searchQuery." WHEN wp.post_content LIKE '%".$searchInput."%' THEN 4 ";
			if(sizeof($keywords)>1){
				$searchQuery = $searchQuery." WHEN ".$contentQuery." THEN 5 " ;
			}
			$searchQuery = $searchQuery." ELSE 6 END , wp.post_date DESC";
			//Yii::log('Search Query is >>>>>>>>>>>> '.$searchQuery);
			$posts = Yii::app()->wpDb->createCommand($searchQuery)->queryAll();
		}
		return count($posts);
	}
		/*Get the heading to be shown on the top of the list page*/
		public static function getListHeading($numberOfPosts, $pageType, $name){
			$heading = array();
			$nonHighlightedTarget = "";
			$highlightedTarget = "";
			$nonHeadingTarget = "";
			$postPerPage ="";
			if($numberOfPosts == 0){
				return NULL;
			}
			else{
				if($numberOfPosts == 1) {
					$postString = " post";
				}
				else{
					$postString = " posts";
				}
				
				if($pageType == BLOG_LIST_PAGE){
					$nonHeadingTarget = "Showing ".$numberOfPosts.$postString;
				}
				else if($pageType != BLOG_LIST_PAGE){
					$nonHeadingTarget = "Showing ".$numberOfPosts;
					$nonHighlightedTarget = $postString." for ";
					if($pageType == TAG_LIST_PAGE){
						$highlightedTarget = "Tag - ".$name;
					}
					else if($pageType == CATEGORY_LIST_PAGE){
						$highlightedTarget = "Category - ".$name;
					}
					else if($pageType == SEARCH_LIST_PAGE || $pageType == PSEUDO_SEARCH_LIST_PAGE){
						$highlightedTarget = $name;
					}
				}
				Yii::log("Heading is >>> ".$nonHeadingTarget."/".$nonHighlightedTarget."/".$highlightedTarget);
				$heading['nonHeadingTarget'] = $nonHeadingTarget;
				$heading['nonHighlightedTarget'] = $nonHighlightedTarget;
				$heading['highlightedTarget'] = $highlightedTarget;
			}
			return $heading;
		}
		
		/*To JSON encode the auto-suggestion API result*/
		public static function jencode($res) {
			header('content/json');
			$res = json_encode($res);
			$res = str_replace('\\/', '/', $res);
			echo $res;
		}
		
		/*To get the SEO Meta-Description for blog posts stored in table*/
		public static function getPostMetaDescription($postId){
			$getMetaDescriptionQuery = "select meta_value as meta_description from wp_postmeta where meta_key = '_yoast_wpseo_metadesc' and post_id = ".$postId;
			$getMetaDescriptionQueryResult = Yii::app()->wpDb->createCommand($getMetaDescriptionQuery)->queryAll();
			if(!empty($getMetaDescriptionQueryResult)){
				return $getMetaDescriptionQueryResult[0]['meta_description'];
			}
		}

		/*To get canonicals URLs for all the pages except post-detail page*/
		public static function getCanonicalUrl($pageType,$searchInput,$pageNo){
			$canonicalUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			if($pageType == SEARCH_LIST_PAGE){
				$searchCanonicalUrl = "https://".$_SERVER['HTTP_HOST'].Yii::app()->baseUrl;
				$searchCanonicalUrl = $searchCanonicalUrl."/search/".$searchInput."/";
				if($pageNo != FIRST_PAGE){
					$searchCanonicalUrl = $searchCanonicalUrl."page/".$pageNo."/";
				}
				$canonicalUrl = $searchCanonicalUrl;
			}
			else if ($pageType == BLOG_ERROR_PAGE){
				$canonicalUrl = HOST_NAME.Yii::app()->baseUrl.ERROR_PAGE_CANONICAL_URL;
			}
			return strtolower($canonicalUrl);
		}

		/*To get the Next/Prev URLs for listing pages*/
		public static function getNextPrevUrls($pageType, $termType, $termSlug, $searchInput,$currentPageNo,$totalNumberOfPosts){
			$paginationSeoUrls =  array();
			$nextPrevBaseUrl = "https://".$_SERVER['HTTP_HOST'].Yii::app()->baseUrl;
			$lastPageNo = BlogUtility::getLastPageNo($totalNumberOfPosts);
			$nextUrl = null;
			$prevUrl = null;
			$searchInput = strtolower($searchInput);

			if($lastPageNo != FIRST_PAGE){
				if($pageType == BLOG_LIST_PAGE){
					$nextPrevBaseUrl = $nextPrevBaseUrl."/page/";
				}
				else if($pageType == CATEGORY_LIST_PAGE){
					$nextPrevBaseUrl = $nextPrevBaseUrl."/".TERM_CATEGORY."/".$termSlug."/page/";
				}
				else if($pageType == TAG_LIST_PAGE){
					$nextPrevBaseUrl = $nextPrevBaseUrl."/".TERM_TAG."/".$termSlug."/page/";
				}
				else if($pageType == PSEUDO_SEARCH_LIST_PAGE || $pageType == SEARCH_LIST_PAGE){
					$nextPrevBaseUrl = $nextPrevBaseUrl."/search/".$searchInput."/page/";
				}


				if($currentPageNo != $lastPageNo){
					$nextUrl = $nextPrevBaseUrl.($currentPageNo+1)."/";
				}


				if($currentPageNo != FIRST_PAGE){
					$prevUrl = $nextPrevBaseUrl.($currentPageNo-1)."/";
				}
				if(($currentPageNo-1) == FIRST_PAGE && ($pageType == TAG_LIST_PAGE || $pageType == SEARCH_LIST_PAGE || $pageType == PSEUDO_SEARCH_LIST_PAGE)){
					$prevUrl = substr($nextPrevBaseUrl,0,strpos($nextPrevBaseUrl,'page/'));
				}

			}
			$paginationSeoUrls ['nextUrl'] = $nextUrl;
			$paginationSeoUrls ['prevUrl'] = $prevUrl;
			return  $paginationSeoUrls;

		}

		/*To get the number of pages for post list pages*/
		public static function getLastPageNo($totalNumberOfPosts){
			return intval($totalNumberOfPosts/LIST_PAGE_POST_LIMIT) + (($totalNumberOfPosts % LIST_PAGE_POST_LIMIT) > 0 ? 1 : 0);
		}
		/*Created by Jiji on Feb 2017
        			This function is to fetch the approved comments of a particular blog post*/
		public static function getPostComments($postId,$userEmail) {
			$postComments = array();
			$postCommentArr = array();
			$query = "select wc.comment_ID,wc.comment_author_email,DATE_FORMAT(wc.comment_date, '%D %b %Y at %h:%i %p') as comment_date, wc.comment_content,wc.comment_parent,
				case when wc.comment_author = ''  THEN  'Anonymous' ELSE wc.comment_author END as comment_author
				from wp_posts as wp inner join wp_comments as wc on wp.`ID` = wc.comment_post_ID
				where
				wp.ID =".$postId." and
				wp.post_status ='publish'";
			if($userEmail!="") {
				$query = $query." and (wc.comment_approved =1  or (wc.comment_approved=0 and wc.comment_author_email= '".$userEmail."'))";
			}
			else {
				$query = $query." and wc.comment_approved =1";
			}

			$postComments = Yii::app()->wpDb->createCommand($query)->queryAll();
			$postCommentArr = BlogUtility::getPostCommentsInOrder($postComments);
			return $postCommentArr;
		}


		/*Created by Jiji on Feb 2017
			This function is arranging comments and replies in order. Also if the commenter name is not there , we are setting it as Anonymous*/
		public static function getPostCommentsInOrder($postComments) {
			global $finalArr;
			$finalArr = array();
			$commentArr = array();
			$parentArr = array();
			$pArr = array();
			foreach ($postComments as $key=>$comment) {
				if($comment['comment_parent'] ==0) {
					array_push($pArr, $comment);
					unset($postComments[$key]);
				}
			}
			//Yii::log(" inside pArr ".json_encode($pArr));
			if(!empty($pArr)) {
				$parentArr[0] = $pArr;
				foreach ($postComments as $comment) {
					if(array_key_exists($comment['comment_parent'], $parentArr)) {
						$childArr = $parentArr[$comment['comment_parent']];
						array_push($childArr, $comment);
						$parentArr[$comment['comment_parent']] = $childArr;
					}
					else{
						$childArr = array();
						array_push($childArr, $comment);
						$parentArr[$comment['comment_parent']] = $childArr;
					}
				}


				$finalArr = BlogUtility::getChildCommentArray($finalArr,$parentArr[0],$parentArr);
			}



			return $finalArr;

		}


		public static function getChildCommentArray($finalArr,$commentArr,$parentArr) {
			global $finalArr;
			if(!empty($commentArr)) {
				foreach ($commentArr as $comment) {
					if(array_key_exists($comment['comment_ID'], $parentArr)) {
						$commentArr = $parentArr[$comment['comment_ID']];
						array_push($finalArr, $comment);
						Yii::log("adding comment in else ".$comment['comment_ID']);
						BlogUtility::getChildCommentArray($finalArr,$commentArr,$parentArr);
					}
					else{
						Yii::log("adding comment in else ".$comment['comment_ID']);
						array_push($finalArr, $comment);
					}

				}
			}

			return $finalArr;
		}



		/*Created By Jiji on Feb 2017
			This function is used to insert comment and its info in to the wp_comments table */
		public static function insertBlogComment($commentInfo) {
			$commentStatus = "";
			if($commentInfo) {
				$comment = New BlogComment();
				$comment->comment_post_ID = $commentInfo['postId'];
				$comment->comment_content = $commentInfo['comment'];
				$comment->comment_author = $commentInfo['commenterName'];
				$comment->comment_author_email = $commentInfo['commenterEmail'];
				$comment->comment_parent = $commentInfo['parentCommentId'];
				$comment->comment_approved = 0;
				$comment->comment_date = date('Y-m-d H:i:s');
				$comment->comment_date_gmt = date('Y-m-d H:i:s');
				if($comment->save()) {
					$commentStatus =0;
				}
			}

			return $commentStatus;
		}
		    /* Nitish Jha
               Blog Notification */

		public static function insertBlogSubscription($blogNotificationSubscription){
		      $blogSubscription= "";
		      if($blogNotificationSubscription){
		          $blog = New BlogSubscription();
		          $blog->recipient = $blogNotificationSubscription['email_Id'];
		          $blog->subscription_plan = $blogNotificationSubscription['subscription_plan'];
		          $blog->category_id = $blogNotificationSubscription['categoryID'];
		          $blog->Is_subscribed = 1;
		          $blog->Is_first_time = 0;
		          $blog->subscribed_date= date('Y-m-d H:i:s');

		          if($blog->save()){
		             $blogSubscription = 0;
		          }
		      }
		      return $blogSubscription;
		}

		public function actionInsertBlogComment() {
        		Yii::log('Begin inserting blog comment', 'info', 'application.actionInsertBlogComment');
        		$res = array();
        		$commentInfo = array();
        		$postId = isset($_REQUEST['postId']) ? $_REQUEST['postId'] : '';
        		$comment = isset($_REQUEST['comment']) ? $_REQUEST['comment'] : '';
        		$commenterName = isset($_REQUEST['commenterName']) ? $_REQUEST['commenterName'] : '';
        		$commenterEmail = isset($_REQUEST['commenterEmail']) ? $_REQUEST['commenterEmail'] : '';
        		$parentId = isset($_REQUEST['parentId']) ? $_REQUEST['parentId'] : '';
        		try{
        			$commentInfo['postId'] = $postId;
        			$commentInfo['comment'] = $comment;
        			$commentInfo['commenterName'] = $commenterName;
        			$commentInfo['commenterEmail'] = $commenterEmail;
        			$commentInfo['parentId'] = $parentId;
        			$commentStatus = BlogUtil::insertBlogComment($commentInfo);
        			$res['commentStatus'] = $commentStatus;
        			$res['status'] = 'success';
        			$res['desc'] = 'Blog comment inserted successfully';

        		}catch(Exception $ex) {
        			Yii::log('Fail to insert blog comment','info','application.actionInsertBlogComment');
        			$res['status']="Fail";
        			$res['posts'] ="";
        			$res['desc']="Fail to insert blog comment";
        		}
        		$this->jencode($res);
        	}


		/*To remove the '," and amp; from titles*/
        public static function processTermName($termName){
            $termName = str_replace('\'','',$termName);
            $termName = str_replace('"','',$termName);
            $termName = str_replace('amp;','',$termName);
            return $termName;
        }
		
		/*To get the URL for sharing the post*/
		public static function getPostShareUrl($postSlug){
			//Yii::log("SHARE URL ".Yii::app()->baseUrl.'/'.$categorySlug.'/'.$publishedOnDate.'/'.$postSlug.'/');
			return HOST_NAME.Yii::app()->baseUrl.'/'.$postSlug.'/';
		}

	    /* Nitish Jha
	       09-05-2017
	       For blog url shortening
	       Remove if loop if blog shorten url we won't implement in future, just keep the else part then
		*/

		public static function getPostCanonicalUrl($postSlug){
			//Yii::log("SHARE URL ".Yii::app()->baseUrl.'/'.$categorySlug.'/'.$publishedOnDate.'/'.$postSlug.'/');
			//return 'https://' . $_SERVER['HTTP_HOST'].Yii::app()->baseUrl.'/'.$categorySlug.'/'.$publishedOnDate.'/'.$postSlug.'/';
			  return BlogUtility::customGetHostName().Yii::app()->baseUrl.'/'.$postSlug.'/';
		}
		public static function customGetHostName(){
			$requestHostName = $_SERVER['HTTP_HOST'];
			$hostName = $_SERVER['HTTP_HOST'];
			if(strpos($requestHostName,MEDINFI_DOMAIN) !== FALSE){
				$hostName = MEDINFI_MAIN_SUBDOMAIN;
			}
			return 'https://'.$hostName;
		}
		
		public static function getPostDetailsFromOtherSources($urlPostSlug){
			$historySlugQuery = "select post_id from wp_postmeta where meta_key = '_wp_old_slug' and meta_value = '".$urlPostSlug."'";
			$historySlugQueryResult = Yii::app()->wpDb->createCommand($historySlugQuery)->queryAll();
			$postDetails = array();
			if(!empty($historySlugQueryResult)){
				///Yii::log("Looking for history!!!");
				$postDetails = BlogUtility::getPostDetails($historySlugQueryResult[0]['post_id'],$isNumericId = true);
			}
			else{
				//Yii::log("No history!!!");
			}
			if(empty($postDetails)){
				//Yii::log("About to Guess!!!");
				$postDetails = BlogUtility::getPostDetails($urlPostSlug,$isNumericId = false ,$isAGuess = true);
			}
			return $postDetails;
		}

		/*This function checks if in the requested URL
		 *1) www is missing 
		 *2) Trailing Slash is missing
		 **/
		public static function needWwwOrTrailingSlash(){
			$requestUri = Yii::app()->request->requestUri;

			$repairedRequestUri = $requestUri;
			if ((stripos($_SERVER['HTTP_HOST'],MEDINFI_DOMAIN) !== false && stripos($_SERVER['HTTP_HOST'],"www") === false)
				|| (false === strpos($repairedRequestUri, '?') && '/' !== substr($repairedRequestUri, strlen($repairedRequestUri) - 1, 1))
				|| ('/' !== substr($repairedRequestUri, strpos($repairedRequestUri, '?') - 1, 1))){
				return true;
			}
			else{
				return false;
			}
			
			//Use this to remove extra trailing slashes
			/*while (false !== strpos($repairedRequestUri, '//')){
				$repairedRequestUri = preg_replace("////", '/', $repairedRequestUri);
			}*/

			/*if (false === strpos($repairedRequestUri, '?') && '/' !== substr($repairedRequestUri, strlen($repairedRequestUri) - 1, 1)){
				$repairedRequestUri .= "/";
			}

			else if ('/' !== substr($repairedRequestUri, strpos($repairedRequestUri, '?') - 1, 1)){
				$repairedRequestUri = substr($repairedRequestUri, 0, strpos($repairedRequestUri, '?')) . '/' . substr($repairedRequestUri, strpos($repairedRequestUri, '?'));
			}*/
		}
		public static function getRedirectUrl(){
			$repairedRequestUri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			if (false === strpos($repairedRequestUri, '?') && '/' !== substr($repairedRequestUri, strlen($repairedRequestUri) - 1, 1)){
				$repairedRequestUri .= "/";
				Yii::log("Without param page ".$repairedRequestUri);
			}

			else if ('/' !== substr($repairedRequestUri, strpos($repairedRequestUri, '?') - 1, 1)){
				$repairedRequestUri = substr($repairedRequestUri, 0, strpos($repairedRequestUri, '?')) . '/' . substr($repairedRequestUri, strpos($repairedRequestUri, '?'));
			}
			
			if(stripos($_SERVER['HTTP_HOST'],MEDINFI_DOMAIN) !== false && stripos($_SERVER['HTTP_HOST'],"www") === false){
				$repairedRequestUri = "www.".$repairedRequestUri;
			}
			
			$repairedRequestUri = 'https://'.$repairedRequestUri;
			
			return $repairedRequestUri;
		}


		//Function to get user IP address.
            public static function getUserIP(){
            $client  = @$_SERVER['HTTP_CLIENT_IP'];
                $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
                $remote  = $_SERVER['REMOTE_ADDR'];

                if(filter_var($client, FILTER_VALIDATE_IP))
                {
                    $ip = $client;
                }
                elseif(filter_var($forward, FILTER_VALIDATE_IP))
                {
                    $ip = $forward;
                }
                else
                {
                    $ip = $remote;
                }

                return $ip;
            }


		/*To get canonicals URLs for all the pages except post-detail page*/
		/*public static function getRedirectUrl($pageType,$termType,$termSlug,$searchInput,$pageNo){
			$canonicalUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			if($pageType == SEARCH_LIST_PAGE){
				$searchCanonicalUrl = "https://".$_SERVER['HTTP_HOST'].Yii::app()->baseUrl;
				$searchCanonicalUrl = $searchCanonicalUrl."/search/".$searchInput."/";
				if($pageNo != FIRST_PAGE){
					$searchCanonicalUrl = $searchCanonicalUrl."page/".$pageNo."/";
				}
				$canonicalUrl = $searchCanonicalUrl;
			}
			else if ($pageType == BLOG_ERROR_PAGE){
				$canonicalUrl = HOST_NAME.Yii::app()->baseUrl.ERROR_PAGE_CANONICAL_URL;
			}
			return strtolower($canonicalUrl);
			if($pageType == BLOG_HOME_PAGE){ //Blog Home Page
				
			}
			else if($pageType == BLOG_LIST_PAGE){ //Blog List Page
				
			}
			else if($pageType == CATEGORY_HOME_PAGE){ //Category Home Page
				
			}
			else if($pageType == CATEGORY_LIST_PAGE){ //Category List Page
				
			}
			else if($pageType == TAG_LIST_PAGE && $pageNo == VALUE_NOT_SET){ //Tag List Page 1 without page no.
				
			}
			else if($pageType == TAG_LIST_PAGE && $pageNo != VALUE_NOT_SET){ //Tag List Page with page no.
				
			}
			else if($pageType == SEARCH_LIST_PAGE && $pageNo != VALUE_NOT_SET){ //Search List Page with page no.
				
			}
			else if($pageType == SEARCH_LIST_PAGE && $pageNo == VALUE_NOT_SET){ //Search List Page without page no.
				
			}
			else if($pageType == PSEUDO_SEARCH_LIST_PAGE && $pageNo != VALUE_NOT_SET){ //Psuedo Search List Page with page no.
				
			}
			else if($pageType == PSEUDO_SEARCH_LIST_PAGE && $pageNo == VALUE_NOT_SET){ //Psuedo Search List Page without page no.
				
			}
		*/

		/*Function to check if the ads should be blocked for this post*/
		public static function checkIfBlockAds($postId)
		{
		    $checkBlogQuery = "Select * from med_block_ads_posts where post_id='".$postId."'";
		    $checkBlogQueryResult = Yii::app()->db->createCommand($checkBlogQuery)->queryAll();

		    if(!empty($checkBlogQueryResult)){
		        $block['hideAds']=true;
		        $block['link']= $checkBlogQueryResult[0]['link'];
		    }
		    else{
		        $block['hideAds']=false;
		        $block['link']= "";
		    }
                return $block;
		}

		public static function getAuthorName($postId)
		{
		    $authorNameQuery = "SELECT display_name from wp_users join wp_posts on wp_users.ID=wp_posts.post_author where wp_posts.ID='".$postId."'";
		    $authorNameQueryResult = Yii::app()->wpDb->createCommand($authorNameQuery)->queryAll();

		    if(!empty($authorNameQueryResult)){
                $author=$authorNameQueryResult[0]['display_name'];
            }
            else{
                $author = "null";
            }

            return $author;
		}

		public static function getPopularPostListForTags($categoryId,$limit){
            $page_view = 'PAGE_VIEW';
            $page_share = 'SHARE';
            //$limit = 10;
            $getpopularpages = "SELECT user_session_page.page_id, COUNT( user_session_page.page_id ) AS count_page_id,page.url as url
                                FROM user_session_page,page
                                WHERE user_session_page.page_id = page.id AND user_session_page.event_type in ('".$page_view."','".$page_share."')
                                and start_time > DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                                GROUP BY user_session_page.page_id
                                order by count_page_id desc";
            $getPopularListQueryResult = Yii::app()->analyticsDb->createCommand($getpopularpages)->queryAll();
            $popularPostList = array();
            $index = 0;
            $urlCategoryId='';
            $allCategories = BlogUtility::getCategories();
            for($i=0;$i<sizeof($getPopularListQueryResult);$i++){
                $getUrl = $getPopularListQueryResult[$i]['url'];
                $getUrl1 = explode("/",$getUrl);
                $len = count($getUrl1);
                $getSlug = $getUrl1[$len-1];
                $urlPostDetails = BlogUtility::getPostDetails($getSlug);
                if(!empty($urlPostDetails)){
                    $categoryInfo2 = BlogUtility::getTermInfoFromName(TERM_CATEGORY,$urlPostDetails['postCategorySlug'],$allCategories);
                    if(!empty($categoryInfo2)){
                        $urlCategoryId = $categoryInfo2['termId'];
                    }
                    if($categoryId == $urlCategoryId){
                        array_push($popularPostList, $urlPostDetails);
                        $index++;
                    }
                }
                if($index == $limit){
                break;
                }
            }
            return $popularPostList;
        }

        public static function getPopularTags($categoryId){
            $tagLimitPerBlog = 3;
            $limit = 10;
            $postList = BlogUtility::getPopularPostListForTags($categoryId,$limit);
            $tagList = array();
            $index = 0;
            if(!empty($postList)) {
                Yii::log("populaar output    ".json_encode($postList));
                foreach ($postList as $post) {
                    $getTagsForPost = "select wtt.term_id,wt.name,wt.slug from wp_terms as wt inner join
                    			wp_term_taxonomy as wtt on wt.term_id = wtt.term_id inner join
                                wp_term_relationships as wtr on wtr.term_taxonomy_id = wtt.term_taxonomy_id inner join
                                wp_posts as wp on wp.ID = wtr.object_id
                                where
                    			wp.post_status = 'publish' and
                    			wtt.taxonomy= 'post_tag' and
                                wp.id = ".$post['id']." ORDER by RAND () LIMIT ".$tagLimitPerBlog;

                    		$tagListQueryResult = Yii::app()->wpDb->createCommand($getTagsForPost)->queryAll();
                    		if(!empty($tagListQueryResult)) {
                                foreach ($tagListQueryResult as $tag) {
                                    $tagList[$index]['tagId'] = $tag['term_id'];
                                    $tagList[$index]['tagName'] = $tag['name'];
                                    $tagList[$index]['tagSlug'] = $tag['slug'];
                    				$tagList[$index]['tagUrl'] = HOST_NAME.Yii::app()->baseUrl.'/'.TERM_TAG.'/'.$tag['slug'].'/';
                    				//Yii::log("ID/Name/Slug/URL >>>> ".$tag['term_id']."||".$tag['name']."||".$tag['slug']."||".$tagList[$index]['tagUrl']);
                                    $index++;
                                }
                            }
                }
            }
            Yii::log("tags output    ".json_encode($tagList));
             return $tagList;
        }


}
?>