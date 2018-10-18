<?php
$this->layoutData = $pageData['layoutData'];
?>
<link href="<?php echo HOST_NAME.Yii::app()->baseUrl.CSS_BASE_PATH.LIST_SEARCH_PAGE_CSS?>" type="text/css" rel="stylesheet"/>

         <script>
         var pageName = "<?php echo $pageData['layoutData']['pageName']?>";
         var categoryId = "<?php echo isset($pageData ['layoutData']['selectedTerm']['selectedTermId'])?$pageData ['layoutData']['selectedTerm']['selectedTermId']: '';?>";
                 $(document).ready(function() {
                     if(categoryId!=''){
                      getTags(categoryId);
                      }
                 });

                  function getTags(categoryId){
                     $.ajax({
                             type: "POST",
                             url: baseUrl+"/blog/getCategoryTags/",
                             data: {'categoryId': categoryId},
                             success: function(data) {
                               obj = $.parseJSON(data);
                              var i = 0;
                              var len = obj.length;
                              for (var key in obj) {
                                if (obj.hasOwnProperty(key)) {
                                  var val = obj[key];
                                  console.log(val['tagName']);
                                   if (i == 0) {
                                          $("div#post-tags").append('<span>Tags </span>');
                                      }
                                  var processedTagName =""+ val['tagName'].replace("\'", "");
                                  $("div#post-tags").append('<a class="tag-anchor" href="'+val['tagUrl']+'" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''+pageName+'\', eventAction: \'Clicked on tag\',eventLabel: \'Clicked on tag '+processedTagName+'\'});">'+val['tagName']+'</a>');


                                    if (i == len - 1) {
                                       $("div#post-tags").append(' . ');
                                    }else{
                                    $("div#post-tags").append(' , ');
                                    }

                                }
                                i++;
                                }

                              },

                             error: function() {
                                 console.log('A problem Occurred!! Please contact to support');
                             }
                      });
                  }

         </script>

<div id="page-row" class="row">    
    <?php
        $layoutData = $pageData['layoutData'];
        if(!empty($pageData['listHeading'])){
            $listHeading = $pageData['listHeading'];
            $listHeadingHtml = '<span id="list-heading">';
            if(!empty($listHeading['nonHeadingTarget'])){
                $listHeadingHtml = $listHeadingHtml.'
                    <h4 id="non-heading-target">
                        '.$listHeading['nonHeadingTarget'].'
                    </h4>';
            }
            if(!empty($listHeading['nonHighlightedTarget']) || !empty($listHeading['highlightedTarget'])){
                $listHeadingHtml = $listHeadingHtml.'<h1 id="heading-target">';
                if(!empty($listHeading['nonHighlightedTarget'])){
                    $listHeadingHtml = $listHeadingHtml.'
                    <span id="non-highlighted-target">
                        '.$listHeading['nonHighlightedTarget'].'
                    </span>';
                }
                if(!empty($listHeading['highlightedTarget'])){
                    $listHeadingHtml = $listHeadingHtml.'
                    <span id="highlighted-target">
                        '.$listHeading['highlightedTarget'].'
                    </span>';
                }
                $listHeadingHtml = $listHeadingHtml.'
            </h1>';
            }
            $listHeadingHtml = $listHeadingHtml.'
        </span>';
        echo $listHeadingHtml;
        }
        if(!empty($pageData['recentPostList'])){
            $recentPostList = $pageData['recentPostList'];
            $postListHtml ='';
            for($index=0;$index < sizeof($recentPostList); $index++){
                $groupId=$index+1;
                $processedPostTitle = str_replace('\'','',$recentPostList[$index]['post_title']);
                $postListHtml=$postListHtml.'
                <a id="recent-row-'.$groupId.'" class="recent-row" href="'.HOST_NAME.Yii::app()->baseUrl.$recentPostList[$index]['post_url'].'" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Clicked on post link '.$groupId.'\',eventLabel: \''.$processedPostTitle.'\'});">
                    <div id="recent-image-div-'.$groupId.'" class="recent-image-div">
                        <img id="recent-image-'.$groupId.'" class="recent-image" src="'.$recentPostList[$index]['post_img'].'"/>
                    </div>
                    <div id="recent-name-date-'.$groupId.'" class="recent-name-date">
                        <h2 id="recent-name-'.$groupId.'" class="recent-name">
                            '.$recentPostList[$index]['post_title'].'
                        </h2>
                        <span id="recent-date-'.$groupId.'" class="recent-date">
                            '.$recentPostList[$index]['post_date'].'
                        </span>
                    </div>
                </a>';
                if($index<(sizeof($recentPostList)-1)){
                    $postListHtml = $postListHtml.'
                    <div class="recent-post-border"></div>';
                }
            }
            echo $postListHtml;
        }
        else{
            $noResultFoundHtml = '<div>
                                          <h1>
                                             Nothing Found
                                          </h1>
                                           <p>
                                             Sorry, but nothing matched your search criteria. Please try again with some different keywords.
                                           </p>
                                       </div>';
            echo $noResultFoundHtml;
        }
        
        if(!empty($pageData['paginationElements'])){
            $paginationElements = $pageData['paginationElements'];
            $paginationHtml = '';
            $currentPage = $pageData['currentPage'];
            $paginationHtml = $paginationHtml.'<div id="pagination-div">
        <ul id="pagination-bar" class="pagination">';
            foreach ($paginationElements as $element){
                $pageNo = $element['pageNo'];
                $pageUrl = $element['pageUrl'];
                if($pageNo == LEFT_ARROW_VALUE){
                    $paginationHtml = $paginationHtml.'
                    <li id="left-arrow-li" class="arrow-li">
                        <a id="left-arrow-anchor" class="arrow-anchor pagination-element" href="'.$pageUrl.'" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Clicked on pagination element\',eventLabel: \'Prev button goto page '.($currentPage-1).'\'});">
                                '.$pageNo.'
                        </a>
                    </li>';
                }
                else if($pageNo == RIGHT_ARROW_VALUE){
                    $paginationHtml = $paginationHtml.'
                    <li id="right-arrow-li" class="arrow-li">
                        <a id="right-arrow-anchor" class="arrow-anchor pagination-element" href="'.$pageUrl.'" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Clicked on pagination element\',eventLabel: \'Next button goto page '.($currentPage+1).'\'});">
                                '.$pageNo.'
                        </a>
                    </li>';
                }
                else if($pageNo == PAGINATION_ELLIPSE_VALUE){
                    $paginationHtml = $paginationHtml.'
                    <li>
                        <span class="disabled-span pagination-element">
                            '.$pageNo.'
                        </span>
                    </li>';
                }
                else if($pageNo == $currentPage){
                    $paginationHtml = $paginationHtml.'
                    <li>
                        <span class="active-span pagination-element">
                            '.$pageNo.'
                        </span>
                    </li>';
                }
                else{
                    $paginationHtml = $paginationHtml.'
                    <li>
                        <a class="inactive-anchor pagination-element" href="'.$pageUrl.'" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Clicked on pagination element\',eventLabel: \'Goto page '.$pageNo.'\'});">
                            '.$pageNo.'
                        </a>
                    </li>';
                }
            }
            $paginationHtml = $paginationHtml.'
            </ul>
        </div>';
        echo $paginationHtml;
        }
    ?>

     <?php
                if(!empty($pageData['categoryTags'])){
                    $postTagList = $pageData['categoryTags'];
                    $postTagHtml = '<div id="featured-posts-col" class="col-sm-12 col-xs-12"> <div id="post-tags">
                <span>Tags ';
                    for($index=0;$index < sizeOf($postTagList);$index++){
                        $tag = $postTagList[$index];
                        $processedTagName = str_replace("\'","",$tag['tagName']);
                        $postTagHtml = $postTagHtml.'
                        <a class="tag-anchor" href="'.$tag['tagUrl'].'" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Clicked on tag\',eventLabel: \'Clicked on tag '.$processedTagName.'\'});">
                            '.$tag['tagName'].'
                        </a>';
                        if($index < (sizeof($postTagList)-1)){
                            $postTagHtml = $postTagHtml.',';
                        }
                        else{
                            $postTagHtml = $postTagHtml.'.';
                        }
                    }
                    $postTagHtml = $postTagHtml.'
                </span>
            </div></div>';
                    echo $postTagHtml;
                } else {

                $postTagHtml = '<div id="featured-posts-col" class="col-sm-12 col-xs-12"> <div id="post-tags">
                 ';
                 $postTagHtml = $postTagHtml .'
                             </div></div>';
                                    echo $postTagHtml;
                }

                ?>

</div>
                        