<!DOCTYPE html>
<html>
	<head>
		{%- set url = url(), theme = session.get('identity-theme'), current_url = router.getRewriteUri() -%}
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="x-ua-compatible" content="ie=edge">

		{% if current_url == '/' %}
			<title>微信小程序之家</title>
		{% else %}
			<title>{{ get_title(false) ~ ' | ' ~ config.site.name }}</title>
		{% endif %}

		<meta content="{{ config.site.keywords }}" name="keyword">
		<meta content="{{ config.site.description }}" name="description">

		{%- if post is defined -%}
		<link rel="publisher" href="{{ config.site.url }}/">
		{%- endif -%}

		{%- if canonical is defined -%}
		<link rel="canonical" href="{{ config.site.url }}/{{ canonical }}">
		<meta property="og:url" content="{{ config.site.url }}/{{ canonical }}">
		<meta property="og:site_name" content="wxappr.com">
		{%- endif -%}

		<link rel="icon" type="image/png" sizes="30x30" href="/favicon-30x30.png">

		{{- stylesheet_link("//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css", false) -}}
		{{- stylesheet_link("//cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css", false) -}}
		{{- stylesheet_link("//cdn.bootcss.com/simplemde/1.11.2/simplemde.min.css", false) -}}
		{{- stylesheet_link("//cdn.bootcss.com/prettify/r298/prettify.min.css", false) -}}
		{{- stylesheet_link("//cdn.bootcss.com/select2/4.0.3/css/select2.min.css", false) -}}
		{{- stylesheet_link("//cdn.bootcss.com/select2-bootstrap-theme/0.1.0-beta.9/select2-bootstrap.min.css", false) -}}
		{{- stylesheet_link("//cdn.bootcss.com/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css", false) -}}
		{{- stylesheet_link("//cdn.bootcss.com/viewerjs/0.5.1/viewer.min.css", false) -}}

		{{- stylesheet_link("css/theme-white.css?v=" ~ app_version, true) -}}
		{{- stylesheet_link("css/fonts.css?v=" ~ app_version, true) -}}
		{{- stylesheet_link("css/style.css?v=" ~ app_version, true) -}}
	</head>
	<body class="with-top-navbar">

		{% if current_url == '/account/login' or current_url == '/account/signup' %}
		<div id="particles-js"></div>
		{% endif %}

		{{ content() }}

		<script type="text/javascript" src="//cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
		<script type="text/javascript" src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="//cdn.bootcss.com/prettify/r298/prettify.min.js"></script>
		<script type="text/javascript" src="//cdn.bootcss.com/simplemde/1.11.2/simplemde.min.js"></script>
		<script type="text/javascript" src="//cdn.bootcss.com/file-uploader/5.11.9/fine-uploader.min.js"></script>
		<script type="text/javascript" src="//cdn.bootcss.com/select2/4.0.3/js/select2.full.min.js"></script>
		<script type="text/javascript" src="//cdn.bootcss.com/autosize.js/3.0.19/autosize.min.js"></script>
		<script type="text/javascript" src="//cdn.bootcss.com/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
		<script type="text/javascript" src="//cdn.bootcss.com/viewerjs/0.5.1/viewer.min.js"></script>
		<script type="text/javascript" src="//cdn.bootcss.com/clipboard.js/1.5.16/clipboard.min.js"></script>

		{{ javascript_include("js/forum.js?v=" ~ app_version) }}

		<script type="text/javascript">Forum.initializeView('{{ url() }}');</script>

		{%- if config.analytics.enabled -%}
		<script>
		var _hmt = _hmt || [];
		(function() {
		  var hm = document.createElement("script");
		  hm.src = "//hm.baidu.com/hm.js?cc33b5729183b3ecfeec291cb2f3a91b";
		  var s = document.getElementsByTagName("script")[0];
		  s.parentNode.insertBefore(hm, s);
		})();
		</script>
		{%- endif -%}
	</body>
</html>
