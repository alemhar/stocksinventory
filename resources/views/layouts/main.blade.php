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
  <title>Dashboard | AIA Cloud Apps</title>
  <link rel="stylesheet" type="text/css" href="/css/app.css">
  <!-- Font Awesome Icons -->
  <!--link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css"-->
  <!-- Theme style -->
  <!--link rel="stylesheet" href="dist/css/adminlte.min.css"-->
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  @if (Auth::check())
    <meta name="user-id" content="{{ Auth::user()->id }}" />
    <meta name="company-id" content="{{ Auth::user()->company_id }}" />
    <meta name="user-email" content="{{ Auth::user()->email }}" />
  @endif
  <style>
    .nav-treeview .nav-icon{
      margin-left: 20px;
      color: antiquewhite;
    }
    
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <!-- i class="fas fa-cubes" style="font-size: 1.5rem;"></i -->     
      <!-- img-circle  -->     
    <a href="index3.html" class="brand-link" style="/*text-align: center;*/">
      <img src="img/aia_logo_200x200.png" alt="AIA Books Logo" class="brand-image elevation-3 img-circle"
           style="background-color: white;padding: 2px;/*opacity: .8*/"> 
      <span class="brand-text font-weight-light"  style="font-size: 1.2rem;/*text-align: left !important;*/">AIA Cloud Apps</span>
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
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <router-link to="/dashboard" class="nav-link" style="color:orange;">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p v-bind:style="[readabilityObject]">
                Dashboard
                <!--span class="right badge badge-danger">New</span-->
              </p>
            </router-link>
          </li>
          
          
          <!--  menu-transaction-->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link" style="color:orange;">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p v-bind:style="[readabilityObject]">
                Transactions
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('isAdminOrUser')
              <li class="nav-item">
                <router-link to="/cd" class="nav-link">
                  <i class="nav-icon fas fa-folder-minus"></i>
                  <p v-bind:style="[readabilityObject]">Cash Disbursements</p>
                </router-link>
              </li>
              @endcan
              @can('isAdmin')
              <li class="nav-item">
                <router-link to="/cr" class="nav-link">
                  <i class="nav-icon fas fa-folder-plus"></i>
                  <p v-bind:style="[readabilityObject]">Cash Receipts</p>
                </router-link>
              </li>
              @endcan
              @can('isAdmin')
              <li class="nav-item">
                <router-link to="/sales" class="nav-link">
                  <i class="nav-icon fas fa-cash-register"></i>

                  <p v-bind:style="[readabilityObject]">Sales</p>
                </router-link>
              </li>
              @endcan
              @can('isAdmin')
              <li class="nav-item">
                <router-link to="/purchase" class="nav-link">

                  <i class="nav-icon fas fa-cart-plus"></i>
                  <p v-bind:style="[readabilityObject]">Purchases</p>
                </router-link>
              </li>
              @endcan
              @can('isAdmin')
              <li class="nav-item">
                <router-link to="/payments" class="nav-link">
                
                  <i class="nav-icon fas fa-money-bill-wave"></i>
                  <p v-bind:style="[readabilityObject]">Payments</p>
                </router-link>
              </li>
              @endcan
              @can('isAdmin')
              <li class="nav-item">
                <router-link to="/collections" class="nav-link">
                
                  <i class="nav-icon fas fa-download"></i>
                  <p v-bind:style="[readabilityObject]">Collections</p>
                </router-link>
              </li>
              @endcan
              @can('isAdmin')
              <li class="nav-item">
                <router-link to="/generaljournal" class="nav-link">
                
                  <i class="nav-icon fas fa-file-invoice"></i>
                  <p v-bind:style="[readabilityObject]">General Journal</p>
                </router-link>
              </li>
              @endcan
            </ul>
          </li>
          <!--  menu-transaction-->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link" style="color:orange;">
              <i class="nav-icon fas fa-newspaper"></i>
              <p v-bind:style="[readabilityObject]">
                Views
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('isAdmin')
              <li class="nav-item">
                <router-link to="/ledger" class="nav-link">
                  <i class="nav-icon fas fa-info-circle"></i>
                  <p v-bind:style="[readabilityObject]">Ledger</p>
                </router-link>
              </li>
              @endcan
              @can('isAdmin')
              <li class="nav-item">
                <router-link to="/transations" class="nav-link">
                  <i class="nav-icon fab fa-buffer"></i>
                  <p v-bind:style="[readabilityObject]">
                    Transactions
                  </p>
                </router-link>
              </li>
              @endcan
              @can('isAdmin')
              <li class="nav-item">
                <router-link to="/checks" class="nav-link">
                  <i class="nav-icon fas fa-money-check"></i>
                  <p v-bind:style="[readabilityObject]">
                    Checks
                  </p>
                </router-link>
              </li>
              @endcan
            </ul>
          </li>
          @can('isAdmin')
          <li class="nav-item">
            <router-link to="/reports" class="nav-link" style="color:orange;">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p v-bind:style="[readabilityObject]">
                Reports 
              </p>
            </router-link>
          </li> 
          @endcan
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link" style="color:orange;">
              <i class="nav-icon fas fa-cogs"></i>
              <p v-bind:style="[readabilityObject]">
                Settings
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                @can('isAdminOrUser')
                <li class="nav-item">
                  <router-link to="/payees" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p v-bind:style="[readabilityObject]">
                      Payees
                    </p>
                  </router-link>
                </li>
                @endcan
                @can('isAdmin')
                <li class="nav-item">
                  <router-link to="/users" class="nav-link">
                    <i class="nav-icon fas fa-user"></i>
                    <p v-bind:style="[readabilityObject]">
                      Users
                    </p>
                  </router-link>
                </li>
                @endcan
                
            </ul>
          </li>
          <li class="nav-item">
            <router-link to="/account" class="nav-link" style="color:orange;">
              <i class="nav-icon fas fa-user"></i>
              <p v-bind:style="[readabilityObject]">
                User Settings 
              </p>
            </router-link>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                <i class="nav-icon fas fa-power-off"></i>                             
                <p v-bind:style="[readabilityObject]">
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
      aiacloudapps.com
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2020 <a href="https://aiacloudapps.com">AIA Cloud Apps</a>.</strong> All rights reserved.
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
