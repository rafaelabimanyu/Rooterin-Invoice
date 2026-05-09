<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Unauthorized</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700;900&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-[#f8fafc] h-screen flex items-center justify-center p-8 font-inter">
    <div class="text-center max-w-md">
        <div class="mb-10 inline-flex items-center justify-center w-24 h-24 bg-rose-50 rounded-3xl text-rose-500 shadow-xl shadow-rose-500/10">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-alert"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
        </div>
        <h1 class="text-6xl font-black text-slate-900 font-outfit mb-4">403</h1>
        <h2 class="text-xl font-bold text-slate-800 mb-4">Access Restricted</h2>
        <p class="text-slate-500 text-sm leading-relaxed mb-10">You do not have the required permissions to access this module. Please contact your system administrator if you believe this is an error.</p>
        <a href="/dashboard" class="inline-flex items-center gap-2 px-8 py-3 bg-[#0f172a] text-white rounded-xl font-bold text-sm hover:scale-105 transition-all shadow-xl shadow-slate-900/20">
            Return to Dashboard
        </a>
    </div>
</body>
</html>
