<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sticky-footer-navbar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    
</head>
<body>
    <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light margin-bottom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name', 'Laravel') }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a href="#" id="dropdownLogout" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownLogout">
                                <li>
                                    <a class="dropdown-item" href="{{ route('change-password', ['id' => Auth::user()->id]) }}">
                                        Change Password
                                    </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="row {{ Auth::check() ? 'offset-md-2' : "" }}">
            @if (Auth::check())
                <nav class="col-md-2 d-none d-md-block bg-light sidebar border-top border-bottom">
                    <div class="sidebar-sticky">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                    <img class="nav-link sidebar-logo" src="{{ asset('storage/logo.png') }}" alt="logo">
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-list-item" href="{{ route('home') }}">
                                <span data-feather="home"></span>
                                <i class="fas fa-home"></i> Home <span class="sr-only">(current)</span>
                                </a>
                            </li>
                            @if(!\Auth::user()->admin)
                            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Student panel</span>
                            <a class="d-flex align-items-center text-muted" href="#">
                                <span data-feather="plus-circle"></span>
                            </a>
                            </h6>
                                <li class="nav-item">
                                    <a class="nav-link custom-list-item" href="{{ route('assigned-tests') }}">
                                    <span data-feather="home"></span>
                                    <i class="fas fa-file-alt"></i> Assigned tests <span class="sr-only">(current)</span>
                                    </a>
                                </li>
                            @endif
                        </ul>

                        <!-- Checking for admin privileges -->
                        @if(\Auth::user()->admin)
                            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Admin panel</span>
                            <a class="d-flex align-items-center text-muted" href="#">
                                <span data-feather="plus-circle"></span>
                            </a>
                            </h6>
                            <ul class="nav flex-column mb-2">
                                <li class="nav-item">
                                    <a class="nav-link custom-list-item" href="{{ route('students.index') }}">
                                    <span data-feather="file-text"></span>
                                    <i class="fas fa-graduation-cap"></i> Students
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link custom-list-item" href="{{ route('universities.index') }}">
                                    <span data-feather="file-text"></span>
                                    <i class="fas fa-university"></i> Universities
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link custom-list-item" href="{{ route('categories.index') }}">
                                    <span data-feather="file-text"></span>
                                    <i class="fas fa-calculator"></i> Categories
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link custom-list-item" href="{{ route('tests.index') }}">
                                    <span data-feather="file-text"></span>
                                    <i class="fas fa-calculator"></i> Tests
                                    </a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link custom-list-item" href="{{ route('questions.index') }}">
                                    <span data-feather="file-text"></span>
                                    <i class="fas fa-question-circle"></i> Questions
                                    </a>
                                </li> -->
                                <!-- <li class="nav-item">
                                    <a class="nav-link custom-list-item" href="{{ route('answers.index') }}">
                                    <span data-feather="file-text"></span>
                                    Answers
                                    </a>
                                </li> -->
                                <li class="nav-item">
                                    <a class="nav-link custom-list-item" href="{{ route('assignments.index') }}">
                                    <span data-feather="file-text"></span>
                                    <i class="fas fa-book"></i> Assignments
                                    </a>
                                </li>
                            </ul>
                        @endif
                    </div>
                </nav>
            @endif

            <div class="col-md-12">
            
                @if(\Session::has('alert'))
                        <div class="alert {{ \Session::get('alert-class', 'alert-info') }} alert-dismissible fade show" role="alert">
                                {{ \Session::get('alert') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
    
    <footer class="footer">
      <div class="container">
        <span class="text-muted">Powered by CP@ARGGO</span>
      </div>
    </footer>

    @yield('before_scripts')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    
    @yield('scripts')

    @yield('after_scripts')
</body>
</html>
