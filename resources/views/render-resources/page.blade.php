<!doctype html>
<html lang="en">
<head>

  <meta charset="UTF-8" />
  <meta name="generator" content="{{ config('app.name') }}" />
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  @isset($profile_id)
<meta http-equiv="X-UA-Compatible" content="{{ $profile_id }}" />
  @endif

  <title>{{ $resource->name }}</title>

</head>
<body>

  <div class="page-wrapper">
    {!! $resource->dom !!}
  </div>

</body>
</html>
