<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Catalog-API | Imports</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <link href="/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="/css/animate.min.css" rel="stylesheet">

    <link href="/css/custom.css" rel="stylesheet">
    <link href="/css/maps/jquery-jvectormap-2.0.3.css" />
    <link href="/css/icheck/flat/green.css" rel="stylesheet" />
    <link href="/css/datatables/tools/css/dataTables.tableTools.css" rel="stylesheet">

    <script src="/js/jquery.min.js"></script>
    <script src="/js/nprogress.js"></script>

    <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
    <![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
        </div>

        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>API | Imports</span></a>
                </div>
          
                <div class="clearfix"></div>

                <div class="profile">&nbsp;</div>
                
                <br />

                @include( 'import.includes.sidebar' )
            </div>
        </div>

        <div class="top_nav">
            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                       <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Profile <span class=" fa fa-angle-down"></span>
                            </a>
                
                            <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                <li><a href="javascript:;">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="right_col" role="main">
            @yield( 'content' )
        </div>
    </div>

    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/nicescroll/jquery.nicescroll.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/custom.js"></script>

    <script type="text/javascript" src="/js/datatables/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="/js/datatables/tools/js/dataTables.tableTools.js"></script>

    <script type="text/javascript" src="/js/notify/pnotify.core.js"></script>
    <script type="text/javascript" src="/js/notify/pnotify.buttons.js"></script>
    <script type="text/javascript" src="/js/notify/pnotify.nonblock.js"></script>

    @yield( 'scripts' )
</body>