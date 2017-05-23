$(function () {
    var validate=$('#form1').validate({
        rules:{
            username:{
                required:true,
                minlength:4,
                maxlength:10,
                remote:{
                    url:'test.php?act=chkUsername',
                    type:'post'
                }
            },
            pwd:{
                required:true,
                minlength:6,
                maxlength:18
            },
            repwd:{
                required:true,
                equalTo:'#pwd'
            },
            verify:{
                require:true,
            }
        },
        messages:{
            username:{
                required:'请填写此字段',
                minlength:'用户名至少4个字符',
                maxlength:'用户名最多10个字符',
                remote:'用户名已被占用'
            },
            pwd:{
                required:'请填写此字段',
                minlength:'密码名至少6个字符',
                maxlength:'密码最多18个字符',

            },
           repwd:{
               required:"请填写此字段",
               equalTo:'两次密码不一样',
        },
            verify:{
                required:'请输入验证码',
            },

        }

    })
  /*  $('#form1').submit(function () {
 if(validate.form()){
 $('#form1').ajaxSubmit(function (response) {
 alert(response.username);
 })
 };
 return false;
 })
 })*/


})