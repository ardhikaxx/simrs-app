<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - {{ config('app.name') }}</title>
    
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- CSS Frameworks -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --simrs-primary: #0D9488;
            --simrs-primary-dark: #0F766E;
            --simrs-primary-light: #2DD4BF;
            --simrs-gray-50: #F8FAFC;
            --simrs-gray-200: #E2E8F0;
            --simrs-gray-700: #334155;
            --simrs-gray-900: #0F172A;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F8FAFC;
            color: var(--simrs-gray-700);
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        .auth-shell {
            min-height: 100vh;
            display: flex;
        }

        .auth-panel {
            flex: 1;
            background: #0F172A;
            color: white;
            padding: 5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .auth-panel::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(13, 148, 136, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .auth-form-side {
            width: 500px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            box-shadow: -10px 0 50px rgba(0,0,0,0.05);
            z-index: 10;
        }

        .brand-logo-container {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--simrs-primary-light), var(--simrs-primary));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 10px 25px rgba(13, 148, 136, 0.3);
            margin-bottom: 2rem;
        }

        @media (max-width: 991px) {
            .auth-panel { display: none; }
            .auth-form-side { width: 100%; padding: 2rem; }
        }
    </style>
    @yield('styles')
</head>
<body>
    @yield('content')
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('partials.swal')
</body>
</html>
