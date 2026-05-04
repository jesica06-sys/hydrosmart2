<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HydroSmart</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        @font-face {
            font-family: 'Poppins';
            src: url('{{ asset('font/Poppins/Poppins-Regular.ttf') }}') format('truetype');
            font-weight: 400;
        }
        @font-face {
            font-family: 'Poppins';
            src: url('{{ asset('font/Poppins/Poppins-Bold.ttf') }}') format('truetype');
            font-weight: 700;
        }
        @font-face {
            font-family: 'Inter';
            src: url('{{ asset('font/Inter/Inter-VariableFont_opsz,wght.ttf') }}') format('truetype');
        }
    </style>
</head>
<body>

    @yield('content')

</body>
</html>