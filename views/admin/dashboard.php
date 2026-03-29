<?php
$pageTitle = 'Provenance Hub | Admin Panel';
include BASE_PATH . '/views/partials/header.php';
?>

<main class="max-w-screen-2xl mx-auto px-8 py-12 flex flex-col gap-12">
    <div>
        <span class="font-ui text-xs uppercase tracking-[0.3em] text-red-500 mb-4 block">Restricted Sector</span>
        <h1 class="font-display text-5xl text-on-surface mb-6">Administrative Panel</h1>
    </div>

    <!-- Users Grid -->
    <section>
        <h2 class="font-display text-2xl font-bold border-l-4 border-primary pl-4 uppercase tracking-tighter mb-6">Registered Profiles</h2>
        <div class="overflow-x-auto bg-surface-container-low/50 rounded-sm">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-high/50 font-ui text-[10px] uppercase tracking-widest text-on-surface-variant">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Registered Date</th>
                    </tr>
                </thead>
                <tbody class="font-body text-sm divide-y divide-outline-variant/10">
                    <?php foreach($users as $u): ?>
                    <tr class="group/row relative transition-all duration-500 ease-out hover:bg-surface border-b border-transparent hover:border-primary/20 hover:shadow-[0_15px_30px_-5px_rgba(116,91,27,0.08)] hover:-translate-y-0.5 z-0 hover:z-10">
                        <td class="px-6 py-4 text-on-surface-variant font-mono text-xs relative">
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] h-0 bg-primary opacity-0 transition-all duration-500 ease-out group-hover/row:h-3/4 group-hover/row:opacity-100 rounded-r-md"></div>
                            <span class="block transition-transform duration-500 group-hover/row:translate-x-2"><?= $u['id'] ?></span>
                        </td>
                        <td class="px-6 py-4 text-on-surface font-medium cursor-pointer transition-transform duration-500 group-hover/row:translate-x-1">
                            <span class="relative inline-block pb-0.5 group/link">
                                <?= htmlspecialchars($u['username']) ?>
                                <span class="absolute left-0 bottom-0 w-0 h-[1px] bg-primary transition-all duration-500 ease-in-out group-hover/link:w-full"></span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-primary transition-all duration-500 group-hover/row:translate-x-1 group-hover/row:text-secondary"><?= htmlspecialchars($u['email']) ?></td>
                        <td class="px-6 py-4 text-on-surface-variant uppercase text-xs font-bold transition-transform duration-500 group-hover/row:translate-x-1">
                            <span class="px-2 py-1 rounded-sm bg-surface-container-high/50 group-hover/row:bg-primary/10 transition-colors"><?= htmlspecialchars($u['role']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-on-surface-variant/60 transition-transform duration-500 group-hover/row:translate-x-1"><?= htmlspecialchars($u['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Auctions Grid -->
    <section>
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-display text-2xl font-bold border-l-4 border-primary pl-4 uppercase tracking-tighter">Auction Catalogue</h2>
            <a href="<?= BASE_URL ?>admin/auction/create" class="bg-primary hover:bg-white hover:text-primary text-white transition-colors border border-primary px-6 py-2 uppercase font-ui text-[10px] tracking-widest font-bold">New Consignment</a>
        </div>
        
        <?php if(isset($_GET['success'])): ?>
            <div class="bg-green-100 text-green-800 p-4 mb-4 text-sm font-ui uppercase">Operation completed successfully.</div>
        <?php endif; ?>

        <div class="overflow-x-auto bg-surface-container-low/50 rounded-sm">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-high/50 font-ui text-[10px] uppercase tracking-widest text-on-surface-variant">
                        <th class="px-6 py-4">Lot #</th>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4 text-right">Current Bid</th>
                        <th class="px-6 py-4">Ends At</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="font-body text-sm divide-y divide-outline-variant/10">
                    <?php foreach($auctions as $a): ?>
                    <tr class="group/row relative transition-all duration-500 ease-out hover:bg-surface border-b border-transparent hover:border-primary/20 hover:shadow-[0_15px_30px_-5px_rgba(116,91,27,0.08)] hover:-translate-y-0.5 z-0 hover:z-10">
                        <td class="px-6 py-4 text-on-surface-variant font-mono text-xs relative">
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] h-0 bg-primary opacity-0 transition-all duration-500 ease-out group-hover/row:h-3/4 group-hover/row:opacity-100 rounded-r-md"></div>
                            <span class="block transition-transform duration-500 group-hover/row:translate-x-2"><?= htmlspecialchars($a['lot_number']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-on-surface font-medium cursor-pointer transition-transform duration-500 group-hover/row:translate-x-1">
                            <span class="relative inline-block pb-0.5 group/link">
                                <?= htmlspecialchars($a['title']) ?>
                                <span class="absolute left-0 bottom-0 w-0 h-[1px] bg-primary transition-all duration-500 ease-in-out group-hover/link:w-full"></span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-price font-bold text-primary transition-all duration-500 group-hover/row:-translate-x-1 group-hover/row:text-secondary group-hover/row:scale-105 origin-right">$<?= number_format($a['current_bid'], 2) ?></td>
                        <td class="px-6 py-4 text-on-surface-variant/60 text-xs font-mono transition-transform duration-500 group-hover/row:translate-x-1"><?= htmlspecialchars($a['ends_at']) ?></td>
                        <td class="px-6 py-4 text-center transition-transform duration-500 group-hover/row:scale-105">
                            <span class="px-2 py-1 uppercase font-ui text-[9px] font-bold rounded <?= $a['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-surface-container-high text-on-surface-variant' ?> transition-colors duration-500 group-hover/row:shadow-sm">
                                <?= htmlspecialchars($a['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-3 items-center transition-transform duration-500 group-hover/row:-translate-x-1">
                            <a href="<?= BASE_URL ?>admin/auction/edit?id=<?= $a['id'] ?>" class="inline-flex items-center justify-center px-4 py-1.5 text-[10px] uppercase font-ui font-bold text-primary bg-transparent rounded-full transition-all duration-400 ease-[cubic-bezier(0.175,0.885,0.32,1.275)] hover:bg-[#8B6F47] hover:text-white hover:-translate-x-2 hover:shadow-[0_4px_12px_rgba(139,111,71,0.3)] hover:scale-105">Edit</a>
                            <form method="POST" action="<?= BASE_URL ?>admin/auction/delete" onsubmit="return confirm('Obliterate this historical record?');" class="ml-1">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                <button type="submit" class="group/btn relative overflow-hidden px-3 py-1.5 border border-red-900/30 bg-red-500/5 text-[10px] uppercase font-ui font-bold text-red-500 hover:text-white hover:border-red-600 hover:shadow-[0_0_15px_rgba(220,38,38,0.5)] transition-all duration-300 rounded-sm">
                                    <span class="relative z-10 tracking-widest transition-transform duration-300 group-hover/btn:scale-105 inline-block">Terminate</span>
                                    <div class="absolute inset-0 bg-red-600 scale-x-0 origin-right group-hover/btn:scale-x-100 group-hover/btn:origin-left transition-transform duration-500 ease-out z-0"></div>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include BASE_PATH . '/views/partials/footer.php'; ?>
