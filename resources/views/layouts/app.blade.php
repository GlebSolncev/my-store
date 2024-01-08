<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Home || Truemart Responsive Html5 Ecommerce Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicons -->
    <link rel="shortcut icon" href="img/favicon.ico">
    <!-- Fontawesome css -->
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <!-- Ionicons css -->
    <link rel="stylesheet" href="/css/ionicons.min.css">
    <!-- linearicons css -->
    <link rel="stylesheet" href="/css/linearicons.css">
    <!-- Nice select css -->
    <link rel="stylesheet" href="/css/nice-select.css">
    <!-- Jquery fancybox css -->
    <link rel="stylesheet" href="/css/jquery.fancybox.css">
    <!-- Jquery ui price slider css -->
    <link rel="stylesheet" href="/css/jquery-ui.min.css">
    <!-- Meanmenu css -->
    <link rel="stylesheet" href="/css/meanmenu.min.css">
    <!-- Nivo slider css -->
    <link rel="stylesheet" href="/css/nivo-slider.css">
    <!-- Owl carousel css -->
    <link rel="stylesheet" href="/css/owl.carousel.min.css">
    <!-- Bootstrap css -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!-- Custom css -->
    <link rel="stylesheet" href="/css/default.css">
    <!-- Main css -->
    <link rel="stylesheet" href="/style.css">
    <!-- Responsive css -->
    <link rel="stylesheet" href="/css/responsive.css">

    <!-- Modernizer js -->
    <script src="/js/vendor/modernizr-3.5.0.min.js"></script>
</head>

<body>
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->

<!-- Main Wrapper Start Here -->
<div class="wrapper">
    <!-- Banner Popup Start -->
    <div class="popup_banner">
        <span class="popup_off_banner">×</span>
        <div class="banner_popup_area">
            <img src="/img/banner/pop-banner.jpg" alt="">
        </div>
    </div>
    <!-- Banner Popup End -->
    <!-- Newsletter Popup Start -->
    <div class="popup_wrapper">
        <div class="test">
            <span class="popup_off">Close</span>
            <div class="subscribe_area text-center mt-60">
                <h2>Newsletter</h2>
                <p>Subscribe to the Truemart mailing list to receive updates on new arrivals, special offers and other discount information.</p>
                <div class="subscribe-form-group">
                    <form action="#">
                        <input autocomplete="off" type="text" name="message" id="message" placeholder="Enter your email address">
                        <button type="submit">subscribe</button>
                    </form>
                </div>
                <div class="subscribe-bottom mt-15">
                    <input type="checkbox" id="newsletter-permission">
                    <label for="newsletter-permission">Don't show this popup again</label>
                </div>
            </div>
        </div>
    </div>
    <!-- Newsletter Popup End -->
    <!-- Main Header Area Start Here -->
    <header>
        <!-- Header Top Start Here -->
        <div class="header-top-area">
            <div class="container">
                <!-- Header Top Start -->
                <div class="header-top">
                    @include('layouts.parts.header.top.left')

                    @include('layouts.parts.header.top.right')
                </div>
                <!-- Header Top End -->
            </div>
            <!-- Container End -->
        </div>
        <!-- Header Top End Here -->
        <!-- Header Middle Start Here -->
        <div class="header-middle ptb-15">
            <div class="container">
                <div class="row align-items-center no-gutters">
                    <div class="col-lg-3 col-md-12">
                        @include('layouts.parts.header.middle.left')
                    </div>
                    <!-- Categorie Search Box Start Here -->
                    <div class="col-lg-5 col-md-8 ml-auto mr-auto col-10">
                        @include('layouts.parts.header.middle.middle')
                    </div>
                    <!-- Categorie Search Box End Here -->
                    <!-- Cart Box Start Here -->
                    <div class="col-lg-4 col-md-12">
                        @include('layouts.parts.header.middle.right')
                    </div>
                    <!-- Cart Box End Here -->
                </div>
                <!-- Row End -->
            </div>
            <!-- Container End -->
        </div>
        <!-- Header Middle End Here -->

        <!-- Header Bottom Start Here -->
        @include('layouts.parts.header.bottom.menu')
    </header>
    <!-- Main Header Area End Here -->
    @yield('content')
</div>
<!-- Main Wrapper End Here -->

<!-- jquery 3.2.1 -->
<script src="/js/vendor/jquery-3.2.1.min.js"></script>
<!-- Countdown js -->
<script src="/js/jquery.countdown.min.js"></script>
<!-- Mobile menu js -->
<script src="/js/jquery.meanmenu.min.js"></script>
<!-- ScrollUp js -->
<script src="/js/jquery.scrollUp.js"></script>
<!-- Nivo slider js -->
<script src="/js/jquery.nivo.slider.js"></script>
<!-- Fancybox js -->
<script src="/js/jquery.fancybox.min.js"></script>
<!-- Jquery nice select js -->
<script src="/js/jquery.nice-select.min.js"></script>
<!-- Jquery ui price slider js -->
<script src="/js/jquery-ui.min.js"></script>
<!-- Owl carousel -->
<script src="/js/owl.carousel.min.js"></script>
<!-- Bootstrap popper js -->
<script src="/js/popper.min.js"></script>
<!-- Bootstrap js -->
<script src="/js/bootstrap.min.js"></script>
<!-- Plugin js -->
<script src="/js/plugins.js"></script>
<!-- Main activaion js -->
<script src="/js/main.js"></script>
</body>

</html>