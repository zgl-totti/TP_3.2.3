<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>列表页</title>
<link href="/totti/Public/Admin/css/style.css" rel="stylesheet" type="text/css" />
<link href="/totti/Public/Admin/css/select.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/totti/Public/Admin/js/jquery.min.1.8.2.js"></script>
<script type="text/javascript" src="/totti/Public/Admin/js/jquery.idTabs.min.js"></script>
<script type="text/javascript" src="/totti/Public/Admin/js/select-ui.min.js"></script>
<script type="text/javascript" src="/totti/Public/Admin/js/kindeditor/kindeditor-all.js"></script>
<script type="text/javascript" src="/totti/Public/Admin/js/time/abc/timer/WdatePicker.js"></script>
    <script type="text/javascript" src="/totti/Public/Admin/js/layer/layer.js"></script>
    <style type="text/css">
        .paginList a, .paginList span{display: inline-block;width:18px;height:18px ;padding: 5px;margin: 2px;text-decoration: none;text-align: center;line-height: 18px;background: #cccccc;  color:#000000;  border: 1px solid #c2d2d7;  }
        .paginList a:hover{background: mediumblue;color:#fff;  }
        .paginList span{background: mediumblue;color: #fff;font-weight: bold;}
    </style>
<script type="text/javascript">
    $(document).ready(function(e) {
        KindEditor.ready(function (K) {
            K.create('#content7', {
                allowFileManager: true,
                afterBlur: function () {  //利用该方法处理当富文本编辑框失焦之后，立即同步数据
                    this.sync("#content7");
                }
            });
        });
    })
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
    <li><a href="#">底部管理</a></li>
    <li><a href="#">底部列表</a></li>
    </ul>
    </div>
    <div class="formbody">
        <div id="usual1" class="usual">
            <div id="tab2" class="tabson">
<form action="<?php echo U('Admin/Foote/search');?>" method="get" id="form1">
    <ul class="seachform">
        <li><label>名称</label><input name="fname" type="text" class="scinput" value="<?php echo ($fname); ?>" style="width: 100px;height: 25px;"/></li>
        <li><label>&nbsp;</label><input name="" type="submit" class="scbtn" value="查询" style="width: 60px;height: 25px;"/></li>
    </ul>
    </form>
    <table class="tablelist">
    	<thead>
    	<tr>
        <th style="font-size: 10px;">编号<i class="sort"><img src="/totti/Public/Admin/images/px.gif" /></i></th>
        <th style="font-size: 10px;">名称</th>
        <th style="font-size: 10px;">添加时间</th>
        <th style="font-size: 10px;">是否展示</th>
        <th style="font-size: 10px;">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($fnamelist)): $k = 0; $__LIST__ = $fnamelist;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$val): $mod = ($k % 2 );++$k;?><tr>
        <td style="font-size: 10px;"><?php echo ($k+$firstRow); ?></td>
        <td style="font-size: 10px;"><a href="<?php echo U('Admin/Foote/index',array('pid'=>$val['id']));?>"><?php echo ($val["fname"]); ?></a></td>
        <td style="font-size: 10px;"><?php echo date('Y-m-d H:i:s',$val['addtime']);?></td>
        <td style="font-size: 10px;" class="zhuangtai"><?php echo ($val['show']==1?'展示':'下架'); ?></td>
        <td style="font-size: 10px;" class="par">
            <a href="<?php echo U('Admin/Foote/editor',array('phid'=>$val['id']));?>" class="tablelink">编辑&nbsp;&nbsp;&nbsp;&nbsp;</a>
            <a  phid="<?php echo ($val['id']); ?>" pid="<?php echo ($val['pid']); ?>" class="tablelink updashow" style="cursor:pointer;"><?php echo ($val['show']==1?'下架':'展示'); ?></a>
            <a href="<?php echo U('Admin/Foote/news',array('id'=>$val['id']));?>" class="tablelink" style="margin-left: 15px;cursor: pointer;"><?php echo ($val['pid']==0?'':'发表文章'); ?></a>
        </td>
        </tr><?php endforeach; endif; else: echo "$empty" ;endif; ?>
        </tbody>
    </table>
       <div class="pagin">
        <div class="message">共<i class="blue" name="count"><?php echo ($count); ?></i>条记录，当前显示第&nbsp;<i class="blue" name="current">&nbsp;<?php echo ($currentPage); ?></i>页</div>
      <div class="paginList">
          <?php echo ($page); ?>
      </div>
    </div>
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
<script type="text/javascript">
    $(function(){
        $('.updashow').click(function(){
            ptid=$(this).attr('phid');
            pid=$(this).attr('pid');
            cur=$(this);
            $.post('<?php echo U("Admin/Foote/updateshow");?>',{ptid:ptid,pid:pid},function(response){
                if(response.status){
                    if(response.info==1){
                        cur.text('下架');
                        cur.parents('.par').prev('.zhuangtai').text('展示');
                    }if(response.info==0){
                        cur.text('展示');
                        cur.parents('.par').prev('.zhuangtai').text('下架');
                    }
                }
            })
        })
    })
</script>

</html>
<script type="text/javascript">
    $(function(){
        $("#exportdata").click(function(){
            $.post("<?php echo U('Goods/export');?>?goodsname=<?php echo ($goodsname); ?>&bname=<?php echo ($bname); ?>&cname=<?php echo ($cname); ?>&time1=<?php echo ($time1); ?>&time2=<?php echo ($time2); ?>&price1=<?php echo ($price1); ?>&price2=<?php echo ($price2); ?>",'',function(res){
                if(res.status==1){
                    window.open("<?php echo U('Goods/export');?>?goodsname=<?php echo ($goodsname); ?>&bname=<?php echo ($bname); ?>&cname=<?php echo ($cname); ?>&time1=<?php echo ($time1); ?>&time2=<?php echo ($time2); ?>&price1=<?php echo ($price1); ?>&price2=<?php echo ($price2); ?>");
                }else{
                    layer.msg(res.info,{icon:5});
                }
            })
        })
    })
</script>