<?php
/* @var $this BlogTaskController */
/* @var $model BlogTask */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Create Task';
?>

<div style="margin-left:45px;">
    <div class="form">
        <h1>Create Blog Task</h1

            <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'register-user-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

            <p class="note">Fields with <span class="required">*</span> are required.</p>
        <p>
        <div class="row">
            <label for="BlogTask_Blog_topic" style="display: inline-block;width:12%">Blog Topic<span class="required">*</span></label>
            <input style="width:50%" name="BlogTask[blog_topic]" id="BlogTask_blog_topic" type="text">
        </div>
        </p>

    <p>
    <div class="row">
        <label for="BlogTask_Focus_keyword" style="display: inline-block;width:12%">Focus Keyword<span class="required">*</span></label>
        <input style="width:50%" name="BlogTask[focus_keyword]" id="BlogTask_focus_keyword" type="text">
    </div>
    </P>

<p>
<div class="row">
    <label for="BlogTask_Target_Submission_Date" style="display: inline-block;width:12%">Target  Submission  Date<span class="required">*</span></label>
    <input type="text" name="assigndate" placeholder="View Calender" style="width:21%;"  id="datepicker" />
</div>
</P>
<p>
    <label for="BlogTask_Select_writer" style="display: inline-block;width:12%">Select Writer<span class="required">*</span></label>
    <select id= "assocList" class="others" size="1" width="144" style="height:40px; width:23%;">
        <?php
        foreach($getBlogWriterResult as $user){
            echo '<option value="'.$user['id'].'" class="others">'.$user['username'].'</option>';
        }
        ?>
    </select>
</p>

<p>
    <label id="error-message" style="height: 20px;color:red;display:none;">Please enter all the required fields</label>
</p>

<div class="row buttons">
    <button id="createTask" onclick="createTask()">Create Task</button>
</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
</div>

<script src="<?php echo HOST_NAME.Yii::app()->baseUrl.JS_BASE_PATH.JQUERY_JS?>"></script>
<link rel="stylesheet" href="<?php echo HOST_NAME.Yii::app()->baseUrl.CSS_BASE_PATH.CALENDER_CSS?>"/>
<script src="<?php echo HOST_NAME.Yii::app()->baseUrl.JS_BASE_PATH.CALENDER_JS?>" type="text/javascript"></script>


<script type="text/javascript">

    var baseUrl = "<?php echo HOST_NAME.Yii::app()->baseUrl; ?>";

    $(function() {
        // $("#datepicker").datepicker();
        $("#datepicker").datepicker({ minDate: 0 });
    });

    function createTask()
    {
        $("#error-message").css("display", "none");
        var blogWriter = $('#assocList').val();
        var submissionDate = $("#datepicker").val();
        var blogTopic = $("#BlogTask_blog_topic").val();
        var focusKeyword = $("#BlogTask_focus_keyword").val();
        if(blogWriter!="" && submissionDate!="" && blogTopic!="" && focusKeyword !="")
        {
            addTask(blogWriter,submissionDate,blogTopic,focusKeyword);
        }
        else{
            $("#error-message").css("display", "block");
            $("#error-message").css("color", "red");
            document.getElementById("error-message").innerHTML = "Please enter all the required fields";
            return false;
        }
    }

    function addTask(blogWriter,submissionDate,blogTopic,focusKeyword){

        document.getElementById("createTask").disabled = true;
        var params = "blogWriter="+blogWriter+"&submissionDate="+submissionDate+"&blogTopic="+blogTopic+"&focusKeyword="+focusKeyword;
        var ajax = new XMLHttpRequest();
        ajax.open("POST", baseUrl+"/addblogtask/", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        ajax.onload = function() {
            try{
                var jsonResponse = JSON.parse(this.responseText);
                if (ajax.status === 200) {
                    if(jsonResponse['status'] =="success"){
                        $("#error-message").css("display", "block");
                        $("#error-message").css("color", "green");
                        document.getElementById("error-message").innerHTML = jsonResponse['desc'];

                        document.getElementById("BlogTask_blog_topic").value = "";
                        document.getElementById("BlogTask_focus_keyword").value = "";
                        document.getElementById("datepicker").value = "";
                        document.getElementById("createTask").disabled = false;

                    }else
                    {
                        $("#error-message").css("display", "block");
                        $("#error-message").css("color", "red");
                        document.getElementById("error-message").innerHTML = jsonResponse['desc'];
                        document.getElementById("createTask").disabled = false;
                    }
                }

            }
            catch(e){
                return;
            }
        };
        ajax.send(params);
    }
</script>
