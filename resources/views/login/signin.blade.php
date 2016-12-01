<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="utf-8" />
    <title>Musik | Web Application</title>
    <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="{{asset('/assets/css/app.css')}}">
    <!--[if lt IE 9]>
    请使用谷歌浏览器
    <![endif]-->
</head>
<body class="bg-info dker">
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    <div class="container aside-xl">
        <a class="navbar-brand block" href="index.html"><span class="h1 font-bold">Musik</span></a>
        <section class="m-b-lg">
            <header class="wrapper text-center">
                <strong>Sign in to get in touch</strong>
            </header>
            <form action="index.html">
                <div class="form-group">
                    <input type="email" placeholder="Email" class="form-control rounded input-lg text-center no-border">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" class="form-control rounded input-lg text-center no-border">
                </div>
                <button type="submit" class="btn btn-lg btn-warning lt b-white b-2x btn-block btn-rounded"><i class="icon-arrow-right pull-right"></i><span class="m-r-n-lg">Sign in</span></button>
                <div class="text-center m-t m-b"><a href="#"><small>Forgot password?</small></a></div>
                <div class="line line-dashed"></div>
                <p class="text-muted text-center"><small>Do not have an account?</small></p>
                <a href="signup.html" class="btn btn-lg btn-info btn-block rounded">Create an account</a>
            </form>
        </section>
    </div>
</section>
<!-- footer -->
<footer id="footer">
    <div class="text-center padder">
        <p>
            <small>Web app framework base on Bootstrap<br>&copy; 2014</small>
        </p>
    </div>
</footer>
<!-- / footer -->
<script src="{{ asset ("/assets/js/app.js") }}" type="text/javascript"></script>


</body>
</html>