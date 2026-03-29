<?php
$pageTitle = 'Provenance Hub | Home';
include __DIR__ . '/partials/header.php';
?>

<!-- Hero Section -->
<section class="relative h-[870px] w-full flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img alt="Antique interior" class="w-full h-full object-cover" data-alt="Moody dark library interior" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBumILHXnSJar2otXC3nUrTgzn21hUsd2DEbQzOLen40C3MnsKXGMorp7nRbQg25rdYP_Y0iXGq7qF216zOa2qDCSUdU31Q6QOZHW5CVyvVvpHISAINOqMGat9dF8jPELXBAGL6ag-dn2e2C9sWyKWVfVLIP1JVoE5fZoObSsybogw7cLp-Ra9i-af5ivEVwh_0bpepkOMulb-xnghCJDLix64jDPOXd-f1yOORQlf5hRXzd9VF4OUTMHCZOBkmWoC6QUNx-xfJz_zV"/>
        <div class="absolute inset-0 bg-[#3D2B1F]/40 bg-gradient-to-r from-[#3D2B1F]/60 to-transparent"></div>
    </div>
    <div class="relative z-10 px-12 md:px-24 max-w-4xl pt-16">
        <span class="text-primary-container font-ui text-xs tracking-[0.3em] uppercase mb-4 block">Est. 1924 • Curated Heritage</span>
        <h1 class="text-white font-display text-6xl md:text-8xl leading-tight mb-8">
            Discover <br/><i class="font-headline italic">Timeless</i> Treasures
        </h1>
        <p class="text-white/80 font-body text-lg md:text-xl max-w-xl mb-12 leading-relaxed">
            Experience the prestige of historical acquisition. Our curated collection spans centuries of craftsmanship and cultural significance.
        </p>
        <div class="flex flex-wrap gap-6">
            <a href="<?= BASE_URL ?>marketplace" class="premium-gradient-btn text-white px-10 py-4 font-ui font-semibold text-sm tracking-widest uppercase hover:brightness-110 transition-all active:scale-[0.98] duration-200">
                Browse Auctions
            </a>
            <a href="<?= BASE_URL ?>page?id=learn-more" class="border border-white/30 text-white px-10 py-4 font-ui font-semibold text-sm tracking-widest uppercase hover:bg-white/10 transition-all text-center">
                Learn More
            </a>
        </div>
    </div>
</section>

<!-- Search / Filter Bar -->
<section class="max-w-7xl mx-auto -mt-12 relative z-20 px-6">
    <form action="<?= BASE_URL ?>marketplace" method="GET" class="bg-surface shadow-ambient p-8 grid grid-cols-1 md:grid-cols-4 gap-8 items-end ">
        <div class="space-y-2">
            <label class="font-ui text-[10px] uppercase tracking-widest text-outline">Category</label>
            <select name="category" class="w-full bg-transparent border-0 border-b border-outline-variant py-2 font-headline text-lg focus:ring-0 focus:border-primary">
                <option class="bg-surface text-on-surface" value="">All Collections</option>
                <option class="bg-surface text-on-surface" value="Furniture">Furniture</option>
                <option class="bg-surface text-on-surface" value="Horology">Horology</option>
                <option class="bg-surface text-on-surface" value="Fine Art">Fine Art</option>
                <option class="bg-surface text-on-surface" value="Silverware">Silverware</option>
                <option class="bg-surface text-on-surface" value="Instruments">Instruments</option>
                <option class="bg-surface text-on-surface" value="Technology">Technology</option>
                <option class="bg-surface text-on-surface" value="Accessories">Accessories</option>
                <option class="bg-surface text-on-surface" value="Ceramics">Ceramics</option>
            </select>
        </div>
        <div class="space-y-2">
            <label class="font-ui text-[10px] uppercase tracking-widest text-outline">Era</label>
            <select name="era" class="w-full bg-transparent border-0 border-b border-outline-variant py-2 font-headline text-lg focus:ring-0 focus:border-primary">
                <option class="bg-surface text-on-surface" value="">All Eras</option>
                <option class="bg-surface text-on-surface" value="18th Century">18th Century</option>
                <option class="bg-surface text-on-surface" value="19th Century">19th Century</option>
                <option class="bg-surface text-on-surface" value="Victorian">Victorian</option>
                <option class="bg-surface text-on-surface" value="Edwardian">Edwardian</option>
                <option class="bg-surface text-on-surface" value="1930s">1930s</option>
                <option class="bg-surface text-on-surface" value="Qing Dynasty">Qing Dynasty</option>
                <option class="bg-surface text-on-surface" value="Renaissance">Renaissance</option>
            </select>
        </div>
        <div class="space-y-2">
            <label class="font-ui text-[10px] uppercase tracking-widest text-outline">Price Range</label>
            <select name="price" class="w-full bg-transparent border-0 border-b border-outline-variant py-2 font-headline text-lg focus:ring-0 focus:border-primary">
                <option class="bg-surface text-on-surface" value="">Any Price</option>
                <option class="bg-surface text-on-surface" value="0-1000">Under $1,000</option>
                <option class="bg-surface text-on-surface" value="1000-5000">$1,000 - $5,000</option>
                <option class="bg-surface text-on-surface" value="5000-10000">$5,000 - $10,000</option>
                <option class="bg-surface text-on-surface" value="10000+">Over $10,000</option>
            </select>
        </div>
        <button type="submit" class="bg-primary text-white py-3.5 font-ui text-xs font-bold tracking-widest uppercase hover:bg-on-surface transition-colors w-full">
            Search
        </button>
    </form>
</section>

<!-- The Provenance Standard Section -->
<section class="max-w-7xl mx-auto py-24 px-6">
    <div class="text-center mb-16">
        <h2 class="font-display text-4xl text-on-surface mb-3">The Provenance Standard</h2>
        <p class="font-headline text-outline italic text-lg">Why distinguished collectors choose our platform</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="engagement-cards">
        <!-- Card 1 -->
        <div class="tilt-card bg-surface-container-low rounded-xl p-10 shadow-ambient overflow-hidden transition-colors border border-outline-variant/10">
            <div class="relative z-20 pointer-events-none">
                <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center mb-8 border border-primary/20">
                    <span class="material-symbols-outlined text-primary text-2xl">verified</span>
                </div>
                <h3 class="font-display text-2xl text-on-surface mb-4">Curated Excellence</h3>
                <p class="font-body text-on-surface-variant leading-relaxed text-sm">
                    Every artifact is subjected to rigorous historical verification by our panel of independent scholars before exhibition to ensure absolute authenticity.
                </p>
            </div>
        </div>
        
        <!-- Card 2 -->
        <div class="tilt-card bg-surface-container-low rounded-xl p-10 shadow-ambient overflow-hidden transition-colors border border-outline-variant/10">
            <div class="relative z-20 pointer-events-none">
                <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center mb-8 border border-primary/20">
                    <span class="material-symbols-outlined text-primary text-2xl">lock</span>
                </div>
                <h3 class="font-display text-2xl text-on-surface mb-4">Private Acquisition</h3>
                <p class="font-body text-on-surface-variant leading-relaxed text-sm">
                    A discreet, highly secure bidding environment designed exclusively for high-net-worth individuals and premier institutions worldwide.
                </p>
            </div>
        </div>
        
        <!-- Card 3 -->
        <div class="tilt-card bg-surface-container-low rounded-xl p-10 shadow-ambient overflow-hidden transition-colors border border-outline-variant/10">
            <div class="relative z-20 pointer-events-none">
                <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center mb-8 border border-primary/20">
                    <span class="material-symbols-outlined text-primary text-2xl">public</span>
                </div>
                <h3 class="font-display text-2xl text-on-surface mb-4">Global Logistics</h3>
                <p class="font-body text-on-surface-variant leading-relaxed text-sm">
                    White-glove worldwide delivery services ensuring your historical artifacts arrive with uncompromising care, climate control, and supreme security.
                </p>
            </div>
        </div>
    </div>
</section>

<style>
/* Pure Engagement Combo Styles */
.tilt-card {
    position: relative;
    transform-style: preserve-3d;
    transform: perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1);
    will-change: transform;
    /* Soft gradient animation background fallback */
    background: linear-gradient(135deg, rgba(230, 224, 212, 0.05) 0%, rgba(201, 169, 97, 0.02) 100%);
    z-index: 1;
}

/* Animated soft gradient border/glow */
.tilt-card::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: inherit;
    padding: 1.5px; /* Border width */
    background: linear-gradient(
        45deg, 
        transparent 20%, 
        rgba(201,169,97,0.3) 50%, 
        transparent 80%
    );
    background-size: 250% 250%;
    animation: gradientShift 6s ease-in-out infinite alternate;
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events: none;
    z-index: -1;
}

/* Cursor Spotlight */
.tilt-card::after {
    content: '';
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.4s ease;
    /* Radial gradient follows cursor */
    background: radial-gradient(
        600px circle at var(--mouse-x, 50%) var(--mouse-y, 50%), 
        rgba(201, 169, 97, 0.08), 
        transparent 40%
    );
    pointer-events: none;
    z-index: -1;
    border-radius: inherit;
}

.tilt-card:hover::after {
    opacity: 1;
}

@keyframes gradientShift {
    0% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.tilt-card');
    
    cards.forEach(card => {
        let bounds;
        
        const updateBounds = () => {
            bounds = card.getBoundingClientRect();
        };

        // Update bounds on resize or scroll to prevent glitches
        window.addEventListener('resize', updateBounds);
        window.addEventListener('scroll', updateBounds, { passive: true });
        
        card.addEventListener('mouseenter', () => {
            updateBounds();
            // Remove CSS transition for snappier tracking while inside
            card.style.transition = 'none';
        });

        card.addEventListener('mousemove', (e) => {
            if (!bounds) updateBounds();
            
            const mouseX = e.clientX - bounds.left;
            const mouseY = e.clientY - bounds.top;
            
            // 1. Spotlight Effect
            card.style.setProperty('--mouse-x', `${mouseX}px`);
            card.style.setProperty('--mouse-y', `${mouseY}px`);
            
            // 2. 3D Tilt Effect
            const centerX = bounds.width / 2;
            const centerY = bounds.height / 2;
            
            // Calculate tilt degrees (subtle, max ~4 degrees)
            const rotateX = ((mouseY - centerY) / centerY) * -4;
            const rotateY = ((mouseX - centerX) / centerX) * 4;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
        });

        card.addEventListener('mouseleave', () => {
            // Restore smooth transition for resetting
            card.style.transition = 'transform 0.5s cubic-bezier(0.23, 1, 0.32, 1)';
            card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
        });
    });
});
</script>
<!-- Curator's Note -->
<section class="py-32 bg-surface">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <span class="material-symbols-outlined text-primary text-4xl mb-6">auto_awesome</span>
        <h2 class="font-display text-5xl text-on-surface mb-8 italic">"Provenance is the heartbeat of every artifact. It is the story that breathes life into the inanimate."</h2>
        <div class="w-12 h-px bg-primary mx-auto mb-8"></div>
        <p class="font-body text-xl text-outline leading-relaxed max-w-2xl mx-auto">
            At Provenance Hub, we do more than auction. We preserve legacies. Every item is meticulously vetted by our historical scholars to ensure its story is told with the dignity it deserves.
        </p>
        <div class="mt-12">
            <p class="font-ui text-xs font-bold tracking-[0.3em] uppercase text-on-surface">Julian Vane-Tempest</p>
            <p class="font-headline italic text-sm text-outline">Chief Curator, Provenance Hub</p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
