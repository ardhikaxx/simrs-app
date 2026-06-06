<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root{--simrs-primary:#0B6477;--simrs-primary-dark:#094E5C;--simrs-primary-light:#14919B;--simrs-primary-pale:#E6F4F7;--simrs-secondary:#2C3E7A;--simrs-gray-50:#F8FAFC;--simrs-gray-100:#F1F5F9;--simrs-gray-200:#E2E8F0;--simrs-gray-500:#64748B;--simrs-gray-700:#334155;--simrs-gray-900:#0F172A;--simrs-danger:#C5372C}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:linear-gradient(135deg,#f8fafc 0%,#e6f4f7 100%);color:var(--simrs-gray-700);min-height:100vh}
        .auth-shell{min-height:100vh;display:grid;grid-template-columns:1fr 460px}
        .auth-panel{background:#0b1f2e;color:white;padding:3rem;display:flex;flex-direction:column;justify-content:space-between}
        .brand-mark{width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,var(--simrs-primary-light),var(--simrs-primary));display:flex;align-items:center;justify-content:center;font-size:1.4rem;box-shadow:0 10px 30px rgba(11,100,119,.35)}
        .auth-card{align-self:center;width:100%;max-width:380px;background:white;border:1px solid var(--simrs-gray-200);border-radius:8px;padding:1.5rem;box-shadow:0 10px 30px rgba(11,100,119,.12)}
        .form-control{border:1.5px solid var(--simrs-gray-200);border-radius:8px;padding:.72rem .85rem;font-size:.88rem}
        .form-control:focus{border-color:var(--simrs-primary);box-shadow:0 0 0 3px rgba(11,100,119,.12)}
        .btn-simrs{background:var(--simrs-primary);border-color:var(--simrs-primary);color:white;border-radius:8px;font-weight:700;padding:.7rem 1rem}
        .btn-simrs:hover{background:var(--simrs-primary-dark);border-color:var(--simrs-primary-dark);color:white}
        .text-mono{font-family:'JetBrains Mono',monospace}
        @media(max-width:991.98px){.auth-shell{grid-template-columns:1fr}.auth-panel{display:none}.auth-card{margin:2rem auto}}
    </style>
</head>
<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('partials.swal')
</body>
</html>
