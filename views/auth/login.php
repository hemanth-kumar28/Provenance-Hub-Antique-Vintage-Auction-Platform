<?php
$pageTitle = 'Login | Provenance Hub';
include BASE_PATH . '/views/partials/header.php';
?>
<main class="max-w-md mx-auto py-24 px-6 min-h-[60vh] flex flex-col justify-center">
    <div class="flex justify-center mb-8">
        <h1 class="font-display text-4xl text-on-surface text-center relative inline-block group cursor-default">
            Log In
            <span class="absolute left-1/2 -bottom-2 w-0 h-[2px] bg-[#C9A961] transition-all duration-500 ease-in-out transform -translate-x-1/2 group-hover:w-full"></span>
        </h1>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="bg-red-100 text-red-800 p-4 mb-6 text-sm font-ui"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['reset_success'])): ?>
        <div class="bg-primary-container/20 border border-primary/30 p-4 mb-6 text-sm font-ui text-center text-primary uppercase tracking-widest">
            Account recovery successful. You may now authenticate with your new credentials.
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>login" class="bg-surface-container p-8 shadow-ambient space-y-6 auth-form" novalidate>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <div>
            <label class="block font-ui text-[10px] uppercase tracking-widest text-outline mb-2">Email Address</label>
            <input name="email" id="login-email" type="email" required class="w-full bg-surface py-3 px-4 border-none focus:ring-1 focus:ring-primary font-body transition-all">
        </div>
        
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="block font-ui text-[10px] uppercase tracking-widest text-outline">Passphrase</label>
                <a href="<?= BASE_URL ?>forgot-password" class="font-ui text-[9px] uppercase tracking-widest text-primary hover:text-white transition-colors">Forgot Identity?</a>
            </div>
            <input name="password" type="password" required class="w-full bg-surface py-3 px-4 border-none focus:ring-1 focus:ring-primary font-body transition-all">
        </div>
        
        <button type="submit" class="w-full py-4 gold-gradient text-white font-ui text-[10px] font-bold tracking-[0.2em] uppercase hover:brightness-110 transition-all">
            Secure Login
        </button>
    </form>
    
    <p class="text-center mt-8 font-ui text-xs text-on-surface-variant">
        Not an established collector? <a href="<?= BASE_URL ?>register" class="text-primary hover:underline font-bold">Request Registration</a>.
    </p>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const emailStr = document.getElementById('login-email');
    if (!emailStr) return;

    // Premium dynamic feedback for Email format
    emailStr.addEventListener('input', (e) => {
        const val = e.target.value.trim();
        const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
        
        if (val.length > 0) {
            if (isValid) {
                emailStr.classList.remove('ring-1', 'ring-red-500/50', 'text-red-600');
                emailStr.classList.add('ring-1', 'ring-green-500/50', 'text-green-700', 'shadow-[0_0_10px_rgba(34,197,94,0.1)]');
            } else {
                emailStr.classList.remove('ring-1', 'ring-green-500/50', 'text-green-700', 'shadow-[0_0_10px_rgba(34,197,94,0.1)]');
                emailStr.classList.add('ring-1', 'ring-red-500/50', 'text-red-600');
            }
        } else {
            emailStr.classList.remove('ring-1', 'ring-red-500/50', 'text-red-600', 'ring-green-500/50', 'text-green-700', 'shadow-[0_0_10px_rgba(34,197,94,0.1)]');
        }
    });
});
</script>
<?php include BASE_PATH . '/views/partials/footer.php'; ?>
