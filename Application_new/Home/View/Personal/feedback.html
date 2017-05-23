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
    .button input{width: 70px;height: 30px;margin-right: 30px;background: #ff2832;border: 0;font-weight: bolder;  cursor: pointer;border-radius: 3px;}
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
                        <li><a href="#" tab="tab1">进行反馈</a></li>
                        <li><a href="#" tab="tab2">您对商城的反馈</a></li>
                        <li><a href="#" tab="tab3">商城对您的回馈</a></li>

                    </ul>
                    <ul class="tab_conbox">


                        <li id="tab1" class="tab_con">
                            <form action="{:U('Personal/feedback')}" method="post" id="form1">
                                <div style="width: 400px;height:350px;margin-left: 70px;">
                                    <span style="font-size: 20px;">欢迎您对我们提出您的宝贵意见:</span>
                                    <div class="vocation">
                                        <textarea name="content" id="content"  cols="100" rows="12" style="border:1px solid #ffd522;" class="textarea" onkeyup="checkLength(this);"></textarea>
                                        <!--<div class="wordage"><span>剩余字数：</span><span id="sy" style="color:Red;">500</span>&nbsp;字</div>-->
                                    </div>
                                    <div style="margin: 20px 0 0 50px;" class="button">
                                        <input type="submit" value="返回" onclick="clickback()">
                                        <input type="button" value="提交" id="btn">
                                    </div>
                                </div>
                            </form>
                        </li>


                        <li id="tab2" class="tab_con">
                            <table class="tablelist">
                                <thead>
                                <tr>
                                    <th>编号<i class="sort"></i></th>
                                    <th>反馈内容</th>
                                    <th>反馈时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <volist name="feedback" id="val" key="k" empty="$empty">
                                        <tr>
                                            <td>{$k}</td>
                                            <td>{$val.content|mb_substr=0,25,'utf-8'}</td>
                                            <td>{:date('Y-m-d H:i:s',$val['addtime'])}</td>
                                            <td><a href="javascript:feeddel({$val['feedback_id']})">删除</a></td>
                                        </tr>
                                    </volist>
                                </tbody>
                            </table>
                        </li>

                        <li id="tab3" class="tab_con">
                            <table class="tablelist">
                                <thead>
                                <tr>
                                    <th>编号<i class="sort"></i></th>
                                    <th>反馈时间</th>
                                    <th>回馈内容</th>
                                    <th>管理员</th>
                                </tr>
                                </thead>
                                <tbody>
                                <volist name="feedback" id="val" key="k" empty="$empty1">
                                    <tr>
                                        <td>{$k}</td>
                                        <td>{:date('Y-m-d H:i:s',$val['addtime'])}</td>
                                        <td>{$val.reply|mb_substr=0,40,'utf-8'}</td>
                                        <td>{$val.feedback_admin}</td>
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
    /*删除操作*/
    function feeddel(fid){
        layer.confirm('是否删除',{icon:3,title:'删除'},function(){
            $.get("{:U('Personal/feeddel')}","fid="+fid,function(res){
                if(res.status=="ok"){
                    layer.msg(res.msg,{icon:1,time:1000},function(){
                        window.location.href="{:U('Personal/feedback')}";
                    })
                }else{layer.msg(res.msg,{icon:2,time:1000});}
            },'json')
        })
    }
    /*返回操作*/
    function clickback(){location.href = "{:U('Personal/feedback')}";}
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".left_add").height($(window).height()-60);
        //当文档窗口发生改变时 触发
        $(window).resize(function(){
            $(".left_add").height($(window).height()-60);
        });
    })
    function checkLength(which) {
        var maxChars = 500;
        if(which.value.length > maxChars){
            layer.open({
                icon:2,
                title:'提示框',
                content:'您出入的字数超多限制!'
            });
            // 超过限制的字数了就将 文本框中的内容按规定的字数 截取
            which.value = which.value.substring(0,maxChars);
            return false;
        }else{
            var curr = maxChars - which.value.length; // 减去 当前输入的
            document.getElementById("sy").innerHTML = curr.toString();
            return true;
        }
    }
    $(function(){
        $("#btn").click(function(){
            $('#form1').ajaxSubmit(function(res){
                if(res.status=="ok")
                {layer.msg(res.msg, {icon:1,time:1000},function(){
                    location.href="{:U('Personal/feedback')}";
                });}
                else{layer.msg(res.msg, {icon:2,time:1000});}
            })
            return false;
        })
    })
</script>
