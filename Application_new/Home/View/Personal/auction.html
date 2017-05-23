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
    body .demo-class {width: 500px;height: 300px;}
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
                        <li><a href="#" tab="tab1">竞拍记录</a></li>
                        <li><a href="#" tab="tab2">已拍商品</a></li>
                    </ul>
                    <ul class="tab_conbox">
                        <li id="tab1" class="tab_con">
                            <table class="tablelist">
                                <thead>
                                <tr>
                                    <th>编号<i class="sort"></i></th>
                                    <th>商品图片</th>
                                    <th>商品名称</th>
                                    <th>竞拍价</th>
                                    <th>购买数量</th>
                                    <th>竞拍时间</th>
                                   <!-- <th>总价</th>
                                    <th>评价</th>-->
                                </tr>
                                </thead>
                                <tbody>
                                <volist name="myAuctionRecord" id="val1" key="k1">
                                    <tr>
                                        <td>{$k1}</td>
                                        <td>
                                            <img src="__PUBLIC__/Admin/Uploads/goods/{$val1['pic']}" style="width: 50px;height: 50px;">
                                        </td>
                                        <td>{$val1['goodsname']|mb_substr=0,15,"utf-8"}</td>
                                        <td>{$val1['auctionprice']}</td>
                                        <td>{$val1['buynum']}</td>
                                        <td>{:date('Y-m-d H:i:s',$val1['addtime'])}</td>
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
                                    <th>竞拍价</th>
                                    <th>购买数量</th>
                                    <th>交易保证金</th>
                                    <th>需支付</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <volist name="getAuctionGoods" id="val2" key="k2">
                                    <form action="" method="post" id="auctionForm{$k2}">
                                        <input name="total_price" value="{$val2['pay']}" type="hidden">
                                        <input name="checkitems[]" value="{$val2['gid']}" type="hidden">
                                        <input name="ag_id" value="{$val2['ag_id']}" type="hidden">
                                        <input name="pay" value="{$val2['pay']}" type="hidden">
                                        <input name="buynum{$val2['gid']}" value="{$val2['buynum']}" type="hidden">
                                    <tr>
                                        <td>{$k2}</td>
                                        <td><img src="__PUBLIC__/Admin/Uploads/goods/{$val2['pic']}" style="width: 50px;height: 50px;"></td>
                                        <td>{$val2['goodsname']}</td>
                                        <td>{$val2['price']}</td>
                                        <td>{$val2['buynum']}</td>
                                        <td>{$val2['deposit']}</td>
                                        <td>{$val2['pay']}</td>
                                        <td>
                                            <if condition="$val2['isshow'] eq 1">
                                                <a href="javascript:pay({$k2})">去付款</a>
                                                <else/>
                                                <a href="{:U('Personal/order')}">查看订单</a>
                                            </if>
                                        </td>
                                    </tr>
                                    </form>
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
    //去付款
    function pay(k){
        $.post("{:U('Order/createOrder')}",$("#auctionForm"+k).serialize(),function(res){
            if(res.status=="ok"){
                layer.msg(res.msg,{icon:1,time:2000},function(){
                    window.location.href="{:U('Order/showlist')}?oid="+res.oid+"&pay="+res.pay;
                })
            }else{
                layer.msg("付款失败，请稍后尝试",{icon:2,time:1000})
            }
        });
    }
</script>