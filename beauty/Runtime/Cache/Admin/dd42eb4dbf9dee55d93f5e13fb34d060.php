<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>列表页</title>
    <style>
        #page a,#page span{
            display: inline-block;
            width:18px;
            height:18px ;
            padding: 5px;
            margin: 2px;
            text-decoration: none;
            text-align: center;
            line-height: 18px;
            background: #f0ead8;
            color:#000000;
            border: 1px solid #c2d2d7;
        }
        #page a:hover{
            background: #333;
            color:#fff;
        }

        #page span{
            background: #333;
            color: #fff;
            font-weight: bold;
        }

    </style>
    <link href="/totti/Public/Admin/css/style.css" rel="stylesheet" type="text/css" />
    <link href="/totti/Public/Admin/css/select.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/totti/Public/Admin/js/jquery.js"></script>
    <script type="text/javascript" src="/totti/Public/Admin/js/jquery.idTabs.min.js"></script>
    <script type="text/javascript" src="/totti/Public/Admin/js/select-ui.min.js"></script>
    <script type="text/javascript" src="/totti/Public/Admin/editor/kindeditor.js"></script>
    <script type="text/javascript" src="/totti/Public/Admin/js/time/abc/timer/WdatePicker.js"></script>
    <script type="text/javascript">
        KE.show({
            id : 'content7',
            cssPath : './index.css'
        });
    </script>

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
        <div id="tab2" class="tabson">
            <form action="<?php echo U('Sale/search');?>" method="get" >
                <ul class="seachform">
                    <li><label>活动名称：</label><input type="text" class="scinput"  name="keywords" value="<?php echo ($keywords); ?>" placeholder="请用户输入关键字"/></li>
                    <li>
                        <label>活动时间：</label>
                        <input id="d11" name="time1" value="<?php echo ($time1); ?>" type="text" onClick="WdatePicker()" style="width: 120px;height: 25px;border: 1px solid #cccccc;"/>
                        <span style="display: inline-block;">-</span>
                        <input class="Wdate"  name="time2" value="<?php echo ($time2); ?>" type="text" id="d15" onFocus="WdatePicker({isShowClear:false,readOnly:true})" style="width: 120px;height: 25px;border: 1px solid #cccccc;"/>
                    </li>

                    <li><label>&nbsp;</label><a href=""><input name="" type="submit" class="scbtn"  value="查询"/></a></li>
                    <li><label>&nbsp;</label><input name="" type="reset" class="scbtn" value="重置"/></li>

                </ul>
            </form>

            <table class="tablelist" style="text-align: center;">
                <thead style="text-align: center;">
                <tr >
                    <th style="text-align: center;">活动编号<i class="sort"><img src="/totti/Public/Admin/images/px.gif" /></i></th>
                    <th style="text-align: center;">活动名称</th>
                    <th style="text-align: center;">开始时间</th>
                    <th style="text-align: center;">结束时间</th>
                    <th style="text-align: center;">活动规则</th>
                    <th style="text-align: center;">参加活动的品牌</th>
                    <th style="text-align: center;">活动添加时间</th>
                    <th style="text-align: center;">状态</th>
                    <th style="text-align: center;">操作</th>
                </tr>
                </thead>
                <tbody>
                    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($k % 2 );++$k;?><tr>
                            <td><?php echo ($k+$firstRow); ?></td>
                            <td><?php echo ($val["aname"]); ?></td>
                            <td><?php echo (date('Y-m-d',$val["starttime"])); ?></td>
                            <td><?php echo (date('Y-m-d',$val["stoptime"])); ?></td>
                            <td><?php echo ($val["rules"]); ?></td>
                            <td><?php echo ($val["bname"]); ?></td>
                            <td><?php echo (date('Y-m-d',$val["addtime"])); ?></td>
                            <td><?php echo ($val['astatus']?'活动进行中':'活动已结束'); ?></td>
                                <td><a href="<?php echo U('Admin/Sale/editor',array('id'=>$val['id'],'catename'=>$val['catename']));?>" class="tablelink">编辑</a></td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                   </tbody>
            </table>
            <div class="pagin">
                <div class="message">共<i class="blue"><?php echo ($count); ?></i>条记录，当前显示第&nbsp;<i class="blue"><?php echo ($currentPage); ?></i>页</div>
                <ul class="paginList" id="page">
                    <?php echo ($page); ?>
                </ul>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("#usual1 ul").idTabs();
    </script>
    <script type="text/javascript">
        $('.tablelist tbody tr:odd').addClass('odd');


        function index(id){
//            alert(123)
            var keywords= $("input[name='keywords']").val();

            var id=id?id:1;
            $.get("<?php echo U('index');?>",{"p":id,"keywords":keywords},function(res){
                $('#tab2').html(res);
            })
        }
    </script>
</div>
</body>
</html>