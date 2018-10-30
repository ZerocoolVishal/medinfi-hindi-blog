<?php
$this->pageTitle=Yii::app()->name;
$this->layoutData = $pageData['layoutData'];
$layoutData = $pageData['layoutData'];
$block = $pageData['additional']['block'];
?>
<head>
    <link href="<?php echo HOST_NAME.Yii::app()->baseUrl.CSS_BASE_PATH.POST_DETAIL_CSS?>" type="text/css" rel="stylesheet"/>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-MGTBDB7');</script>
    <!-- End Google Tag Manager -->
    <script>
        var postTitle = "<?php echo $pageData['postDetails']['postTitle']?>";
        var processedPostTitle = postTitle.replace("'","");
        var postCategoryId = "<?php echo $this->layoutData['selectedTerm']['selectedTermId'];?>";
        var unsubEmail= "<?php echo $this->layoutData['selectedTerm']['selectedTermUnsubscribe']; ?>";
        var unsubRedirectUrl = "<?php echo HOST_NAME.Yii::app()->baseUrl; ?>";
        var trackSource= "<?php echo $this->layoutData['selectedTerm']['trackSource']; ?>";
        var med_host="<?php echo HOST_NAME; ?>";
        var questionCheck= "1";
        var blogCategoryName = "<?php echo $this->layoutData['selectedTerm']['selectedTermName']?>";
        var sessionId = "<?php echo $pageData ['primeSurveyDetail']['sessionId'] ?>";
        var fetchLocation = "<?php echo $pageData ['userCurrentLocation']['fetchLocation'] ?>";
        var userLogin = "<?php echo $pageData ['userCurrentLocation']['userLogin'] ?>";
        var userCity = "<?php echo $pageData ['userCurrentLocation']['userLocality'] ?>";
        var readMore = "<?php echo $pageData['userCurrentLocation']['readMoreOption']?>";
        var imgClickLink = "<?php echo $pageData['additional']['imgClickLink']?>";
    </script>
    <style>
        #post-image {
            max-width: 750px;
        }

        #post-content {
            max-width: 900px;
        }
    </style>
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MGTBDB7"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div id="page-row" class="page-row-class">
        <?php
            if(isset($layoutData['postId']) && ($layoutData['postId']=='1316' || $layoutData['postId']=='1092' || $layoutData['postId']=='763' || $layoutData['postId']=='1862' || $layoutData['postId']=='1729' )){
                 $adsense_mid_level='<div id="adsense_code_bottom" class="amazon_affiliate_ads">
                              <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                              <!-- Ad Unit - 320*50 -->
                              <ins class="adsbygoogle"
                                    style="display:inline-block;width:320px;height:50px"
                                    data-ad-client="ca-pub-1541745750577109"
                                    data-ad-slot="6668671074"></ins>
                              <script>
                              (adsbygoogle = window.adsbygoogle || []).push({});
                              </script>
                              </div>';

                         echo $adsense_mid_level;
            }


            $processedPostTitle = str_replace('\'','',$pageData['postDetails']['postTitle']);
            if(!empty($this->layoutData['selectedTerm'])){
                $selectedTerm = $this->layoutData['selectedTerm'];
                $processedCategoryName = str_replace('\'','',$selectedTerm['selectedTermName']);
                $xsCategoryLinkHtml = '<a id="xs-category-link" href="'.HOST_NAME.Yii::app()->baseUrl.'/'.TERM_CATEGORY.'/'.$selectedTerm['selectedTermSlug'].'/page/1/" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Clicked on category backlink\',eventLabel: \'Clicked on category backlink - '.$processedCategoryName.'\'});">
                    <h4 id="xs-category-link-text" class="xs-category-link-element">
                        '.LEFT_ARROW_VALUE.' '.$selectedTerm['selectedTermName'].'
                    </h4>
                </a>';
                echo $xsCategoryLinkHtml;
            }
        ?>
        <div id="title-row-div" class="row">
            <div id="post-title-col" class="col-xs-12 col-md-12">
    <!--             <div class="row">
                <div class="col-xs-12" style="text-align:center"> -->
                  <!-- Nitish Jha
                       Amazon Affiliate Ad-slots (Pilot)
                       27-04-2017


                <?php
                    /* if(array_key_exists($layoutData['postId'],$GLOBALS['top_level'])) {
                          echo '<div class="amazon_affiliate_ads">'.$GLOBALS['top_level'][$layoutData['postId']].'</div>';
                    } */ ?>


                   <!-- Amazon affiliated banner ads -->
    <!--                        <div class="row">
                           <div class="col-xs-12" style="text-align:center">
                           <div id="mobile-amazon-banner-ads-anchor" >
                           <a target="_blank"  href="https://www.amazon.in/health-and-personal-care/b/ref=sd_allcat_beauty_health_all?ie=UTF8&amp;node=1350384031&_encoding=UTF8&tag=medinfi-21&linkCode=ur2&linkId=2705ddc402eb6a5f85cbb70fae3d23de&camp=3638&creative=24630"
                           onclick="trackAdGA();" >
                               <div><img src="<?php //echo Yii::app()->baseUrl.//TEMPLATE_IMAGE_BASE_PATH.AMAZON_AD_IMAGE ?>" style="border:0.5px solid #eae6e6; width: 100%;" alt=""/></div>
                           </a>
                           </div>
                           </div>
                           </div> -->

                <h1 id="post-title" style="font-size: 40px">
                    <?php
                      echo $pageData['postDetails']['postTitle'];
                    ?>
                </h1>

                <div>
                        <?php
                            echo "<span id='date-and-author'>".$pageData['postDetails']['postDate']."</span> पर प्रविष्ट किया";

                            if($block && $pageData['additional']['author']!="null")
                                echo " by <span id='date-and-author'>".$pageData['additional']['author']." </span><br>";
                        ?>
                </div>

            </div>
            <div id="right-whitespace-col" class="col-xs-0 col-md-5"></div>
        </div>
        <!--Col containing post content,share tags comments, reply -->
        <div id="content-row-div" class="row">
            <div id="post-detail-col" class="col-xs-12 col-md-12" style="font-size: 20px;">

            <?php if($block && $pageData['additional']['imgClickLink']!=""){ ?>
            <a id"linkid" onclick="return openImgLink();" >
            <img id="post-image" src="<?php echo $pageData['postDetails']['postImage']?>" />
            </a>
            <?php }else{ ?>
                <img id="post-image" src="<?php echo $pageData['postDetails']['postImage']?>" />
            <?php    } ?>

                <div class="row" style="padding-left: 15px;">
                    <div id="center-share-div" class="col-xs-12 " >
                                            <a id="center-share-img-anchor" onclick="showShare()" class="share-feedback-img-anchor" style="width: 100%;">
                                                <img id="center-share-img" class="share-feedback-img" src="<?php echo SHARE_POST_ENCODED_IMAGE?>" alt=""/>
                                                <div id="center-share-text" class="share-feedback-text " style="font-size: 20px">
                                                    <?php echo SHARE_TEXT ?>
                                                </div>
                                            </a>
                                         </div>
                             </div>

                 <div id="ads">
                  <?php


                  if(!$block)
                  {
                        Yii::log("MID LEVEL AD");

                      /* Nitish Jha
                           Google Ad-sense of size 320*50 in Mobile View
                           04-08-2017
                       */


                             $adsense_mid_level='<div id="adsense_code_bottom" class="amazon_affiliate_ads">
                                  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                  <!-- Ad Unit - 320*50 -->
                                  <ins class="adsbygoogle"
                                       style="display:inline-block;width:320px;height:50px"
                                       data-ad-client="ca-pub-1541745750577109"
                                       data-ad-slot="6668671074"></ins>
                                  <script>
                                  (adsbygoogle = window.adsbygoogle || []).push({});
                                  </script>
                             </div>';
                             echo $adsense_mid_level;

                       /* Nitish Jha
                          Google Ad-sense Responsive in Desktop View
                          10-08-2017
                       */

                             $adsense_responsive='<div id="adsense_code_midlevel" class="adsense_ads">
                                   <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                       <!-- Responsive Ads in Desktop -->
                                       <ins class="adsbygoogle"
                                            style="display:block"
                                            data-ad-client="ca-pub-1541745750577109"
                                            data-ad-slot="3824578670"
                                            data-ad-format="auto"></ins>
                                            <script>
                                                (adsbygoogle = window.adsbygoogle || []).push({});
                                            </script>
                                       </div>';
                             echo $adsense_responsive;
                  }


                   /*
                   Nitish Jha
                   11-05-2017
                   First-Cry-Banner Ads
                   We have commented this as in future if any ads require we can use this code
                   */
                   /*
                   else if(array_key_exists($layoutData['postId'],$GLOBALS['first_cry_mid_level'])) {
                   $caseValue = $GLOBALS['first_cry_mid_level'][$layoutData['postId']];
                   switch ($caseValue) {
                       case 1: ?>
                           <div class="amazon_affiliate_ads"><a target="_blank" href="<?php echo OFFER_FIRST_CRY ?>" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'FirstCry_MidLevel',eventLabel: 'Flat499_320*100'});" ><img  class="first-cry-img" src="<?php echo Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.FIRST_CRY_MID_SMALL ?>" alt=""/></a></div>
                           <?php break;
                       case 2: ?>
                           <div class="amazon_affiliate_ads"><a target="_blank" href="<?php echo FIRST_CRY_OFFER ?>" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'FirstCry_MidLevel',eventLabel: 'Flat600_320*100'});" ><img  class="first-cry-img" src="<?php echo Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.FIRST_CRY_BOTTOM_SMALL ?>" alt=""/></a></div>
                           <?php break;
                       case 3: ?>
                           <div class="amazon_affiliate_ads"><a target="_blank" href="<?php echo OFFER_FIRST_CRY ?>"  onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'FirstCry_MidLevel',eventLabel: 'Flat499_320*250'});" ><img  class="first-cry-img" src="<?php echo Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.FIRST_CRY_MID_BIG ?>" alt=""/></a></div>
                           <?php break;
                       case 4: ?>
                           <div class="amazon_affiliate_ads"><a target="_blank" href="<?php echo FIRST_CRY_OFFER ?>" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'FirstCry_MidLevel',eventLabel: 'Flat600_320*250'});" ><img  class="first-cry-img" src="<?php echo Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.FIRST_CRY_BOTTOM_BIG ?>" alt=""/></a></div>
                        <?php   break;
                          }
                   }
                  */ ?>
                </div>


                <div id="post-content" class="blog-post-content">
                <?php

                 $pageData['postDetails']['postContent']=str_replace('#google_320_50_ad',GOOGLE_320_50_AD ,$pageData['postDetails']['postContent']);
                 $pageData['postDetails']['postContent']=str_replace('#google_responsive_ad',GOOGLE_RESPONSIVE_AD ,$pageData['postDetails']['postContent']);
                 $pageData['postDetails']['postContent']=str_replace('#google_responsive_320_50_ad',GOOGLE_RESPONSIVE_320_50_AD ,$pageData['postDetails']['postContent']);


                 if($pageData['userCurrentLocation']['readMoreOption'] == "TRUE"){
                  // echo $pageData['postDetails']['postContent'];
                   $stringPost =  $pageData['postDetails']['postContent'];

                    // strip tags to avoid breaking any html
                    //$string = strip_tags($stringPost);

                    if (strlen($stringPost) > 500) {

                        // truncate string
                        $stringCut = substr($stringPost, 0, 500);

                        // make sure it ends in a word so read doesn't become re...
                        $stringPost = substr($stringCut, 0, strrpos($stringCut, ' ')).'... <a id="read-more" href="'.HOST_NAME.Yii::app()->baseUrl.LOGIN_URL.'" style="font-weight: bold;" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Read More Clicked\',eventLabel: \'Read More\'});">Read More</a>';
                    }
                    echo $stringPost;
                  }else{
                     echo $pageData['postDetails']['postContent'];
                  }
                ?>
                </div>

                <?php
                if(!empty($pageData['postTagList'])){
                    $postTagList = $pageData['postTagList'];
                    $postTagHtml = '<div id="post-tags">
                    <span>Tagged ';
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
                </div>';
                    echo $postTagHtml;
                }
             /*  if($pageData ['primeSurveyDetail']['isSurveyEnable'] == "TRUE"){
                     if($processedCategoryName == "Womens Health"){?>
                      <div id="survey-questions" class="row">
                            <div id = "question-div">
                                <div id="first-question" class="question-class" value="<?php echo WOMENS_BLOG_FIRST_QUESTION?>">
                                    <img class="med-logo-survey" src="<?php echo Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.SPLASHLOGO?>"/>
                                    <p class="question-text">Would you want to choose your blog topic?</p>
                                </div>
                                <div id="second-question" class="question-class" value="<?php echo SECOND_QUESTION?>">
                                    <p class="question-text">Would you like to pay a minimal fee for this?</p>
                                </div>
                            </div>

                            <div id="answer-div" >

                              <div class="answer-survey" >
                                <div class="col-xs-4">
                                    <a href="javascript:void(0);" id="yes-answer" class="imgContainer" onclick="answer(this)" value="1">
                                        <img class="answer-img" src="<?php echo Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.YES?>" height="20" width="20"/>
                                        <p class="answer-text">YES</p>
                                    </a>
                                </div>
                                <div class="col-xs-4">
                                    <a href="javascript:void(0);" id="no-answer" class="imgContainer" onclick="answer(this)">
                                       <img class="answer-img" src="<?php echo Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.NO?>" height="20" width="20"/>
                                        <p class="answer-text">NO</p>
                                    </a>
                                </div>
                                <div class="col-xs-4">
                                    <a  href="javascript:void(0);" id="notsure-answer" class="imgContainer" onclick="answer(this)">
                                      <img class="answer-img" src="<?php echo Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.NOTSURE?>" height="20" width="20"/>
                                      <p class="answer-text">NOT SURE</p>
                                    </a>
                                </div>
                              </div>
                            </div>

                            <div id="thankyou-div">
                                <div class="thankyou-class">
                                  <img class="med-logo-survey" src="<?php echo Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.SPLASHLOGO?>"/>
                                  <p class="thankyou-text">Thank you for your valuable feedback.</p>
                                </div>
                            </div>
                      </div>
                    <?php }
                     else if ($processedCategoryName=="Health Conditions") { ?>

                      <div id="survey-questions" class="row">
                            <div id = "question-div">
                                <div id="first-question" class="question-class" value="<?php echo HEALTH_BLOG_FIRST_QUESTION?>">
                                    <img class="med-logo-survey" src="<?php echo Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.SPLASHLOGO?>"/>
                                    <p class="question-text">Would you want to request a specialist doctor in your locality?</p>
                                </div>
                                <div id="second-question" class="question-class" value="<?php echo SECOND_QUESTION?>">
                                    <p class="question-text">Would you like to pay a minimal fee for this?</p>
                                </div>
                            </div>

                            <div id="answer-div" >

                              <div class="answer-survey" >
                                <div class="col-xs-4">
                                    <a href="javascript:void(0);" id="yes-answer" class="imgContainer" onclick="answer(this)" value="1">
                                        <img class="answer-img" src="<?php echo Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.YES?>" height="20" width="20"/>
                                        <p class="answer-text">YES</p>
                                    </a>
                                </div>
                                <div class="col-xs-4">
                                    <a href="javascript:void(0);" id="no-answer" class="imgContainer" onclick="answer(this)">
                                       <img class="answer-img" src="<?php echo Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.NO?>" height="20" width="20"/>
                                        <p class="answer-text">NO</p>
                                    </a>
                                </div>
                                <div class="col-xs-4">
                                    <a  href="javascript:void(0);" id="notsure-answer" class="imgContainer" onclick="answer(this)">
                                      <img class="answer-img" src="<?php echo Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.NOTSURE?>" height="20" width="20"/>
                                      <p class="answer-text">NOT SURE</p>
                                    </a>
                                </div>
                              </div>
                            </div>

                            <div id="thankyou-div">
                                <div class="thankyou-class">
                                 <img class="med-logo-survey" src="<?php echo Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.SPLASHLOGO?>"/>
                                  <p class="thankyou-text">Thank you for your valuable feedback.</p>
                                </div>
                            </div>
                      </div>
                  <?php } } */
                    //removed adsence filter for blog
                    if(!$block)
                    {
                        Yii::log("BOTTOM AD");
                        $adsense_bottom='<div id="adsense_code_bottom">
                                            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                             <!-- Pilot Launch Ad -->
                                             <ins class="adsbygoogle"
                                                  style="display:block"
                                                  data-ad-client="ca-pub-1541745750577109"
                                                  data-ad-slot="3824578670"
                                                  data-ad-format="auto"></ins>
                                             <script>
                                             (adsbygoogle = window.adsbygoogle || []).push({});
                                             </script>
                                        </div>';
                         echo $adsense_bottom;
                    }
                    $commentSectionHtml = '';
                    if(!empty($pageData['commentCount']) > 0){
                        $postComments = $pageData['postComments'];
                        $commentSectionHtml = '
                    <h2 id="comments-section-title">
                        '.$pageData['commentCount'].COMMENT_SECTION_TITLE.'
                        </h2>
                        <div id="comments-list">';
                        for($index = 0; $index < $pageData['commentCount']; $index++){
                            $commentTagIndex = $index + 1;
                            $commentSectionHtml = $commentSectionHtml.'
                                <div id="comment-'.$postComments[$index]['comment_ID'].'" class="comment-element">
                                    <img class="user-profile-image" alt="" src="'.HOST_NAME.Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.USER_PLACEHOLDER_PROFILE_IMAGE.'"/>
                                    <div id="commment-details-'.$commentTagIndex.'" class="comment-details">
                                        <span id="comment-meta-'.$commentTagIndex.'" class="comment-meta">
                                            '.$postComments[$index]['comment_author'].' said on <a class="comment-date-anchor" href="#comment-'.$postComments[$index]['comment_ID'].'" onclick="ga(\'send\', {hitType: \'event\',eventCategory:\''.$layoutData['pageName'].'\', eventAction: \'Clicked on comment date anchor\',eventLabel: \'Comment ID - '.$postComments[$index]['comment_ID'].'\'});" >
                                                '.$postComments[$index]['comment_date'].'</a>:
                                        </span>
                                        <span id="comment-text-'.$commentTagIndex.'" class="comment-text">
                                            '.$postComments[$index]['comment_content'].'
                                        </span>
                                        <a rel="nofollow" id="comment-reply-'.$commentTagIndex.'" class="comment-reply" href="#response-section" onclick="replyTo('.$postComments[$index]['comment_ID'].')">
                                            Reply ↓
                                        </a>
                                    </div>
                                </div>
                                ';
                        }
                        $commentSectionHtml = $commentSectionHtml.'
                    </div>';
                    }
                ?>
                <div id="comments-section">
                    <?php echo $commentSectionHtml ?>

                    <div id="response-section" name="response-section">
                        <h3 id="response-section-title">उत्तर छोड़ दें</h3>
                        <div id="response-form">
                            <div id="comment-area" class="response-form-row">
                                <div id="response-comment-label-div" class="reponse-form-label-div">
                                    <label id="response-comment-label">टिप्पणी</label>
                                </div>
                                <div id="response-comment-text-area-div" class="reponse-form-input-div">
                                    <textarea id="response-comment-text-area" class="reponse-form-input" rows="8"></textarea>
                                    <label id="response-comment-error-message" class="error-message"><?php echo COMMENT_ERROR_MESSAGE?></label>
                                </div>
                            </div>
                            <div id="name-input" class="response-form-row">
                                <div id="response-name-label-div" class="reponse-form-label-div">
                                    <label id="response-name-label">नाम</label>
                                </div>
                                <div id="response-name-input-div" class="reponse-form-input-div">
                                    <input id="response-name-input" class="reponse-form-input" type="text">
                                </div>
                            </div>
                            <div id="email-input" class="response-form-row" >
                                <div id="response-email-label-div" class="reponse-form-label-div">
                                    <label id="response-comment-label">ईमेल</label>
                                </div>
                                <div id="response-email-input-div" class="reponse-form-input-div">
                                    <input id="response-email-input" class="reponse-form-input" type="text">
                                    <label id="response-email-error-message" class="error-message"><?php echo EMAIL_ERROR_MESSAGE?></label>
                                    <span id="comment-email-subtext"><?php echo COMMENT_EMAIL_SUBTEXT?></span>
                                </div>
                            </div>
                            <div id="post-button-row" class="response-form-row" >
                                <div id="post-button-whitespace" class="reponse-form-label-div">
                                </div>
                                <div id="post-button-div" class="reponse-form-input-div">
                                     <button id="post-button" type="button">उत्तर पोस्ट करें</button>
                                </div>
                                <input type="hidden" name="comment_post_ID" value="<?php echo $pageData['postDetails']['id'] ?>" id="comment_post_ID">
                                <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo HOST_NAME.Yii::app()->baseUrl.JS_BASE_PATH.POST_DETAIL_JS?>"></script>

    <div id="comment-acknowledgement-modal" class="modal">
      <div id="comment-acknowledgement-modal-content" class="modal-content">
        <span id="comment-acknowledgement-modal-close" class="close">&times;</span>
        <p id="model-text"><?php echo COMMENT_ACKNOWLEDGEMENT_MESSAGE ?></p>
      </div>
    </div>
        <!-- Created By Jiji on Feb 2017
            Below modal popup is for share feature -->
        <div id="shareModal" class="modal">

          <!-- Share Modal content -->
          <div id="shareModalContent" class="modal-content">
            <div id="shareModalHeader">
                <img id="modal-share-img" class="share-feedback-img" src="<?php echo HOST_NAME.SHARE_POST_ENCODED_IMAGE?>" alt=""/>
                <span>&nbsp;&nbsp;SHARE WITH FRIENDS</span>
                <span id="shareClose" onclick="shareClose()" class="close">&times;</span>
            </div>

            <div id="shareModalBody">
                <a href = "<?php echo 'whatsapp://send?text=Hey%20check%20this%20out%20'.$pageData['layoutData']['pageShareUrl'] ?>" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName']?>', eventAction: 'Shared - <?php echo $processedPostTitle?>',eventLabel: 'Shared using WhatsApp'});trackEvents('SHARE');">
                    <img id="whatsapp-share-img" class="share-imgs" src="<?php echo HOST_NAME.Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.WHATS_APP_ICON?>" alt=""/>
                </a>

                <a href="<?php echo 'http://www.facebook.com/sharer.php?u='.urlencode($pageData['layoutData']['pageShareUrl']) ?>" target="_blank" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName']?>', eventAction: 'Shared - <?php echo $processedPostTitle?>',eventLabel: 'Shared using Facebook'});trackEvents('SHARE');">
                    <img id="fb-share-img" class="share-imgs" src="<?php echo HOST_NAME.Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.FB_ICON?>" alt=""/>
                </a>

                 <a href="<?php echo 'https://twitter.com/share?url='.urlencode($pageData['layoutData']['pageShareUrl'])."&amp;text=Hey%20check%20this%20out" ?>" target="_blank" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName']?>', eventAction: 'Shared - <?php echo $processedPostTitle?>',eventLabel: 'Shared using Twitter'});trackEvents('SHARE');">
                    <img id="twitter-share-img" class="share-imgs" src="<?php echo HOST_NAME.Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.TWITTER_ICON?>" alt=""/>
                </a>

                <a href="<?php echo 'mailto:?subject='.rawurlencode($pageData['postDetails']['postTitle'])."&body=Hey%20check%20this%20out:%20".$pageData['layoutData']['pageShareUrl'] ?>" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName']?>', eventAction: 'Shared - <?php echo $processedPostTitle?>',eventLabel: 'Shared using Email'});trackEvents('SHARE');">
                    <img id="mail-share-img" class="share-imgs" src="<?php echo HOST_NAME.Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.MAIL_ICON?>" alt=""/>
                </a>

                <a href="<?php echo 'http://www.linkedin.com/shareArticle?mini=true&amp;url='.urlencode($pageData['layoutData']['pageShareUrl']) ?>" target="_blank" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName']?>', eventAction: 'Shared - <?php echo $processedPostTitle?>',eventLabel: 'Shared using LinkedIn'});trackEvents('SHARE');">
                    <img id="linkedin-share-img" class="share-imgs" src="<?php echo HOST_NAME.Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.LINKEDIN_ICON?>" alt=""/>
                </a>
            </div>

          </div>

        </div>

    <!-- Nitish Jha
           Blog Notification -->
        <div id="newsletterModal" class="modal">

           <!-- Share Modal content -->
          <div id="newsletterModalContent" class="modal-newsletter-content">
                <div id="newsletterModalHeader">
                    <img id="get-newsletter-img" class="newsletter-img" src="<?php echo HOST_NAME.Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.GET_NEWSLETTER?>" alt=""/>
                    <span>&nbsp;&nbsp;GET HEALTH NEWSLETTER</span>
                    <span id="newsletterClose" class="close">&times;</span>
                </div>

            <div id="newsletterModalBody">
               <div id="newsletter-info-text-div">
                   <span id="newsletter-info-text-span">
                   <?php echo GET_NEWSLETTER_INFO; ?>
                   </span>
               </div>
               <div id="blog-notification-border-div">
                   <input id="email-notification" type="text" class="form-control"  placeholder="Your Email Address" onkeyup="checkEmail(this)">
                   <img id="email-notification-border" src="<?php echo HOST_NAME.Yii::app()->baseUrl.TEMPLATE_IMAGE_BASE_PATH.SEARCH_BOX_BORDER?>" alt=""/>
                   <label id="email-notification-error-message" class="email-error-message"><?php echo EMAIL_ERROR_MESSAGE;?></label>
               </div>
               <div id="newsletter-send-details-text-div">
                   <span id="newsletter-send-details">
                   <?php echo HOW_OFTEN_SHOULD_WE_SEND; ?>
                   </span>
               </div>
               <div id="newsletter-subscription-plan-div">
                   <button type="button" id="daily-subscription" class="subscription-button" onclick="subscriptionPlan(this);">DAILY</button>
                   <button type="button" id="weekly-subscription" class="subscription-button" onclick="subscriptionPlan(this);">WEEKLY </button>
                   <button type="button"  id="monthly-subscription" class="subscription-button" onclick="subscriptionPlan(this);">MONTHLY</button>
                   <label id="subscription-plan-click" class="error-message"><?php echo SUBSCRIBE_ERROR_MESSAGE;?></label>
               </div>
               <div id="policy-termsncondition" >
                    <p>By clicking subscribe, I agree to Medinfi's
                    <a target="_blank" href="<?php echo MEDINFI_FOLDER_BASE_URL."/termscondition"?>" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Newsletter Modal Popup',eventLabel: 'Terms Of Use'});" style="color:#EA235B;text-decoration: underline;">Terms of Use</a>
                       and <a target="_blank" style="color:#EA235B;text-decoration: underline;" href="<?php echo MEDINFI_FOLDER_BASE_URL."/privacypolicy"?>" onclick="ga('send', {hitType: 'event',eventCategory:'<?php echo $layoutData['pageName'] ?>', eventAction: 'Newsletter Modal Popup',eventLabel: 'Privacy Policy'});"> Privacy Policy</a>
                    </p>
               </div>
               <div id="subscription-button-div">
                    <button type="button" id="subButton" class="newsletter-subscribe" onclick="checkSubscribe(this)">SUBSCRIBE</button>
               </div>
               <input type="hidden" name="category_Name" value="<?php echo $processedCategoryName; ?>" id="blog_category_Name">
            </div>
          </div>
        </div>
        <div id="subscription-acknowledgement-modal" class="modal">
          <div id="subscription-acknowledgement-modal-content" class="modal-content">
            <span id="subscription-acknowledgement-modal-close" class="close">&times;</span>
            <p id="model-text" class="acknowledgement-model-text"><?php echo SUBSCRIPTION_ACKNOWLEDGEMENT_MESSAGE ;?></p>
           <!-- <span class="glyphicon glyphicon-ok"></span> -->
            <p id="model-text-message_second" class="acknowledgement-text"><?php echo SUBSCRIPTION_ACKNOWLEDGEMENT_MESSAGE_SECOND; ?></p>
            <p id="model-text-message" class="acknowledgement-text"><?php echo SUBSCRIPTION_ACKNOWLEDGEMENT_MESSAGE_THIRD; ?></p>
          </div>
        </div>
        <!--
          Blog Notification Unsubscribe
          Nitish Jha
          13-04-2017
        -->
        <div id="unsubscribe-modal-popup" class="unsubscribe-modal">
          <div id="unsubscribe-modal-content" class="modal-content">
            <div id="unsubscribe-text">
             <img id="unsubscribe-newsletter-img" class="newsletter-img" src="<?php echo HOST_NAME.Yii::app()->baseUrl.POST_DETAIL_PAGE_IMAGE_BASE_PATH.UNSUBSCRIBE_NEWSLETTER?>" alt=""/>
             <div id="unsub-newsletter-text">&nbsp;&nbsp;<?php echo UNSUBSCRIBE_NEWSLETTER_TEXT; ?></div>
            </div>
             <div id="unsubscribe-button" class="unsubscribe-radio">
             <input type="radio" name="unsubscribe" value="unsubscribe" id="unsubscribe-radio-button" /><span id="remove-sub">&nbsp;&nbsp;Remove me from Medinfi Subscriptions</span><br>
             <label id="unsubscription-radio-click" class="error-message"><?php echo UNSUBSCRIBE_ERROR_MESSAGE;?></label>
             </div>
             <div id="unsubscribe-submit-button-div">
                <button type="button" id="unsubButton" class="unsubscribe-submit">SUBMIT</button><span id="unsubscription-close" class="close">&nbsp;&nbsp; No, Keep my Subscription</span>
             </div>
          </div>
        </div>

        <script type="application/javascript">
            function showShare() {
                document.getElementById('shareModal').style.display = "block";
            }

            function shareClose() {
                document.getElementById('shareModal').style.display = "none";
            }
        </script>
</body>