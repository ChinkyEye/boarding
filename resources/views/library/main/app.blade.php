<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <meta name="description" content="Library HTML Template">
  <meta name="keywords" content="industry, html">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="{{URL::to('/')}}/favicon.ico" rel="shortcut icon"/>
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="{{URL::to('/')}}/backend/css/all.min.css">
  <link rel="stylesheet" href="{{URL::to('/')}}/backend/plugins/pace-progress/themes/black/pace-theme-flat-top.css">
  <link rel="stylesheet" href="{{URL::to('/')}}/backend/css/adminlte.min.css">
  <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"> --}}
  <link rel="stylesheet" href="{{URL::to('/')}}/backend/css/toastr.min.css">
  <link rel="stylesheet" href="{{URL::to('/')}}/backend/css/style.back.css">
  @stack('style')
</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    @include('library.main.header')
    @include('library.main.sidebar')
    <div class="content-wrapper">
      @yield('content')
    </div>
    @include('library.main.footer')
  </div>
  <script src="{{URL::to('/')}}/backend/js/jquery.min.js"></script>
  <script src="{{URL::to('/')}}/backend/js/bootstrap.bundle.min.js"></script>
  <script src="{{URL::to('/')}}/backend/plugins/pace-progress/pace.min.js"></script>
  <script src="{{URL::to('/')}}/backend/js/adminlte.js"></script>
  <script src="{{URL::to('/')}}/backend/js/demo.js"></script>
  <!-- <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script> -->
  <script src="{{URL::to('/')}}/backend/js/toastr.min.js"></script>
  <script src="{{URL::to('/')}}/backend/js/toggle.main.js"></script>
  <script>
    @if(Session::has('message'))
    var type = "{{ Session::get('alert-type', 'info') }}";
    switch(type){
      case 'info':
      toastr.info("{{ Session::get('message') }}");
      break;

      case 'warning':
      toastr.warning("{{ Session::get('message') }}");
      break;

      case 'success':
      toastr.success("{{ Session::get('message') }}");
      break;

      case 'error':
      toastr.error("{{ Session::get('message') }}");
      break;
    }
    @endif
  </script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script>
    $('.delete-confirm').on('click', function (e) {
      event.preventDefault();
      const url = $(this).attr('action');
      var token = $('meta[name="csrf-token"]').attr('content');
      swal({
        title: 'Are you sure?',
        text: 'This record and it`s details will be permanantly deleted!',
        icon: 'warning',
        buttons: ["Cancel", "Yes!"],
        dangerMode: true,
        closeOnClickOutside: false,
      }).then(function(value) {
        if(value == true){
          $.ajax({
            url: url,
            type: "POST",
            data: {
              _token: token,
              '_method': 'DELETE',
            },
            success: function (data) {
              swal({
                title: "Success!",
                type: "success",
                text: data.message+"\n Click OK",
                icon: "success",
                showConfirmButton: false,
              }).then(location.reload(true));
              
            },
            error: function (data) {
              swal({
                title: 'Opps...',
                text: data.message+"\n Please refresh your page",
                type: 'error',
                timer: '1500'
              });
            }
          });
        }else{
          swal({
            title: 'Cancel',
            text: "Data is safe.",
            icon: "success",
            type: 'info',
            timer: '1500'
          });
        }
      });
    });
  </script> 
  <script>
    $('.delete-c').on('click', function (e) {
      event.preventDefault();
      const url = $(this).attr('action');
      var token = $('meta[name="csrf-token"]').attr('content');
      swal({
        title: 'Are you sure?',
        text: 'This record and it`s details will be permanantly deleted!',
        icon: 'warning',
        buttons: ["Cancel", "Yes!"],
        dangerMode: true,
        closeOnClickOutside: false,
      }).then(function(value) {
        if(value == true){
          $.ajax({
            url: url,
            type: "POST",
            data: {
              _token: token,
              '_method': 'DELETE',
            },
            success: function (data) {
              swal({
                title: "Success!",
                type: "success",
                text: data.message+"\n Click OK",
                icon: "success",
                showConfirmButton: false,
              }).then(location.reload(true));
              
            },
            error: function (data) {
              swal({
                title: 'Opps...',
                text: data.message+"\n Please refresh your page",
                type: 'error',
                timer: '1500'
              });
            }
          });
        }else{
          swal({
            title: 'Cancel',
            text: "Data is safe.",
            icon: "success",
            type: 'info',
            timer: '1500'
          });
        }
      });
    });
  </script> 
  <script type="text/javascript">
    $(".sort").keydown(function (e) {
      Pace.start();
      if (e.which == 9 || e.which === 13){
        var id = $(event.target).attr('ids'),
        page = $(event.target).attr('page'),
        value = document.getElementById('main'+id).innerHTML,
        token = $('meta[name="csrf-token"]').attr('content');
        var url = $(event.target).attr('url');
        $.ajax({
          type:"POST",
          dataType:"JSON",
          url:url,
          data:{
            _token:token,
            id : id,
            value : value,
          },
          success:function(e){
            toastr.success(e.msg);
            location.reload();
          },
          error: function (e) {
            toastr.error('Sorry! this data is used some where');
            Pace.stop();
          }
        });
      }
    });
  </script>
  <script>
    $('body').ready(function() {
      $('[data-toggle="tooltip"]').tooltip()
    })
  </script>
  @stack('javascript')
</body>
</html>


