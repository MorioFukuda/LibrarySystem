<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

 <html>
 <head>
 	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php if(isset($title)): echo $this->h($title) . ' - '; endif; ?>OASIS</title>

	<link rel="stylesheet" type="text/css" href="/css/reset.css">
	<link rel="stylesheet" type="text/css" href="/css/maxmertkit.css">
	<link rel="stylesheet" type="text/css" href="/css/maxmertkit-components.css">
	<link rel="stylesheet" type="text/css" href="/css/maxmertkit-animation.css">
	<link rel="stylesheet" type="text/css" href="/css/shadow.css">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<script type="text/javascript" src="/js/libs/html5shiv.js"></script>
	<script type="text/javascript" src="/js/libs/jquery.js"></script>
	<script type="text/javascript" src="/js/libs/easing.js"></script>
	<script type="text/javascript" src="/js/libs/imagesLoaded.js"></script>
	<script type="text/javascript" src="/js/libs/modernizr.js"></script>
	<script type="text/javascript" src="/js/plugins/maxmertkit.js"></script>
	<script type="text/javascript" src="/js/plugins/maxmertkit.affix.js"></script>
	<script type="text/javascript" src="/js/plugins/maxmertkit.scrollspy.js"></script>
	<script type="text/javascript" src="/js/plugins/maxmertkit.popup.js"></script>
	<script type="text/javascript" src="/js/plugins/maxmertkit.tabs.js"></script>
	<script type="text/javascript" src="/js/plugins/maxmertkit.modal.js"></script>
	<script type="text/javascript" src="/js/plugins/maxmertkit.carousel.js"></script>
	<script type="text/javascript" src="/js/plugins/maxmertkit.button.js"></script>
	<script type="text/javascript" src="/js/plugins/maxmertkit.notify.js"></script>
	<script type="text/javascript" src="/js/tinybox.js"></script>
	<script type="text/javascript" src="/js/main.js"></script>

</head>
<body>

<div id="main_container" class="effect7">
	<div id="header">
		<div id="navi">
			<a href="<?php echo $base_url?>/book/search" class="js-tooltip" data-content='本を探す'><img src="/img/common/icon/search_1.png" class="rollover" alt="本を探す"></a>
			<a href="<?php echo $base_url?>/book/inputByIsbn" class="js-tooltip" data-content='本の登録'><img src="/img/common/icon/input_1.png" class="rollover"></a>
			<a href="<?php echo $base_url?>/book/inputShelfMulti" class="js-tooltip" data-content='本の移動'><img src="/img/common/icon/move_1.png" class="rollover"></a>
			<a href="<?php echo $base_url?>/account/signout" class="js-tooltip" data-content='ログアウト'><img src="/img/common/icon/logout_1.png" class="rollover"></a>
		<!-- /div#navi --></div>
	<!-- /div#header --></div>
	<div id="contents_container">
		<h1 class="title"><img src="/img/common/line.png" class="line"><?php if(isset($title)): echo $this->h($title); endif; ?><img src="/img/common/line.png" class="line"></h1>
		<?php echo $_content; ?>
	<!-- /div#contents_container --></div>
<!-- /div#main_container --></div>

</body>
</html>
