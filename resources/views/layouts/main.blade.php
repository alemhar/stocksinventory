<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard | AIA Books</title>
  <link rel="stylesheet" type="text/css" href="/css/app.css">
  <!-- Font Awesome Icons -->
  <!--link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css"-->
  <!-- Theme style -->
  <!--link rel="stylesheet" href="dist/css/adminlte.min.css"-->
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  @if (Auth::check())
    <meta name="user-id" content="{{ Auth::user()->id }}" />
  @endif
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <!--nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav -->
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <!-- i class="fas fa-cubes" style="font-size: 1.5rem;"></i -->     
      <!-- img-circle  -->     
    <a href="index3.html" class="brand-link" style="/*text-align: center;*/">
      <img src="img/si_logo2_200x200.png" alt="Stocks Inventory Logo" class="brand-image elevation-3 img-circle"
           style="background-color: white;padding: 2px;/*opacity: .8*/"> 
      <span class="brand-text font-weight-light"  style="font-size: 1.2rem;/*text-align: left !important;*/">AIA Books</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <!-- div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div -->
        <div class="info" style="/*margin: auto;*/">
          <a href="#" class="d-block">Hi {{ isset(Auth::user()->name) ? ucwords(Auth::user()->name) : '' }}!</a>
          <!-- p>{{ isset(Auth::user()->name) ? Auth::user()->company_id : '' }}</p -->
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <router-link to="/dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <!--span class="right badge badge-danger">New</span-->
              </p>
            </router-link>
          </li>
          
          <!-- @can('isAdmin') -->
          <!--  menu-open-->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Transactions
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/cd" class="nav-link">
                  <i class="nav-icon fas fa-minus-circle"></i>
                  <p>Cash Disbursements</p>
                </router-link>
              </li>
              @can('isAdmin')
              <li class="nav-item">
                <router-link to="/cash" class="nav-link">
                  <i class="nav-icon fas fa-plus-circle"></i>
                  <p>Cash Receipts</p>
                </router-link>
              </li>
              @endcan
            </ul>
          </li>
          
          <li class="nav-item">
            <router-link to="/payees" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Payees
              </p>
            </router-link>
          </li>
          <!-- @endcan -->
          @can('isAdmin')
          <li class="nav-item">
            <router-link to="/account" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Accounts
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/users" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Users
              </p>
            </router-link>
          </li>
          @endcan
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                <i class="nav-icon fas fa-power-off"></i>                             
                <p>
                  {{ __('Logout') }}
                </p>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="content">
      <div class="container-fluid">
        <!-- Main content -->
        <router-view></router-view>
        <vue-progress-bar></vue-progress-bar>
        <!-- /.content -->
      </div>  
    </div>
    
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <!--aside class="control-sidebar control-sidebar-dark">
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside -->
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      aiabooks.com
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>
</div>

@auth
<script>
    window.user = @json(auth()->user())
</script>
@endauth

<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<!--script src="plugins/jquery/jquery.min.js"></script -->
<!-- Bootstrap 4 -->
<!--script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script-->
<!-- AdminLTE App -->
<!--script src="dist/js/adminlte.min.js"></script -->

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>

<!-- script type="/js/adminlte.js"></script -->

</body>
</html>
