<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Dokter</title>
</head>
<body class="h-full bg-white">
    <div class="min-h-full">
        <x-sidebar-dokter></x-sidebar-dokter>
        <main class="p-4 sm:ml-64 bg-white">
            {{ $slot }}
        </main>
        <x-footer></x-footer>
      </div>
</body>
</html>
