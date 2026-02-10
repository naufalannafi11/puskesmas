<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="text-center">
    <h1 class="text-4xl font-bold py-3 mb-4">Selamat Datang di Sistem Puskesmas Wonokerto</h1>
    <a href="{{ route('login') }}"
       class="bg-green-600 text-white px-6 py-2 rounded">
        Login
    </a>
</div>

</body>
</html>
