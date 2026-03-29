<?php
$pageTitle = 'Provenance Hub | My Dashboard';
include __DIR__ . '/partials/header.php';
?>

<div class="flex min-h-[calc(100vh-72px)]">
    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-surface-container hidden md:block py-10 px-6 border-r-0 shadow-none">
        <div class="space-y-1">
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg <?= $_SERVER['REQUEST_URI'] === '/dashboard' ? 'bg-primary-container text-white font-semibold' : 'hover:bg-surface-container-high text-on-surface-variant transition-all' ?> font-ui" href="<?= BASE_URL ?>dashboard">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg <?= strpos($_SERVER['REQUEST_URI'], 'dashboard/bids') !== false ? 'bg-primary-container text-white font-semibold' : 'hover:bg-surface-container-high text-on-surface-variant transition-all' ?> font-ui" href="<?= BASE_URL ?>dashboard/bids">
                <span class="material-symbols-outlined">gavel</span>
                My Bids
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg <?= strpos($_SERVER['REQUEST_URI'], 'profile') !== false ? 'bg-primary-container text-white font-semibold' : 'hover:bg-surface-container-high text-on-surface-variant transition-all' ?> font-ui" href="<?= BASE_URL ?>profile">
                <span class="material-symbols-outlined">person</span>
                Profile Settings
            </a>
            <?php if(isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'curator'])): ?>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-red-900 overflow-hidden text-red-500 font-ui transition-all mt-8 border border-red-500/30" href="<?= BASE_URL ?>admin">
                <span class="material-symbols-outlined">shield</span>
                Admin Panel
            </a>
            <?php endif; ?>
        </div>
        <div class="mt-20 p-4 bg-surface-container-low rounded-lg ">
            <p class="text-[10px] font-ui uppercase tracking-widest text-outline mb-2">Curator Level</p>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">verified</span>
                <span class="font-display text-lg text-primary"><?= ucfirst(htmlspecialchars($user['role'])) ?></span>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-6 md:p-12 max-w-7xl mx-auto w-full">
        <!-- User Header & Quick Stats -->
        <section class="mb-12">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 pb-12 ">
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-lg bg-on-surface text-surface-container flex items-center justify-center font-display text-5xl">
                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                        </div>
                    </div>
                    <div>
                        <h1 class="font-display text-4xl md:text-5xl text-on-surface font-bold tracking-tight"><?= htmlspecialchars($user['username']) ?></h1>
                        <p class="font-body italic text-on-surface-variant text-lg">Member since <?= date('F Y', strtotime($user['created_at'])) ?></p>
                    </div>
                </div>
                <!-- Quick Stats -->
                <div class="flex gap-4 md:gap-8">
                    <div class="text-center px-4">
                        <p class="font-price text-3xl font-bold text-primary"><?= $activeBidsCount ?></p>
                        <p class="font-ui text-[10px] uppercase tracking-wider text-outline">Active Bids</p>
                    </div>
                    <div class="w-px h-12 bg-outline-variant/30 hidden md:block self-center"></div>
                    <div class="text-center px-4">
                        <p class="font-price text-3xl font-bold text-primary">0</p>
                        <p class="font-ui text-[10px] uppercase tracking-wider text-outline">Won Auctions</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Left Column: Active Bids -->
            <div class="lg:col-span-2 space-y-10">
                <header class="flex justify-between items-center">
                    <h2 class="font-display text-2xl font-bold border-l-4 border-primary pl-4 uppercase tracking-tighter">Current Engagements</h2>
                </header>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php if (empty($activeEngagements)): ?>
                        <p class="font-ui text-sm text-outline">You have no active bids.</p>
                    <?php endif; ?>
                    <?php foreach($activeEngagements as $eng): ?>
                    <!-- Active Bid Card -->
                    <div class="bg-surface-container-lowest group overflow-hidden relative shadow-ambient hover:shadow-ambient transition-all duration-500">
                        <div class="aspect-[4/3] overflow-hidden">
                            <img alt="<?= htmlspecialchars($eng['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="<?= str_starts_with($eng['image_url'], 'http') ? htmlspecialchars($eng['image_url']) : BASE_URL . htmlspecialchars($eng['image_url']) ?>"/>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-ui text-[10px] uppercase tracking-widest text-primary font-bold">Lot #<?= htmlspecialchars($eng['lot_number']) ?></span>
                                <span class="flex items-center gap-1 text-red-600 text-[10px] font-price">
                                    <span class="material-symbols-outlined text-xs">timer</span> Ends <?= date('M d, H:i', strtotime($eng['ends_at'])) ?>
                                </span>
                            </div>
                            <h3 class="font-display text-xl mb-4 group-hover:text-primary transition-colors"><?= htmlspecialchars($eng['title']) ?></h3>
                            <div class="flex justify-between items-end pt-4">
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <span class="font-ui text-[10px] uppercase text-outline shrink-0">Your Max Bid</span>
                                    <span class="font-price text-2xl md:text-3xl text-primary font-bold leading-none shrink-0">$<?= number_format($eng['my_bid'], 2) ?></span>
                                </div>
                                <?php if($eng['my_bid'] < $eng['current_bid']): ?>
                                <a href="<?= BASE_URL ?>auction?id=<?= $eng['id'] ?>" class="bg-red-600 text-white px-4 py-2 text-xs font-ui uppercase tracking-widest rounded-sm hover:shadow-ambient transition-all shrink-0">Outbid</a>
                                <?php else: ?>
                                <span class="bg-green-600 text-white px-4 py-2 text-xs font-ui uppercase tracking-widest rounded-sm shrink-0">Winning</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-10">
                <section class="bg-[#3D2B1F] text-[#FDF9F0] p-8 shadow-ambient relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 opacity-10">
                        <span class="material-symbols-outlined text-9xl">notifications_active</span>
                    </div>
                    <h2 class="font-display text-xl font-bold mb-8 relative z-10 text-[#C9A961]">Curator Alerts</h2>
                    <div class="space-y-6 relative z-10">
                        <?php foreach($activeEngagements as $eng): ?>
                            <?php if($eng['my_bid'] < $eng['current_bid']): ?>
                            <div class="flex gap-4">
                                <div class="w-2 h-2 rounded-full bg-red-500 mt-2 shrink-0"></div>
                                <div>
                                    <p class="font-ui text-sm font-semibold">Price Alert: Lot #<?= htmlspecialchars($eng['lot_number']) ?></p>
                                    <p class="font-body text-xs opacity-70">A new high bid was placed on '<?= htmlspecialchars($eng['title']) ?>'. You are no longer in the lead.</p>
                                    <a class="text-[#C9A961] text-xs underline font-ui inline-block mt-2" href="<?= BASE_URL ?>auction?id=<?= $eng['id'] ?>">Increase Bid</a>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
