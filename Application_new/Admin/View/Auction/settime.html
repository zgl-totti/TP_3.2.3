<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>无标题文档</title>
    <link href="__PUBLIC__/Admin/css/style.css" rel="stylesheet" type="text/css" />
    <link href="__PUBLIC__/Admin/css/select.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__PUBLIC__/Admin/js/jQuery-1.8.2.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Admin/js/jquery.idTabs.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Admin/js/select-ui.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Admin/js/timer/WdatePicker.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Admin/js/layer/layer.js"></script>
    <script type="text/javascript">
        $(document).ready(function(e) {
            $(".select1").uedSelect({
                width : 345
            });
            $(".select2").uedSelect({
                width : 167
            });
            $(".select3").uedSelect({
                width : 100
            });
        });
    </script>

</head>
<body>
<div class="place">
    <span>位置：</span>
    <ul class="placeul">
        <li><a href="#">首页</a></li>
        <li><a href="#">系统设置</a></li>
    </ul>
</div>
<div class="formbody">
    <div id="usual1" class="usual">
        <div id="tab1" class="tabson">
            <form action="{:U('Auction/settime')}" method="post" id="form1">
            <ul class="forminfo">
                <volist name="auctionInfo" id="val1">
                    <input type="hidden" name="id" value="{$val1['ag_id']}" />
                <li><label>商品名称<b>*</b></label>
                    <input disabled="disabled" name="" type="text" value="{$val1['goodsname']}" class="dfinput" value="请填写单位名称"  style="width:518px;"/>
                </li>
                <li>
                    <label>商品图片<b>*</b></label>
                    <div class="imagBox ">
                        <div class="imgsml">
                            <img id="img0"  src="__PUBLIC__/Admin/Uploads/goods/{$val1['pic']}" alt="" width="150px" height="100px"/>
                        </div>
                    </div>
                    <!--<input id="file0" style="margin-left: 85px;margin-top: 10px;  " type="file" name="pic"/>-->
                </li>
                <li><label>开始时间<b>*</b></label>
                    <!--<input id="d11" style="border:1px;" type="text" onClick="WdatePicker()" />-->
                    <input id="d11" name="starttime" onClick="WdatePicker()" type="text" class="dfinput" style="height:20px;width:146px;"/>
                </li>
                <li><label>结束时间<b>*</b></label><input name="endtime" class="Wdate" type="text" id="d15" onFocus="WdatePicker({isShowClear:false,readOnly:true})"/></li>
                <li><label>&nbsp;</label><input id="sub" type="button" class="btn" value="确认设置"/></li>
                </volist>
            </ul>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<script>
    $(function(){
        //设置拍卖时间
        $("#sub").click(function(){
            $.post("{:U('Auction/settime')}",$("#form1").serialize(),function(res){
                if(res.status=="ok"){
                    layer.msg(res.msg,{icon:1,time:2000},function(){
                        window.location.href="{:U('Auction/showlist')}";
                    })
                }else{
                    layer.msg(res.msg,{icon:2,time:2000})
                }
            })
        })
    })
</script>
<script>
    $("#file0").change(function(){
        var objUrl = getObjectURL(this.files[0]) ;
        console.log("objUrl = "+objUrl) ;
        if (objUrl) {
            $("#img0").attr("src", objUrl) ;
        }
    }) ;
    //建立一個可存取到該file的url
    function getObjectURL(file) {
        var url = null ;
        if (window.createObjectURL!=undefined) { // basic
            url = window.createObjectURL(file) ;
        } else if (window.URL!=undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file) ;
        } else if (window.webkitURL!=undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file) ;
        }
        return url ;
    }
</script>
