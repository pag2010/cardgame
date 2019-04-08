<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title><?php if (isset($data['title'])) {echo $data['title'];} ?></title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <script src="/js/jquery-3.3.1.min.js" type="text/javascript"></script>
	<?php include 'js/php/'.$content_js; ?>
	<?php include 'css/php/'.$content_css; ?>
</head>
<body>
	<?php include 'application/views/'.$content_view; ?>
</body>
</html>