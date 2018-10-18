function replyTo(parentCommentId) {
    ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Clicked on comment reply anchor',eventLabel:'Replied to comment ID - '+parentCommentId});
    var parentCommentIdTag = document.getElementById("comment_parent");
    if (parentCommentIdTag !== null) {
        parentCommentIdTag.value = parentCommentId;
    }
    else{
        console.log("Page has not fully loaded!!");
    }
}
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@'"]+(\.[^<>()\[\]\\.,;:\s@'"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
var actionNewsletter="";
var sub_plan="";
function subscriptionPlan(subscribe){

     if(subscribe.id=="daily-subscription" || subscribe.id=="newsletter-daily-subscription"){
           if(subscribe.id=="daily-subscription"){
                actionNewsletter = "Newsletter Modal Popup";
           }else if(subscribe.id=="newsletter-daily-subscription"){
                actionNewsletter= "Newletter";
           }
           sub_plan = "Daily";
           //sub_plan.style.color = "blue";
     }
     else if(subscribe.id=="weekly-subscription" || subscribe.id=="newsletter-weekly-subscription"){
           if(subscribe.id=="weekly-subscription"){
                actionNewsletter = "Newsletter Modal Popup";
           }else if(subscribe.id=="newsletter-weekly-subscription"){
                actionNewsletter= "Newletter";
           }
          sub_plan = "Weekly";
     }
     else if(subscribe.id=="monthly-subscription" || subscribe.id=="newsletter-monthly-subscription"){
           if(subscribe.id=="monthly-subscription"){
                actionNewsletter = "Newsletter Modal Popup";
           }else if(subscribe.id=="newsletter-monthly-subscription"){
                actionNewsletter= "Newletter";
           }
           sub_plan = "Monthly";

     }
     ga('send', {hitType: 'event',eventCategory: pageName,eventAction: actionNewsletter,eventLabel:sub_plan});
}

// function answer(ans){
// var submitAnswer="";
// var questionAnswer="";
//     if(questionCheck == "1"){
//          questionAnswer= document.getElementById("first-question").getAttribute("value");
//       }else{
//           questionAnswer= document.getElementById("second-question").getAttribute("value");
//        }
//   if (ans.id=="yes-answer") {
//      submitAnswer = "Yes";
//   }
//   else if (ans.id=="no-answer") {
//      submitAnswer = "No";
//   }
//   else if (ans.id=="notsure-answer") {
//      submitAnswer = "Not Sure";
//   }

//   addPrimeSurvey(questionAnswer,submitAnswer);
// }

//function fadeOut(element) {
//    var op = 1;  // initial opacity
//    var timer = setInterval(function () {
//        if (op <= 0.1){
//            clearInterval(timer);
//            element.style.display = 'none';
//        }
//        element.style.opacity = op;
//        element.style.filter = 'alpha(opacity=' + op * 100 + ")";
//        op -= op * 0.1;
//    }, 50);
//}
//function fadeIn(element) {
//    var op = 0.1;  // initial opacity
//    element.style.display = 'block';
//    var timer = setInterval(function () {
//        if (op >= 1){
//            clearInterval(timer);
//        }
//        element.style.opacity = op;
//        element.style.filter = 'alpha(opacity=' + op * 100 + ")";
//        op += op * 0.1;
//    }, 50);
//}
//
//function errorMessageFadeInAndOut(element) {
//    element.style.height = "20px";
//    var time = setInterval(function(){
//            element.style.height = "0px";
//        },8000);
//}
function errorMessageFadeIn(element) {
    element.style.height = "20px";
}
function errorMessageFadeOut(element) {
    element.style.height = "0px";
}
function showSharePopup() {
    ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Clicked on share with friends',eventLabel:'Click on share with friends - '+processedPostTitle});
    shareModal.style.display = "block";
    document.body.style.overflowY = "hidden";
    shareModal.style.overflowY = "hidden";
}
    /* Nitish Jha
       Blog Notification */
function showNewsletterPopup(){
    if(popUpFlag === 0){
        ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Get Health Newsletter Auto PopUp',eventLabel:'Get Health Newsletter Auto PopUp'});
    }else{
        ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Get Health Newsletter',eventLabel:'Get Health Newsletter'});
    }
    newsletterModal.style.display = "block";
    document.body.style.overflowY = "hidden";
    newsletterModal.style.overflowY = "scroll";
    document.getElementById("email-newsletter-notification").value="";
    sub_plan="";
    errorMessageFadeOut(document.getElementById("newsletter-email-error-message"));
    errorMessageFadeOut(document.getElementById("newsletter-subscription-plan-click"));

}


//window.onload = function() {
window.addEventListener('load',function(){
    //document.getElementById("subscription-acknowledgement-modal").style.display = "none";

   // This is to remove console error in test server and production only
    var med_host_ip = med_host;

    if(med_host_ip=="https://www.medinfi.com" || med_host_ip=="https://54.169.135.30" ||med_host_ip=="http://www.medinfi.com" || med_host_ip=="http://54.169.135.30" || med_host_ip=="http://52.76.146.208"){
         console.log = function() {}
    }

    var redirectUrl = unsubRedirectUrl;
    var postButton = document.getElementById("post-button");
    var emailInputBox = document.getElementById("response-email-input");
    document.getElementsByClassName("form-control").value ="";
    // Get the modal

    var modal = document.getElementById('comment-acknowledgement-modal');
    var subscriptionModal = document.getElementById('subscription-acknowledgement-modal');
    var unsubscriptionModal = document.getElementById('unsubscribe-modal-popup');
    var unsubClose = document.getElementById("unsubscription-close");
    // Get the <span> element that closes the modal
    var span = document.getElementById('comment-acknowledgement-modal-close');//[0];
    var subscriptionSpan = document.getElementById('subscription-acknowledgement-modal-close');
    var shareModal = document.getElementById('shareModal');
    var newsletterModal = document.getElementById('newsletterModal');
    var shareButtonAnchor = document.getElementById("center-share-img-anchor");
    var newsletterButtonAnchor = document.getElementById("center-newsletter-img-anchor");
    var unsubscribeUserEmail= unsubEmail;
    var unsubButtonRadio = document.getElementById("unsubButton");

    var firstQuestion = document.getElementById('first-question');
    var secondQuestion = document.getElementById('second-question');
    var thankYou = document.getElementById('thankyou-div');
    var answerDiv = document.getElementById('answer-div');

    /*Added by jiji on 27-4-2017 for tracking the link click is from newsletter or not */
    if(trackSource!="" && trackSource=="newsletter") {
       ga('send', {hitType: 'event',eventCategory: 'Blog Newsletter',eventAction: 'click_on_link' ,eventLabel:postTitle});
    }
    else if(trackSource!="" && trackSource=="browser_notification") {
       ga('send', {hitType: 'event',eventCategory: 'Browser Notification',eventAction: 'click_on_link' ,eventLabel:postTitle});
    }

    if(fetchLocation == "NO"){
           showPosition();
    }


   /*
   Blog Notification Unsubscribe
   Nitish Jha
   13-04-2017
   */
    if(unsubscribeUserEmail !=""){
        unsubscriptionModal.style.display = "block";
        document.body.style.overflowY = "hidden";
        unsubscriptionModal.style.overflowY = "scroll";
    }

    unsubClose.onclick = function() {
         unsubscriptionModal.style.display = "none";
         document.body.style.overflowY = "scroll";
         unsubscriptionModal.style.overflowY = "scroll";
         location.href = redirectUrl;
         ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Unsubscribe',eventLabel:'Closed without Unsubscribing'});
     }
     if(unsubButtonRadio !=null){
         unsubButtonRadio.addEventListener("click",function(e){
         var unsubClick = document.getElementById("unsubscribe-radio-button");
         var unsubUrlRedirect = redirectUrl;
         var unsubEmailUser = unsubEmail;
         var unsubscribeErrorMessage = document.getElementById("unsubscription-radio-click");
         validUnsubButtonClick = true;
         if(unsubClick.checked == false){
          errorMessageFadeIn(unsubscribeErrorMessage);
             validUnsubButtonClick = false;
             return;
          }
          else{
              errorMessageFadeOut(unsubscribeErrorMessage);
          }
          if(validUnsubButtonClick){
              //var unsubscribeEmail = unsubEmailUser.value.trim();
              ga('send', {hitType: 'event',eventCategory: pageName,eventAction:"Unsubscribe" ,eventLabel:' Unsubscribe Button Clicked'});
              var params = "unsubscribeEmail="+unsubEmail;
              var ajax = new XMLHttpRequest();
              ajax.open("POST", baseUrl+"/blogNotificationUnsubscription/", true);
              ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
              ajax.onload = function() {
              try{
                  var jsonResponse = JSON.parse(this.responseText);
                  console.log(jsonResponse['status']);
                  if (ajax.status === 200 && jsonResponse['status'] != 'Fail') {
                      errorMessageFadeOut(document.getElementById("unsubscription-radio-click"));
                      document.getElementById("unsubscribe-modal-popup").style.display = "none";
                      document.body.style.overflowY = "scroll";
                      document.getElementById("unsubscribe-modal-popup").style.overflowY = "scroll";
                      location.href = unsubUrlRedirect;
                  }
              }
                 catch(e){
                     return;
                 }
              };
              ajax.send(params);
          }
           else{
             console.log("Page has not loaded fully!!");
           }
     });
  }

    if (shareButtonAnchor !=null) {
        shareButtonAnchor.addEventListener("click",showSharePopup);
    }
    if (newsletterButtonAnchor !=null) {
        newsletterButtonAnchor.addEventListener("click",showNewsletterPopup);
    }

    if (emailInputBox != null) {
        emailInputBox.addEventListener("keyup",function(e){
            var emailId = this.value;
                if (emailId.length > 0 && !validateEmail(emailId)) {
                    this.style.color = "red";
                }
                else{
                    this.style.color = "black";
                }
            });
    }

    if (postButton != null) {
//      $postId = isset($_REQUEST['postId']) ? $_REQUEST['postId'] : '';
//		$comment = isset($_REQUEST['comment']) ? $_REQUEST['comment'] : '';
//		$commenterName = isset($_REQUEST['commenterName']) ? $_REQUEST['commenterName'] : '';
//		$commenterEmail = isset($_REQUEST['commenterEmail']) ? $_REQUEST['commenterEmail'] : '';
//		$parentCommentId = isset($_REQUEST['parentCommentId']) ? $_REQUEST['parentCommentId'] : 0;

        postButton.addEventListener("click",function(e){
            var commentTextArea = document.getElementById("response-comment-text-area");
            var commentatorNameInput = document.getElementById("response-name-input");
            var commentatorEmailInput = document.getElementById("response-email-input");
            var parentCommentIdTag = document.getElementById("comment_parent");
            var postIdTag = document.getElementById("comment_post_ID");
            var validComment = true;
            var validEmail = true;

            //console.log("HERE"+commentTextArea+commentatorNameInput+commentatorEmailInput+parentCommentIdTag+postIdTag);
            if (commentatorEmailInput !== null && commentatorNameInput != null && commentTextArea !== null && parentCommentIdTag !== null && postIdTag !== null) {
                var comment = commentTextArea.value.trim();
                var email = commentatorEmailInput.value.trim();
                var commentErrorMessage = document.getElementById("response-comment-error-message");
                var emailErrorMessage = document.getElementById("response-email-error-message");
                if (comment.length == 0) {
                    errorMessageFadeIn(commentErrorMessage);
                    validComment = false;
                }
                else{
                    errorMessageFadeOut(commentErrorMessage);
                }
                if (email.length > 0 && !validateEmail(email)) {
                    errorMessageFadeIn(emailErrorMessage);
                    validEmail = false;
                }
                else{
                    errorMessageFadeOut(emailErrorMessage);
                }

                if (validComment && validEmail) {
                    var name = commentatorNameInput.value.trim();
                    var parentCommentId = parentCommentIdTag.value;
                    var postId = postIdTag.value;
                    console.log("/"+comment+"/"+name+"/"+email+"/");
                    var commentFlag = (comment.length > 0 ? 'Set' : 'Not Set');
                    var emailFlag = (email.length > 0 ? 'Set' : 'Not Set');
                    var nameFlag = (name.length > 0 ? 'Set' : 'Not Set');
                    ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Clicked on post comment button',eventLabel:' Comment : '+commentFlag + '/ Name : '+ nameFlag + '/ Email : '+emailFlag});
                    var params = "postId="+postId+"&comment="+comment+"&commenterName="+name+"&commenterEmail="+email+"&parentCommentId="+parentCommentId;
                    var ajax = new XMLHttpRequest();
                    ajax.open("POST", baseUrl+"/addBlogComment/", true);
                    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    ajax.onload = function() {
                        try{
                            var jsonResponse = JSON.parse(this.responseText);
                            console.log(jsonResponse['status']);
                            if (ajax.status === 200 && jsonResponse['status'] != 'Fail') {
                                modal.style.display = "block";
                                document.body.style.overflowY = "hidden";
                                modal.style.overflowY = "hidden";
                                commentTextArea.value = "";
                                commentatorNameInput.value = "";
                                commentatorEmailInput.value = "";
                                //console.log("Oh Yeah !! Comment Submitted.");
                            }
                        }
                        catch(e){
                            return;
                        }
                    };
                    ajax.send(params);
                }
            }
            else{
                console.log("Page has not loaded fully!!");
            }

        });
    }

    // When the user clicks on <span> (x) of the comment modal, close the modal
    span.onclick = function() {
        modal.style.display = "none";
        document.body.style.overflowY = "scroll";
        modal.style.overflowY = "scroll";
        ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Closed comment acknowledgment message',eventLabel:'Closed using click on cross button'});
    }
    subscriptionSpan.onclick = function() {
        subscriptionModal.style.display = "none";
        document.body.style.overflowY = "scroll";
        subscriptionModal.style.overflowY = "scroll";
        ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Closed subscription acknowledgment message',eventLabel:'Closed subscription acknowledgment using click on cross button'});
    }
    // When the user clicks anywhere outside of the comment modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            document.body.style.overflowY = "scroll";
            modal.style.overflowY = "scroll";
            ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Closed comment acknowledgment message',eventLabel:'Closed using click on blank space'});
        }
    }
    window.onclick = function(event) {
        if (event.target == subscriptionModal) {
            subscriptionModal.style.display = "none";
            document.body.style.overflowY = "scroll";
            subscriptionModal.style.overflowY = "scroll";
            ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Closed subscription acknowledgment message',eventLabel:'Closed using click on blank space'});
        }
    }
    // When the user clicks on <span> (x)of the share modal, close the share modal
    document.getElementById('shareClose').onclick = function() {
        ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Closed share with friends',eventLabel:'Closed share with friends - '+processedPostTitle});
        shareModal.style.display = "none";
        document.body.style.overflowY = "scroll";
    }
    var emailIdNoti = "";
    window.checkEmail = function(e){
       var emailId = e.value;
          if (emailId.length > 0 && !validateEmail(emailId)) {
             e.style.color = "red";
             emailIdNoti = e;
          }
          else{
             e.style.color = "black";
             emailIdNoti = e;
          }

    }
    document.getElementById('newsletterClose').onclick = function() {
    if(popUpFlag === 0){
        ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Get Health Newsletter Auto PopUp',eventLabel:'Closed Newsletter Auto PopUp'});
    } else {
        ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Get Health Newsletter',eventLabel:'Closed Newsletter'});
    }
        newsletterModal.style.display = "none";
        document.body.style.overflowY = "scroll";
        sub_plan ="";
        document.getElementById("email-notification").value ="";
        errorMessageFadeOut(document.getElementById("email-notification-error-message"));
        errorMessageFadeOut(document.getElementById("subscription-plan-click"));

    }

    /*document.getElementById('read-more').onclick = function(){
      ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Read More Clicked',eventLabel:'Read More'});
      document.getElementById('post-content-full').style.display = "block";
      document.getElementById('post-content').style.display = "none";
    }

/*
       Blog Notification 07-04-2017
       Nitish Jha
    */

     var subscribe="";
     var actionHealthNewsletter="";
     var categoryName="";
     var emailRequiredMessage="";
     var subscriptionButtonClickRequired= "";
      window.checkSubscribe = function(sub){
          if(sub.id =="subButton"){
             var subscribeButton = document.getElementById("subButton");
               subscribe = subscribeButton;
               categoryName = document.getElementById("blog_category_Name");
               actionHealthNewsletter= "Newsletter Modal Popup";
               subscriptionButtonClickRequired = document.getElementById("subscription-plan-click");
               emailRequiredMessage = document.getElementById("email-notification-error-message");
       }
          else if(sub.id =="subButtonNewsletter"){
              var subscribeButton = document.getElementById("subButtonNewsletter");
              subscribe = subscribeButton;
              categoryName = document.getElementById("blogCategoryName");
              actionHealthNewsletter= "Newsletter";
              subscriptionButtonClickRequired = document.getElementById("newsletter-subscription-plan-click");
              emailRequiredMessage = document.getElementById("newsletter-email-error-message");
              }
                    if (subscribe != null){
                    var subscribeEmailInput = emailIdNoti;
                    if(subscribeEmailInput.length== 0){
                      errorMessageFadeIn(emailRequiredMessage);
                      validEmail = false;
                      return;
                    }else{
                    var atpos = subscribeEmailInput.value.indexOf("@");
                    var dotpos = subscribeEmailInput.value.lastIndexOf(".");
                    var categoryId = categoryName;
                    var sub_Plan = sub_plan;
                    //alert(sub_Plan);
                    var validEmail = true;
                    var validButtonClick = true;
                    if(subscribeEmailInput!== null && sub_Plan!== null){
                        var email = subscribeEmailInput.value.trim();
                        if(email.length > 0 && !validateEmail(email)){
                          errorMessageFadeIn(emailRequiredMessage);
                          validEmail = false;
                          return;
                        }
                        else{
                           errorMessageFadeOut(emailRequiredMessage);
                        }
                        if (atpos<1 || dotpos<atpos+2 || dotpos+2>=subscribeEmailInput.value.length) {
                           errorMessageFadeIn(emailRequiredMessage);
                           return false;
                        }
                        else{
                           errorMessageFadeOut(emailRequiredMessage);
                        }

                       if(sub_Plan == ''){
                           errorMessageFadeIn(subscriptionButtonClickRequired);
                           validButtonClick = false;
                        }
                        else{
//                           sub_Plan.style.border = "1px solid #ea235b";
//                           sub_Plan.style.color = "#ea235b";
                           errorMessageFadeOut(subscriptionButtonClickRequired);
                        }
                        if (validEmail && validButtonClick) {
                            var category_Id = categoryId.value.trim();
                            //alert(category_Id);
                            var action_Newsletter = actionHealthNewsletter;
                            subscribe.disabled = true;
                            //alert(action_Newsletter);
                           // console.log("/"+email+"/"+sub_Plan+"/"+category_Id+"/");
                             if(popUpFlag === 0){
                                   ga('send', {hitType: 'event',eventCategory: pageName,eventAction:action_Newsletter ,eventLabel:' Subscribe Auto PopUp'});
                             }else{
                                   ga('send', {hitType: 'event',eventCategory: pageName,eventAction:action_Newsletter ,eventLabel:' Subscribe'});
                             }
                            var params = "categoryId="+postCategoryId+"&subscriptionPlan="+sub_Plan+"&subscriptionEmail="+email;
                            var ajax = new XMLHttpRequest();
                            ajax.open("POST", baseUrl+"/blogNotificationSubscription/", true);
                            ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                            ajax.onload = function() {
                                 try{

                                     var jsonResponse = JSON.parse(this.responseText);
                                     console.log(jsonResponse['status']);
                                     if (ajax.status === 200 && jsonResponse['status'] != 'Fail') {
                                         document.getElementById("newsletterModal").style.display = "none";
                                         subscriptionModal.style.display = "block";
                                         document.body.style.overflowY = "hidden";
                                         subscriptionModal.style.overflowY = "hidden";
                                         email.value = "";
                                         sub_Plan.value = "";
                                         category_Id.value = "";
                                         sub_plan ="";
                                         emailIdNoti.value ="";
                                         subscribe.disabled = false;
                                       //errorMessageFadeOut(document.getElementsByClassName("email-error-message"));
                                         errorMessageFadeOut(document.getElementById("subscription-plan-click"));
                                         errorMessageFadeOut(document.getElementById("newsletter-subscription-plan-click"));
                                         subscriptionEmail(email);
                                         //console.log("Oh Yeah !! Comment Submitted.");
                                     }
                                 }
                                 catch(e){
                                     subscribe.disabled = false;
                                     return;
                                 }
                             };
                             ajax.send(params);
                         }
                     }
                     else{
                         console.log("Page has not loaded fully!!");
                     }
                    }
         }

         }

});



    function trackAdGA(){
     ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Buy Health Product link in Blog post detail',eventLabel:'From Amazon'});
    }


// function addPrimeSurvey(questionAnswer,submitAnswer) {
//             var sessionID = sessionId;
//             var blogCategory = blogCategoryName;
//             var surveyQuestion = questionAnswer;
//             var surveyAnswers = submitAnswer;

//             var params = "session_id="+sessionID+"&blog_category="+blogCategory+"&survey_question="+surveyQuestion+"&survey_answers="+surveyAnswers;
//             var ajax = new XMLHttpRequest();
//             ajax.open("POST", baseUrl+"/primeSurvey/", true);
//             ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
//             ajax.onload = function() {
//                  try{
//                      var jsonResponse = JSON.parse(this.responseText);
//                      if (jsonResponse['status'] == 'success') {
//                       ga('send', {hitType: 'event',eventCategory: blogCategory,eventAction:surveyAnswers ,eventLabel:surveyQuestion});
//                            if(surveyAnswers === "Yes"){

//                                   if(questionCheck=="1"){
//                                     document.getElementById("second-question").style.display = "block";
//                                     questionCheck = questionCheck +1;
//                                   }else{
//                                     document.getElementById("question-div").style.display = "none";
//                                     document.getElementById("answer-div").style.display = "none";
//                                     document.getElementById("thankyou-div").style.display = "inline-block";
//                                   }

//                            }else if (surveyAnswers === "No" || surveyAnswers === "Not Sure") {
//                                     document.getElementById("question-div").style.display = "none";
//                                     document.getElementById("answer-div").style.display = "none";
//                                     document.getElementById("thankyou-div").style.display = "inline-block";
//                            }

//                      }
//                      else{
//                         // alert("Please try again");
//                      }
//                  }
//                  catch(e){
//                  console.log('inside fail');
//                      return;
//                  }
//              };
//              ajax.send(params);
// }


function showPosition(){
        var userLat;
        var userLong;
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(function(position){
                var positionInfo = "Your current position is (" + "Latitude: " + position.coords.latitude + ", " + "Longitude: " + position.coords.longitude + ")";
                //alert(positionInfo);

            // Get location data
            userLat = position.coords.latitude;
            userLong =position.coords.longitude;
            if(userLat!="" && userLong!==""){
            var params = "latitude="+userLat+"&longitude="+userLong;
            var ajax = new XMLHttpRequest();
            ajax.open("POST", baseUrl+"/user-location/", true);
            ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            ajax.onload = function() {
                    try{
                        var jsonResponse = JSON.parse(this.responseText);
                        //console.log(jsonResponse['status']);
                        //alert(jsonResponse['locality']);
                        if (ajax.status === 200) {
                          if(jsonResponse['locality'] =="Pune"){
                              location.href = window.location;
                            }else
                            {
                            ec.get("med_userId", function(value) {
                             updateUserSession(value);
                              });
                            }
                        }

                    }
                        catch(e){
                            return;
                        }
                };
                ajax.send(params);
            }


            });


        }
    }
    /* Gopi
        Subscription Email notification and Popup Subscription */

        function subscriptionEmail(email){
                                    $.ajax({
                                            type: "POST",
                                            url: baseUrl+"/blogSubscriptionEmail/",
                                            data: {'Email':email},
                                            success: function(data) { },
                                            error: function() {
                                                console.log('A problem Occurred!! Please contact to support');
                                            }
                                     });
         }
        popUpFlag = 1;
        function popUpSubscription() {
            if(popUpFlag === 0){
                setTimeout( function() {
                setCookie("username","oldUser",365);
                showNewsletterPopup();
                },60000);
            }else {
            return;
            }
        }
        checkCookie();
        function setCookie(cname,cvalue,exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires=" + d.toGMTString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }
        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');

            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
        function checkCookie() {
            var user=getCookie("username");
            if (user != "" && user == "oldUser") {
               popUpFlag = 1;
            } else {
                   popUpFlag = 0;
            }
        }

         function openImgLink () {
                   ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Clicked on Image',eventLabel:'Open Ads Link'});
                   window.open(imgClickLink);
                }