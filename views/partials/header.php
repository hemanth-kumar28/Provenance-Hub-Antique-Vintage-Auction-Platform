<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= htmlspecialchars($pageTitle ?? 'Provenance Hub | Antique & Vintage Auctions') ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Lora:ital,wght@0,400..700;1,400..700&family=Montserrat:wght@100..900&family=IBM+Plex+Mono:wght@300;400;500;600&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        body {
            background-color: #fdf9f0;
            color: #3D2B1F;
        }
        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24
        }
        .premium-gradient-btn {
            background: linear-gradient(to right, #745b1b, #c9a961);
        }
        .gold-gradient {
            background: linear-gradient(135deg, #745b1b 0%, #c9a961 100%)
        }


        /* Custom Antique Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #fdf9f0; }
        ::-webkit-scrollbar-thumb { background: #C9A961; border-radius: 4px; border: 2px solid #fdf9f0; }
        ::-webkit-scrollbar-thumb:hover { background: #8B6F47; }

        /* Paragraph Hover - Reveal Underline */
        p:not(.no-hover-line) {
            position: relative;
            display: inline-block;
        }
        p:not(.no-hover-line)::after {
            content: '';
            position: absolute;
            width: 0;
            height: 1px;
            bottom: -2px;
            left: 0;
            background-color: #C9A961;
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        p:not(.no-hover-line):hover::after {
            width: 100%;
        }

        /* Image Zoom + Grain Sharpen */
        img {
            transition: transform 0.6s cubic-bezier(0.2, 0.8, 0.2, 1), filter 0.6s ease;
        }
        img:hover, .group:hover img, .auction-item:hover img {
            transform: scale(1.05);
            filter: contrast(1.15) sepia(0.15) saturate(1.1);
        }

        /* Emboss/Deboss Buttons */
        button, .gold-gradient, .premium-gradient-btn {
            box-shadow: inset 1px 1px 2px rgba(255,255,255,0.4), inset -1px -1px 2px rgba(0,0,0,0.1), 0 4px 6px rgba(116, 91, 27, 0.1);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        button:hover, .gold-gradient:hover, .premium-gradient-btn:hover {
            box-shadow: inset 1px 1px 3px rgba(255,255,255,0.5), inset -1px -1px 3px rgba(0,0,0,0.15), 0 8px 12px rgba(116, 91, 27, 0.2);
            filter: brightness(1.05);
            transform: translateY(-2px);
        }
        button:active, .gold-gradient:active, .premium-gradient-btn:active {
            box-shadow: inset 2px 2px 4px rgba(0,0,0,0.3), 0 1px 2px rgba(116, 91, 27, 0.1) !important;
            transform: translateY(1px) scale(0.97) !important;
        }

        /* Form Glow on Focus */
        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 15px 2px rgba(201, 169, 97, 0.3) !important;
            border-color: #C9A961 !important;
            transform: scale(1.01);
            outline: none;
        }

        /* Smooth universal transitions */
        a, input, select, textarea, .group, .auction-item, .material-symbols-outlined {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Subtle Click Press Micro-interaction for links */
        a:not(.no-press):active {
            transform: scale(0.97) !important;
        }

        /* Navbar link animated underline */
        nav a { position: relative; }
        nav a::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -4px;
            width: 0%;
            height: 1px;
            background: #C9A961;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        nav a:hover::after { width: 100%; }

        /* Cards/Items Scaling & Soft Shadow */
        .auction-item, .bg-surface-container-lowest.group, article {
            transition: transform 0.4s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.4s ease;
        }
        .auction-item:hover, .bg-surface-container-lowest.group:hover, article:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 40px -15px rgba(61, 43, 31, 0.25) !important;
            z-index: 10;
        }

        /* Scroll Reveal Discovery System */
        .reveal-drawer {
            opacity: 0;
            transform: translateY(40px) scale(0.98);
            transition: opacity 0.8s ease-out, transform 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
            will-change: opacity, transform;
        }
        .reveal-drawer.visible {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        /* Material Ripple Effect */
        .ripple {
            position: relative;
            overflow: hidden;
        }
        .ripple .ripple-overlay {
            position: absolute;
            border-radius: 50%;
            transform: scale(0);
            animation: ripple-anim 600ms linear;
            background-color: rgba(255, 255, 255, 0.25);
            pointer-events: none;
        }
        @keyframes ripple-anim {
            to { transform: scale(4); opacity: 0; }
        }

        /* Modern Structural Logo */
        .logo-group:hover .logo-left {
            transform: translateX(-3px);
            filter: drop-shadow(0 0 8px rgba(255,255,255,0.2));
        }
        .logo-group:hover .logo-right {
            transform: translateX(3px);
            filter: drop-shadow(0 0 12px rgba(201,169,97,0.4));
        }
        .logo-group:hover .logo-slash {
            color: #FDF9F0;
            opacity: 1;
            transform: skewX(-15deg) scale(1.05);
            text-shadow: 0 0 15px rgba(201, 169, 97, 0.6);
        }
        .logo-group .logo-left, .logo-group .logo-right, .logo-group .logo-slash {
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform;
        }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-dim": "#dddad1",
                        "on-background": "#3D2B1F",
                        "on-surface": "#3D2B1F",
                        "background": "#fdf9f0",
                        "on-surface-variant": "#4d4639",
                        "surface-container": "#f1eee5",
                        "primary": "#745b1b",
                        "primary-container": "#c9a961",
                        "outline-variant": "#d0c5b4",
                        "outline": "#7f7667",
                        "surface": "#fdf9f0",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f7f3ea",
                        "surface-container-highest": "#e6e2d9",
                        "secondary": "#8B6F47",
                    },
                    fontFamily: {
                        "display": ["Playfair Display", "serif"],
                        "headline": ["Cormorant Garamond", "serif"],
                        "body": ["Lora", "serif"],
                        "ui": ["Montserrat", "sans-serif"],
                        "price": ["IBM Plex Mono", "monospace"]
                    },
                    boxShadow: {
                        "ambient": "0 24px 40px -15px rgba(77, 70, 57, 0.05)"
                    },
                    borderRadius: {
                        'none': '0',
                        'sm': '0.375rem',    // 6px (previously 2px)
                        DEFAULT: '0.625rem', // 10px (previously 4px)
                        'md': '0.875rem',    // 14px (previously 6px)
                        'lg': '1.25rem',     // 20px (previously 8px)
                        'xl': '1.75rem',     // 28px
                        'full': '9999px',
                    }
                }
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>const BASE_URL = '<?= BASE_URL ?>';</script>
</head>
<body class="bg-background text-on-surface font-body selection:bg-primary-container selection:text-on-primary-container min-h-screen relative">

    
    <header id="main-header" class="bg-[#3D2B1F]/70 backdrop-blur-md text-[#C9A961] flex justify-between items-center w-full px-6 py-4 sticky top-0 z-40 shadow-ambient transition-all duration-300 border-b border-white/5">
        <div class="flex items-center">
            <a href="<?= BASE_URL ?>" class="logo-group flex items-center no-press transition-transform duration-700 active:scale-95 hover:scale-[1.02]">
                <!-- Left Side -->
                <div class="logo-left flex flex-col justify-center text-right font-ui leading-[0.85] text-[13px] uppercase tracking-tighter">
                    <div>
                        <span class="font-light text-white/80">FINE</span> 
                        <span class="font-extrabold text-[#FDF9F0]">ART &</span>
                    </div>
                    <div>
                        <span class="font-extrabold text-[#C9A961]">RARE</span> 
                        <span class="font-light text-white/80">ANTIQUES</span>
                    </div>
                </div>
                
                <!-- Slash Divider -->
                <div class="logo-slash text-[42px] font-light mx-3 text-[#C9A961] opacity-70 transform -skew-x-[15deg] leading-none relative -top-0.5">
                    /
                </div>
                
                <!-- Right Side -->
                <div class="logo-right flex flex-col justify-center text-left font-ui leading-[0.85] uppercase">
                    <div class="text-[22px] font-black tracking-[0.02em] text-[#FDF9F0]">PROVENANCE</div>
                    <div class="text-[22px] font-black tracking-[0.41em] text-[#C9A961] relative -left-[0.02em]">HUB</div>
                </div>
            </a>
        </div>
        <nav class="hidden md:flex gap-8 items-center font-ui text-sm font-medium tracking-wide">
            <a href="<?= BASE_URL ?>marketplace" class="text-[#FDF9F0] opacity-80 hover:opacity-100 transition-opacity">Browse</a>
            <a href="<?= BASE_URL ?>dashboard" class="text-[#FDF9F0] opacity-80 hover:opacity-100 transition-opacity">Dashboard</a>
            <a href="<?= BASE_URL ?>timeline" class="text-[#FDF9F0] opacity-80 hover:opacity-100 transition-opacity">Timeline</a>
        </nav>
        <div class="flex items-center gap-8">
            <div class="relative hidden lg:block" id="search-container">
                <input type="text" id="header-search-input" placeholder="Search archives..." autocomplete="off" class="bg-[#2A1D15] border-none text-[#FDF9F0] text-sm px-4 py-2 w-64 focus:ring-1 focus:ring-[#C9A961] rounded-sm font-ui" />
                <div id="search-suggestions" class="absolute top-10 w-full bg-[#fdf9f0] border border-outline-variant rounded-sm shadow-ambient hidden z-50 max-h-96 overflow-y-auto">
                    <ul class="text-[#3D2B1F] text-sm font-ui flex flex-col"></ul>
                </div>
            </div>
            <div class="flex gap-6 items-center">
                <?php if(!empty($_SESSION['user_id'])): ?>
                    <?php if(in_array($_SESSION['role'] ?? '', ['admin', 'curator'])): ?>
                        <a href="<?= BASE_URL ?>admin" class="text-[#FDF9F0] font-ui text-xs flex items-center gap-2 opacity-80 hover:text-[#C9A961] transition-colors"><span class="material-symbols-outlined">shield</span> Admin Panel - <?= htmlspecialchars($_SESSION['username']) ?></a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>dashboard" class="text-[#FDF9F0] font-ui text-xs flex items-center gap-2 opacity-80 hover:text-[#C9A961] transition-colors"><span class="material-symbols-outlined">person</span> <?= htmlspecialchars($_SESSION['username']) ?></a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>logout" class="text-[#C9A961] font-ui text-[10px] font-bold tracking-widest uppercase hover:text-white transition-colors">Logout</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>login" class="text-[#C9A961] bg-white/10 px-4 py-2 rounded-sm font-ui text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-white/20 transition-colors">Login / Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <script>
        $(document).ready(function() {
            let searchTimeout = null;
            $('#header-search-input').on('input', function() {
                const q = $(this).val();
                clearTimeout(searchTimeout);
                
                if (q.length < 1) {
                    $('#search-suggestions').addClass('hidden');
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    $.ajax({
                        url: BASE_URL + 'api/search-suggestions',
                        method: 'GET',
                        data: { q: q },
                        success: function(resp) {
                            if(resp.success && resp.data.length > 0) {
                                let html = '';
                                resp.data.forEach(item => {
                                    html += '<li class="border-b border-outline-variant/30 last:border-0 hover:bg-[#e6e2d9] transition-colors">' +
                                        '<a href="' + BASE_URL + 'auction?id=' + item.id + '" class="flex items-center gap-3 p-3">' +
                                            '<img src="' + (item.image_url.startsWith('http') ? item.image_url : BASE_URL + item.image_url) + '" class="w-10 h-10 object-cover rounded-sm">' +
                                            '<div class="flex flex-col">' +
                                                '<span class="font-headline font-semibold text-base whitespace-nowrap overflow-hidden text-ellipsis w-40">' + item.title + '</span>' +
                                                '<span class="font-price text-xs text-primary">$' + item.current_bid.toFixed(2) + '</span>' +
                                            '</div>' +
                                        '</a>' +
                                    '</li>';
                                });
                                $('#search-suggestions ul').html(html);
                                $('#search-suggestions').removeClass('hidden');
                            } else {
                                $('#search-suggestions ul').html('<li class="p-3 text-xs opacity-70">No artifacts found.</li>');
                                $('#search-suggestions').removeClass('hidden');
                            }
                        }
                    });
                }, 300);
            });
            
            $(window).on('scroll', function() {
                const scrollY = window.scrollY;
                
                if (scrollY > 40) {
                    $('#main-header')
                        .removeClass('bg-[#3D2B1F]/70 border-white/5 py-4')
                        .addClass('bg-[#3D2B1F]/90 border-[#C9A961]/20 py-3 shadow-xl backdrop-blur-lg');
                } else {
                    $('#main-header')
                        .removeClass('bg-[#3D2B1F]/90 border-[#C9A961]/20 py-3 shadow-xl backdrop-blur-lg')
                        .addClass('bg-[#3D2B1F]/70 border-white/5 py-4 backdrop-blur-md');
                }
            });

            // Intersection Observer - Drawers Sliding
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        obs.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '0px 0px -50px 0px', threshold: 0.1 });
            
            // Apply reveal classes
            setTimeout(() => {
                $('main > section, main > div, .auction-item, article, aside').addClass('reveal-drawer').each(function() {
                    observer.observe(this);
                });
            }, 50);

            $(document).on('click', function(e) {
                if(!$(e.target).closest('#search-container').length) {
                    $('#search-suggestions').addClass('hidden');
                }
            });

            // Instant Feedback - Ripple Effect logic globally
            $(document).on('mousedown', '.gold-gradient, .premium-gradient-btn, button, .ripple-target', function(e) {
                const $btn = $(this);
                if ($btn.css('position') === 'static') $btn.css('position', 'relative');
                if (!$btn.hasClass('ripple')) $btn.addClass('ripple overflow-hidden cursor-pointer');

                const circle = $('<span class="ripple-overlay"></span>');
                const diameter = Math.max($btn.outerWidth(), $btn.outerHeight());
                const radius = diameter / 2;
                
                circle.css({ width: diameter, height: diameter, left: e.pageX - $btn.offset().left - radius, top: e.pageY - $btn.offset().top - radius });
                $btn.append(circle);
                setTimeout(() => circle.remove(), 600);
            });

            // Guided Interaction - Active Navigation States
            $(document).ready(function() {
                const currentPath = window.location.pathname.replace(/\/$/, '') + window.location.search;
                $('nav a').each(function() {
                    const linkPath = $(this).attr('href').replace(BASE_URL, '/webtech/stitch/');
                    if (currentPath.includes(linkPath) && linkPath.length > '/webtech/stitch/'.length) {
                        $(this).addClass('text-[#C9A961] opacity-100 font-semibold').removeClass('text-[#FDF9F0] opacity-80');
                        $(this).append('<div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-full h-[2px] bg-[#C9A961]"></div>');
                    }
                });
            });

            $('#header-search-input').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    const q = $(this).val().trim();
                    if (q.length > 0) {
                        window.location.href = BASE_URL + 'marketplace?q=' + encodeURIComponent(q);
                    }
                }
            });
        });
    </script>
