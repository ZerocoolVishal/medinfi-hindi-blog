<?php
$this->pageTitle=Yii::app()->name;
$this->layoutData = $pageData['layoutData'];
?>
<link href="<?php echo HOST_NAME.Yii::app()->baseUrl.CSS_BASE_PATH.HOME_PAGE_CSS?>" type="text/css" rel="stylesheet"/>
<script>
    var trackSource = "<?php echo $pageData['layoutData']['trackSource']; ?>";
    var selectedCategory = "<?php echo isset($pageData ['layoutData']['selectedTerm']['selectedTermName'])?$pageData ['layoutData']['selectedTerm']['selectedTermName'] : ''; ?>";

</script>
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
         <style>
            @media only screen and (max-width: 768px) {
                #page-row {
                    padding-top: 0px !important;
                }
            }
         </style>
<div id="page-row" class="row">


                <h1 id="blog-category-title" style="font-weight: 400;">
                    <div id="featured-posts-col" class="col-sm-12 col-xs-12" style="margin-bottom: 10px; margin-top: 10px;">
                        <?php
                        if(!empty($pageData ['layoutData']['selectedTerm']['selectedTermName'])){
                          echo $pageData ['layoutData']['selectedTerm']['selectedTermName']." ब्लॉग";
                          }
                        ?>
                     </div>
                </h1>

        <?php
        $layoutData = $pageData['layoutData'];
         if(!empty($pageData['recentPostList'])){
            $recentPostList = $pageData['recentPostList'];
            $postListHtml = '<!--Featured Post Column-->
                <div id="featured-posts-col" class="col-sm-6 col-xs-12">';
            for($index=0;$index < sizeof($recentPostList); $index++){
                $processedPostTitle = str_replace('\'','',$recentPostList[$index]['post_title']);
                if($index < NO_OF_FEATURED_POST){
                    $groupId=$index+1;
                    $postListHtml=$postListHtml.'
            <div id="featured-row-'.$groupId.'" class="featured-row">
                <a id="featured-anchor-border-'.$groupId.'" class="featured-anchor-border" href="'.HOST_NAME.Yii::app()->baseUrl.$recentPostList[$index]['post_url'].'" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Clicked on featured post\',eventLabel: \''.$processedPostTitle.'\'});">
                    <div id="featured-image-desc-'.$groupId.'" class="featured-image-desc">
                        <img id="featured-image-'.$groupId.'" class="featured-image" src="'.$recentPostList[$index]['post_img'].'"/>
                        <div id="featured-desc-'.$groupId.'" class="featured-desc">
                            <div id="featured-content-'.$groupId.'" class="feature-content">
                                <h2 class="featured-name">
                                    '.$recentPostList[$index]['post_title'].'
                                </h2>
                                <span class="featured-date">
                                    '.$recentPostList[$index]['post_date'].'
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>';
                }
                else{
                    $groupId=($index)-1;
                    if($index==NO_OF_FEATURED_POST){
                        $postListHtml=$postListHtml.'
                        <!--Recent Post Column-->
                        <div id="recent-posts-col" class="col-sm-6 col-xs-12">';
                    }
                    $postListHtml=$postListHtml.'
                        <a id="recent-row-'.$groupId.'" class="recent-row" href="'.HOST_NAME.Yii::app()->baseUrl.$recentPostList[$index]['post_url'].'" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Clicked on recent post\',eventLabel: \''.$processedPostTitle.'\'});">
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
                if($index==NO_OF_FEATURED_POST-1 || $index==(sizeof($recentPostList)-1)){
                    if($index==(sizeof($recentPostList)-1) && $pageData['showViewAll']==true){
                        $viewAllLink = HOST_NAME.Yii::app()->baseUrl."/page/1/";
                        if(!empty($this->layoutData['selectedTerm'])){
                            $selectedTerm = $this->layoutData['selectedTerm'];
                            $viewAllLink = HOST_NAME.Yii::app()->baseUrl."/".$selectedTerm['selectedTermSlug']."/page/1/";
                        }
                        $postListHtml = $postListHtml.'
                        <div id="view-all">
                            <a id="view-all-anchor" href="'.$viewAllLink.'" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Clicked on view all blog post link\',eventLabel: \'Clicked on view all blog post link\'});">
                                '.VIEW_ALL_BLOG_POSTS.'
                            </a>
                        </div>';
                    }
                    $postListHtml = $postListHtml.'
                </div>';
                }
            }
            echo $postListHtml;
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

</div><!--page-row-->
