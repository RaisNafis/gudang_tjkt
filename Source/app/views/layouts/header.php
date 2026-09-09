<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Gudang Sekolah TKJ'); ?></title>
    <!-- Offline & Online Assets (Local First) & PWA Support -->
    <link rel="manifest" href="manifest.json">
    <link rel="shortcut icon" type="image/png" href="assets/img/belmoti.png">
    <meta name="theme-color" content="<?= $activeThemePalette['600'] ?? '#2e7d32'; ?>">
    <script src="assets/js/tailwind.min.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/JsBarcode.all.min.js"></script>
    <script src="assets/js/qrcode.min.js"></script>
    <script src="assets/js/html5-qrcode.min.js"></script>
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('sw.js').catch(err => console.log('SW reg error:', err));
        });
    }
    </script>
    <?php
    date_default_timezone_set('Asia/Jakarta');
    if (!function_exists('generateTailwindPaletteFromHex')) {
        function generateTailwindPaletteFromHex($hex) {
            $namedPresets = [
                'kuning' => '#eab308', 'orange' => '#ea580c', 'hijau' => '#2e7d32',
                'merah' => '#dc2626', 'biru' => '#2563eb', 'ungu' => '#7c3aed',
                'pink' => '#e11d48', 'cyan' => '#0891b2'
            ];
            $key = strtolower($hex);
            if (isset($namedPresets[$key])) {
                $hex = $namedPresets[$key];
            }
            $cleanHex = ltrim($hex, '#');
            if (strlen($cleanHex) === 3) {
                $cleanHex = $cleanHex[0].$cleanHex[0].$cleanHex[1].$cleanHex[1].$cleanHex[2].$cleanHex[2];
            }
            if (strlen($cleanHex) !== 6 || !ctype_xdigit($cleanHex)) {
                $cleanHex = 'eab308'; // Default yellow
            }

            $r = hexdec(substr($cleanHex, 0, 2)) / 255;
            $g = hexdec(substr($cleanHex, 2, 2)) / 255;
            $b = hexdec(substr($cleanHex, 4, 2)) / 255;

            $max = max($r, $g, $b);
            $min = min($r, $g, $b);
            $l = ($max + $min) / 2;
            $h = 0; $s = 0;

            if ($max !== $min) {
                $d = $max - $min;
                $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
                switch ($max) {
                    case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
                    case $g: $h = ($b - $r) / $d + 2; break;
                    case $b: $h = ($r - $g) / $d + 4; break;
                }
                $h /= 6;
            }

            $steps = [
                '50' => 0.95, '100' => 0.88, '200' => 0.78, '300' => 0.65,
                '400' => 0.52, '500' => 0.42, '600' => 0.32, '700' => 0.24,
                '800' => 0.16, '900' => 0.10
            ];

            $palette = [];
            foreach ($steps as $shade => $targetL) {
                $q = $targetL < 0.5 ? $targetL * (1 + $s) : $targetL + $s - $targetL * $s;
                $p = 2 * $targetL - $q;

                $rVal = round(hue2rgb_local($p, $q, $h + 1/3) * 255);
                $gVal = round(hue2rgb_local($p, $q, $h) * 255);
                $bVal = round(hue2rgb_local($p, $q, $h - 1/3) * 255);

                $palette[$shade] = sprintf('#%02x%02x%02x', $rVal, $gVal, $bVal);
            }
            $palette['600'] = '#' . $cleanHex;
            return $palette;
        }

        function hue2rgb_local($p, $q, $t) {
            if ($t < 0) $t += 1;
            if ($t > 1) $t -= 1;
            if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
            if ($t < 1/2) return $q;
            if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
            return $p;
        }
    }

    $rawColor = $user['warna_tema'] ?? '';
    if (empty($rawColor)) {
        $jurusanName = strtolower($user['nama_jurusan'] ?? '');
        if (strpos($jurusanName, 'tkj') !== false || strpos($jurusanName, 'dkv') !== false) $rawColor = '#eab308';
        elseif (strpos($jurusanName, 'tkr') !== false || strpos($jurusanName, 'dpib') !== false || strpos($jurusanName, 'tkp') !== false) $rawColor = '#ea580c';
        elseif (strpos($jurusanName, 'elind') !== false || strpos($jurusanName, 'titl') !== false) $rawColor = '#2e7d32';
        elseif (strpos($jurusanName, 'pm') !== false || strpos($jurusanName, 'las') !== false) $rawColor = '#dc2626';
        else $rawColor = '#2e7d32';
    }

    $activeThemePalette = generateTailwindPaletteFromHex($rawColor);
    ?>
    <script>
        // Instant theme init: Default to Dark Mode
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme !== 'light') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        sage: <?= json_encode($activeThemePalette); ?>
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar styling - Dynamic White & Dark Mode */
        html {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
        }
        html.dark {
            scrollbar-color: #333333 #0a0a0a;
        }

        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark ::-webkit-scrollbar-track,
        html.dark ::-webkit-scrollbar-track {
            background: #0a0a0a;
        }
        .dark ::-webkit-scrollbar-thumb,
        html.dark ::-webkit-scrollbar-thumb {
            background: #333333;
            border-radius: 9999px;
        }
        .dark ::-webkit-scrollbar-thumb:hover,
        html.dark ::-webkit-scrollbar-thumb:hover {
            background: #525252;
        }

        /* Lightweight Fast Fade-In-Up Page & Tab Transition */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            will-change: opacity, transform;
        }

        /* =========================================================
           ULTRA-SMOOTH EXPAND & DROPDOWN TRANSITIONS (Apple/Linear Easing)
           ========================================================= */
        .smooth-dropdown-popover {
            transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.22s cubic-bezier(0.16, 1, 0.3, 1),
                        visibility 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            transform-origin: top right;
            will-change: opacity, transform, visibility;
        }
        .smooth-dropdown-popover.popover-closed {
            opacity: 0 !important;
            transform: scale(0.95) translateY(-6px) !important;
            pointer-events: none !important;
            visibility: hidden !important;
        }
        .smooth-dropdown-popover.popover-open {
            opacity: 1 !important;
            transform: scale(1) translateY(0) !important;
            pointer-events: auto !important;
            visibility: visible !important;
        }

        /* Smooth Accordion Body & Chevron Rotation */
        .smooth-accordion-body {
            transition: max-height 0.38s cubic-bezier(0.16, 1, 0.3, 1),
                        opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.38s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: max-height, opacity, transform;
        }
        .accordion-chevron {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .accordion-chevron.rotate-90 {
            transform: rotate(90deg) !important;
        }
        #topHeaderProfileArrow {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        #topHeaderProfileArrow.rotate-180 {
            transform: rotate(180deg) !important;
        }

        /* Disable background controls when modal is open */
        body.modal-open {
            overflow: hidden !important;
        }
        body.modal-open #mainSidebar,
        body.modal-open header,
        body.modal-open main {
            pointer-events: none !important;
            user-select: none !important;
            filter: blur(1px);
            transition: filter 0.2s ease;
        }

        /* =========================================================
           OLED FULL DARK THEME (#000000 Pure Black + Soft Emerald)
           ========================================================= */
        html.dark body {
            background-color: #000000 !important;
            color: #d4d4d4 !important;
        }

        /* Top Header, Sidebar, Footer, Cards */
        html.dark header,
        html.dark #mainSidebar,
        html.dark footer,
        html.dark .bg-white {
            background-color: #0a0a0a !important;
            border-color: #262626 !important;
            color: #f5f5f5 !important;
        }

        /* Subtle Dark Containers & Card Inner Boxes */
        html.dark .bg-slate-50,
        html.dark .bg-slate-100,
        html.dark .bg-sage-50,
        html.dark .bg-sage-50\/50,
        html.dark .bg-sage-50\/60,
        html.dark .bg-sage-50\/80 {
            background-color: #121212 !important;
            border-color: #262626 !important;
            color: #d4d4d4 !important;
        }

        /* Dynamic Badges & Accents in Full Dark Mode */
        html.dark .bg-sage-100 {
            background-color: <?= $activeThemePalette['900'] ?? '#0d3810'; ?> !important;
            color: <?= $activeThemePalette['400'] ?? '#81c784'; ?> !important;
            border-color: <?= $activeThemePalette['600'] ?? '#2e7d32'; ?> !important;
        }
        html.dark .bg-sage-600,
        html.dark .bg-emerald-100 {
            background-color: <?= $activeThemePalette['600'] ?? '#2e7d32'; ?> !important;
            color: #ffffff !important;
        }
        html.dark .text-emerald-600 {
            color: #ffffff !important;
        }
        html.dark .text-sage-700,
        html.dark .text-sage-800,
        html.dark .text-sage-600 {
            color: <?= $activeThemePalette['400'] ?? '#81c784'; ?> !important;
        }

        /* Text Hierarchy for Full Black Contrast */
        html.dark .text-slate-800,
        html.dark .text-slate-700 {
            color: #ffffff !important;
        }
        html.dark .text-slate-600,
        html.dark .text-slate-500 {
            color: #a3a3a3 !important;
        }
        html.dark .text-slate-400 {
            color: #737373 !important;
        }

        /* Custom Modern Checkbox Styling for Light & Dark Mode */
        input[type="checkbox"] {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            width: 1.1rem !important;
            height: 1.1rem !important;
            border-radius: 0.375rem !important;
            border: 1.5px solid #cbd5e1 !important;
            background-color: #ffffff !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            position: relative !important;
            transition: all 0.15s ease-in-out !important;
            outline: none !important;
            vertical-align: middle !important;
        }
        input[type="checkbox"]:hover {
            border-color: <?= $activeThemePalette['600'] ?? '#d97706'; ?> !important;
        }
        input[type="checkbox"]:checked {
            background-color: <?= $activeThemePalette['600'] ?? '#d97706'; ?> !important;
            border-color: <?= $activeThemePalette['600'] ?? '#d97706'; ?> !important;
        }
        input[type="checkbox"]:checked::after {
            content: '' !important;
            width: 0.35rem !important;
            height: 0.6rem !important;
            border: solid #ffffff !important;
            border-width: 0 2px 2px 0 !important;
            transform: translate(-50%, -55%) rotate(45deg) !important;
            position: absolute !important;
            top: 45% !important;
            left: 50% !important;
            box-shadow: none !important;
        }

        /* Date & DateTime Picker Indicator Styling */
        input[type="date"], input[type="datetime-local"] {
            cursor: pointer !important;
        }
        ::-webkit-calendar-picker-indicator {
            cursor: pointer !important;
            filter: invert(0.4) !important;
            opacity: 0.8 !important;
            transition: opacity 0.15s ease !important;
        }
        ::-webkit-calendar-picker-indicator:hover {
            opacity: 1 !important;
        }
        html.dark ::-webkit-calendar-picker-indicator {
            filter: invert(1) !important;
            opacity: 0.9 !important;
        }

        /* Inputs, Selects, Textareas in Dark Mode */
        html.dark input:not([type="submit"]):not([type="button"]):not([type="checkbox"]):not([type="radio"]),
        html.dark select,
        html.dark textarea {
            background-color: #000000 !important;
            border-color: #262626 !important;
            color: #ffffff !important;
        }
        html.dark input:focus,
        html.dark select:focus,
        html.dark textarea:focus {
            border-color: <?= $activeThemePalette['600'] ?? '#2e7d32'; ?> !important;
        }
        html.dark input::placeholder,
        html.dark textarea::placeholder {
            color: #737373 !important;
        }

        /* Dark Mode Custom Checkbox */
        html.dark input[type="checkbox"] {
            background-color: #171717 !important;
            border-color: #404040 !important;
        }
        html.dark input[type="checkbox"]:hover {
            border-color: <?= $activeThemePalette['400'] ?? '#fbbf24'; ?> !important;
        }
        html.dark input[type="checkbox"]:checked {
            background-color: <?= $activeThemePalette['600'] ?? '#d97706'; ?> !important;
            border-color: <?= $activeThemePalette['600'] ?? '#d97706'; ?> !important;
        }
        html.dark input[type="checkbox"]:checked::after {
            border-color: #ffffff !important;
        }

        /* Data Tables */
        html.dark table thead {
            background-color: #171717 !important;
            color: #a3a3a3 !important;
            border-color: #262626 !important;
        }
        html.dark table tbody tr:hover {
            background-color: #171717 !important;
        }
        html.dark table tbody td {
            border-color: #1c1c1c !important;
        }

        /* Clean Dark Borders Everywhere */
        html.dark .border-sage-100,
        html.dark .border-sage-200,
        html.dark .border-sage-200\/80,
        html.dark .border-slate-100,
        html.dark .border-slate-200,
        html.dark .divide-slate-100 > :not([hidden]) ~ :not([hidden]),
        html.dark .divide-slate-200 > :not([hidden]) ~ :not([hidden]) {
            border-color: #262626 !important;
        }

        /* GPU Hardware Acceleration & Smooth 60fps Rendering */
        html {
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .tab-content,
        .modal-content,
        #mainSidebar,
        .liquid-glass-nav {
            will-change: transform, opacity;
            backface-visibility: hidden;
        }
        table tr {
            contain: content;
        }

        /* Fluid Theme Mode Transitions */
        body, header, aside, main, .bg-white, .bg-sage-50, .border, tr, td, th {
            transition: background-color 0.25s ease-in-out, border-color 0.25s ease-in-out, color 0.25s ease-in-out;
        }

        /* Shimmer Skeleton Loader */
        .skeleton-shimmer {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 0.75rem;
        }
        html.dark .skeleton-shimmer {
            background: linear-gradient(90deg, #171717 25%, #262626 50%, #171717 75%);
            background-size: 200% 100%;
        }
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
    <script>
    function toggleTheme() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        updateThemeToggleIcons(isDark);
        showToast(isDark ? 'Mode Gelap (Dark Mode) aktif' : 'Mode Terang (Light Mode) aktif', 'success');
        if (typeof initInventoryChart === 'function') {
            setTimeout(initInventoryChart, 100);
        }
    }

    function updateThemeToggleIcons(isDark) {
        const sunIcons = document.querySelectorAll('.themeSunIcon');
        const moonIcons = document.querySelectorAll('.themeMoonIcon');
        sunIcons.forEach(icon => isDark ? icon.classList.remove('hidden') : icon.classList.add('hidden'));
        moonIcons.forEach(icon => isDark ? icon.classList.add('hidden') : icon.classList.remove('hidden'));
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            updateThemeToggleIcons(document.documentElement.classList.contains('dark'));
        });
    } else {
        updateThemeToggleIcons(document.documentElement.classList.contains('dark'));
    }
    function showToast(message, type = 'success') {
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'fixed top-5 right-5 z-[100] flex flex-col gap-2.5 max-w-sm w-full pointer-events-none';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `pointer-events-auto p-4 rounded-2xl shadow-xl border flex items-center gap-3 transition-all duration-300 transform translate-x-10 opacity-0 ${
            type === 'success' 
                ? 'bg-white border-emerald-200 text-slate-800' 
                : 'bg-white border-red-200 text-slate-800'
        }`;

        const iconHtml = type === 'success'
            ? `<div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
               </div>`
            : `<div class="w-8 h-8 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
               </div>`;

        toast.innerHTML = `
            ${iconHtml}
            <div class="flex-1 text-xs">
                <p class="font-bold ${type === 'success' ? 'text-emerald-700' : 'text-red-700'}">${type === 'success' ? 'Berhasil' : 'Pemberitahuan'}</p>
                <p class="text-slate-600 mt-0.5">${message}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('translate-x-10', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        }, 10);

        setTimeout(() => {
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-10', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    <?php
    $globalFlashMsg = getFlash();
    if ($globalFlashMsg):
    ?>
    document.addEventListener('DOMContentLoaded', () => {
        showToast(<?= json_encode($globalFlashMsg['message']); ?>, <?= json_encode($globalFlashMsg['type']); ?>);
    });
    <?php endif; ?>
    </script>
</head>
<body class="bg-sage-50/60 font-sans h-full overflow-hidden">
<div id="toastContainer" class="fixed top-5 right-5 z-[100] flex flex-col gap-2.5 max-w-sm w-full pointer-events-none"></div>
