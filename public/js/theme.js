var Theme = App.Theme = {}

Theme.themes = {};
Theme.setTheme = function(theme,k){
	$.cookie(k,'',{expires:0});
	$.cookie(k,theme,{expires:30});
	Portal.unhandRedirect('index.html');
};
Theme.init = function (k){
	var data={};
	data.k=k;
	$.ajax({
		url: '/getThemes.php',
        method: 'post',
		data:data,
		async:false,
		dataType: 'json',
		success: function(ret) {
			if(ret.code) {
				
			} else {
				Theme.themes=ret.data.themes;
			}
		}
	});
};

$(function(){
	var show = $('.show');
	if(show.length == 0){
		$('.skins_link').attr('style','display:none;');
	}else{
		var mains = $('.theme.mains');
		var admin = $('.theme.admin');
		var dev = $('.theme.dev');
		var portal = $('.theme.portal');
		var k ='';
		if(mains.length != 0) {
			k = 'theme.main';
		}else if(admin.length != 0) {
			k = 'theme.admin';
		}else if(dev.length != 0) {
			k = 'theme.dev';
		}else if(portal.length != 0) {
			k = 'theme.portal';
		}
		Theme.init(k);
		//$.cookie('k','', {expires:0});
		//$.cookie('test','test',{expires:30});
		//$.cookie('k','', {expires:0});
		//console.log($.cookie('test'));
		var isShow = false;
		$('.theme').click(function(){
			if(isShow==false){
				var html='';
				for(var i=0;i<Theme.themes.length;i++){
					if($.cookie(k)!=null&&$.cookie(k)==Theme.themes[i]){
					
					}else{
						html = html+'<div class="skin_link_contain"><a href="javascript:;" class="skin_link level" onclick="Theme.setTheme(\''+Theme.themes[i]+'\',\''+k+'\')"><span>'+Theme.themes[i]+'</span></a></div>';
					}
					// style="width:75px;height:30px;background:#e6e6e6;border:2px solid white;vertical-align:50%;line-height:30px;"
				}
				$("#themes").html(html);
				isShow=true;
			}else{
				$("#themes").html('');
				isShow=false;
			}
		});
	}
});
