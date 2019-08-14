<!doctype html>
<html lang="en">
<head>

  <meta charset="UTF-8" />
  <meta name="generator" content="{{ config('app.name') }}" />
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />

  <title>{{ $resource->name }}</title>

  <link rel="stylesheet" href="{{ asset(mix('css/backend.css')) }}" />
  <link rel="stylesheet" href="{{ asset($domain->css_file) }}" id="page-css" />

</head>
<body>

  <div class="page-wrapper">
    {!! $resource->dom !!}
  </div>

  <script src="{{ asset(mix('js/manifest.js')) }}"></script>
  <script src="{{ asset(mix('js/vendor.js')) }}"></script>
  <script src="{{ asset(mix('js/editor.js')) }}"></script>

</body>
</html>
