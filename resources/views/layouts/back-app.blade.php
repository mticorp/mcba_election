<!DOCTYPE html>
<html>
<head>

@include('back-partials.head')
</head>
<body class="sidebar-mini layout-fixed accent-info layout-navbar-fixed">
<!-- Site wrapper -->
<div class="wrapper">
@include('back-partials.header')
@include('back-partials.sidebar')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      @yield('breadcrumb')
    <!-- Main content -->
    @yield('content')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer text-sm">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 0.0.1
    </div>
    <strong>Copyright &copy; 2020 <a href="http://www.mti.com.mm" target="_blank">MTI ( Myanmar Technology and Investment Corporation Co., Ltd)</a>.</strong> All rights
    reserved.
  </footer>
</div>
<!-- ./wrapper -->

@include('back-partials.javascript')
</body>
</html>
