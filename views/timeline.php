<?php
$pageTitle = 'Provenance Hub | Auction Timeline';
include __DIR__ . '/partials/header.php';

$currentTime = time();
$maxTime = $currentTime;
foreach($items as $item) {
    $t = strtotime($item['ends_at']);
    if ($t > $maxTime) $maxTime = $t;
}

$totalDuration = max(1, $maxTime - $currentTime); // Avoid div by zero
?>

<main class="max-w-screen-2xl mx-auto px-8 py-16">
    <div class="mb-12">
        <h1 class="font-display text-5xl md:text-6xl text-on-surface mb-4 leading-[1.1]">Auction <i class="font-headline font-light italic text-primary">Timeline</i></h1>
        <p class="font-body text-lg text-on-surface-variant max-w-2xl leading-relaxed">
            Visualize the active lifecycle of our curated artifacts. Each segment maps the precise duration until the gavel falls.
        </p>
    </div>

    <!-- Timeline Container -->
    <div class="bg-surface-container-lowest shadow-ambient border border-outline-variant p-8 overflow-x-auto relative min-h-[500px]">
        <!-- Timeline Header (Months / Weeks) -->
        <div class="flex justify-between border-b border-outline-variant pb-4 mb-8 min-w-[800px] text-xs font-ui uppercase tracking-widest text-outline">
            <span>Current Date</span>
            <span>+1 Week</span>
            <span>+2 Weeks</span>
            <span>Conclusion</span>
        </div>

        <div class="relative min-w-[800px] space-y-6">
            <!-- Vertical markers -->
            <div class="absolute inset-0 z-0 flex justify-between pointer-events-none opacity-10">
                <div class="w-px h-full bg-outline"></div>
                <div class="w-px h-full bg-outline"></div>
                <div class="w-px h-full bg-outline"></div>
                <div class="w-px h-full bg-outline"></div>
            </div>

            <?php foreach($items as $item): 
                $endTs = strtotime($item['ends_at']);
                $duration = max(0, $endTs - $currentTime);
                $widthPercent = ($duration / $totalDuration) * 100;
                // Cap at 100% just in case
                $widthPercent = min(100, max(0, $widthPercent));
                
                // Days remaining
                $daysRemaining = round($duration / 86400);
            ?>
            <div class="relative z-10 flex items-center group">
                <!-- Data Label -->
                <div class="w-48 flex-shrink-0 pr-4">
                    <a href="<?= BASE_URL ?>auction?id=<?= $item['id'] ?>" class="font-headline font-semibold text-lg text-on-surface hover:text-primary transition-colors line-clamp-1" title="<?= htmlspecialchars($item['title']) ?>">
                        <?= htmlspecialchars($item['title']) ?>
                    </a>
                    <p class="font-price text-xs text-primary mt-1">$<?= number_format($item['current_bid'], 2) ?></p>
                </div>
                <!-- Bar Container -->
                <div class="flex-grow flex items-center relative h-10">
                    <div class="gold-gradient h-8 rounded-sm shadow-md transition-all duration-300 group-hover:brightness-110 flex items-center px-4" style="width: <?= $widthPercent ?>%;">
                        <span class="text-white text-[10px] font-ui font-semibold truncate uppercase tracking-widest drop-shadow-md">
                            Ends in <?= $daysRemaining ?> days
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($items)): ?>
            <p class="font-body text-on-surface-variant italic">No active auctions at this time.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
