<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />

    <title>{{ config('app.name') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta name="description" content="" />

    <meta name="HandheldFriendly" content="True" />
    <meta name="MobileOptimized" content="320" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <meta http-equiv="cleartype" content="on" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}" />
</head>
<body>
    <div class="container">
        <div class="py-5 text-center">
            <h2>Welcome to our {{ config('app.name') }}!</h2>
        </div>
        <p>To test our API, the following links are available:</p>
        <ul class="list-group list-group-flush">
            <li class="list-group-item list-group-item-no-border"><code><b>GET</b></code> <a href="{{ url('/get-products') }}" target="_blank">{{ url('/get-products') }}</a></li>
            <li class="list-group-item list-group-item-no-border"><code><b>GET</b></code> <a href="{{ url('/get-products/1') }}" target="_blank">{{ url('/get-products/1') }}</a></li>
            <li class="list-group-item list-group-item-no-border"><code><b>GET</b></code> <a href="{{ url('/get-categories') }}" target="_blank">{{ url('/get-categories') }}</a></li>
            <li class="list-group-item list-group-item-no-border"><code><b>GET</b></code> <a href="{{ url('/get-total-value') }}" target="_blank">{{ url('/get-total-value') }}</a></li>
            <li class="list-group-item list-group-item-no-border"><code><b>POST</b></code> <a href="{{ url('/create-product') }}" target="_blank">{{ url('/create-product') }}</a></li>
            <li class="list-group-item list-group-item-no-border"><code><b>PATCH</b></code> <a href="{{ url('/create-product/1') }}" target="_blank">{{ url('/create-product/1') }}</a></li>
            <li class="list-group-item list-group-item-no-border"><code><b>DELETE</b></code> <a href="{{ url('/delete-product/1') }}" target="_blank">{{ url('/delete-product/1') }}</a></li>
        </ul>
        <br /><br />
        <p>Enjoy! :) And if you have any questions, please don't hesitate to contact us!</p>
    </div>
    <footer class="my-5 pt-4 text-muted text-center text-small">
        &copy; {{ date('Y') }} {{ config('app.name') }}
    </footer>
</body>
</html>
