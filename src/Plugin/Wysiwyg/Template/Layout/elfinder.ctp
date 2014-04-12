<!DOCTYPE html>
<html>
	<head>
		<title>elFinder 2.0</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>

		<?php echo $this->Html->css('/wysiwyg/js/elfinder/css/elfinder.min.css'); ?>
		<?php echo $this->Html->css('/wysiwyg/js/elfinder/css/theme.css'); ?>
		<?php echo $this->Html->script('/system/js/jquery-ui.js'); ?>
		<?php echo $this->Html->script('/wysiwyg/js/elfinder/js/elfinder.min.js'); ?>
	</head>

	<body>
		<?php echo $this->fetch('content'); ?>	
	</body>
</html>