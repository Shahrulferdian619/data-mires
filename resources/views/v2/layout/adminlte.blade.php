<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistem Informasi | PT. Mires Mahisa Globalindo</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/Ionicons/css/ionicons.min.css">

    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/dist/css/AdminLTE.min.css">

    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/select2/dist/css/select2.min.css">

    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/dist/css/skins/_all-skins.min.css">


    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <script nonce="515a9151-4c7b-49d9-99c6-3debd53c29ae">
        (function(w, d) {
            ! function(bv, bw, bx, by) {
                bv[bx] = bv[bx] || {};
                bv[bx].executed = [];
                bv.zaraz = {
                    deferred: [],
                    listeners: []
                };
                bv.zaraz.q = [];
                bv.zaraz._f = function(bz) {
                    return function() {
                        var bA = Array.prototype.slice.call(arguments);
                        bv.zaraz.q.push({
                            m: bz,
                            a: bA
                        })
                    }
                };
                for (const bB of ["track", "set", "debug"]) bv.zaraz[bB] = bv.zaraz._f(bB);
                bv.zaraz.init = () => {
                    var bC = bw.getElementsByTagName(by)[0],
                        bD = bw.createElement(by),
                        bE = bw.getElementsByTagName("title")[0];
                    bE && (bv[bx].t = bw.getElementsByTagName("title")[0].text);
                    bv[bx].x = Math.random();
                    bv[bx].w = bv.screen.width;
                    bv[bx].h = bv.screen.height;
                    bv[bx].j = bv.innerHeight;
                    bv[bx].e = bv.innerWidth;
                    bv[bx].l = bv.location.href;
                    bv[bx].r = bw.referrer;
                    bv[bx].k = bv.screen.colorDepth;
                    bv[bx].n = bw.characterSet;
                    bv[bx].o = (new Date).getTimezoneOffset();
                    if (bv.dataLayer)
                        for (const bI of Object.entries(Object.entries(dataLayer).reduce(((bJ, bK) => ({
                                ...bJ[1],
                                ...bK[1]
                            }))))) zaraz.set(bI[0], bI[1], {
                            scope: "page"
                        });
                    bv[bx].q = [];
                    for (; bv.zaraz.q.length;) {
                        const bL = bv.zaraz.q.shift();
                        bv[bx].q.push(bL)
                    }
                    bD.defer = !0;
                    for (const bM of [localStorage, sessionStorage]) Object.keys(bM || {}).filter((bO => bO.startsWith("_zaraz_"))).forEach((bN => {
                        try {
                            bv[bx]["z_" + bN.slice(7)] = JSON.parse(bM.getItem(bN))
                        } catch {
                            bv[bx]["z_" + bN.slice(7)] = bM.getItem(bN)
                        }
                    }));
                    bD.referrerPolicy = "origin";
                    bD.src = "/cdn-cgi/zaraz/s.js?z=" + btoa(encodeURIComponent(JSON.stringify(bv[bx])));
                    bC.parentNode.insertBefore(bD, bC)
                };
                ["complete", "interactive"].includes(bw.readyState) ? zaraz.init() : bv.addEventListener("DOMContentLoaded", zaraz.init)
            }(w, d, "zarazData", "script");
        })(window, document);
    </script>
</head>

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">
        <header class="main-header">
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="https://adminlte.io/themes/AdminLTE/index2.html" class="navbar-brand"><b>MMG</b></a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            
                        </ul>
                    </div>


                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">

                            <li class="dropdown user user-menu">

                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                                    <img src="" class="user-image" alt="User Image">

                                    <span class="hidden-xs">{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu">

                                    <li class="user-header">
                                        <img src="https://static.vecteezy.com/system/resources/previews/005/005/788/original/user-icon-in-trendy-flat-style-isolated-on-grey-background-user-symbol-for-your-web-site-design-logo-app-ui-illustration-eps10-free-vector.jpg" class="img-circle" alt="User Image">
                                        <p>
                                            {{Auth::user()->name}} - {{Auth::user()->position}}
                                            <small>Member since Nov. 2012</small>
                                        </p>
                                    </li>

                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="#" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                </div>

            </nav>
        </header>

        <div class="content-wrapper">
            <div class="container">

                <section class="content-header">
                    <h1>
                        @yield('header')
                    </h1>
                    
                </section>

                <section class="content">

                    @yield('content')

                </section>

            </div>

        </div>

        <footer class="main-footer">
            <div class="container">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.0.1
                </div>
                <strong>Copyright &copy; 2021 <a href="https://miresmahisa.com">Mires Mahisa</a>.</strong> All rights
                reserved.
            </div>

        </footer>
    </div>


    <script src="https://adminlte.io/themes/AdminLTE/bower_components/jquery/dist/jquery.min.js"></script>

    <script src="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <script src="https://adminlte.io/themes/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>

    <script src="https://adminlte.io/themes/AdminLTE/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

    <script src="https://adminlte.io/themes/AdminLTE/bower_components/fastclick/lib/fastclick.js"></script>

    <script src="https://adminlte.io/themes/AdminLTE/dist/js/adminlte.min.js"></script>

    <script src="https://adminlte.io/themes/AdminLTE/dist/js/demo.js"></script>

    @yield('custom_js')
</body>

</html>