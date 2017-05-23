$(function () {
    var Obj=$('.form1').validate({
        rules:{
            username:{
                required:true,
                minlength:2,
                maxlength:15
            },
            password:{
                required:true,
                minlength:6,
                maxlength:18
            }
        },
        messages:{
            username:{
                required:'请填写此字段',
                minlength:'用户名至少2个字符',
                maxlength:'用户名最多15个字符'
            },
            password:{
                required:'请填写此字段',
                minlength:'密码名至少6个字符',
                maxlength:'密码最多18个字符'
            }
        }
    })
})