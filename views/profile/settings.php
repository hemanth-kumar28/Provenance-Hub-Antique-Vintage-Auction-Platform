<?php
$pageTitle = 'Provenance Hub | Profile Settings';
include BASE_PATH . '/views/partials/header.php';
?>

<div class="flex min-h-[calc(100vh-72px)] bg-surface text-on-surface">
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
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
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
    <main class="flex-1 p-6 md:p-12 max-w-4xl mx-auto w-full">
        <section class="mb-12">
            <h1 class="font-display text-4xl md:text-5xl text-on-surface font-bold tracking-tight mb-4">Profile Settings</h1>
            <p class="font-body italic text-on-surface-variant text-lg">Manage your account identity and preferences.</p>
        </section>

        <?php if(isset($_GET['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 font-ui text-sm">
                Profile updated successfully.
            </div>
        <?php endif; ?>

        <div class="bg-surface-container-lowest p-8 shadow-ambient">
            <form action="<?= BASE_URL ?>profile" method="POST" class="space-y-6">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div>
                    <label class="block font-ui text-[10px] uppercase tracking-widest text-outline mb-2">Username / Alias</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required
                           class="w-full bg-surface-container-low border border-outline-variant p-3 font-ui text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block font-ui text-[10px] uppercase tracking-widest text-outline mb-2">Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required
                           class="w-full bg-surface-container-low border border-outline-variant p-3 font-ui text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                </div>

                <div class="pt-6">
                    <button type="submit" class="bg-primary text-white px-8 py-3 font-ui text-xs font-bold tracking-widest uppercase hover:bg-on-surface transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<?php include BASE_PATH . '/views/partials/footer.php'; ?>
