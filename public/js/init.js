var siteurl = '';
var js = /themes\/(.*)application\.js(\?.*)?$/;
$('head script[src]').each(function(i, s) {
    if (s.src.match(js)) {
        var path = s.src.replace(js, '');
        siteurl = path;
    }
});
