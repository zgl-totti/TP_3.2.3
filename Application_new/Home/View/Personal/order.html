<layout name="Public/layout"/>

<style>
    #orderPage{width: 400px;height: 50px; float: right}
    #orderPage span{display:inline-block;width:30px;height:30px;margin-left:10px;background-color:#D86C01;text-align:center;margin-top:10px;line-height:30px;font-size:10px;}
    #orderPage a{display:inline-block;width:30px;height:30px;margin-left:10px;background-color:#D86C01;text-align:center;margin-top:10px;line-height:30px;}
</style>

<div class="i_bg bg_color">
    <!--Begin 用户中心 Begin -->
    <div class="m_content">
        <include file="Public/user_left"/>

        <div class="right_style">
            <div class="info_content">
                <div class="title_Section"><span>订单管理</span></div>
                <div class="Order_Sort">
                    <ul>
                        <li><a href="{:U('Home/Personal/order',array('order_status'=>1))}"><img src="__PUBLIC__/Home/images/icon-dingdan1.png"><br>未付款</a></li>
                        <li><a href="{:U('Home/Personal/order',array('order_status'=>2))}"><img src="__PUBLIC__/Home/images/delivery.ico"><br>未发货</a></li><a href="#"></a>
                        <li class="noborder" style="width: 220px"><a href="{:U('Home/Personal/order',array('order_status'=>3))}"><img src="__PUBLIC__/Home/images/icon-weibiaoti101.png"><br>未签收</a></li>
                        <li><a href="{:U('Home/Personal/order',array('order_status'=>5))}"><img src="__PUBLIC__/Home/images/icon-dingdan.png"><br>已完成</a></li>
                    </ul>
                </div>
                <div class="Order_form_list">
                    <table>
                        <thead>
                        <tr><td class="list_name_title0">商品</td>
                            <td class="list_name_title1">商品单价(元)</td>
                            <td class="list_name_title2">购买数量</td>
                            <td class="list_name_title4">订单总价(元)</td>
                            <td class="list_name_title5">订单状态</td>
                            <td class="list_name_title6">操作</td>
                        </tr></thead>

                        <tbody>
                        <volist name="list" id="orderVal">
                        <tr class="Order_info">
                            <td colspan="6" class="Order_form_time">下单时间：{:date("Y-m-d H:i:s",$orderVal['addtime'])} | 订单号：{$orderVal['order_syn']}<em></em></td>
                        </tr>
                        <tr class="Order_Details">
                            <td colspan="3">
                                <table class="Order_product_style">
                                    <tbody>
                                    <volist name="orderVal['goods']" id="goodsVal">
                                    <tr>
                                        <td>
                                            <div class="product_name clearfix">
                                                <a href="#" class="product_img"><img src="__PUBLIC__/Admin/Uploads/goods/{$goodsVal['pic']}" width="80px" height="80px"></a>
                                                <a href="3">{$goodsVal['goodsname']|mb_substr=0,15,'utf-8' }</a>
                                                <p class="specification">{$goodsVal['introduction']}</p>
                                            </div>
                                        </td>
                                        <td>{$goodsVal['price']}</td>
                                        <td>{$goodsVal['buynum']}</td>
                                    </tr>
                                    </volist>
                                    </tbody></table>
                            </td>
                            <td class="split_line">{$goodsVal['order_price']}</td>
                            <td class="split_line">{$goodsVal['status_name']}</td>
                            <td class="operating">
                                <if condition="$orderVal['order_status'] eq 1">
                                <a href="{:U('Home/Order/showlist',array('oid'=>$orderVal['id']))}">{$orderVal['status']['member_opt']}</a>
                                    <elseif condition="$orderVal['order_status'] eq 2"/>
                                    <span>{$orderVal['status']['member_opt']}</span>
                                    <elseif condition="$orderVal['order_status'] eq 3"/>
                                    <a href="javascript:qianshou({$orderVal['id']})">{$orderVal['status']['member_opt']}</a>
                                    <elseif condition="$orderVal['order_status'] eq 4"/>
                                    <a href="{:U('Personal/comment',array('oid'=>$orderVal['id']))}">{$orderVal['status']['member_opt']}</a>
                                    <elseif condition="$orderVal['order_status'] eq 5"/>
                                    <span>{$orderVal['status']['member_opt']}</span>
                                </if>
                                <a href="javascript:delOrder({$orderVal['id']})">删除</a>
                            </td>
                        </tr>
                        </volist>
                        </tbody>
                        <!--<tbody>
                        <tr class="Order_info"><td colspan="6" class="Order_form_time">下单时间：2015-12-3 | 订单号：445454654654654<em></em></td></tr>
                        <tr class="Order_Details">
                            <td colspan="3">
                                <table class="Order_product_style">
                                    <tbody><tr>
                                        <td>
                                            <div class="product_name clearfix">
                                                <a href="#" class="product_img"><img src="images/products/p_12.jpg" width="80px" height="80px"></a>
                                                <a href="3">天然绿色多汁香甜无污染水蜜桃</a>
                                                <p class="specification">礼盒装20个/盒</p>
                                            </div>
                                        </td>
                                        <td>5</td>
                                        <td>2</td>
                                    </tr>
                                    </tbody></table>
                            </td>
                            <td class="split_line">100</td>
                            <td class="split_line">已发货，待收货</td>
                            <td class="operating">
                                <a href="#">查看详细</a>
                                <a href="#">删除</a>
                            </td>
                        </tr>
                        </tbody>
                        <tbody>
                        <tr class="Order_info"><td colspan="6" class="Order_form_time">下单时间：2015-12-3 | 订单号：445454654654654<em></em></td></tr>
                        <tr class="Order_Details">
                            <td colspan="3">
                                <table class="Order_product_style">
                                    <tbody><tr>
                                        <td>
                                            <div class="product_name clearfix">
                                                <a href="#" class="product_img"><img src="images/products/p_12.jpg" width="80px" height="80px"></a>
                                                <a href="3">天然绿色多汁香甜无污染水蜜桃</a>
                                                <p class="specification">礼盒装20个/盒</p>
                                            </div>
                                        </td>
                                        <td>5</td>
                                        <td>2</td>
                                    </tr>
                                    </tbody></table>
                            </td>
                            <td class="split_line">100</td>
                            <td class="split_line">已发货，待收货</td>
                            <td class="operating">
                                <a href="#">查看详细</a>
                                <a href="#">删除</a>
                            </td>
                        </tr>
                        </tbody>-->
                    </table>
                    <div id="orderPage">{$page}</div>
                </div>
                <script>jQuery(".Order_form_list").slide({titCell:".Order_info", targetCell:".Order_Details",defaultIndex:1,delayTime:300,trigger:"click",defaultPlay:false,returnDefault:false});</script>
            </div>
        </div>
        </div>
    </div>

<script type="text/javascript">
    //订单删除
    function delOrder(oid){
        layer.confirm("你确定要删除我吗?",{icon:3,btn:['确定','取消']},function(){
            $.get("{:U('Order/delOrder')}",{id:oid},function(res){
                if(res.status=="ok"){
                    layer.msg(res.msg,{icon:1,time:1000},function(){
                        window.location.reload();
                    })
                }else{
                    layer.msg(res.msg,{icon:2,time:1000})
                }
            })
        })
    }
    //订单签收
    function qianshou(oid){
        $.get("{:U('Order/qianshou')}",{id:oid},function(res){
            if(res.status=="ok"){
                layer.msg(res.msg,{icon:1,time:1000},function(){
                    window.location.reload();
                })
            }else{
                layer.msg(res.msg,{icon:2,time:1000})
            }
        })
    }
</script>