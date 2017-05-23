<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>列表页</title>
    <link href="__PUBLIC__/Admin/css/style.css" rel="stylesheet" type="text/css" />
    <link href="__PUBLIC__/Admin/css/select.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__PUBLIC__/Admin/js/jQuery-1.8.2.min.js"></script>
    <!--<script type="text/javascript" src="__PUBLIC__/Admin/js/jquery.js"></script>-->
    <script type="text/javascript" src="__PUBLIC__/Admin/js/jquery.idTabs.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Admin/js/select-ui.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Admin/js/layer/layer.js"></script>
    <style type="text/css">
        div.pagin{background-color: red;}
        div.pagin div{float: right}
        div.pagin span{text-align:center;line-height: 30px; display: inline-block;width: 30px;height: 30px; background-color:orange;}
        div.pagin a{text-align:center;line-height: 30px;display: inline-block;width: 30px;height: 30px; background-color:gray;}
    </style>


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


            <ul class="seachform">
                <form action="{:U('Vote/showlist')}" method="get" id="form1">
                <li><label>综合查询</label><input value="{$keywords?$keywords:''}" name="keywords" type="text" class="scinput" /></li>
                <li><label>&nbsp;</label><input name="" type="submit" class="scbtn" value="查询"/></li>
                </form>
                <li style="border-radius:5px;float: right;height:34px;width:80px;background-color:#3994C7;text-align:center;line-height:34px;">
                    <a style="color:white;font-size:16px;" href="{:U('Vote/voteRecord')}">投票记录</a>
                </li>

            </ul>
            <table class="tablelist">
                <thead>
                <tr>
                    <th><input name="" type="checkbox" value="" checked="checked"/></th>
                    <th>编号<i class="sort"><img src="__PUBLIC__/Admin/images/px.gif" /></i></th>
                    <th>图片</th>
                    <th>商品名</th>
                    <th>开始时间</th>
                    <th>结束时间</th>
                    <th>总票数</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <volist name="voteList" id="vote" key="k">
                <tr>
                    <td><input name="" type="checkbox" value="" /></td>
                    <td>{$k+$firstRow}</td>
                    <td><img width="50" height="50" src="__PUBLIC__/Admin/Uploads/goods/{$vote['pic']}"></td>
                    <td>{$vote['goodsname']}</td>
                    <td>{:date("Y-m-d H:i:s",$vote['starttime'])}</td>
                    <td>{:date("Y-m-d H:i:s",$vote['endtime'])}</td>
                    <td>{$vote['votecount']}</td>
                    <td>
                        <a href="javascript:add({$vote['id']})" class="tablelink"> 增加票数</a>
                        <a href="javascript:jianshao({$vote['id']})" class="tablelink"> 减少票数</a>
                    </td>
                </tr>
                </volist>
                </tbody>
            </table>

            <div class="pagin">
                <div>{$page}</div>
            </div>


        </div>

    </div>

    <script type="text/javascript">
        $("#usual1 ul").idTabs();
    </script>

    <script type="text/javascript">
        $('.tablelist tbody tr:odd').addClass('odd');
    </script>
    <script type="text/javascript">
        //增加票数
        function add(id){
            $.get("{:U('Vote/addVote')}",{id:id},function(res){
                if(res.status=="ok"){
                    layer.msg("增加10票成功",{icon:1,time:2000},function(){
                        window.location.reload();
                    })
                }else{
                    layer.msg("增加票数成功",{icon:1,time:2000})
                }
            })
        }
        //增加票数
        function jianshao(id){
            $.get("{:U('Vote/jianshao')}",{id:id},function(res){
                if(res.status=="ok"){
                    layer.msg("减10票成功",{icon:1,time:2000},function(){
                        window.location.reload();
                    })
                }else{
                    layer.msg("减10票失败",{icon:2,time:2000})
                }
            })
        }
    </script>
</div>
</body>
</html>
