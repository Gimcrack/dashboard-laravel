<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aynchronous Laravel Dashboard</title>
    <link rel="stylesheet" href="/css/all.css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,300,700" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Ubuntu:400,300,700" media="screen" title="no title" charset="utf-8">

    <script type="text/javascript" src="/js/async-grid/jApp.class.js"> </script>
    <script type="text/javascript" src="/js/all.js"> </script>

    <script type="text/javascript" src="/js/async-grid/jForm.class.js"> </script>
    <script type="text/javascript" src="/js/async-grid/jInput.class.js"> </script>
    <script type="text/javascript" src="/js/async-grid/jLinkTable.class.js"> </script>

    <script type="text/javascript" src="/js/async-grid/jGrid.class.js"> </script>
    <script type="text/javascript" src="/js/working/admin.users.html.js"> </script>
  </head>
  <body class="{{ env('CSS_CLASS') }}">
    <div id="wrapper" class="{{ env('CSS_CLASS') }}">


      @include('partials.topbar')

      <div id="page-wrapper" class="">
        @yield('content')
      </div><!-- /#page-wrapper -->

      @include('partials.modalOverlays')

    </div><!-- /#wrapper -->

  </body>
</html>
