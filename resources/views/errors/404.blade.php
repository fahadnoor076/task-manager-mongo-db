<!Doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href={!! URL::to(config('constants.ADMIN_CSS_URL').'vendor.min.css'); !!}">
    <link rel="stylesheet" type="text/css" href="{!! URL::to(config('constants.ADMIN_CSS_URL').'plugins.min.css'); !!}">
    <link rel="stylesheet" type="text/css" href="{!! URL::to(config('constants.ADMIN_CSS_URL').'beast.min.css'); !!}">
    <title>{!! APP_NAME; !!}</title>
</head>

<body>
    <div class="bst-wrapper">
        <div class="bst-main lost-bg">
            <div class="page-error">
                <div class="row">
                    <div class="col-xs-11 col-sm-10 sol-md-7 col-lg-5 center-block">
                        <div class="bst-form-block text-center bst-full-block">
                            <div class="login-bar"> <img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'login-bars.png') !!}" class="img-responsive" alt="login bar" width="743" height="7"> </div>
                            <div class="mrgn-b-lg">
                                <p>Oops! You're lost</p>
                            </div>
                            <div class="mrgn-b-lg">
                                <h2>404</h2> </div>
                            <div class="mrgn-b-lg">
                                <p class="content font-lg"> We can not find the page you're looking for.
                                    <br>Return home or try again later. &#x1f609; </p>
                            </div> <a href="{!! URL::to(config('/')); !!}" class="btn btn-success btn-block font-lg">Back to Home</a> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{!! URL::to(config('constants.ADMIN_JS_URL').'vendor.js') !!}" type="text/javascript"></script>
    <script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins.js') !!}" type="text/javascript"></script>
    <script src="{!! URL::to(config('constants.ADMIN_JS_URL').'beast.js') !!}" type="text/javascript"></script>
</body>

</html>