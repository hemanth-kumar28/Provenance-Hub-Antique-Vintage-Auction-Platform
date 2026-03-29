<?php
$pageTitle = 'Provenance Hub | Marketplace';
include __DIR__ . '/partials/header.php';
?>

<main class="max-w-screen-2xl mx-auto px-8 py-12 flex flex-col lg:flex-row gap-12">
    <!-- Left Sidebar Filters -->
    <aside class="w-full lg:w-64 flex-shrink-0 space-y-10">
        <form method="GET" action="<?= BASE_URL ?>marketplace" id="filter-form" class="space-y-8">
            <div>
                <h3 class="font-display text-xl text-primary mb-4">Refine Search</h3>
                <div class="space-y-4 font-body text-sm text-on-surface" id="custom-filters">

                    <!-- Custom Category Dropdown -->
                    <div class="relative custom-dropdown" data-name="category">
                        <label class="block font-ui text-[10px] uppercase tracking-widest text-outline mb-1">Category</label>
                        <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']??'') ?>">
                        <button type="button" class="w-full bg-surface-container py-2 px-3 border border-outline-variant/30 text-on-surface flex justify-between items-center focus:outline-none focus:border-primary">
                            <span class="dropdown-text" data-default="All Collections"><?= htmlspecialchars(empty($_GET['category']) ? 'All Collections' : $_GET['category']) ?></span>
                            <span class="material-symbols-outlined text-sm pointer-events-none">expand_more</span>
                        </button>
                        <div class="absolute z-50 w-full mt-1 bg-surface border border-outline-variant/30 text-on-surface shadow-ambient hidden dropdown-menu max-h-60 overflow-y-auto">
                            <?php 
                            $categories = ['Furniture', 'Horology', 'Fine Art', 'Silverware', 'Instruments', 'Technology', 'Accessories', 'Ceramics']; 
                            ?>
                            <div class="dropdown-item px-3 py-2 cursor-pointer hover:bg-primary/10 hover:text-primary transition-colors <?= empty($_GET['category']) ? 'text-primary bg-primary/5' : '' ?>" data-value="">All Collections</div>
                            <?php foreach($categories as $cat): ?>
                            <div class="dropdown-item px-3 py-2 cursor-pointer hover:bg-primary/10 hover:text-primary transition-colors <?= ($_GET['category']??'') === $cat ? 'text-primary bg-primary/5' : '' ?>" data-value="<?= $cat ?>"><?= $cat ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- Custom Era Dropdown -->
                    <div class="relative custom-dropdown" data-name="era">
                        <label class="block font-ui text-[10px] uppercase tracking-widest text-outline mb-1">Era</label>
                        <input type="hidden" name="era" value="<?= htmlspecialchars($_GET['era']??'') ?>">
                        <button type="button" class="w-full bg-surface-container py-2 px-3 border border-outline-variant/30 text-on-surface flex justify-between items-center focus:outline-none focus:border-primary">
                            <span class="dropdown-text" data-default="All Eras"><?= htmlspecialchars(empty($_GET['era']) ? 'All Eras' : $_GET['era']) ?></span>
                            <span class="material-symbols-outlined text-sm pointer-events-none">expand_more</span>
                        </button>
                        <div class="absolute z-50 w-full mt-1 bg-surface border border-outline-variant/30 text-on-surface shadow-ambient hidden dropdown-menu max-h-60 overflow-y-auto">
                            <?php 
                            $eras = ['18th Century', '19th Century', 'Victorian', 'Edwardian', '1930s', 'Qing Dynasty', 'Renaissance']; 
                            ?>
                            <div class="dropdown-item px-3 py-2 cursor-pointer hover:bg-primary/10 hover:text-primary transition-colors <?= empty($_GET['era']) ? 'text-primary bg-primary/5' : '' ?>" data-value="">All Eras</div>
                            <?php foreach($eras as $era): ?>
                            <div class="dropdown-item px-3 py-2 cursor-pointer hover:bg-primary/10 hover:text-primary transition-colors <?= ($_GET['era']??'') === $era ? 'text-primary bg-primary/5' : '' ?>" data-value="<?= $era ?>"><?= $era ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- Custom Price Dropdown -->
                    <div class="relative custom-dropdown" data-name="price">
                        <label class="block font-ui text-[10px] uppercase tracking-widest text-outline mb-1">Price Range</label>
                        <input type="hidden" name="price" value="<?= htmlspecialchars($_GET['price']??'') ?>">
                        <button type="button" class="w-full bg-surface-container py-2 px-3 border border-outline-variant/30 text-on-surface flex justify-between items-center focus:outline-none focus:border-primary">
                            <span class="dropdown-text" data-default="Any Price">
                                <?php
                                $priceLabels = ['0-1000' => 'Under $1,000', '1000-5000' => '$1,000 - $5,000', '5000-10000' => '$5,000 - $10,000', '10000+' => 'Over $10,000'];
                                echo htmlspecialchars(isset($priceLabels[$_GET['price']??'']) ? $priceLabels[$_GET['price']] : 'Any Price');
                                ?>
                            </span>
                            <span class="material-symbols-outlined text-sm pointer-events-none">expand_more</span>
                        </button>
                        <div class="absolute z-50 w-full mt-1 bg-surface border border-outline-variant/30 text-on-surface shadow-ambient hidden dropdown-menu max-h-60 overflow-y-auto">
                            <div class="dropdown-item px-3 py-2 cursor-pointer hover:bg-primary/10 hover:text-primary transition-colors <?= empty($_GET['price']) ? 'text-primary bg-primary/5' : '' ?>" data-value="" data-label="Any Price">Any Price</div>
                            <?php foreach($priceLabels as $val => $label): ?>
                            <div class="dropdown-item px-3 py-2 cursor-pointer hover:bg-primary/10 hover:text-primary transition-colors <?= ($_GET['price']??'') === $val ? 'text-primary bg-primary/5' : '' ?>" data-value="<?= $val ?>" data-label="<?= $label ?>"><?= $label ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <noscript><button type="submit" class="mt-4 w-full bg-primary text-white py-2">Apply Filters</button></noscript>
            </div>
            <div class="h-px bg-outline-variant/30"></div>
            <a href="<?= BASE_URL ?>marketplace" class="block text-center font-ui text-[10px] tracking-widest text-outline hover:text-primary transition-colors uppercase">Clear Filters</a>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const dropdowns = document.querySelectorAll('.custom-dropdown');
                
                dropdowns.forEach(dropdown => {
                    const btn = dropdown.querySelector('button');
                    const menu = dropdown.querySelector('.dropdown-menu');
                    const input = dropdown.querySelector('input[type="hidden"]');
                    const textSpan = dropdown.querySelector('.dropdown-text');
                    const items = dropdown.querySelectorAll('.dropdown-item');
                    
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        // Close others
                        document.querySelectorAll('.dropdown-menu').forEach(m => {
                            if (m !== menu) m.classList.add('hidden');
                        });
                        menu.classList.toggle('hidden');
                    });
                    
                    items.forEach(item => {
                        item.addEventListener('click', () => {
                            const val = item.getAttribute('data-value');
                            const label = item.getAttribute('data-label') || item.textContent.trim();
                            
                            input.value = val;
                            textSpan.textContent = label;
                            
                            items.forEach(i => i.classList.remove('text-primary', 'bg-primary/5'));
                            item.classList.add('text-primary', 'bg-primary/5');
                            
                            menu.classList.add('hidden');
                            document.getElementById('filter-form').submit();
                        });
                    });
                });
                
                document.addEventListener('click', (e) => {
                    if(!e.target.closest('.custom-dropdown')) {
                        document.querySelectorAll('.dropdown-menu').forEach(menu => {
                            menu.classList.add('hidden');
                        });
                    }
                });
            });
        </script>
    </aside>

    <!-- Marketplace Content -->
    <section class="flex-grow">
        <!-- Hero Section -->
        <div class="mb-16">
            <span class="font-ui text-xs uppercase tracking-[0.3em] text-primary mb-4 block">Archive Edition</span>
            <h1 class="font-display text-5xl md:text-7xl text-on-surface mb-6 leading-[1.1]">The Curated <br/><i class="font-headline font-light italic">Marketplace</i></h1>
            <p class="font-body text-lg text-on-surface-variant max-w-2xl leading-relaxed">
                Discover timeless treasures available for immediate acquisition. Each piece has been meticulously vetted for provenance, craftsmanship, and historical significance.
            </p>
        </div>

        <!-- Items Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-16" id="marketplace-grid">
            <?php foreach($items as $item): ?>
            <article class="auction-item group relative" data-category="<?= htmlspecialchars($item['category']) ?>">
                <div class="aspect-[4/3] overflow-hidden bg-surface-container mb-6 rounded-sm relative">
                    <img alt="<?= htmlspecialchars($item['title']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="<?= str_starts_with($item['image_url'], 'http') ? htmlspecialchars($item['image_url']) : BASE_URL . htmlspecialchars($item['image_url']) ?>"/>
                    <div class="absolute top-4 left-4 flex gap-2">
                        <div class="bg-white/90 backdrop-blur px-3 py-1 flex items-center gap-1.5 shadow-ambient">
                            <span class="material-symbols-outlined text-primary text-sm">verified</span>
                            <span class="font-ui text-[10px] uppercase tracking-widest font-semibold">Verified</span>
                        </div>
                    </div>
                </div>
                <div class="px-5 pb-5">
                    <div class="flex justify-between items-start mb-2">
                        <span class="font-ui text-[10px] uppercase tracking-widest text-outline"><?= htmlspecialchars($item['era'] . ' • ' . $item['category']) ?></span>
                        <span class="font-price text-primary font-medium">$<?= number_format($item['current_bid'], 2) ?></span>
                    </div>
                    <h2 class="font-headline text-2xl text-on-surface mb-4 group-hover:text-primary transition-colors"><?= htmlspecialchars($item['title']) ?></h2>
                    <div class="flex gap-3 mt-6">
                        <button class="flex-grow py-3 gold-gradient text-white font-ui text-[10px] uppercase tracking-widest font-bold rounded-sm shadow-ambient active:scale-95 transition-transform" onclick="window.location.href='<?= BASE_URL ?>auction?id=<?= $item['id'] ?>'">View Details</button>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
