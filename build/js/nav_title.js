var allHeaders = []
/** 获取所有id 不为空的对象*/
allHeaders = $("*[id]")

for (var i = 0; i <allHeaders.length; i++){
    console.log(allHeaders[i]);
}

var last;

function updateSidebar () {
    var doc = document.documentElement
    var top = doc && doc.scrollTop || document.body.scrollTop
    if (!allHeaders) return
    // var last
    for (var i = allHeaders.length - 1; i > 0; i--) {
        var link = allHeaders[i]
        console.log($(last).attr("id") + "link.offsetTop " + link.offsetTop + " top " + top)
        if (link.offsetTop > top) {
        } else {
            if(last !== link) {
                updateLast(link)
                break
            }
        }
    }
}

function updateLast(value) {
    if(last !== value) {
        console.log($(last).attr("id") + " _ "  + $(value).attr("id"));
        last = value;
        android_js_bridge.navTitleId($(last).attr("id"));
    }
}

// listen for scroll event to do positioning & highlights
window.addEventListener('scroll', updateSidebar)
window.addEventListener('resize', updateSidebar)