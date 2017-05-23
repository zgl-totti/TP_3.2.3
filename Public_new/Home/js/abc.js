function getTag(){
    return document.getElementById('hzy_fast_login');
}
function addEvent(elm, evType, fn, useCapture) {
    if (elm.addEventListener) {
        elm.addEventListener(evType, fn, useCapture);//DOM2.0
        return true;
    }
    else if (elm.attachEvent) {
        var r = elm.attachEvent('on' + evType, fn);//IE5+
        return r;
    }
    else {
        elm['on' + evType] = fn;//DOM 0
    }
}
function doLogin(){
    var appid = '15822ddde3e3a1';
    var token = 'eff9148c2c3d8c5378748bd5f7255b54';
    var target = getTag();

    var span = document.createElement('a');
    var toHost = 'http://open.51094.com';
    var fromHost = 'http://open.51094.com';
    span.href=toHost+'/user/hezuo/1.html?appid=15822ddde3e3a1&token=eff9148c2c3d8c5378748bd5f7255b54';
    span.style['margin']='9px 3px';
    span.className='open_login_1';
    var img = document.createElement('img');
    img.src=fromHost+'/Public/img/hezuo/hz_1_24.jpg';
    img.style['max-width']='40px';
    img.style['max-height']='40px';
    img.style['border-radius']='8px';
    span.appendChild(img);
    target.appendChild(span);

    var loginNodes = target.childNodes;
    for( var i=0;i<loginNodes.length;i++){
        addEvent(loginNodes[i],'click',function(e){
            //this.href+='?appid='+appid+'&token='+token;
        })
    }
    document.cookie='hzy_login_back='+document.referrer+';path=/';
}
addEvent(window,'load',doLogin);