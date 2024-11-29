<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>Digital Development Vision</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>
    <link rel="icon" href="{{ asset('styles_dashboard/assets/img/favicon.png') }}" type="image/x-icon"/>
    @yield("token")
    <!-- Fonts and icons -->
    <script src="{{ asset("styles_dashboard/assets/js/plugin/webfont/webfont.min.js") }}"></script>
    <script>
        WebFont.load({
            google: {families: ["Public Sans:300,400,500,600,700"]},
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset("styles_dashboard/assets/css/fonts.min.css") }}"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset("css_bootstrap/vendor/bootstrap/css/bootstrap.min.css") }}"/>
    <link rel="stylesheet" href="{{ asset("styles_dashboard/assets/css/plugins.min.css") }}"/>
    <link rel="stylesheet" href="{{ asset("css_bootstrap/bootstrap-icons/bootstrap-icons.css") }}">
    <link rel="stylesheet" href="{{ asset("styles_dashboard/assets/css/kaiadmin.min.css") }}"/>

    <link rel="stylesheet" href="{{ asset("styles_dashboard/assets/global_style.css") }}"/>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    @yield('style')
</head>
<body>
<div class="wrapper sidebar_minimize">
    @php
        $routename = request()->route()->getName();
    @endphp
    <!-- Sidebar -->
    <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
            <!-- Logo Header -->
            <div class="logo-header mb-0" data-background-color="dark">
                <a href="#" class="logo">
                    <img
                        src="{{ asset('styles_dashboard/assets/img/logo-white2.png') }}"
                        alt="navbar brand"
                        class="navbar-brand"
                        height="50"
                    />
                </a>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="gg-menu-right"></i>
                    </button>
                    <button class="btn btn-toggle sidenav-toggler">
                        <i class="gg-menu-left"></i>
                    </button>
                </div>
                <button class="topbar-toggler more">
                    <i class="gg-more-vertical-alt"></i>
                </button>
            </div>
            <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
                <ul class="nav nav-secondary">
                    <li @class(['nav-item', 'active'=>str_starts_with($routename, 'dashboard_admin')])>
                        <a href="{{ route('dashboard_admin') }}">
                            <i class="fas fa-home"></i>
                            <p>ACCUEIL</p>
                        </a>
                    </li>
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                          <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">NAVIGATION</h4>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#base">
                            <i class="fas fa-plus-circle"></i>
                            <p>OPTIONS D'AJOUT</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse ml-3" id="base">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('register') }}">
                                        <span class="bi-circle" style="font-size: 10pt"> ajouter un utilisateur</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('projet.ajouter') }}">
                                        <span class="bi-circle" style="font-size: 10pt"> ajouter un projet</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li @class(['nav-item', 'active'=>str_starts_with($routename, 'user.')])>
                        <a data-bs-toggle="collapse" href="#sidebarLayouts">
                            <i class="fas bi-people"></i>
                            <p>UTILISATEURS</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse ml-3" id="sidebarLayouts">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('user.list_users') }}">
                                        <span class="bi-circle" style="font-size: 10pt"> liste des utilisateurs</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="bi-circle" style="font-size: 10pt"> droits et permissions</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li @class(['nav-item', 'active'=>str_starts_with($routename, 'agenda.')])>
                        <a href="{{ route('agenda.agenda') }}">
                            <i class="fas bi-calendar"></i>
                            <p>AGENDA</p>
                            <span class="badge badge-warning">
                                @php
                                    $heureCarbon = \Carbon\Carbon::now();
                                    $tasks = \App\Models\Agenda::where("user_id", $current_user->id)->where("statut", 0)->whereDate("date", \Carbon\Carbon::today())->get();
                                    $task_count = 0;
                                    foreach ($tasks as $task) {
                                        if (!$heureCarbon->greaterThan($task->heure_fin)) {
                                            $task_count += 1;
                                        }
                                    }
                                @endphp
                                {{ $task_count }}
                            </span>
                        </a>
                    </li>
                    <li @class(['nav-item', 'active'=>str_starts_with($routename, 'projet.')])>
                        <a href="{{ route("projet.list") }}">
                            <i class="fas fa-plus-square"></i>
                            <p>PROJETS</p>
                            <span class="badge badge-success">{{ $projet_count }}</span>
                        </a>
                    </li>
                    <li @class(['nav-item', 'active'=>str_starts_with($routename, 'documentation.')])>
                        <a href="#">
                            <i class="fas bi-question-circle-fill"></i>
                            <p>DOCUMENTATION</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Sidebar -->

    <div class="main-panel">
        <div class="main-header">
            <div class="main-header-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="#" class="logo">
                        <img
                            src="{{ asset('styles_dashboard/assets/img/logo-white2.png') }}"
                            alt="navbar brand"
                            class="navbar-brand"
                            height="50"
                        />
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <!-- Navbar Header -->
            <nav
                class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
            >
                <div class="container-fluid">
                    <nav
                        class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
                    >
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="submit" class="btn btn-search pe-1">
                                    <i class="fa fa-search search-icon"></i>
                                </button>
                            </div>
                            <input
                                type="text"
                                placeholder="Rechercher ..."
                                class="form-control"
                                disabled
                            />
                        </div>
                    </nav>

                    <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                        <li class="nav-item topbar-user dropdown hidden-caret">
                            <a
                                class="dropdown-toggle profile-pic"
                                data-bs-toggle="dropdown"
                                href="#"
                                aria-expanded="false"
                            >
                                <div class="avatar-sm">
                                    <img
                                        src="@if($current_user->photo) /storage/{{ $current_user -> photo }} @else {{ asset('assets\utilisateur.png') }} @endif"
                                        alt="..."
                                        class="avatar-img rounded-circle"
                                    />
                                </div>
                                <span class="profile-username">
                                    <span class="op-7">Bonjour,</span>
                                    <span class="fw-bold">{{ $current_user->nom }}</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="avatar-lg">
                                                <img
                                                    src="@if($current_user->photo) /storage/{{ $current_user -> photo }} @else {{ asset('assets\utilisateur.png') }} @endif"
                                                    alt="image profile"
                                                    class="avatar-img rounded"
                                                />
                                            </div>
                                            <div class="u-text">
                                                <h4>{{ $current_user->nom }}</h4>
                                                <p class="text-muted">{{ $current_user->email }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li style="padding: 10px">
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item btn btn-primary text-light" style="text-align: center" href="{{ route('profile.edit') }}">paramètre de compte</a>
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('logout') }}" method="post">
                                            @csrf
                                            <button style="text-align: center" class="dropdown-item btn btn-warning">
                                                se déconnecter
                                            </button>
                                        </form>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>

        <div class="container">
            <div class="page-inner">
                <div>
                    @yield('big_title')
                    <h6 class="op-7 mb-2">@yield('small_description')</h6>
                </div>
                @yield('content')
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
                <nav class="pull-left">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                Digital Development Vision (DDV)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"> Aide </a>
                        </li>
                    </ul>
                </nav>
                <div class="copyright">
                    2024, <i class="fa fa-copyright text-secondary"></i> by
                    <a href="#">yourierick@yahoo.com</a>
                </div>
                <div>
                    Distribuée par
                    <a target="_blank" href="https://www.linkedin.com/in/Erick-Bitangalo"><span class="bi-linkedin"></span>Ir Erick BITANGALO</a>.
                </div>
            </div>
        </footer>
    </div>
</div>
<!--   Core JS Files   -->
<script src="{{ asset("styles_dashboard/assets/js/core/jquery-3.7.1.min.js") }}"></script>
<script src="{{ asset("styles_dashboard/assets/js/core/popper.min.js") }}"></script>
<script src="{{ asset("styles_dashboard/assets/js/core/bootstrap.min.js") }}"></script>

<!-- jQuery Scrollbar -->
<script src="{{ asset("styles_dashboard/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js") }}"></script>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- jQuery Sparkline -->
<script src="{{ asset("styles_dashboard/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js") }}"></script>

<!-- Chart Circle -->
<script src="{{ asset("styles_dashboard/assets/js/plugin/chart-circle/circles.min.js") }}"></script>

<!-- Bootstrap Notify -->
<script src="{{ asset("styles_dashboard/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js") }}"></script>

<!-- Sweet Alert -->
<script src="{{ asset("styles_dashboard/assets/js/plugin/sweetalert/sweetalert.min.js") }}"></script>

<!-- Kaiadmin JS -->
<script src="{{ asset("styles_dashboard/assets/js/kaiadmin.min.js") }}"></script>

<script src="{{ asset("styles_dashboard/assets/js/plugin/datatables/datatables.min.js") }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/file-saver"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/vfs_fonts.js"></script>

@yield('scripts')
<script>
    $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#177dff",
        fillColor: "rgba(23, 125, 255, 0.14)",
    });

    $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#f3545d",
        fillColor: "rgba(243, 84, 93, .14)",
    });

    $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#ffa534",
        fillColor: "rgba(255, 165, 52, .14)",
    });
</script>
</body>
</html>
