<?php
$pageTitle = 'Provenance Hub | Verify Code';
include BASE_PATH . '/views/partials/header.php';
?>

<main class="max-w-md mx-auto py-24 px-6 min-h-[60vh] flex flex-col justify-center">
    <div class="text-center mb-10">
        <span class="font-ui text-xs uppercase tracking-[0.3em] text-primary mb-4 block">Identity Verification</span>
        <h1 class="font-display text-4xl text-on-surface mb-4">Enter Code</h1>
        <p class="font-body text-outline-variant">Enter the 6-digit verification code sent to your email.</p>
    </div>

    <?php if(isset($error)): ?>
        <div class="bg-red-50 text-red-800 border border-red-200 p-4 mb-8 rounded text-center">
            <p class="font-ui text-xs uppercase tracking-widest leading-relaxed"><?= htmlspecialchars($error) ?></p>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>verify-code" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? $_POST['email'] ?? '') ?>">
        
        <div>
            <label class="block font-ui text-[10px] uppercase tracking-widest text-outline mb-2">6-Digit Code</label>
            <input name="code" type="text" maxlength="6" pattern="\d{6}" required class="w-full bg-surface py-4 px-5 border-none focus:ring-1 focus:ring-primary font-body text-on-surface text-center tracking-[0.5em] text-xl transition-colors" placeholder="000000">
        </div>

        <button type="submit" class="w-full gold-gradient text-white font-ui text-[10px] font-bold tracking-[0.2em] uppercase py-4 shadow-ambient hover:brightness-110 transition-all rounded-sm opacity-90 hover:opacity-100">
            Verify Code
        </button>
    </form>
</main>

<?php include BASE_PATH . '/views/partials/footer.php'; ?>
