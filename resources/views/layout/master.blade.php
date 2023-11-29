<!DOCTYPE html>
<html>
<head>
  <title>{{env('APP_NAME')}} | {{ $page['pageTitle'] ?? '' }}</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}" id="meta-csrf" />

  <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

  <!-- plugin css -->
  
  <!--BOOTSTRAP-->
  <link href="{{ asset('assets/plugins/@mdi/font/css/materialdesignicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">
  <!-- <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet"> -->
  <link  href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <!-- end plugin css -->

  @stack('plugin-styles')

  <!-- common css -->
  <link href="{{ asset('vendor/css/vendor.css')}}" rel="stylesheet">
  <link href="{{ asset('css/app.css')}}" rel="stylesheet">
  <link href="{{ asset('css/custom.css')}}" rel="stylesheet">
  <!-- end common css -->

  @stack('style')

  <!--JQUERY & OTHER HEADER JS-->
  <script src="{{ asset('/vendor/js/vendor.header.js?v=') }}"></script>

  @include('layout.NX-variables')
  
</head>
<body data-base-url="{{url('/')}}">
  <div id="new-loader" class="overlay spanner">
    <div class="loading"></div>
  </div>
  <div class="container-scroller" id="app">
    @include('layout.header')
    <div class="container-fluid page-body-wrapper">
      @include('layout.sidebar')
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        @include('layout.footer')
      </div>
    </div>
  </div>

  @include('modals.common-modal-wrapper')
  @include('modals.actions-modal-wrapper')
  @include('modals.notify-modal-wrapper')
  
  <!--dynamic load lead lead (dynamic_trigger_dom)-->

  <!-- automation -->
  <!--flash messages longer duration-->
  @if(Session::has('success-notification-longer'))
  <span id="js-trigger-session-message" data-type="success"
      data-message="{{ Session::get('success-notification-longer') }}"></span>
  @endif

  @if(Session::has('error-notification-longer'))
  <span id="js-trigger-session-message" data-type="error"
      data-message="{{ Session::get('error-notification-longer') }}"></span>
  @endif

  <!-- base js -->
  <!-- <script src="{{ asset('js/app.js') }}"></script> -->
  <!-- end base js -->

  <!-- plugin js -->
  @stack('plugin-scripts')
  <!-- end plugin js -->

  <!--ALL THIRD PART JAVASCRIPTS-->
  <script src="{{ asset('/vendor/js/vendor.footer.js?v='. config('system.versioning')) }}"></script>

  <!-- common js -->
  <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
  <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('assets/js/misc.js') }}"></script>
  <script src="{{ asset('assets/js/settings.js') }}"></script>
  <script src="{{ asset('assets/js/todolist.js') }}"></script>


  <!--nextloop.core.js-->
  <script src="{{ asset('/js/core/ajax.js?v='. config('system.versioning')) }}"></script>

  <!--MAIN JS - AT END-->
  <script src="{{ asset('/js/core/boot.js?v='. config('system.versioning')) }}"></script>

  <!--EVENTS-->
  <script src="{{ asset('/js/core/events.js?v='. config('system.versioning')) }}"></script>

  <!--CORE-->
  <script src="{{ asset('/js/core/app.js?v='. config('system.versioning')) }}"></script>

  <!--BILLING-->
  <script src="{{ asset('/js/core/billing.js?v='. config('system.versioning')) }}"></script>

  <!--PURCHASE-->
  <script src="{{ asset('/js/core/purchase.js?v='. config('system.versioning')) }}"></script>
  <!-- end common js -->

  <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

  <!-- <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script> -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script> 
  <!-- <script src="{{ asset('vendor/datatables/buttons.server-side.js')}}"></script>  -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
  @stack('scripts')
</body>
</html>