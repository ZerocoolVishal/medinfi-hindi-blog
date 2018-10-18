<?php
/* @var $this BlogTaskController */
/* @var $model BlogTask */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Create User';
?>

<div style="margin-left:45px;">
    <div class="form">
        <h1>Create user</h1

            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'register-user-form',
                'enableClientValidation'=>true,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                ),
            )); ?>

        <p class="note">Fields with <span class="required">*</span> are required.</p>

        <p>
            <label for="User_Type" style="display: inline-block;width:12%">User Type<span class="required">*</span></label>
            <select id= "user_type" onchange="clearmsg()" class="others" size="1" width="144" style="height:40px; width:23%;">
                <option selected disabled hidden>Select an option</option>
                <?php
                    if(isset(Yii::app()->session['med_role'])) $role = Yii::app()->session['med_role'];
                ?>
                <?php  if(!Yii::app()->user->isGuest && ($role==1)) {  ?>
                    <option value="7">Blog Auditor</option>
                <?php } ?>
                <option value="8">Blog Editor</option>
                <option value="9">Blog Writer</option>
            </select>
        </p>

        <p>
            <div class="row">
                <label for="User_Name" style="display: inline-block;width:12%">Name<span class="required">*</span></label>
                <input style="width:50%" onchange="clearmsg()" name="User[name]" id="user_name" type="text">
            </div>
        </p>

        <p>
            <div class="row">
                <label for="BlogTask_User_Mobile_No" style="display: inline-block;width:12%">Mobile No</label>
                <input style="width:50%" onchange="clearmsg()" name="User[mobile _no]" id="user_mobile_no" type="text">
            </div>
        </p>

        <p>
            <div class="row">
                <label for="BlogTask_User_Email" style="display: inline-block;width:12%">Email<span class="required">*</span></label>
                <input style="width:50%" onchange="clearmsg()" name="User[email]" id="user_email" type="text">
            </div>
        </p>

        <p>
            <div class="row">
                <label for="BlogTask_User_Address" style="display: inline-block;width:12%">Address</label>
                <input style="width:50%" onchange="clearmsg()" name="User[address]" id="user_address" type="text">
            </div>
        </p>

        <p>
            <label id="error-message" style="height: 20px;color:red;display:none;"></label>
        </p>

        <div class="row buttons">
            <button id="submitbtn" onclick="createUser()">Create User</button>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>

<script src="<?php echo HOST_NAME.Yii::app()->baseUrl.JS_BASE_PATH.JQUERY_JS?>"></script>
<link rel="stylesheet" href="<?php echo HOST_NAME.Yii::app()->baseUrl.CSS_BASE_PATH.CALENDER_CSS?>"/>
<script src="<?php echo HOST_NAME.Yii::app()->baseUrl.JS_BASE_PATH.CALENDER_JS?>" type="text/javascript"></script>

<script type="text/javascript">

var baseUrl = "<?php echo HOST_NAME.Yii::app()->baseUrl; ?>";

    function createUser()
    {
        $("#error-message").css("display", "none");
        var user_type = $('#user_type').val();
        var user_name = $('#user_name').val();
        var user_mobile_no = $('#user_mobile_no').val();
        var user_email = $('#user_email').val();
        var user_address = $('#user_address').val();
        if(user_type!="" && user_name!="" && user_email !="")
        {
            var emailreg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var emailval=emailreg.test(user_email);
            var namereg = /^[a-zA-Z]+(\s{0,1}[a-zA-Z])*$/;
            var nameval=namereg.test(user_name);
            var mobreg = /^\d{10}$/;
            var mobval=mobreg.test(user_mobile_no);
            var valerror="";
            var valres=true;
            if(emailval)
            {
                if(mobval || user_mobile_no=="")
                {
                    if(nameval)
                    {
                        $("#submitbtn").prop("disabled", true);
                        addUser(user_type,user_name,user_mobile_no,user_email,user_address);
                        //return valres;
                    }
                    else
                    {
                        valerror = "Invalid name";
                        valres=false;
                    }
                }
                else
                {
                    valerror = "Invalid mobile number";
                    valres=false;
                }
            }
            else
            {
                valerror = "Invalid email id";
                valres=false;
            }
        }
        else
        {
            valerror = "Please enter all the required fields";
            valres=false;
        }
        $("#error-message").css("display", "block");
        $("#error-message").css("color", "red");
        document.getElementById("error-message").innerHTML = valerror;
        return valres;
    }

    function addUser(userType,userName,userMobileNo,userEmail,userAddress)
    {
        var params = "userType="+userType+"&userName="+userName+"&userMobileNo="+userMobileNo+"&userEmail="+userEmail+"&userAddress="+userAddress;
        var ajax = new XMLHttpRequest();
        ajax.open("POST", baseUrl+"/adduser/", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        ajax.onload = function() {
            try{
                var jsonResponse = JSON.parse(this.responseText);
                if (ajax.status === 200) {
                    if(jsonResponse['status'] =="success"){
                        $("#error-message").css("display", "block");
                        $("#error-message").css("color", "green");
                        document.getElementById("error-message").innerHTML = jsonResponse['desc'];

                        document.getElementById("user_type").value = "blog_auditor";
                        document.getElementById("user_name").value = "";
                        document.getElementById("user_mobile_no").value = "";
                        document.getElementById("user_email").value = "";
                        document.getElementById("user_address").value = "";

                    }else
                    {
                        $("#error-message").css("display", "block");
                        $("#error-message").css("color", "red");
                        document.getElementById("error-message").innerHTML = jsonResponse['desc'];
                    }
                        $("#submitbtn").prop("disabled", false);
                }
            }
            catch(e){
                return;
            }
        };
        ajax.send(params);
    }

    function clearmsg()
    {
        document.getElementById("error-message").innerHTML="";
    }
</script>