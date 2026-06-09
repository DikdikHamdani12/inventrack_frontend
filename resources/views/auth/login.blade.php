<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - InvenTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 h-screen flex justify-center items-center">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">InvenTrack</h1>
            <p class="text-slate-500 mt-2">Sistem Manajemen Inventori</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="#" method="POST">
            @csrf
            <div class="mb-4">
                <label for="username" class="block text-slate-700 font-medium mb-2">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Masukkan username" required autofocus>
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-slate-700 font-medium mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Masukkan password" required>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                Masuk
            </button>
        </form>

        <p class="text-center text-slate-500 mt-6 text-sm">
            &copy; {{ date('Y') }} InvenTrack. All rights reserved.<br>
            <span class="text-xs">Admin: admin/password | Staff: staff/password</span>
        </p>
    </div>
</body>
</html>
