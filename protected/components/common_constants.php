<?php

//Base paths
//Original Version
/*define("CSS_BASE_PATH","/css/v9.2/originals/");
define("JS_BASE_PATH","/js/v9.2/originals/");*/

//Minified Version
define("CSS_BASE_PATH","/css/v9.2/");
define("JS_BASE_PATH","/js/v9.2/");


define("POST_DETAIL_PAGE_IMAGE_BASE_PATH","/images/post-detail/");
define("TEMPLATE_IMAGE_BASE_PATH","/images/template/");
define("ERROR_IMAGE_BASE_PATH","/images/error/");

//CSS files
//Original Version
/*define("POST_DETAIL_CSS","post-detail.css");
define("ERROR_PAGE_CSS","error-page.css");
define("HOME_PAGE_CSS","home-page.css");
define("LIST_SEARCH_PAGE_CSS","list-search-page.css");
define("BOOTSTRAP_CSS","bootstrap-lightest.min.css");
define("TEMPLATE_CSS","template.css");
define("MAIN_CSS","main.css");
define("MENU_STYLE_CSS","menu-styles.css");
define("STYLE_CSS","style.css");
define("CALENDER_CSS","calender.css");*/


//Minified Version
define("POST_DETAIL_CSS","post-detail.min.css");
define("ERROR_PAGE_CSS","error-page.min.css");
define("HOME_PAGE_CSS","home-page.min.css");
define("LIST_SEARCH_PAGE_CSS","list-search-page.min.css");
define("BOOTSTRAP_CSS","bootstrap-lightest.min.css");
define("TEMPLATE_CSS","template.min.css");
define("MAIN_CSS","main.min.css");
define("MENU_STYLE_CSS","menu-styles.min.css");
define("STYLE_CSS","style.min.css");
define("CALENDER_CSS","calender.min.css");


//JS files
//Original Version
/*define("TEMPLATE_JS","template.js");
define("POST_DETAIL_JS","post-detail.js");
define("CALENDER_JS","calender.js");
define("SERVICE_WORKER","/service-worker.js");
define("JQUERY_JS","jquery-1.12.4.js");
define("BROWSER_NOTIFICATION_JS","browser-notification.js");
define("BELOW_FOLD_JS","below_fold.js");
define("ANALYTICS_JS","analytics.js");*/

//Minified Version
define("TEMPLATE_JS","template.min.js");
define("POST_DETAIL_JS","post-detail.min.js");
define("CALENDER_JS","calender.min.js");
define("JQUERY_JS","jquery-1.12.4.min.js");
define("SERVICE_WORKER","/service-worker.min.js");
define("BROWSER_NOTIFICATION_JS","browser-notification.js");
define("BELOW_FOLD_JS","below_fold.min.js");
define("ANALYTICS_JS","analytics.min.js");

define("MANIFEST","/manifest.json");
//S3 configuration
define("S3_ACCESS_KEY","AKIAIQ4S2HALLHSZ7GRA"); //new s3 key
define("S3_SCRET_KEY","sYr3XZKEtBUEp/7ID0kCz7sxN8IDXlyndQxUAy5h");

//Page related variables
define("DUMMY_CONSTANT","dummy-value");
define("MIN_CATEGORY_POST_COUNT",4);
define("DEFAULT_POST_OFFSET",0);
define("DEFAULT_POST_LIMIT",10);
define("HOME_PAGE_POST_OFFSET",0);
define("HOME_PAGE_POST_LIMIT",7);
define("POST_DETAIL_PAGE_POST_OFFSET",0);
define("POST_DETAIL_PAGE_POST_LIMIT",3);
define("AUTO_SUGGESTION_OFFSET",0);
define("AUTO_SUGGESTION_LIMIT",6);
define("BLOG_HOME_PAGE","Blog Home Page");
define("CATEGORY_HOME_PAGE","Category Home Page");
define("BLOG_LIST_PAGE","Blog All-Categories List Page");
define("CATEGORY_LIST_PAGE","Category List Page");
define("TAG_LIST_PAGE","Tag List Page");
define("SEARCH_LIST_PAGE","Search List Page");
define("PSEUDO_SEARCH_LIST_PAGE","Pseudo Search List Page");
define("BLOG_POST_PAGE","Blog Post Page");
define("BLOG_ERROR_PAGE","Blog Error Page");
define("BLOG_PUBLISH_NOTIFY_EMAIL","jyoti@medinfi.com");

define("AUTO_SUGGESTION_LIST","auto-suggestion-list");
define("NO_OF_FEATURED_POST",2); // On blog and bategory home page.
define("LIST_PAGE_POST_LIMIT",10);
define("SESSION_DURATION",30);// session duration in minutes
//define("VALUE_NOT_SET","value-not-set");
define("VALUE_NOT_SET",NULL);
define("TERM_CATEGORY","category");
define("TERM_TAG","tag");
define("NON_EXISTING_TERM",NULL);
define("DEFAULT_VIEW",'blogIndex');
define("HOME_PAGE_VIEW",'blogIndex');
define("LIST_PAGE_VIEW",'PostListSearch');
define("POST_DETAIL_PAGE_VIEW",'PostDetail');
define("MAX_PAGINATION_ELEMENT",7);
define("PAGINATION_ELLIPSE_VALUE","...");
define("FIRST_PAGE",1);
define("LEFT_ARROW_VALUE","❮");
define("RIGHT_ARROW_VALUE","❯");
define("BLOG_MAIN_TEMPLATE",'blogMain');
define("MEDIUM_LARGE_IMAGE",'medium_large');
define("MEDIUM_IMAGE",'medium');
define("DEFAULT_ID",NULL);
define("DEFAULT_TERM_TYPE",NULL);
define("DEFAULT_PAGE_NUMBER",1);
define("HEADER_DOCTOR_LINK_NAME","Find Doctors");
define("HEADER_DOCTOR_LINK_SLUG","find-doctors");
define("HEADER_DOCTOR_LINK_ID",NULL);
define("HEADER_DOCTOR_LINK_URL","/doctor-search/new-delhi/doc_/1");
define("HEADER_LINK_DEFAULT_CITY","New Delhi");
define("ERROR_PAGE_HOSPITAL_LINK_URL","/hospital-search/new-delhi/doc_/1");
define("ERROR_PAGE_CANONICAL_URL","/page-not-found/");
define("ASK_A_FRIEND_LINK_URL","/askfriendforsuggestion");
define("ASK_A_FRIEND_LINK_NAME","Ask A Friend");
define("ASK_A_FRIEND_LINK_SLUG","ask-a-friend");

define("LOGOUT","LOGOUT");
define("LOGIN","LOGIN");
define("HEADER_LOGIN_LOGOUT_SLUG","login-logout");
define("LOGOUT_URL","/logout");
define("LOGIN_URL","/login");
define("HTTP","https://");


//Images
define("MEDINFI_LOGO","logo.png");
define("GIVE_FEEDBACK_IMAGE","give-feedback.png");
define("SHARE_POST_IMAGE","share-post.png");
define("FIND_DOCTORS_IMAGE","find-doctors.png");
define("ASK_A_FRIEND_IMAGE","ask-a-friend.png");
define("AMAZON_ICON","amazonicon.png");
define("AMAZON_AD_IMAGE","amazon-banner-ad2.png");
define("FOOTER_ANDROID_IMAGE","android-grey.png");
define("MEDINFI_FAVICON_IMAGE","medinfi-favicon.ico");
define("PAGE_NOT_FOUND_IMAGE","page-not-found.jpg");
define("USER_PLACEHOLDER_PROFILE_IMAGE","user-placeholder-profile-image.png");
define("WHATS_APP_ICON", "whatsapp.png");
define("FB_ICON", "facebook.png");
define("TWITTER_ICON", "twitter.png");
define("LINKEDIN_ICON", "linkedin.png");
define("MAIL_ICON", "email.png");
define("HEADER_ANDROID_IMAGE","get-app.png");
define("SEARCH_BOX_BORDER","search-box-border.png");
define("YES", "tick.png");
define("NO", "cross.png");
define("NOTSURE", "notsure.png");
define("SPLASHLOGO", "splash_logo.png");
define("WOMENS_BLOG_FIRST_QUESTION", "Would you want to choose your blog topic?");
define("SECOND_QUESTION", "Would you like to pay a minimal fee for this?");
define("HEALTH_BLOG_FIRST_QUESTION", "Would you want to request a specialist doctor in your locality?");
define("LOGIN_IMAGE","login.png");

//Constant texts
define("FEEDBACK_TEXT","GIVE YOUR FEEDBACK");
define("SHARE_TEXT","SHARE WITH FRIENDS");
define("RECENT_POSTS","RECENT POSTS");
define("POPULAR_POSTS","POPULAR POSTS");
define("GET_HEALTH_NEWSLETTER","GET HEALTH NEWSLETTER");
define("UNSUBSCRIBE_NEWSLETTER_TEXT","Confirm Unsubscribe Request");
define("GET_NEWSLETTER_INFO","Latest health and wellness articles from the medinfi blogs delivered by email.");
define("HOW_OFTEN_SHOULD_WE_SEND","How often should we send?");
define("VIEW_ALL_BLOG_POSTS","View All Blog Posts");
define("MEDINFI_MOBILE_NAV_TEXT","MEDINFI BLOG");
define("COMMENT_SECTION_TITLE"," Thoughts on this blog post");
define("COMMENT_EMAIL_SUBTEXT","will not be published");
define("COMMENT_ERROR_MESSAGE","Please type a comment");
define("EMAIL_ERROR_MESSAGE","Please enter a valid Email ID");
define("SUBSCRIBE_ERROR_MESSAGE","Please choose a subscription plan");
define("UNSUBSCRIBE_ERROR_MESSAGE","Please click on the button to unsubscribe.");
define("COMMENT_ACKNOWLEDGEMENT_MESSAGE","Thank you. Your comment is awaiting moderation.");
define("SUBSCRIPTION_ACKNOWLEDGEMENT_MESSAGE","THANK YOU!");
define("SUBSCRIPTION_ACKNOWLEDGEMENT_MESSAGE_SECOND","You have successfully subscribed for Medinfi Blog Newsletter.");
define("SUBSCRIPTION_ACKNOWLEDGEMENT_MESSAGE_THIRD","You shall start receiving emails soon.");

//Page Title and Meta-description related constants
define("BLOG_DEFAULT_PAGE_TITLE","Medinfi Blog - Find trusted health tips and articles.");
define("BLOG_DEFAULT_PAGE_DESCRIPTION","Understand your health better.Find thoughtful health articles on informative topics like common health ailments, food &amp; nutrition and healthy lifestyle on Medinfi Blog.");
define("CATEGORY_TITLE_TEXT"," - Health tips and articles - Medinfi Blog Category");
define("CATEGORY_DESCRIPTION_TEXT","Find insights into topics related to Category : <Category Name> on Medinfi Blog. We want to help you understand your health better.");
define("CATEGORY_DESCRIPTION_MARKER","<Category Name>");
define("TAG_TITLE_TEXT"," - Health tips and articles - Medinfi Blog Tag");
define("TAG_DESCRIPTION_TEXT","Find insights into topics related to Tag : <Tag Name> on Medinfi Blog. We want to help you understand your health better.");
define("TAG_DESCRIPTION_MARKER","<Tag Name>");
define("SEARCH_TITLE_TEXT","You searched for <term> - Medinfi Blog");
define("SEARCH_TITLE_MARKER","<term>");
define("SEARCH_DESCRIPTION_TEXT",null);
define("POST_TITLE_TEXT"," - Medinfi Blog");
define("ERROR_PAGE_TITLE","Page not found - Medinfi Blog");
define("POST_PER_PAGE_COUNT", 10);

//blog backend roles
define("BLOG_AUDITOR", 'Blog Auditor');
define("BLOG_EDITOR", 'Blog Editor');
define("BLOG_WRITER", 'Blog Writer');
define("ADMIN", 'Admin');
define("BLOG_AUDITOR_ROLE", 7);
define("ADMIN_ROLE", 1);
//User role who can access Blog backend
$GLOBALS['BLOG_BACKEND_ACCESS']=array(1,7,8,9);

//Encoded Images
define("SHARE_POST_ENCODED_IMAGE","data:image/png;base64,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");
define("SEARCH_GLASS_ENCODED_IMAGE","data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAArCAQAAACQ7xNpAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QA/4ePzL8AAAAJcEhZcwAACxMAAAsTAQCanBgAAAAHdElNRQfhARwPBiK+BQJPAAACFUlEQVRYw+3WXWiNcRwH8M85dsbm5WQvXrK8tA2Rtgu5kCjNjUzhRimXwoUrKRdcSJSs3MnVlBuSNITQakUuSGxTFJKOmZOX2hxtnnM2d4q9nKfnnLlg38vn/61P/1/P8+thMv9lYiFbZWYoFfjmu+HiIxXWadJonqkCaV3adegt3j3L7fJQYMArHW5q90JGTqf9ksUharQa1OOMjeYrkzBNtbWOey2rzfLCiSXuyLqsQXzEiOucM+CxxsKIClcFTpk1xvk0B2U8sCg6EXNEzlnl43RKHJV1ViIq0qDHIwvytJJu6NcUFTkpa3eI3iYZF5REIao81WVuiOZ097xXl68WH+VZrToPfAyBZLSbZ1UUZIlyXSFv/Rz1UZBKpEMinwSqoiAxQi/BYcP5999oyBdUh0QqlfocBXlrwMqQyAq8Ctn9LXN0e5p/0ihzywfLoiC0COwM0dugz6Woi2W1tPt5P8cZrsjYHI0g7oScFlPH7RwUaB23kydz3Tbo6Jh7OGGfPi8tjU7Acvf90GrpKN9BjdP6Dfloe2EItS4LvHHMGrMlxJRIanBIt5y7Dnin145CmVn26pTT75nrLmrz2FdDXjusGlukisEw3y7nPZGS1qPTRXss/jXCZim9hQ8N4pIWqrdYhSl/nDVL+VAcZrw0e//3mG0TzWz9B5n1Iw8i/c6MkWs4pGyi7xIzc8QrPpn/Nz8Bp5WPzQ/G6/4AAAAldEVYdGRhdGU6Y3JlYXRlADIwMTctMDEtMjhUMTU6MDY6MzQtMDU6MDB5FPyiAAAAJXRFWHRkYXRlOm1vZGlmeQAyMDE3LTAxLTI4VDE1OjA2OjM0LTA1OjAwCElEHgAAAABJRU5ErkJggg==");
define("GET_NEWSLETTER","get-newsletter.png");
define("UNSUBSCRIBE_NEWSLETTER","unsubscribe.png");
define("NEWS_LETTER_TRACKING", 'newsletter');

//  /*   Nitish Jha
//       Amazon Affiliate Ad-slots (Pilot)
//       27-04-2017
//       We are defining the ad placement in global variables as a array in which key is post id and
//       value is html code of amazon affiliate ads
//  */

// $GLOBALS['top_level'] = array("1166" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0"src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B01GQ7D3LS&asins=B01GQ7D3LS&linkId=fb235635e98421bf1c62068ebe3aee5d&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                              </iframe>',
//                    "1092" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B00PAPJYY8&asins=B00PAPJYY8&linkId=fb6df2045b2921c667d64534d1bd6b6e&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                                           </iframe>',
//                    "735" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B0081QYSL2&asins=B0081QYSL2&linkId=b943baeb433acc8e6dbd24ba080a0e03&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                                   </iframe>',
//                    "872" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B00M57WRHU&asins=B00M57WRHU&linkId=e7340031182d16c2ac06f6d8390244ca&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                                        </iframe>',
//                    "789" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B00K25QHYQ&asins=B00K25QHYQ&linkId=126285efe60b4425a4fbc4f956cf098f&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                                   </iframe>'
//                    );


// $GLOBALS['amazon_mid_level'] = array("1166" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B00CI4LI3O&asins=B00CI4LI3O&linkId=8e5193e30fd5f4f28fb64815f39a1291&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                                          </iframe>',
//                    "1092" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B06XNTYVPB&asins=B06XNTYVPB&linkId=0c0bb75665529166cf6fecb58b93a04e&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                                   </iframe>',
//                    "735" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B002DYQ6Q8&asins=B002DYQ6Q8&linkId=ea34b25fb13520480da0b1d920bbd6ce&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                                  </iframe>',
//                    "872" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B00AHUELKI&asins=B00AHUELKI&linkId=10b2bb561d795ff91da9c5d8d3b4998a&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                                  </iframe>',
//                    "789" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B01N03340R&asins=B01N03340R&linkId=e53d7c25c0a758b15983c2e8e3c883b0&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                                  </iframe>'
//                    );

// $GLOBALS['amazon_bottom_level'] = array("1166" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B0198GT9BW&asins=B0198GT9BW&linkId=4a58ac9719add0684131762b2b0b7eea&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                                             </iframe>',
//                    "1092" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B00UEGBYD2&asins=B00UEGBYD2&linkId=c12631d9c058fd229b0687e41c5f9446&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                                   </iframe>',
//                    "735" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B010GGCW5S&asins=B010GGCW5S&linkId=31f6514fa052286724db912f33430aff&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                                  </iframe>',
//                    "872" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B008R9QD0M&asins=B008R9QD0M&linkId=96890133d43ac6c7a9e24f0f1fc65b51&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                                  </iframe>',
//                    "789" => '<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-in.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=IN&source=ac&ref=tf_til&ad_type=product_link&tracking_id=medinfi-21&marketplace=amazon&region=IN&placement=B00M5XTABU&asins=B00M5XTABU&linkId=61edcebc82886344cd1c8aad2af65680&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
//                                  </iframe>'
//                    );




/*   Nitish Jha
      First Cry Banner Ads (Pilot)
      11-05-2017

define("OFFER_FIRST_CRY",'http://www.firstcry.com/?c=MYAF499&Ref=med&utm_source=med&utm_medium=aff&utm_content=MYAF499');
define("FIRST_CRY_OFFER",'http://www.firstcry.com/?c=MYAF600&Ref=med&utm_source=med&utm_medium=aff&utm_content=MYAF600');
define("FIRST_CRY_MID_BIG","first-cry-flat-499-big.png");
define("FIRST_CRY_MID_SMALL","first-cry-flat-499-small.png");
define("FIRST_CRY_BOTTOM_BIG","first-cry-flat-600-big.png");
define("FIRST_CRY_BOTTOM_SMALL","first-cry-flat-600-small.png");
$GLOBALS['first_cry_mid_level'] = array( "316" => '1',
                                         "753" => '2',
                                         "799" => '2',
                                         "175" => '4',
                                         "1040" => '3',
                                         "541" => '3'
                                        );

$GLOBALS['first_cry_bottom_level'] = array( "1098" => '2',
                                            "753" => '1',
                                            "541" => '2',
                                            "760" => '3',
                                            "1040" => '4',
                                            "799" => '3'
                                           );


*/

//Constants for ads
define("GOOGLE_320_50_AD","<div id=\"adsense_code_bottom\" class=\"amazon_affiliate_ads\">
                          <script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
                          <!-- Ad Unit - 320*50 -->
                          <ins class=\"adsbygoogle\"
                                style=\"display:inline-block;width:320px;height:50px\"
                                data-ad-client=\"ca-pub-1541745750577109\"
                                data-ad-slot=\"6668671074\"></ins>
                          <script>
                          (adsbygoogle = window.adsbygoogle || []).push({});
                          </script>
                          </div>");
define("GOOGLE_RESPONSIVE_AD","<div id=\"adsense_code_bottom\">
                                        <script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
                                         <!-- Pilot Launch Ad -->
                                         <ins class=\"adsbygoogle\"
                                              style=\"display:block\"
                                              data-ad-client=\"ca-pub-1541745750577109\"
                                              data-ad-slot=\"3824578670\"
                                              data-ad-format=\"auto\"></ins>
                                         <script>
                                         (adsbygoogle = window.adsbygoogle || []).push({});
                                         </script>
                                    </div>");
define("GOOGLE_RESPONSIVE_320_50_AD","<div id=\"adsense_code_bottom\" class=\"amazon_affiliate_ads\">
                          <script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
                          <!-- Ad Unit - 320*50 -->
                          <ins class=\"adsbygoogle\"
                               style=\"display:inline-block;width:320px;height:50px\"
                               data-ad-client=\"ca-pub-1541745750577109\"
                               data-ad-slot=\"6668671074\"></ins>
                          <script>
                          (adsbygoogle = window.adsbygoogle || []).push({});
                          </script>
                     </div>
                     <div id=\"adsense_code_midlevel\" class=\"adsense_ads\">
                           <script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
                               <!-- Responsive Ads in Desktop -->
                               <ins class=\"adsbygoogle\"
                                    style=\"display:block\"
                                    data-ad-client=\"ca-pub-1541745750577109\"
                                    data-ad-slot=\"3824578670\"
                                    data-ad-format=\"auto\"></ins>
                                    <script>
                                        (adsbygoogle = window.adsbygoogle || []).push({});
                                    </script>
                               </div>");

$med_host=$_SERVER['HTTP_HOST'];
if($med_host=="localhost"){
    define("MEDINFI_FOLDER_BASE_URL","http://localhost/medinfi-website-backend-29092017");
    define("BLOG_POST_IMAGE_PATH","http://52.76.146.208/wordpress-backend/wp-content/uploads/");
    define("HOST_NAME","http://localhost");
    define("MEDINFI_MAIN_SUBDOMAIN","www.medinfi.com");
    define("MEDINFI_DOMAIN","medinfi.com");

    define("GOOGLE_API_KEY","AIzaSyCpP6Jr326JGueIGRVWJJdXrvcK97rkwzU");//Harsh's Server Key

	define("S3_FIRST_BUCKET","medinfi1");
    define("S3_FIRST_FOLDER_PREFIX","medinfitest/");
    define("S3_FIRST_BUCKET_DOC_URL","https://".S3_FIRST_BUCKET.".s3.amazonaws.com/");
    define("S3_FOLDER_NAME","BlogDocuments/");
}
else if($med_host=="52.76.146.208"){
    define("MEDINFI_FOLDER_BASE_URL","https://52.76.146.208/medinfi");
    //Change the below mentioned constant to point to test version of the URL once the Blog New UI has been released.
    define("BLOG_POST_IMAGE_PATH","https://52.76.146.208/wordpress-backend/wp-content/uploads/");
    define("HOST_NAME","https://52.76.146.208");
    define("MEDINFI_MAIN_SUBDOMAIN","www.jiji.in");
    define("MEDINFI_DOMAIN","jiji.com");

    define("GOOGLE_API_KEY","AIzaSyCpP6Jr326JGueIGRVWJJdXrvcK97rkwzU");//Harsh's Server Key

	define("S3_FIRST_BUCKET","medinfi1");
    define("S3_FIRST_FOLDER_PREFIX","medinfitest/");
    define("S3_FIRST_BUCKET_DOC_URL","https://".S3_FIRST_BUCKET.".s3.amazonaws.com/");
    define("S3_FOLDER_NAME","BlogDocuments/");
}
else{
    define("MEDINFI_FOLDER_BASE_URL","https://www.medinfi.com/medinfi");
    define("BLOG_POST_IMAGE_PATH","https://www.medinfi.com/wordpress-backend/wp-content/uploads/");
    //define("BLOG_POST_IMAGE_PATH","https://www.medinfi.com/blog/wp-content/uploads/");
    define("HOST_NAME","https://www.medinfi.com");
    define("MEDINFI_MAIN_SUBDOMAIN","www.medinfi.com");
    define("MEDINFI_DOMAIN","medinfi.com");

    define("GOOGLE_API_KEY","AIzaSyCPrKmM-6OyJk3uyf583QCseqf4JRUlm-o");
    //define("GOOGLE_API_KEY","AIzaSyCpP6Jr326JGueIGRVWJJdXrvcK97rkwzU");//Harsh's Server Key

	define("S3_FIRST_BUCKET","medinfi1");
    define("S3_FIRST_FOLDER_PREFIX","");
    define("S3_FIRST_BUCKET_DOC_URL","https://".S3_FIRST_BUCKET.".s3.amazonaws.com/");
    define("S3_FOLDER_NAME","BlogDocuments/");
}

//Meta - title and description

$GLOBALS['meta_title'] = array( "health-conditions" => "Diseases & Health Conditions -  Causes, Symptoms & Home Remedies - Medinfi",
                                "womens-health" => "Women’s Health Care Article, Fitness, Sex, Pregnancy and Weight Loss Tips",
                                "nutrition-diet" => "Diets and Nutrition Latest Articles, Home Remedies, Tips - Medinfi",
                                "child-health" => "Children's Health Care Articles, Health Tips, Illnesses – Medinfi",
                                "healthy-living" => "Healthy Living - Health Tips, Latest articles, Health Diet – Medinfi",
                                "trending-topics-in-healthcare" => "Articles on Various Trending Medical Advancements - Medinfi",
                                "home-page" => "Latest Health & Wellness Articles Online, Apps On Mobile - Medinfi India"
                                        );

$GLOBALS['meta_description'] = array( "health-conditions" => "Read the latest Diseases & Health Conditions articles online with Symptoms, Causes & Remedies. Download the App For Diseases & Health Conditions blog articles",
                                "womens-health" => "Read the latest Women’s health care articles online with Home remedies, Fitness, Sex, Pregnancy and Weight Loss Tips. Download the App For Women’s health blog",
                                "nutrition-diet" => "Read the latest Diets and Nutrition articles online with Home remedies, Fitness, and Weight Loss Tips. Download the App For Diets and Nutrition blog",
                                "child-health" => "Read the latest Children's health care, or pediatrics articles online with health care articles, health tips, Home remedies, etc.. Download the App For  Kids health blog",
                                "healthy-living" => "Read the latest Healthy Living articles online with Home remedies, Fitness, Health Recipes and Diet tips. Download the App For Healthy Living blog",
                                "trending-topics-in-healthcare" => "Read the latest articles on Health Conditions & Procedures with Symptoms & Home Remedies. Download the App for Trending Health blog articles",
                                "home-page" => "Medinfi - health & wellness information latest articles, Hospital and Doctors Directories in india and Health article app and much more"
                                        );




?>