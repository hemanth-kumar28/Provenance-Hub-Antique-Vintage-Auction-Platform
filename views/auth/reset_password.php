<?php
$pageTitle = 'Provenance Hub | Reset Password';
include BASE_PATH . '/views/partials/header.php';
?>

<main class="max-w-md mx-auto py-24 px-6 min-h-[60vh] flex flex-col justify-center">
    <div class="text-center mb-10">
        <span class="font-ui text-xs uppercase tracking-[0.3em] text-primary mb-4 block">Security Update</span>
        <h1 class="font-display text-4xl text-on-surface mb-4">New Password</h1>
        <p class="font-body text-outline-variant">Create a new secure password for your account.</p>
    </div>

    <?php if(isset($error)): ?>
        <div class="bg-red-50 text-red-800 border border-red-200 p-4 mb-8 rounded text-center">
            <p class="font-ui text-xs uppercase tracking-widest leading-relaxed"><?= htmlspecialchars($error) ?></p>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>reset-password" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <div>
            <label class="block font-ui text-[10px] uppercase tracking-widest text-outline mb-2">New Password</label>
            <input name="password" type="password" required class="w-full bg-surface py-4 px-5 border-none focus:ring-1 focus:ring-primary font-body text-on-surface transition-colors" placeholder="••••••••">
            <p class="text-[10px] text-outline-variant mt-2 font-ui">Min 6 chars, uppercase, lowercase, number, special char.</p>
        </div>

        <div>
            <label class="block font-ui text-[10px] uppercase tracking-widest text-outline mb-2">Confirm Password</label>
            <input name="confirm" type="password" required class="w-full bg-surface py-4 px-5 border-none focus:ring-1 focus:ring-primary font-body text-on-surface transition-colors" placeholder="••••••••">
        </div>

        <button type="submit" class="w-full gold-gradient text-white font-ui text-[10px] font-bold tracking-[0.2em] uppercase py-4 shadow-ambient hover:brightness-110 transition-all rounded-sm opacity-90 hover:opacity-100">
            Secure Account
        </button>
    </form>
</main>

<?php include BASE_PATH . '/views/partials/footer.php'; ?>
