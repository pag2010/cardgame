<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php if (isset($data['title'])) {echo $data['title'];} ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="/js/jquery-3.3.1.min.js" type="text/javascript"></script>
</head>

<body>
    <div id="root">

    </div>
    <?php /*include "js/php/auth_login_js.php";*/ include 'application/views/'.$content_view;?>
</body>
<?php include 'js/php/'.$content_js; ?>
</html>