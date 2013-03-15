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
	<link rel="stylesheet" type="text/css" href="/css/modal.css">
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
<?php if(isset($title)):?>
<h1 id="header"> <?php echo $this->h($title) ?></h1>
<?php endif; ?>
<div id="modal">
	<?php echo $_content ?>
</div>
</body>
</html>
