<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>已发货订单页</title>
<link href="/totti/Public/Admin/css/style.css" rel="stylesheet" type="text/css" />
<link href="/totti/Public/Admin/css/select.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/totti/Public/Admin/js/jquery.js"></script>
<script type="text/javascript" src="/totti/Public/Admin/js/jquery.idTabs.min.js"></script>
<script type="text/javascript" src="/totti/Public/Admin/js/select-ui.min.js"></script>
<script type="text/javascript" src="/totti/Public/Admin/editor/kindeditor.js"></script>
<script type="text/javascript" src="/totti/Public/Admin/js/time/abc/timer/WdatePicker.js"></script>
    <style type="text/css" >
        #page a,#page span{display: inline-block; width:18px;height:18px ;  padding: 5px;  margin: 2px;  text-decoration: none;  text-align: center;  line-height: 18px;  background: #f0ead8;  color:#000000;  border: 1px solid #c2d2d7;  }
        #page a:hover{background: #EDF6FA;color:#333;}
        #page span{background: #333;color: #fff;font-weight: bold;}
        #page div{float:right;}

    </style>
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
    <li><a href="#">订单管理</a></li>
    </ul>
    </div>
    
    <div class="formbody">
    <div id="usual1" class="usual"> 

  	<div id="tab2" class="tabson">

        <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($k % 2 );++$k;?><div id="order" style="float: left;margin-right: 500px">
            <ul>
                <li style="padding: 10px;font-size: 16px">订单编号:<?php echo ($val["orderno"]); ?></li>
                <li style="padding: 10px;font-size: 16px">买家姓名:<?php echo ($val["username"]); ?></li>
                <li style="padding: 10px;font-size: 16px">付款时间:<?php echo date('Y-m-d H:i:s',$val['create_time']);?></li>
            </ul>
        </div>
        <div>
            <ul>
                <li style="padding: 10px;font-size: 16px">买家手机号:<?php echo ($val["mobile"]); ?></li>
                <li style="padding: 10px;font-size: 16px">邮编:<?php echo ($val["ecode"]); ?></li>
                <li style="padding: 10px;font-size: 16px">收货地址:<?php echo ($val["area"]); echo ($val["address"]); ?></li>
            </ul>
        </div>




            <table class="tablelist">
                <thead>
                <tr>
                <th><input name="" type="checkbox" value="" checked="checked"/></th>
                <th><i class="sort"><img src="/totti/Public/Admin/images/px.gif" /></i>编号</th>
                <th>商品名称</th>
                <th>图片</th>
                <th>商品价格</th>
                <th>商品数量</th>
                <th>商品总价</th>
                </tr>
                </thead>
                <tbody>
             <?php if(is_array($val['goods'])): $k1 = 0; $__LIST__ = $val['goods'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k1 % 2 );++$k1;?><tr>
                    <td><input name="" type="checkbox" value="" /></td>
                    <td><?php echo ($k1); ?></td>
                    <td><?php echo ($v["goodsname"]); ?></td>
                    <td><img src="/Uploads/<?php echo ($v["imageurl"]); echo ($v["imagename"]); ?>" width="50" height="50"/></td>
                    <td><?php echo ($v["saleprice"]); ?></td>
                    <td><?php echo ($v["buynum"]); ?></td>
                    <td><?php echo ($v['saleprice']*$v['buynum']); ?></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>  
       
	</div> 
 
	<script type="text/javascript"> 
      $("#usual1 ul").idTabs(); 
    </script>
    
    <script type="text/javascript">
	$('.tablelist tbody tr:odd').addClass('odd');
	</script>

    </div>

</body>

</html>