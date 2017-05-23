
<layout name="Public/layout"/>

<!--购物车样式-->
<div class="Inside_pages clearfix">
    <include file="Public/user_left"/>
    <div class="shop_carts" style="float:left;width:900px;margin-top:-15px;">
        <div class="Shopping_list">
            <style>
                #tab{width:900px;}
                #tab #nam{width:300px;}
            </style>
            <div class="shopping">
                <form  method="post" action="{:U('Order/createOrder')}" id="cartForm">
                    <input type="hidden" name="total_price" id="totalprices" value="">
                    <table class="table_list" id="tab">
                        <tr class="tr">
                            <th class="checkbox"></th>
                            <th id="nam" style="width:300px;" class="name">商品名称</th>
                            <th class="scj sp">市场价</th>
                            <th class="bgj sp">本店价</th>
                            <th class="sl">购买数量</th>
                            <th class="xj">小计</th>
                            <th class="cz">操作</th>
                        </tr>
                        <volist name="cartList" id="val1" key="k">
                            <tr class="tr">
                                <td class="checkbox"><input onclick="gettotalprice()" name="checkitems[]" type="checkbox" value="{$val1['gid']}" /></td>
                                <td class="name">
                                    <div class="img"><a href="#"><img src="__PUBLIC__/Admin/Uploads/goods/{$val1['pic']}" /></a></div>
                                    <div class="p_name"><a href="#">{$val1['goodsname']|mb_substr=0,20,'utf-8'}</a></div>
                                </td>
                                <td class="scj sp"><span id="Original_Price_1">￥{$val1['marketprice']}</span></td>
                                <td class="bgj sp" ><span class="price_item_1" id="price_item_1">{$val1['price']}</span></td>
                                <td class="sl">
                                    <div class="Numbers">
                                        <a onClick="setAmount.reduce('#qty_item_1')" href="javascript:jian({$k})" class="jian">-</a>
                                        <input onkeyup="chgnum(this)" type="text" id="buy-num{$k}" name="buynum{$val1['gid']}" value="{$val1['buynum']}" id="qty_item_1" onkeyup="setAmount.modify('#qty_item_1')" class="number_text">
                                        <a onclick="setAmount.add('#qty_item_1')" href="javascript:jia({$k})" class="jia">+</a>
                                    </div>
                                </td>
                                <td class="xj" >
                                    <span class="total_item_1" ></span>
                                </td>
                                <td class="cz">
                                    <p><a href="javascript:del({$val1['gid']})">删除</a><P>
                                    <p><a id="collect" href="javascript:collect({$val1['gid']})">收藏该商品</a></p>
                                    <!--<p><a style="display:none;" id="collect2" href="">已收藏</a></p>-->
                                </td>
                            </tr>
                        </volist>
                    </table>
                    <div class="sp_Operation clearfix">
                        <div class="select-all">
                            <div class="cart-checkbox">
                                <input type="checkbox"  onclick="gettotalprice();" id="chkAll" />全选
                                <!--<input type="checkbox"   id="CheckedAll" name="toggle-checkboxes" class="jdcheckbox" clstag="clickcart">全选-->
                            </div>
                            <div class="operation">
                                <a href="javascript:void(0);" id="send">删除选中的商品</a>
                            </div>
                        </div>
                        <!--结算-->
                        <div class="toolbar_right">
                            <ul class="Price_Info">
                                <li class="p_Total">
                                    <label class="text">商品总价：</label>
                                    <span class="price sumPrice" id="total"></span>
                                </li>
                                <li class="integral">本次购物可获得<b id="total_points"></b>积分</li>
                            </ul>
                            <div class="btn">
                                <!--<a class="cartsubmit" href="javascript:sub()"></a>-->
                                <a class="cartsubmit"></a>
                                <a class="continueFind" href="{:U('Home/ProductList/showlist')}"></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--推荐产品样式-->
        <div class="recommend_shop">
            <div class="title_name">推荐购买</div>
            <div class="recommend_list">
                <div class="hd">
                    <a class="prev" href="javascript:void(0)">&gt;</a>
                    <a class="next" href="javascript:void(0)">&lt;</a>
                </div>
                <div class="bd">
                    <ul>
                        <volist name="recommendInfo" id="recommendList">
                            <li class="recommend_info">
                                <a href="{:U('Detail/detail',array('gid'=>$recommendList['id']))}" class="buy_btn">查看详情</a>
                                <a href="{:U('Detail/detail',array('gid'=>$recommendList['id']))}" class="img"><img src="__PUBLIC__/Admin/Uploads/goods/{$recommendList['pic']}" width="160px" height="160px"/></a>
                                <a href="{:U('Detail/detail',array('gid'=>$recommendList['id']))}" class="name">{$recommendList['goodsname']}</a>
                                <h4><span class="Price"><i>RNB</i>{$recommendList['price']}</span></h4>
                            </li>
                        </volist>
                    </ul>
                </div>
            </div>
            <script>jQuery(".recommend_list").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",autoPlay:true,vis:6});</script>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $(".cartsubmit").click(function(){
            $.post("{:U('Order/createOrder')}",$("#cartForm").serialize(),function(res){
                if(res.status=="ok"){
                    layer.msg(res.msg,{icon:1,time:1000},function(){
                        window.location.href="{:U('Order/showlist')}?oid="+res.oid;
                    })
                }else if(res.status=="login"){
                    layer.msg(res.msg,{icon:1,time:1000},function(){
                        layer.open({
                            type:2,
                            title:"",
                            skin:'demo-class',
                            area:["480px","56%"],
                            shadeClose: true,
                            shade: 0.8,
                            content:"{:U('Cart/tologin')}"
                        })
                    })
                }else{
                    layer.msg(res.msg,{icon:2,time:1000})
                }
            })
        })
    })
</script>
<script type="text/javascript">
    //全选
    function chk(){
        var chkAll=document.getElementById('chkAll');
        var chks=document.getElementsByName('checkitems[]');
        for(var i=0;i<chks.length;i++){
            chks[i].checked=chkAll.checked;
            if(chks[i].checked){
                gettotalprice();
            }else {
                document.getElementById('total').innerHTML="￥"+0;
            }
        }
    }
    document.getElementById('chkAll').onclick=chk;
    //        $('.cateList').hide();
    //加
    function jia(n){
        var num=document.getElementById("buy-num"+n).value;
        num++;
        document.getElementById("buy-num"+n).value=num;
        getprices();
        gettotalprice();
    }
    //减
    function jian(n){
        var num=document.getElementById("buy-num"+n).value;
        num--;
        if(num  <= 1){
            num=1;
        }
        document.getElementById("buy-num"+n).value=num;
        getprices();
        gettotalprice();
    }

    //提交表单
    //    function sub(){
    //        document.getElementById("cartForm").submit();
    //    }

    function chgnum(v){
        if(v.value<1){
            v.value=1;
        }
        if(v.value>199){
            v.value=199;
        }
        if(isNaN(v.value)){
            v.value=1;
        }

        gettotalprice();
    }
    //小计
    function getprices(){
        var nums=document.getElementsByClassName('number_text');
        var price=document.getElementsByClassName('price_item_1');
        var prices=document.getElementsByClassName('total_item_1');
        for(var i=0;i<price.length;i++){
            prices[i].innerHTML=(parseFloat(price[i].innerHTML)*parseFloat(nums[i].value)).toFixed(2);
        };
    }
    //总计
    function gettotalprice(){
        getprices();
        var inputs=document.getElementsByName('checkitems[]');
        var prices=document.getElementsByClassName('total_item_1');
        var totalprice=0;
        for(var i=0;i<inputs.length;i++){
            if(inputs[i].checked){
                totalprice+=parseFloat(prices[i].innerHTML);
            };
        };
        document.getElementById('total').innerHTML='￥'+totalprice;
        document.getElementById('totalprices').value=totalprice;
    }
    gettotalprice();
    //删除
    function del(id){
        layer.confirm("确定要删除我吗?",{icon:3,btn:['确定','取消']},function(){
            $.get("{:U('Home/Cart/del')}",{gid:id},function(res){
                if(res.status=="ok"){
                    layer.msg(res.msg,{icon:1,time:1000},function(){
                        window.location.href="{:U('Home/Personal/myCart')}";
                    })
                }else{
                    layer.msg(res.msg,{icon:2,time:1000})
                }
            })
        })
    }
    //收藏
    function collect(id){
        $.get("{:U('Collect/addCollect')}",{gid:id},function(res){
            if(res.status=="ok"){
                layer.msg(res.msg,{icon:1,time:1000})
            }else if(res.status=="error"){
                layer.msg(res.msg,{icon:2,time:1000})
            }else{
                layer.alert(res.msg,{icon:3},function(){
                    layer.open({
                        type:2,
                        title:"",
                        skin:'demo-class',
                        area:["480px","56%"],
                        shadeClose: true,
                        shade: 0.8,
                        content:"{:U('Collect/tologin')}"
                    })
                });
            }
        })
    }
</script>
