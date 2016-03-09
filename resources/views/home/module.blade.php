<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>FAST</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="apple-touch-icon-precomposed" href="{{env('APP_URL')}}/images/114.png" sizes="114x114" />
    <link rel="shortcut icon" href="{{env('APP_URL')}}/images/32.ico" type="image/x-icon" />
    <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.5.2/css/amazeui.min.css"/>
    <style>
        body {
            background-color: #e2e2e2;
        }

        .am-form input[type=text] {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }

        .am-form input[type=password] {
            border-bottom-left-radius: 5px !important;
            border-bottom-right-radius: 5px !important;
        }

        button[type=submit], button#forget {
            border-radius: 5px;
        }

        .header {
            text-align: center;
        }

        .header h1 {
            font-size: 200%;
            color: #333;
            margin-top: 30px;
        }

        .header p {
            font-size: 14px;
        }

        .logo {
            box-sizing: border-box;
            width: 60%;
            left: 20%;
            vertical-align: middle;
            border: 0;
        }
    </style>
    @yield("moreCss")
</head>
<body>
<header data-am-widget="header" class="am-header am-header-default">
    @yield('back')
    <h1 class="am-header-title">
        FAST系统
    </h1>
</header>
@yield('content')
<footer style="height: 49px;">
    <p style="text-align: center;margin-bottom: 0;line-height: 49px;">© 2016-2017 FESCO, Inc.</p>
</footer>


<!--[if (gte IE 9)|!(IE)]><!-->
<script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<!-- <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script> -->
<!--<![endif]-->
<script src="http://cdn.amazeui.org/amazeui/2.5.2/js/amazeui.min.js"></script>
<script>
    var initFooter = function () {
        height = (window.innerHeight > 0) ? window.innerHeight : screen.height;
        var maxHeight = height - 49 * 2;
        $('section').css("min-height", maxHeight);
    };
    $(window).resize(function () {
        initFooter();
    });
    $(document).ready(function () {
        initFooter();
    });
</script>
@yield('moreJs')
</body>
</html>
