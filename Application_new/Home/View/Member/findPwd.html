<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>欢迎来到找回密码页面</title>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/styles.css">
    <script src="__PUBLIC__/Home/js/login/jQuery-1.8.2.min.js"></script>
    <script src="__PUBLIC__/Home/js/login/jquery.validate.js"></script>
   <script src="__PUBLIC__/Home/js/layer/layer.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Home/js/abc.js"></script>
    <script>
        $(function(){
            //validate表单验证
            var validate=$('#form1').validate({
                //设置验证规则
                rules:{
                    username:{
                        required:true,
                        minlength:2,
                        maxlength:15,
                        remote:{
                            url:'{:U("chkUserName1")}',
                            type:'post'
                        }
                    },
                    password:{
                        required:true,
                        minlength:5,
                        maxlength:20
                    },
                    repwd:{
                        required:true,
                        equalTo:"#pwd"

                    },
                    verify:{
                        required:true,
                        remote:{
                            url:'{:U("chkVerify")}',
                            type:'post'
                        }
                    }

                },
                messages:{
                    username:{
                        required:'用户名不能为空',
                        minlength:'用户名至少需要2个字符',
                        maxlength:'用户名最多15个字符',
                        remote:'用户名已被占用'
                    },
                    password:{
                        required:'密码不能为空',
                        minlength:'密码长度至少5个字符',
                        maxlength:'密码长度最多20个字符'
                    },
                    repwd:{
                        required:'重复密码不能为空',
                        equalTo:'两次密码输入不一致'
                    },
                    verify:{
                        required:'请输入验证码',
                        remote:'验证码不正确'
                    }
                },
                success: function(div) {
                    div.addClass("ok").text('通过验证');
                },
                validClass:'ok',
                errorElement:'div'
            })


            $('.floating-btn').click(function(){
                //表单提交之前判断前端验证是否通过，只有通过时才提交表单


                    if (validate.form()) {
                        $.post("{:U('register')}", $('#form1').serialize(), function (res) {
                            if (res.status == 1) {
                                layer.open({
                                    content: res.info,
                                    icon: 1,
                                    yes: function () {
                                        location.href = "{:U('Home/Index/index')}";
                                    }
                                });
                            } else {
                                layer.open({
                                    content: res,
                                    icon: 2,
                                    title: '错误提示'
                                });
                            }
                        }, 'json')
                    }

            })
        })
    </script>
</head>
<body>
<div class="login-top">
    <div class="wrapper">
        <span class="logo"><img src="__PUBLIC__/Home/images/logo2.png" alt=""></span>
    </div>
</div>
<div class="zhu">
    <img src="__PUBLIC__/Home/images/zs.png" alt="左上" class="zs">
    <img src="__PUBLIC__/Home/images/ys.png" alt="右上" class="ys">
    <!--<div class="ad"><img src="__PUBLIC__/Home/images/1.png" alt="" class="yuan"></div>-->
    <div class="panel-lite">
        <div class="img"><img  src="__PUBLIC__/Home/images/h1.png" alt=""/></div>
        <h4>找回密码</h4>
        <form action="" method="post" id="form1">
            <div class="form-group">
                <input name="username" required="required" class="form-control" autocomplete="off"/>
                <label class="form-label">用户名</label>
            </div>
            <div class="form-group">
                <input name="password" id="pwd" type="password" required="required"  class="form-control"/>
                <label class="form-label">手机号</label>
            </div>
            <div class="form-group">
                <input name="repwd" type="password" required="required" class="form-control"/>
                <label class="form-label">输入验证码</label>
            </div>
            <div class="denglu">
                <!--<div class="qq"><img src="__PUBLIC__/Home/images/qq.png"></div>
                <div class="wb"><img src="__PUBLIC__/Home/images/wb.png"></div>
                <div class="zfb"><img src="__PUBLIC__/Home/images/zfb.png"></div>-->
                <span id="hzy_fast_login"></span>
            </div>
            <div>
                <button id="btnSub" class="floating-btn" type="button" ><i class="icon-arrow"></i></button>
            </div>

        </form>
    </div>
    <img src="__PUBLIC__/Home/images/zx.png" alt="左下" class="zx">
    <img src="__PUBLIC__/Home/images/yx.png" alt="右下" class="yx">
</div>


<div class="footer">
    关于我们 | 联系我们 | 人才招聘 | 商家入驻 | 广告服务 | 手机电商 | 友情链接 | 销售联盟 | 美食社区 | 热爱公益 | English Site<br>
    <span>Copyright © 2004-2016  我爱我家wawj.com 版权所有</span>
</div>
</body>
</html>
