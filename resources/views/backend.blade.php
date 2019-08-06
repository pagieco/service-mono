<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

  <style type="text/css" id="anti-click-jack">body{display:none!important;}</style>
  <script type="text/javascript">
    if(self===top){var acj=document.getElementById("anti-click-jack");acj.parentNode.removeChild(acj)}
    else top.location=self.location;
  </script>

  <meta charset="UTF-8"/>
  <meta name="robots" content="noindex, nofollow" />
  <meta http-equiv="Content-Language" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
  <meta http-equiv="X-UA-Compatible" content="ie=edge"/>

  <title>{{ config('app.name') }}</title>

  <link rel="stylesheet" href="{{ asset(mix('css/vendor.css')) }}" />
  <style>
    [v-cloak] > * { display:none }
    [v-cloak]::before { content: "loading…" }
  </style>

</head>
<body>

  <div id="app" v-cloak>
    <noscript>
      We're sorry but {{ config('app.name') }} doesn't work properly without JavaScript enabled. Please enable it to continue.
    </noscript>
  </div>

  <script src="{{ asset(mix('js/manifest.js')) }}"></script>
  <script src="{{ asset(mix('js/vendor.js')) }}"></script>
  <script src="{{ asset(mix('js/backend.js')) }}"></script>

</body>
</html>
