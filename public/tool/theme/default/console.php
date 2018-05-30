<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>API测试工具</title>
	<link rel="stylesheet" type="text/css" href="/lib/bootstrap/dist/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="/cache/css/tool.console.css">
</head>
<body>
	<div class="home_apitest_wrap">
		<div class="apitest_inner">
		    <div class="home_apitest_banner"><p class="logo"></p></div>

		    <div class="home_apitest_content" id="pl_api_index">
		    	<div class="inner clearfix">
		        	<div class="inner_left">
		            	<!--左侧表单-->
		                <div class="form_apitest">
		                  <dl class="s1 clearfix">
		                    <dt>
							<select select="" node-type="applist">
																	<option value="4154598188">ufsso</option>
												<option value="68206533">Lay</option>
																	</select>
							</dt>
		                    <dd><a href="javascript:;" action-type="getAccessToken" class="W_btn_b"><span>获取Acess Token</span></a></dd>

		                  </dl>
		                  <dl class="s2">
		                    <dt>Access Token:</dt>
		                    <dd><input type="text" node-type="accessToken" class="W_inputStp" value=""></dd>
		                  </dl>
		                  <p class="line"></p>
		                  <dl class="s3 clearfix">
		                    <dt>API分类：</dt>
		                    <dd><select node-type="apitype"><option value="63">微博普通读取接口</option><option value="65">微博高级读取接口</option><option value="67">微博普通写入接口</option><option value="69">微博高级写入接口</option><option value="71">评论普通读取接口</option><option value="73">评论普通写入接口</option><option value="75">用户普通读取接口</option><option value="79">关系普通读取接口</option><option value="81">复合关系普通读取接口</option><option value="97">账户普通读取接口</option><option value="101">账户普通写入接口</option><option value="105">收藏普通读取接口</option><option value="107">收藏普通写入接口</option><option value="109">话题普通读取接口</option><option value="113">标签普通读取接口</option><option value="115">标签普通写入接口</option><option value="119">搜索联想普通接口</option><option value="125">推荐普通读取接口</option><option value="127">推荐普通写入接口</option><option value="129">提醒普通读取接口</option><option value="133">短链转换普通接口</option><option value="135">短链数据普通接口</option><option value="145">公共服务读取接口</option><option value="146">公共服务写入接口</option><option value="147">地理信息读取接口</option><option value="148">地理信息写入接口</option><option value="155">搜索话题高级接口</option><option value="156">提醒高级写入接口</option><option value="158">位置服务普通读取接口</option><option value="159">位置服务普通写入接口</option><option value="160">位置服务高级读取接口</option><option value="161">位置服务高级写入接口</option><option value="179">用户邮箱高级读取接口</option><option value="202">消息服务写入接口</option><option value="205">消息服务读取接口</option></select></dd>
		                  </dl>
		                  <dl class="s3 clearfix">
		                    <dt>API名称：</dt>
		                    <dd><select node-type="apiname"><option value="https://api.weibo.com/2/statuses/public_timeline.json">statuses/public_timeline</option><option value="https://api.weibo.com/2/statuses/friends_timeline.json">statuses/friends_timeline</option><option value="https://api.weibo.com/2/statuses/home_timeline.json">statuses/home_timeline</option><option value="https://api.weibo.com/2/statuses/user_timeline.json">statuses/user_timeline</option><option value="https://api.weibo.com/2/statuses/repost_timeline.json">statuses/repost_timeline</option><option value="https://api.weibo.com/2/statuses/repost_by_me.json">statuses/repost_by_me</option><option value="https://api.weibo.com/2/statuses/mentions.json">statuses/mentions</option><option value="https://api.weibo.com/2/statuses/show.json">statuses/show</option><option value="https://api.weibo.com/2/statuses/querymid.json">statuses/querymid</option><option value="https://api.weibo.com/2/statuses/queryid.json">statuses/queryid</option><option value="https://api.weibo.com/2/statuses/hot/repost_daily.json">statuses/hot/repost_daily</option><option value="https://api.weibo.com/2/statuses/hot/repost_weekly.json">statuses/hot/repost_weekly</option><option value="https://api.weibo.com/2/statuses/hot/comments_daily.json">statuses/hot/comments_daily</option><option value="https://api.weibo.com/2/statuses/hot/comments_weekly.json">statuses/hot/comments_weekly</option><option value="https://api.weibo.com/2/statuses/friends_timeline/ids.json">statuses/friends_timeline/ids</option><option value="https://api.weibo.com/2/statuses/bilateral_timeline.json">statuses/bilateral_timeline</option><option value="https://api.weibo.com/2/statuses/user_timeline/ids.json">statuses/user_timeline/ids</option><option value="https://api.weibo.com/2/statuses/repost_timeline/ids.json">statuses/repost_timeline/ids</option><option value="https://api.weibo.com/2/statuses/mentions/ids.json">statuses/mentions/ids</option><option value="https://api.weibo.com/2/statuses/count.json">statuses/count</option><option value="https://api.weibo.com/2/statuses/go.json">statuses/go</option></select></dd>
		                  </dl>
						  <dl class="s3 clearfix"><dt>获取方式：</dt><dd>
						  <label><input type="radio" checked="checked" value="get" name="request_type">GET</label>
			              <label><input type="radio" value="post" name="request_type">POST</label></dd>
						  </dl>
		                  <dl class="s3 clearfix">
		                    <dt>API文档：</dt>
		                    <dd><a node-type="devdoc" href="http://open.weibo.com/wiki/2/statuses/public_timeline" target="_blank">点击获取文档</a></dd>
		                  </dl>
		                  <dl class="addbox" node-type="addbox">
						   <dt class="W_fb">API参数</dt>
		                                    
		                  
						  
						  
						  
						  
		                  
		                  				 
		              
		                  <dd class="inp"><input name="key" class="W_inputStp wid1" type="text" value="Key" onfocus="if(this.value=='Key'){this.value='';} this.style.color='#666';" onblur="if(this.value==''){this.value='Key'; this.style.color='#ccc';};">：<input name="val" class="W_inputStp wid2" type="text" value="Value" onfocus="if(this.value=='Value'){this.value=''} this.style.color='#666';" onblur="if(this.value==''){this.value='Value'; this.style.color='#ccc';}"><a href="javascript:;" class="op_closes" action-type="delrow"></a></dd><dd class="inp"><input name="key" class="W_inputStp wid1" type="text" value="Key" onfocus="if(this.value=='Key'){this.value='';} this.style.color='#666';" onblur="if(this.value==''){this.value='Key'; this.style.color='#ccc';};">：<input name="val" class="W_inputStp wid2" type="text" value="Value" onfocus="if(this.value=='Value'){this.value=''} this.style.color='#666';" onblur="if(this.value==''){this.value='Value'; this.style.color='#ccc';}"><a href="javascript:;" class="op_closes" action-type="delrow"></a></dd><dd class="inp"><input name="key" class="W_inputStp wid1" type="text" value="Key" onfocus="if(this.value=='Key'){this.value='';} this.style.color='#666';" onblur="if(this.value==''){this.value='Key'; this.style.color='#ccc';};">：<input name="val" class="W_inputStp wid2" type="text" value="Value" onfocus="if(this.value=='Value'){this.value=''} this.style.color='#666';" onblur="if(this.value==''){this.value='Value'; this.style.color='#ccc';}"><a href="javascript:;" class="op_closes" action-type="delrow"></a></dd><dd class="link" node-type="link"><span class="icon_op_add"></span><a href="javascript:;" action-type="addrow">添加</a></dd>
						  </dl>
		                  <dl class="s2">
		                  <dd><a href="javascript:;" class="W_btn_b" action-type="request"><span>调用接口</span></a></dd>
		                  </dl>
		                </div>
		            	<!--/左侧表单-->
						            </div>
		        	<div class="inner_right">
					            	<!--右侧代码-->
		                <div class="form_code">
		                  <dl>
		                    <dt>请求：<a href="javascript:;" action-type="toggle">折叠请求<span class="icon_op_off"></span></a></dt>
		                    <dd style="display:block;" node-type="infobox"><div class="W_inputStp heig1">

		                    
		                    </div></dd>
		                  </dl>
		<!--                
							<dl>
		                    <dt>请求：<a href="#">展开请求<span class="icon_op_on"></span></a></dt>
		                    <dd><div class="W_inputStp heig1">
		                    请求方式：GET<br/><br/>   
		                    请求参数：https://api.weibo.com/2/users/counts.json?access_token=24ec3ecf5a841f2ea9b1f82c1fcd0b1
		                    </div></dd>
							</dl>
		-->                  <dl>
		                    <dt class="clearfix"><em class="fr"><a href="/wiki/FAQ" target="_blank">FAQ</a><span class="W_vline">|</span><a href="/wiki/Help/error" target="_blank">常见错误代码及释义</a></em>返回的内容：</dt>
		                    <dd><div>
							<textarea class="W_inputStp heig2" node-type="resultbox" readonly="readonly"></textarea>
		                    </div></dd>
		                  </dl>
		                </div>
		            	<!--/右侧代码-->
					  </div>
					        </div>
		    </div>
		</div>
	</div>
	<div class="op_footer">
		<div class="inner">
		    <p class="links">
				<a href="/dev/">关于</a>
				<span class="W_vline">|</span>
				<a href="http://www.dcux.com">联系我们</a>
				<span class="W_vline">|</span>
				<a href="/dev/wiki">文档</a>
				<span class="W_vline">|</span>
				<a href="/dev/support">支持</a>
			</p>
		    <p class="copyright W_textb">
				<span>上海龙盟信息科技有限公司</span>
				<span> </span>
				<span>Copyright © 2011-2015 DCUX</span></p>
		</div>
	</div>
</body>
</html>