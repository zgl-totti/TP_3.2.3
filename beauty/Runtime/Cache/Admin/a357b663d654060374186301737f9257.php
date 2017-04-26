<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>无标题文档</title>
    <link href="/totti/Public/Admin/css/style.css" rel="stylesheet" type="text/css" />
    <link href="/totti/Public/Admin/css/select.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/totti/Public/Admin/js/jQuery-1.8.2.min.js"></script>
    <script type="text/javascript" src="/totti/Public/Admin/js/jquery.idTabs.min.js"></script>
    <script type="text/javascript" src="/totti/Public/Admin/js/select-ui.min.js"></script>
    <script type="text/javascript" src="/totti/Public/Admin/js/layer/layer.js"></script>
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
            /*添加分类三级联动*/
            var getCate=function(pid,name){
                $.post('<?php echo U("Admin/Foote/getCateByPid");?>',{pid:pid},function(res){
                    if(res.status){
                        var str='<option value="0" selected>请选择</option>';
                        for(var i in res.info){
                            str+='<option value="'+res.info[i].id +'">' + res.info[i].fname+ '</option>';
                        }
                        $('select[name="'+name+'"]').html(str);
                        $('select[name="'+name+'"]').parent().find(".uew-select-text").text($('select[name="'+name+'"]').find(':selected').text());
                    }
                })
            };
            getCate(0,'firstCate');

            $('select[name="firstCate"]').change(function(){
                getCate($(this).find(':selected').val(),'secondCate');
                $(this).parents('.vocation').next('.vocation').show();
                $('select[name="thirdCate"]').empty().parents('.vocation').hide();
            });

            /*添加分类三级联动*/

            /*向分类表中添加分类*/
            $('.btn').click(function(){
                $.post('<?php echo U("Admin/Foote/editorCateByPid");?>',$("#form1").serialize(),function(res){
                    if(res.info=='编辑成功'){
                        layer.msg('编辑成功',function(){
                            location='<?php echo U("Foote/index");?>'
                        });
                    }if(res.info=='编辑失败'){
                        layer.msg('编辑失败',function(){
                            location='<?php echo U("Foote/editorCate");?>'
                        });
                    }
                })
            });

            /*向分类表中添加分类*/

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
            <form action="" method="post" id="form1">
                <ul class="forminfo">
                    <li><label>分类名称<b>*</b></label><input name="fname" type="text" class="dfinput" value="<?php echo ($cname); ?>"    style="width:240px;"/></li>
                    <li><label>顶级分类<b>*</b></label>
                        <input type="hidden" name="id" value="<?php echo ($id); ?>"/>
                        <div class="vocation">
                            <select class="select2" name="firstCate">
                            </select>
                        </div>
                        <div class="vocation" style="display:none">
                            <select class="select2" name="secondCate" >

                            </select>
                        </div>
                    </li>
                    <li><label>&nbsp;</label><input type="button" class="btn" value="马上编辑"/></li>
                </ul>
            </form>
        </div>
    </div>
</div>

</body>

</html>