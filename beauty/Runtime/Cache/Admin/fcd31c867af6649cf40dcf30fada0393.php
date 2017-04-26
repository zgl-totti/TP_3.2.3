<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css" >
        #page a,#page span{display: inline-block; width:18px;height:18px ;  padding: 5px;  margin: 2px;  text-decoration: none;  text-align: center;  line-height: 18px;  background: #f0ead8;  color:#000000;  border: 1px solid #c2d2d7;  }
        #page a:hover{background: #EDF6FA;color:#333;}
        #page span{background: #333;color: #fff;font-weight: bold;}
        #page div{float:right;}
    </style>

<title>列表页</title>
<link href="/totti/Public/Admin/css/style.css" rel="stylesheet" type="text/css" />
<link href="/totti/Public/Admin/css/select.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/totti/Public/Admin/js/jquery.js"></script>
<script type="text/javascript" src="/totti/Public/Admin/js/jquery.idTabs.min.js"></script>
<script type="text/javascript" src="/totti/Public/Admin/js/select-ui.min.js"></script>
<script type="text/javascript" src="/totti/Public/Admin/js/jquery.min.1.8.2.js"></script>
<script type="text/javascript" src="/totti/Public/Admin/js/layer/layer.js"></script>
<script type="text/javascript" src="/totti/Public/Admin/editor/kindeditor.js"></script>
<script type="text/javascript" src="/totti/Public/Admin/js/time/abc/timer/WdatePicker.js"></script>
<script type="text/javascript">
    KE.show({
        id : 'content7',
        cssPath : './index.css'
    });
  </script>
<script type="text/javascript">
  $(function() {
      $('.layer').click(function () {
          var id=$(this).attr('id');
         $.post('<?php echo U("Admin/Order/sendGoods");?>',{id:id},function(res){
             if(res.status==1){
                 layer.msg('发货完成',{time:1000},function(){
                     location="<?php echo U('Admin/Order/index');?>";
                 });
             }
          })
      })
  });
</script>
</head>

<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="#">首页</a></li>
    <li><a href="#">订单管理</a></li>
    </ul>
    </div>
    <div class="formbody">
    <div id="usual1" class="usual">
  	<div id="tab2" class="tabson">

    <form action="<?php echo U('Order/index');?>" method="get">
    <ul class="seachform">
    <li><label>订单编号</label>
        <input name="keywords" style="width: 80px;height:25px;border: 1px solid #cccccc;" type="text" class="scinput" value="<?php echo ($keywords); ?>"/></li>
    <li>
        <label>创建时间：</label>
        <input id="d11" type="text" onClick="WdatePicker()" style="width: 80px;height:25px;border: 1px solid #cccccc;" name="time1" />
        <span style="display: inline-block;">-</span>
        <input name="time2" class="Wdate" type="text" id="d15" onFocus="WdatePicker({isShowClear:false,readOnly:true})" style="width: 80px;height: 25px;border: 1px solid #cccccc;"/>
    </li>
    <li><label>&nbsp;</label><input name="" type="submit" class="scbtn" style="width: 80px;height:25px;" value="查询"/></li>
    <li><label>&nbsp;</label><input name="" type="button" class="scbtn" id="exportdata" value="Excel表导出" style="width: 90px;height: 25px;margin:0;padding: 0;"/></li>
    </ul>
    </form>

      <table class="tablelist">
    	<thead>
    	<tr>
        <th><input name="" type="checkbox" value="" checked="checked"/></th>
        <th><i class="sort"><img src="/totti/Public/Admin/images/px.gif" /></i>编号</th>
        <th>用户名</th>
        <th>订单序列号</th>
        <th>订单价格</th>
        <th>订单创建时间</th>
        <th>订单详情</th>
        <th>订单状态</th>
        <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($k % 2 );++$k;?><tr>
            <td><input name="" type="checkbox" value="" /></td>
            <td><?php echo ($k+$firstRow); ?></td>
            <td><?php echo ($val["username"]); ?></td>
            <td><?php echo ($val["orderno"]); ?></td>
            <td><?php echo ($val["price"]); ?></td>
            <td><?php echo date('Y-m-d H:i:s',$val['create_time']);?></td>
            <td><a href="<?php echo U('Admin/Order/detial',array('oid'=>$val['id']));?>">查看详情</a></td>
            <td><?php echo ($val["statusname"]); ?></td>
            <td><a href="#" class="layer" style="color:#ef5b00;" id="<?php echo ($val["id"]); ?>"><?php echo ($val["adminstatus"]); ?></a> </td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
      </table>
        <div id="page"><?php echo ($page); ?></div>
    </div>
	</div>
	<script type="text/javascript"> 
      $("#usual1 ul").idTabs(); 
    </script>
    
    <script type="text/javascript">
	$('.tablelist tbody tr:odd').addClass('odd');



    $(function(){
        $("#exportdata").click(function(){
            $.post("<?php echo U('Order/export');?>?keywords=<?php echo ($keywords); ?>",'',function(res){
                if(res.status==1){
                    window.open("<?php echo U('Order/export');?>?keywords=<?php echo ($keywords); ?>");
                }else{
                    layer.msg(res.info,{icon:5});
                }
            })
        })
    })

	</script>

    </div>

</body>

</html>