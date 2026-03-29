<?php
$pageTitle = 'Provenance Hub | Recover Access';
include BASE_PATH . '/views/partials/header.php';
?>

<main class="max-w-md mx-auto py-24 px-6 min-h-[60vh] flex flex-col justify-center">
    <div class="text-center mb-10">
        <span class="font-ui text-xs uppercase tracking-[0.3em] text-primary mb-4 block">Identity Verification</span>
        <h1 class="font-display text-4xl text-on-surface mb-4">Recover Access</h1>
        <p class="font-body text-outline-variant">Enter your registered email address to receive secure reset instructions.</p>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="bg-primary-container/20 border border-primary/30 p-4 mb-8 rounded text-center">
            <p class="font-ui text-xs text-primary uppercase tracking-widest leading-relaxed">If an account matching that identity exists in our registry, a recovery dispatch has been sent.</p>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>forgot-password" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <div>
            <label class="block font-ui text-[10px] uppercase tracking-widest text-outline mb-2">Email Identity</label>
            <input name="email" type="email" required class="w-full bg-surface py-4 px-5 border-none focus:ring-1 focus:ring-primary font-body text-on-surface transition-colors" placeholder="collector@example.com">
        </div>

        <button type="submit" class="w-full gold-gradient text-white font-ui text-[10px] font-bold tracking-[0.2em] uppercase py-4 shadow-ambient hover:brightness-110 transition-all rounded-sm opacity-90 hover:opacity-100">
            Dispatch Recovery Link
        </button>

        <div class="text-center pt-6">
            <a href="<?= BASE_URL ?>login" class="font-ui text-[10px] uppercase tracking-widest text-outline hover:text-primary transition-colors">Return to Authentication</a>
        </div>
    </form>
</main>

<?php include BASE_PATH . '/views/partials/footer.php'; ?>
