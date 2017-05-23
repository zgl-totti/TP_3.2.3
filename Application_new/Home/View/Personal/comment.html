<layout name="Public/layout"/>
<style type="text/css">
    body,ul,li{margin: 0;padding: 0;list-style: none;}
    a{text-decoration: none;color: #000;font-size: 14px;}
    #tabbox{ width:900px; overflow:hidden; margin:0 auto;}
    .tab_conbox{border: 1px solid #999;border-top: none;}
    .tab_con{ display:none;}
    .tabs{height: 32px;border-bottom:1px solid #999;border-left: 1px solid #999;width: 100%;}
    .tabs li{height:31px;line-height:31px;float:left;border:1px solid #999;border-left:none;margin-bottom: -1px;background: #e0e0e0;overflow: hidden;position: relative;}
    .tabs li a {display: block;padding: 0 20px;border: 1px solid #fff;outline: none;}
    .tabs li a:hover {background: #ccc;}
    .tabs .thistab,.tabs .thistab a:hover{background: #fff;border-bottom: 1px solid #fff;}
    .tab_con {padding:12px;font-size: 14px; line-height:175%;}
    .tablelist{border:solid 1px #cbcbcb; width:100%; clear:both;border: 0;padding: 0;margin: 0;}
    .tablelist td{line-height:35px; text-indent:11px; border-right: dotted 1px #c7c7c7;}
    .tablelist tbody tr.odd{background:#f5f8fa;}
    .tablelist tbody tr:hover{background:#e5ebee;}
    body .demo-class .layui-layer-title{background: #e15e6b; color: #333 border: none;font-size: 20px;}
    body .demo-class .layui-layer-btn{border-top:1px solid #E9E7E7}
    body .demo-class .layui-layer-btn a{background:#333;}
    body .demo-class .layui-layer-btn .layui-layer-btn1{background:#999;}
    body .demo-class {width: 400px;height: 400px;}
</style>
<script type="text/javascript">
    $(document).ready(function() {
        jQuery.jqtab = function(tabtit,tabcon) {
            $(tabcon).hide();
            $(tabtit+" li:first").addClass("thistab").show();
            $(tabcon+":first").show();
            $(tabtit+" li").click(function() {
                $(tabtit+" li").removeClass("thistab");
                $(this).addClass("thistab");
                $(tabcon).hide();
                var activeTab = $(this).find("a").attr("tab");
                $("#"+activeTab).fadeIn();
                return false;
            });
        };
        /*调用方法如下：*/
        $.jqtab("#tabs",".tab_con");
    });
</script>

<div class="i_bg bg_color">
    <!--Begin 用户中心 Begin -->
    <div class="m_content">
        <include file="Public/user_left"/>
        <div class="right_style" style="margin-top: 10px;">
            <div class="info_content" style="float: left;width:1000px;">
                <!--评论-->
                <div id="tabbox">
                    <ul class="tabs" id="tabs">
                        <li><a href="#" tab="tab1">未评价</a></li>
                        <li><a href="#" tab="tab2">已评价</a></li>
                    </ul>
                    <ul class="tab_conbox">
                        <li id="tab1" class="tab_con">
                            <table class="tablelist">
                                <thead>
                                <tr>
                                    <th>编号<i class="sort"></i></th>
                                    <th>商品图片</th>
                                    <th>商品名称</th>
                                    <th>单价</th>
                                    <th>购买数量</th>
                                    <th>总价</th>
                                    <th>评价</th>
                                </tr>
                                </thead>
                                <tbody>
                                <volist name="list" id="val" key="k" empty="$empty">
                                    <tr>
                                        <td>{$k}</td>
                                        <td><img src="__PUBLIC__/Admin/Uploads/goods/thumb100/100_{$val['pic']}" style="width: 50px;height: 50px;"></td>
                                        <td>{$val.goodsname|mb_substr=0,20,'utf-8'}</td>
                                        <td>{$val.price}</td>
                                        <td>{$val.buynum}</td>
                                        <td>{$val['price']*$val['buynum']}</td>
                                        <td><a href="javascript:comment({$val['id']},{$val['oid']})" class="tablelink">我要评价</a></td>
                                    </tr>
                                </volist>
                                </tbody>
                            </table>
                        </li>

                        <li id="tab2" class="tab_con">
                            <table class="tablelist">
                                <thead>
                                <tr>
                                    <th>编号<i class="sort"></i></th>
                                    <th>商品图片</th>
                                    <th>商品名称</th>
                                    <th>单价</th>
                                    <th>购买数量</th>
                                    <th>总价</th>
                                    <th>评价内容</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                            <tbody>
                                <volist name="aready" id="val" key="k" empty="$empty">
                                    <tr>
                                        <td>{$k}</td>
                                        <td><img src="__PUBLIC__/Admin/Uploads/goods/thumb100/100_{$val['pic']}" style="width: 50px;height: 50px;"></td>
                                        <td>{$val.goodsname|mb_substr=0,20,'utf-8'}</td>
                                        <td>{$val.price}</td>
                                        <td>{$val.buynum}</td>
                                        <td>{$val['price']*$val['buynum']}</td>
                                        <td>{$val.commentcontent|mb_substr = 0,10,'utf-8'}</td>
                                        <td><a href="javascript:del({$val['id']})">删除</a>
                                            <a href="{:U('Detail/detail',array('gid'=>$val['gid']))}">查看详情</a></td>
                                    </tr>
                                </volist>
                           </tbody>
                            </table>
                        </li>
                    </ul>
                </div>
                </div>
            </div>
         </div>
    </div>
</div>
<script>
    function comment(gid,oid){
        layer.open({
            type:2,
            title:'评价',
            skin:'demo-class',
            area: ['600px', '620px'],
           content:"{:U('Personal/commentlist')}?gid="+gid+"&&oid="+oid
        });
    }
    function del(mid){
        layer.confirm('是否删除',{icon:3,title:'删除'},function(){
            $.get("{:U('Personal/del')}","mid="+mid,function(res){
                if(res.status=="ok"){
                    layer.msg(res.msg,{icon:1,time:1000},function(){
                        window.location.href="{:U('Personal/comment')}";
                    })
                }else{layer.msg(res.msg,{icon:2,time:1000});}
            },'json')

        })
    }
</script>