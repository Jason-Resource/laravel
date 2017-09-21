## resources/views/layouts/app.blade.php

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    @yield('body')

    <script src="main.js"></script>
    @yield('js')
</body>
</html>

/***************************************************************************/

## resources/views/1.blade.php

@extends('layouts.app')

@section('body')
    这里是1
    @include('2')
@endsection

@section('js')
    <script src="1.js"></script>
    @stack('scripts')
@endsection

/***************************************************************************/

## resources/views/2.blade.php

这里是2

@push('scripts')
<script src="/2.js"></script>
@endpush


这里是22

@push('scripts')
<script src="/22.js"></script>
@endpush


/***************************************************************************/

## 最后输出




<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
        这里是1
    这里是2



这里是22


    <script src="main.js"></script>
        <script src="1.js"></script>
    <script src="/2.js"></script>
<script src="/22.js"></script>
</body>
</html>