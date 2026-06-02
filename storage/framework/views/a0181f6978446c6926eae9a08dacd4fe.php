<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Not Found</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('img/logo-rooterin.png')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700;900&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
</head>
<body class="bg-[#f8fafc] h-screen flex items-center justify-center p-8 font-inter">
    <div class="text-center max-w-md">
        <div class="mb-10 inline-flex items-center justify-center w-24 h-24 bg-indigo-50 rounded-3xl text-indigo-500 shadow-xl shadow-indigo-500/10">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-x"><path d="m13.5 8.5-5 5"/><path d="m8.5 8.5 5 5"/><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </div>
        <h1 class="text-6xl font-black text-slate-900 font-outfit mb-4">404</h1>
        <h2 class="text-xl font-bold text-slate-800 mb-4">Page Disappeared</h2>
        <p class="text-slate-500 text-sm leading-relaxed mb-10">The resource you are looking for might have been moved or deleted from the system ledger. Double check the URL or return home.</p>
        <a href="/dashboard" class="inline-flex items-center gap-2 px-8 py-3 bg-[#0f172a] text-white rounded-xl font-bold text-sm hover:scale-105 transition-all shadow-xl shadow-slate-900/20">
            Return to Dashboard
        </a>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/errors/404.blade.php ENDPATH**/ ?>