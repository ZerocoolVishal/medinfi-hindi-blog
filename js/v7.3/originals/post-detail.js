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
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
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

//window.onload = function() {
window.addEventListener('load',function(){
    var postButton = document.getElementById("post-button");
    var emailInputBox = document.getElementById("response-email-input");
    // Get the modal
    var modal = document.getElementById('comment-acknowledgement-modal');
    // Get the <span> element that closes the modal
    var span = document.getElementById("comment-acknowledgement-modal-close");//[0];
    var shareModal = document.getElementById('shareModal');
    var shareButtonAnchor = document.getElementById("center-share-img-anchor");
    
    if (shareButtonAnchor !=null) {
        shareButtonAnchor.addEventListener("click",showSharePopup);
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
    // When the user clicks anywhere outside of the comment modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            document.body.style.overflowY = "scroll";
            modal.style.overflowY = "scroll";
            ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Closed comment acknowledgment message',eventLabel:'Closed using click on blank space'});
        }
    }
    // When the user clicks on <span> (x)of the share modal, close the share modal
    document.getElementById('shareClose').onclick = function() {
        ga('send', {hitType: 'event',eventCategory: pageName,eventAction: 'Closed share with friends',eventLabel:'Closed share with friends - '+processedPostTitle});
        shareModal.style.display = "none";
        document.body.style.overflowY = "scroll";
    }    


});







