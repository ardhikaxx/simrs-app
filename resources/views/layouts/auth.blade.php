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
            --simrs-primary: #0891b2;
            --simrs-primary-dark: #0e7490;
            --simrs-primary-light: #22d3ee;
            --simrs-gray-50: #f8fafc;
            --simrs-gray-200: #e2e8f0;
            --simrs-gray-700: #334155;
            --simrs-gray-900: #0f172a;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
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
            background: linear-gradient(145deg, #0f172a, #1e293b);
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
            top: -20%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(34, 211, 238, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse-glow 8s infinite alternate;
        }
        
        @keyframes pulse-glow {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.1); opacity: 1; }
        }

        .auth-form-side {
            width: 500px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            box-shadow: -20px 0 50px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .brand-logo-container {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--simrs-primary-light), var(--simrs-primary));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: white;
            box-shadow: 0 10px 25px rgba(8, 145, 178, 0.4);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }
        
        .brand-logo-container:hover {
            transform: scale(1.05) rotate(-5deg);
        }

        @media (max-width: 991px) {
            .auth-panel { display: none; }
            .auth-form-side { 
                width: 100%; 
                padding: 2rem; 
                background: white; 
            }
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
