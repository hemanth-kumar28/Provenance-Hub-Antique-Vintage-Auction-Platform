<?php
$pageTitle = 'Provenance Hub | ' . htmlspecialchars($auction['title']);
include __DIR__ . '/partials/header.php';
$specifications = json_decode($auction['specifications'], true) ?? [];
?>
<main class="max-w-7xl mx-auto px-6 py-12">
    <!-- Breadcrumbs -->
    <nav class="flex gap-2 text-xs font-ui uppercase tracking-widest text-on-surface-variant/60 mb-8">
        <a class="hover:text-primary transition-colors" href="<?= BASE_URL ?>marketplace">Catalogue</a>
        <span>/</span>
        <a class="hover:text-primary transition-colors" href="<?= BASE_URL ?>marketplace?category=<?= urlencode($auction['category']) ?>"><?= htmlspecialchars($auction['category']) ?></a>
        <span>/</span>
        <span class="text-on-surface-variant"><?= htmlspecialchars($auction['title']) ?></span>
    </nav>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <!-- Left Column: Image Gallery -->
        <div class="lg:col-span-7 flex flex-col gap-6">
            <div class="relative aspect-[4/3] bg-surface-container-low overflow-hidden rounded-sm group cursor-crosshair">
                <img id="main-product-image" alt="<?= htmlspecialchars($auction['title']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="<?= str_starts_with($auction['image_url'], 'http') ? htmlspecialchars($auction['image_url']) : BASE_URL . htmlspecialchars($auction['image_url']) ?>"/>
                <div class="absolute top-4 right-4 bg-background/80 backdrop-blur-sm px-3 py-1 text-[10px] font-ui uppercase tracking-widest">
                    Lot #<?= htmlspecialchars($auction['lot_number']) ?>
                </div>
            </div>
        </div>
        <!-- Right Column: Bidding Action -->
        <div class="lg:col-span-5 flex flex-col gap-8">
            <section>
                <h1 class="font-display text-5xl text-on-surface mb-2 leading-tight"><?= htmlspecialchars($auction['title']) ?></h1>
                <p class="font-headline text-xl italic text-on-surface-variant/80"><?= htmlspecialchars($auction['era']) ?> - <?= htmlspecialchars($auction['category']) ?></p>
            </section>
            
            <div class="bg-surface-container-lowest p-8 rounded-sm shadow-[0_40px_60px_-15px_rgba(77,70,57,0.06)] relative overflow-hidden bid-container">
                <div class="flex justify-between items-end mb-6">
                    <div>
                        <p class="font-ui text-[10px] uppercase tracking-[0.2em] text-on-surface-variant/60 mb-1">Current Highest Bid</p>
                        <span class="font-price text-4xl text-primary font-medium current-price">$<?= number_format($auction['current_bid'], 2) ?></span>
                    </div>
                    <div class="text-right">
                        <p class="font-ui text-[10px] uppercase tracking-[0.2em] text-on-surface-variant/60 mb-1">Time Remaining</p>
                        <span class="font-price text-xl text-on-surface"><?= htmlspecialchars($auction['ends_at']) ?></span>
                    </div>
                </div>
                
                <div class="mb-8">
                    <div class="flex justify-between text-[10px] font-ui uppercase tracking-widest text-on-surface-variant/50 mb-2">
                        <span>Reserve <?= $auction['current_bid'] >= $auction['reserve_price'] ? 'Met' : 'Not Met' ?></span>
                        <span>Est: $<?= number_format($auction['starting_price']) ?> - $<?= number_format($auction['reserve_price']) ?></span>
                    </div>
                    <div class="h-[2px] w-full bg-surface-container-highest">
                        <div class="h-full gold-gradient" style="width: <?= min(100, max(0, ($auction['current_bid'] - $auction['starting_price']) / max(1, $auction['reserve_price'] - $auction['starting_price']) * 100)) ?>%;"></div>
                    </div>
                </div>
                
                <?php if($auction['status'] === 'active'): ?>
                <div class="flex flex-col gap-4">
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-price text-on-surface-variant/40">$</span>
                        <input class="w-full bg-transparent border-b border-outline-variant py-4 pl-8 focus:border-primary transition-colors font-price outline-none text-lg bid-input" placeholder="Enter bid amount" type="number" min="<?= $auction['current_bid'] > 0 ? $auction['current_bid'] * 1.05 : $auction['starting_price'] ?>"/>
                    </div>
                    <?php if(!empty($_SESSION['user_id'])): ?>
                        <?php if(in_array($_SESSION['role'] ?? '', ['admin', 'curator'])): ?>
                        <div class="block text-center w-full bg-[#3D2B1F] text-on-surface-variant font-ui font-bold uppercase tracking-[0.2em] py-5 text-sm shadow-ambient cursor-not-allowed opacity-50">
                            Curators Cannot Bid
                        </div>
                        <?php else: ?>
                        <button data-auction-id="<?= $auction['id'] ?>" class="place-bid-btn flex items-center justify-center gap-2 w-full gold-gradient text-white font-ui font-bold uppercase tracking-[0.2em] py-5 text-sm active:scale-[0.98] transition-all hover:brightness-110 shadow-ambient shadow-primary/20">
                            <span class="material-symbols-outlined text-[18px]">gavel</span> Place Bid
                        </button>
                        <?php endif; ?>
                    <?php else: ?>
                    <a href="<?= BASE_URL ?>login" class="block text-center w-full bg-[#3D2B1F] text-[#C9A961] font-ui font-bold uppercase tracking-[0.2em] py-5 text-sm active:scale-[0.98] transition-all hover:bg-primary shadow-ambient shadow-primary/20">
                        Login to Bid
                    </a>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="p-4 bg-outline-variant/30 text-center font-ui text-sm font-bold tracking-widest uppercase">
                    Auction <?= htmlspecialchars($auction['status']) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Details Sections: Bento Grid Style -->
    <div class="mt-24 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Provenance History -->
        <div class="lg:col-span-2 bg-surface-container-low p-10 rounded-sm">
            <h3 class="font-ui text-xs font-bold uppercase tracking-[0.3em] text-primary mb-8">Provenance History</h3>
            <p class="font-body italic text-lg text-on-surface leading-relaxed">
                <?= nl2br(htmlspecialchars($auction['provenance_history'] ?? 'No provenance history provided.')) ?>
            </p>
        </div>
        
        <!-- Item Details -->
        <div class="bg-surface p-10 rounded-sm flex flex-col gap-8">
            <div>
                <h3 class="font-ui text-xs font-bold uppercase tracking-[0.3em] text-primary mb-6">Specifications</h3>
                <ul class="space-y-4">
                    <?php foreach($specifications as $key => $val): ?>
                    <li class="flex justify-between items-baseline pb-2 border-b border-outline-variant/30 last:border-0">
                        <span class="font-ui text-[10px] uppercase tracking-widest text-on-surface-variant"><?= htmlspecialchars($key) ?></span>
                        <span class="font-headline text-sm"><?= htmlspecialchars($val) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h3 class="font-ui text-xs font-bold uppercase tracking-[0.3em] text-primary mb-4">Condition Report</h3>
                <p class="font-body text-sm leading-relaxed text-on-surface-variant">
                    <?= nl2br(htmlspecialchars($auction['condition_report'] ?? 'No condition report provided.')) ?>
                </p>
            </div>
        </div>
    </div>
    
    <!-- History -->
    <div class="mt-12 grid grid-cols-1 lg:grid-cols-2 gap-12">
        <section>
            <h3 class="font-ui text-xs font-bold uppercase tracking-[0.3em] text-primary mb-8 flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">history</span>
                Recent Bidding History
            </h3>
            <div class="overflow-hidden bg-surface-container-low/50 rounded-sm">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-container-high/50 font-ui text-[10px] uppercase tracking-widest text-on-surface-variant">
                            <th class="px-6 py-4">Bidder</th>
                            <th class="px-6 py-4">Amount</th>
                            <th class="px-6 py-4 text-right">Time</th>
                        </tr>
                    </thead>
                    <tbody id="bids-table-body" class="font-price text-xs divide-y divide-outline-variant/10">
                        <?php if(empty($bids)): ?>
                        <tr><td colspan="3" class="px-6 py-4 text-center text-on-surface-variant/60">No bids placed yet.</td></tr>
                        <?php endif; ?>
                        <?php foreach($bids as $bid): ?>
                        <tr class="hover:bg-surface-container transition-colors group">
                            <td class="px-6 py-4 text-on-surface font-medium"><?= htmlspecialchars(substr($bid['username'], 0, 3)) ?>***</td>
                            <td class="px-6 py-4 text-primary font-bold">$<?= number_format($bid['amount'], 2) ?></td>
                            <td class="px-6 py-4 text-right text-on-surface-variant/60"><?= htmlspecialchars($bid['created_at']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- Image Overlay Modal -->
    <div id="image-overlay" class="fixed inset-0 z-[100] bg-black/95 hidden items-center justify-center p-4 backdrop-blur-sm cursor-zoom-out transition-opacity duration-300 opacity-0">
        <img id="overlay-image" src="" class="max-w-full max-h-full object-contain rounded-md shadow-2xl transition-transform duration-500 scale-95" alt="Full Screen Image">
        <button id="close-overlay" class="absolute top-6 right-6 text-white/70 hover:text-primary transition-colors cursor-pointer">
            <span class="material-symbols-outlined text-4xl">close</span>
        </button>
    </div>
</main>

<script>
$(document).ready(function() {
    const auctionId = <?= (int)$auction['id'] ?>;
    
    // Image Overlay Logic
    const overlay = $('#image-overlay');
    const overlayImg = $('#overlay-image');
    const closeBtn = $('#close-overlay');
    const mainImg = $('#main-product-image');

    mainImg.on('click', function() {
        overlayImg.attr('src', $(this).attr('src'));
        overlay.removeClass('hidden').addClass('flex');
        // Trigger reflow
        overlay[0].offsetHeight;
        overlay.removeClass('opacity-0').addClass('opacity-100');
        overlayImg.removeClass('scale-95').addClass('scale-100');
        $('body').css('overflow', 'hidden');
    });

    const closeOverlay = () => {
        overlay.removeClass('opacity-100').addClass('opacity-0');
        overlayImg.removeClass('scale-100').addClass('scale-95');
        setTimeout(() => {
            overlay.removeClass('flex').addClass('hidden');
            $('body').css('overflow', '');
        }, 300);
    };

    closeBtn.on('click', closeOverlay);
    overlay.on('click', function(e) {
        if (e.target === this) closeOverlay();
    });

    // Dynamic Bidding History Polling
    const tbody = $('#bids-table-body');
    const currentPriceEl = $('.current-price');
    const numericFormatter = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' });

    setInterval(function() {
        $.ajax({
            url: '<?= BASE_URL ?>api/auction-bids',
            data: { id: auctionId },
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    // Update current price
                    currentPriceEl.text(numericFormatter.format(res.current_bid));
                    
                    // Rebuild rows
                    tbody.empty();
                    if (res.bids.length === 0) {
                        tbody.append('<tr><td colspan="3" class="px-6 py-4 text-center text-on-surface-variant/60">No bids placed yet.</td></tr>');
                    } else {
                        res.bids.forEach(bid => {
                            const formattedAmount = numericFormatter.format(bid.amount);
                            const row = `
                                <tr class="hover:bg-surface-container transition-colors group">
                                    <td class="px-6 py-4 text-on-surface font-medium">${bid.username}</td>
                                    <td class="px-6 py-4 text-primary font-bold">${formattedAmount}</td>
                                    <td class="px-6 py-4 text-right text-on-surface-variant/60">${bid.created_at}</td>
                                </tr>
                            `;
                            tbody.append(row);
                        });
                    }
                }
            }
        });
    }, 5000);
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
