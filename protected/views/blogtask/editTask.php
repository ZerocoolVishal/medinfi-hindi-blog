<?php
/* @var $this BlogTaskController */
/* @var $model BlogTask */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' -Edit Blog Task';
?>

<script>
    var blogTitle = "<?php echo $taskDetails['blog_topic']; ?>";
    var blogId = "<?php echo $taskDetails['id']; ?>";
    var docVersion = "<?php echo $docVersion; ?>";
    var currentFileName = "<?php if(!empty($additionalInfo)) { echo $additionalInfo[0]['document_name']; } else { echo "";} ?>";
</script>

<div style="margin-left:45px;">
    <div class="form">
        <h1><?php echo $taskDetails['blog_topic']; ?>
            <style>
                table, th, td {
                    border: 1px solid black;
                }
            </style>
        </h1



            <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'register-user-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

            <?php
            if(!empty($detailActionResult) && ($userRoleDetails->role_name == BLOG_AUDITOR || $userRoleDetails->role_name == ADMIN)) {

                $tableData = "<table style='width:70%; padding: 5px;'>
  <tr>
    <th>Action</th>
    <th>Status</th>
    <th>Action By</th>
    <th>Comments</th>
    <th>Reason</th>
    <th>Action On</th>
  </tr>";
                foreach($detailActionResult as $data){
                    $tableData = $tableData."<tr>
      <td>".$data['action']."</td>
      <td>".$data['status']."</td>
      <td>".$data['name']."</td>
      <td>".$data['reason']."</td>
      <td>".$data['comments']."</td>
      <td>".$data['action_on']."</td>
    </tr>";
                }

                $tableData = $tableData."</table><br>";
                echo $tableData;
            }
            ?>

            <?php
            if(!empty($additionalInfo)) {
                $downloadLink = "<p><div class='row'><label for='download' style='display: inline-block;width:12%'>Download blog document</label>
<a href=".S3_FIRST_BUCKET_DOC_URL.$additionalInfo[0]['document_link'].">".$additionalInfo[0]['document_name']."</a>
</div>
<br>
</p>";
                echo $downloadLink;
            }
            ?>

            <p>
            <label for="blog" style="display: inline-block;width:12%">Select blog document</label>
            <input style="width:50%" id="blog_doc" type='file' onchange="checkfile(this);" accept=".doc, .docx"  />
            </p>

        <p>
        <div class="row">
            <label for="comments" style="display: inline-block;width:12%">Comments</label>
            <input style="width:50%" name="blog_comments" id="blog_comments" type="text">
        </div>
        </P>

    <?php

    if($userRoleDetails->role_name == BLOG_AUDITOR || $userRoleDetails->role_name == ADMIN){
        $auditorViews='<p>
                    <div class="row">
                       <label for="bloglink" style="display: inline-block;width:12%">Blog link</label>
                       <input style="width:50%" name="blog_link" id="blog_link" type="text" onchange="onChangeBlogLink()">
                       <label for="bloglinktxt" style="display: inline-block;width:12%">mandatory if you are publishing task </label>
                   </div>
               </p>
<p>
 <label for="rejectreason" style="display: inline-block;width:12%">Select Reason</label>
 <select id= "rejectList" class="others" size="1" width="144" style="height:40px; width:23%;" onchange="onChangeRejectList()">

           <option value="" class="others"></option>
           <option value="SOP Violation" class="others">SOP Violation</option>
           <option value="Plagiarised Material" class="others">Plagiarised Material</option>
           <option value="Incorrect Facts" class="others">Incorrect Facts</option>
           <option value="Too Many Grammatical Error" class="others">Too Many Grammatical Error</option>

        </select>
        <label for="bloglinktxt" style="display: inline-block;width:12%">mandatory if you are rejecting task </label>
</p>';
        echo $auditorViews;
    }
    ?>

    <p>
        <label id="error-message" style="height: 20px;color:red;display:none;">Please enter all the required fields</label>
        <br><br>
    </p>

    <?php
    if($userRoleDetails->role_name == BLOG_EDITOR ){
        $button='<div class="row buttons">
        <button id="approvebtn" onclick="createTask(\'approved_by_editor\')">Approve Task</button>
        <button id="reassignbtn" onclick="createTask(\'reassigned_by_editor\')">Re-Assign Task</button>
    </div>';
        echo $button;
    }
    else if($userRoleDetails->role_name == BLOG_WRITER ){
        $button='<div class="row buttons">
        <button id="submitbtn" onclick="createTask(\'submitted_by_writer\')">Submit Task</button>
        </div>';
        echo $button;
    } else if($userRoleDetails->role_name == BLOG_AUDITOR || $userRoleDetails->role_name == ADMIN){
        $button='<div class="row buttons">
            <button id="publishbtn" onclick="createTask(\'published_by_auditor\')">Publish Task</button>
            <button id="auditorreassignbtn" onclick="createTask(\'reassigned_by_auditor\')">Re-Assign Task</button>
            <button id="rejectbtn" onclick="createTask(\'rejected_by_auditor\')">Reject Task</button>
        </div>';
        echo $button;
    }
    ?>

    <?php $this->endWidget(); ?>
</div><!-- form -->
</div>

<script src="<?php echo HOST_NAME.Yii::app()->baseUrl.JS_BASE_PATH.JQUERY_JS?>"></script>
<script type="text/javascript">

    var baseUrl = "<?php echo HOST_NAME.Yii::app()->baseUrl; ?>";
    var userRole = "<?php echo $userRoleDetails->role_name; ?>";
    var BLOG_EDITOR = "<?php echo BLOG_EDITOR; ?>";
    var BLOG_WRITER = "<?php echo BLOG_WRITER; ?>";
    var BLOG_AUDITOR = "<?php echo BLOG_AUDITOR; ?>";
    var ADMIN = "<?php echo ADMIN; ?>";

    function createTask(action)
    {
        $("#error-message").css("display", "none");

        if($.trim($('#blog_doc').val()).length){
            $("#error-message").css("display", "none");

            var validExts = new Array(".doc", ".docx");
            var fileExt = $('#blog_doc').val();
            fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
            if (validExts.indexOf(fileExt) < 0) {
                $("#error-message").css("display", "block");
                $("#error-message").css("color", "red");
                document.getElementById("error-message").innerHTML = "Invalid file selected, valid files are of " +
                    validExts.toString() + " types.";
                return false;
            }

            var comments = $("#blog_comments").val();
            var picFile = $("#blog_doc").prop("files")[0];
            var form_data = new FormData();
            form_data.append('file', picFile);
            var fileName = "";

            if (docVersion == 0)
            {
                fileName = getImageName();
            }
            else{

                var name = document.getElementById('blog_doc');
                var newFileName = name.files.item(0).name;
                newFileName = newFileName.substring(0,newFileName.lastIndexOf('.'));

                var currentFileNameTmp = currentFileName.substring(0,newFileName.lastIndexOf('_'));
                var docVersionTmp = docVersion;
                currentFileNameTmp = currentFileNameTmp +"_"+(++docVersionTmp);

                if((currentFileNameTmp == newFileName) == true){
                    fileName = currentFileNameTmp;
                }else{
                    $("#error-message").css("display", "block");
                    $("#error-message").css("color", "red");
                    document.getElementById("error-message").innerHTML = "Kindly update the version number of the file by 1 and upload again";
                    return false;
                }

            }

            var blogLink = "";
            var blogReason = "";
            if(userRole == BLOG_AUDITOR || userRole == ADMIN){
                blogLink = $("#blog_link").val().trim();
                blogReason = $('#rejectList').val().trim();

                if(action == "published_by_auditor" && blogLink == "") {
                    $("#error-message").css("display", "block");
                    $("#error-message").css("color", "red");
                    document.getElementById("error-message").innerHTML = "Please fill the live blog link";
                    return false;
                }
                else if (action == "rejected_by_auditor" && blogReason == "") {
                    $("#error-message").css("display", "block");
                    $("#error-message").css("color", "red");
                    document.getElementById("error-message").innerHTML = "Please select reason for rejecting task";
                    return false;
                }
            }

            if(userRole == BLOG_EDITOR){
                document.getElementById("approvebtn").disabled = true;
                document.getElementById("reassignbtn").disabled = true;
            }
            else if(userRole == BLOG_WRITER ){
                document.getElementById("submitbtn").disabled = true;
            }
            else if(userRole == BLOG_AUDITOR || userRole == ADMIN){
                document.getElementById("publishbtn").disabled = true;
                document.getElementById("auditorreassignbtn").disabled = true;
                document.getElementById("rejectbtn").disabled = true;
            }

            form_data.append('blogId',blogId);
            form_data.append('fileName',fileName);
            form_data.append('action',action);
            form_data.append('comments',comments);

            if(userRole == BLOG_AUDITOR || userRole == ADMIN){
                if(action == "published_by_auditor" ){
                    form_data.append('bloglink',blogLink);
                }
                else if (action == "rejected_by_auditor" ){
                    form_data.append('reason',blogReason);
                }
            }

            $.ajax({
                type: "POST",
                url: baseUrl+"/uploadblogtask/",
                cache: false,
                contentType: false,
                processData: false,
                dataType:'json',
                data: form_data,
                success: function(data) {

                    if(data.status =="success"){
                        $("#error-message").css("display", "block");
                        $("#error-message").css("color", "green");
                        document.getElementById("error-message").innerHTML = data.desc;

                        if(userRole == BLOG_EDITOR ){
                            document.getElementById("approvebtn").disabled = true;
                            document.getElementById("reassignbtn").disabled = true;
                        }
                        else if(userRole == BLOG_WRITER ){
                            document.getElementById("submitbtn").disabled = true;
                        }
                        else if(userRole == BLOG_AUDITOR || userRole == ADMIN){
                            document.getElementById("publishbtn").disabled = true;
                            document.getElementById("auditorreassignbtn").disabled = true;
                            document.getElementById("rejectbtn").disabled = true;
                        }

                    }else
                    {
                        $("#error-message").css("display", "block");
                        $("#error-message").css("color", "red");
                        document.getElementById("error-message").innerHTML = data.desc;

                        if(userRole == BLOG_EDITOR ){
                            document.getElementById("approvebtn").disabled = false;
                            document.getElementById("reassignbtn").disabled = false;
                        }
                        else if(userRole == BLOG_WRITER ){
                            document.getElementById("submitbtn").disabled = false;
                        }
                        else if(userRole == BLOG_AUDITOR || userRole == ADMIN){
                            document.getElementById("publishbtn").disabled = false;
                            document.getElementById("auditorreassignbtn").disabled = false;
                            document.getElementById("rejectbtn").disabled = false;
                        }
                    }

                },
                error: function() {
                    alert('A problem Occured!! Please contact to support');
                }
            });


        }else{

            $("#error-message").css("display", "block");
            $("#error-message").css("color", "red");
            document.getElementById("error-message").innerHTML = "Please upload a world document";
            return false;

        }

    }

    function checkfile(sender) {
        $("#error-message").css("display", "none");
        var validExts = new Array(".doc", ".docx");
        var fileExt = sender.value;
        fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
        if (validExts.indexOf(fileExt) < 0) {
            $("#error-message").css("display", "block");
            $("#error-message").css("color", "red");
            document.getElementById("error-message").innerHTML = "Invalid file selected, valid files are of " +
                validExts.toString() + " types.";
            return false;
        }
        else return true;
    }

    function getImageName() {
        var filename = blogTitle.replace(/[^a-zA-Z0-9 ]/g, "");
        filename = filename.replace(/\s+/g,"_");
        filename = filename.toUpperCase();
        filename = blogId+"_"+filename+"_1";
        return filename;
    }

    function onChangeBlogLink() {
          var val = document.getElementById("blog_link").value;
          if(val){
          document.getElementById("rejectList").disabled = true;
          document.getElementById("rejectList").selectedIndex = "0";
          }else
          {
          document.getElementById("rejectList").disabled = false;
          }
    }

    function onChangeRejectList() {
          var val =  $('#rejectList').val().trim();
          if(val){
          document.getElementById("blog_link").disabled = true;
          document.getElementById("blog_link").value = "";
          }else
          {
          document.getElementById("blog_link").disabled = false;
          }
        }

</script>
