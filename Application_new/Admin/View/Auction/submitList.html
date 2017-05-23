<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>列表页</title>
    <style type="text/css">
        div.pagin{background-color: red;}
        div.pagin div{float: right}
        div.pagin span{text-align:center;line-height: 30px; display: inline-block;width: 30px;height: 30px; background-color:orange;}
        div.pagin a{text-align:center;line-height: 30px;display: inline-block;width: 30px;height: 30px; background-color:gray;}
    </style>
    <link href="__PUBLIC__/Admin/css/style.css" rel="stylesheet" type="text/css" />
    <link href="__PUBLIC__/Admin/css/select.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="__PUBLIC__/Admin/js/jQuery-1.8.2.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Admin/js/jquery.idTabs.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Admin/js/select-ui.min.js"></script>
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
        <div id="tab2" class="tabson">
            <form action="{:U('Auction/submitList')}" method="get">
            <ul class="seachform">
                <li><label>综合查询</label><input value="{$keywords?$keywords:''}" name="keywords" type="text" class="scinput" /></li>
                <li><label>&nbsp;</label><input name="" type="submit" class="scbtn" value="查询"/></li>
            </ul>
            </form>
            <table class="tablelist">
                <thead>
                <tr>
                    <th><input name="" type="checkbox" value="" checked="checked"/></th>
                    <th>编号<i class="sort"><img src="__PUBLIC__/Admin/images/px.gif" /></i></th>
                    <th>图片</th>
                    <th>商品名称</th>
                    <th>买家</th>
                    <th>成交价</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <volist name="successInfo" id="val1" key="k" empty="暂时还没有记录">
                <tr>
                    <td><input name="" type="checkbox" value="" /></td>
                    <td>{$k+$firstRow}</td>
                    <td><img width="50" height="50" src="__PUBLIC__/Admin/Uploads/goods/{$val1['pic']}" alt=""/></td>
                    <td>{$val1["goodsname"]}</td>
                    <td>{$val1["username"]}</td>
                    <td>{$val1["price"]}</td>
                    <td>
                        <!--<a href="{:U('Auction/settime',array('ag_id'=>$val1['ag_id']))}" class="tablelink">查看</a>-->
                        <a href="javascript:checkDetail({$val1['id']})" class="tablelink">查看详情</a> |
                        <a href="javascript:del({$val1['id']})" class="tablelink">删除</a>
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
        //查看详情
        function checkDetail(id){
            layer.open({
                type:2,
                title:"",
                skin:'demo-class',
                area:['740px','500px'],
                shadeClose: true,
                shade: 0.8,
                content:"{:U('Auction/checkDetail')}?id="+id
            })
        }
        //删除
        function del(id){
            layer.confirm("你确定要删除我吗",{btn:['确定','取消']},function(){
                $.get("{:U('Auction/subDel')}",{id:id},function(res){
                    if(res.status=="ok"){
                        layer.msg(res.msg,{icon:1,time:2000},function(){
                            window.location.href="{:U('Auction/submitList')}";
                        })
                    }else{
                        layer.alert(res.msg,{icon:2});
                    }
                })
            })
        }
    </script>
</div>
</body>
</html>
