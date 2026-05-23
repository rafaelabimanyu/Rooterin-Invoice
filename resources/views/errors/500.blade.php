<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo-rooterin.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700;900&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-[#f8fafc] h-screen flex items-center justify-center p-8 font-inter">
    <div class="text-center max-w-md">
        <div class="mb-10 inline-flex items-center justify-center w-24 h-24 bg-amber-50 rounded-3xl text-amber-500 shadow-xl shadow-amber-500/10">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap-off"><path d="M12.41 6.75 13 2l-2.43 2.92"/><path d="M18.57 12.91 21 10h-5.34"/><path d="M8 8l-5 6h9l1 8 5-6"/><line x1="2" y1="2" x2="22" y2="22"/></svg>
        </div>
        <h1 class="text-6xl font-black text-slate-900 font-outfit mb-4">500</h1>
        <h2 class="text-xl font-bold text-slate-800 mb-4">System Interruption</h2>
        <p class="text-slate-500 text-sm leading-relaxed mb-10">The server encountered an internal ledger conflict. Our technical team has been notified. Please try again in a few moments.</p>
        <a href="/dashboard" class="inline-flex items-center gap-2 px-8 py-3 bg-[#0f172a] text-white rounded-xl font-bold text-sm hover:scale-105 transition-all shadow-xl shadow-slate-900/20">
            Retry Connection
        </a>
    </div>
</body>
</html>
