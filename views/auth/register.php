<?php
$pageTitle = 'Register | Provenance Hub';
include BASE_PATH . '/views/partials/header.php';
?>
<main class="max-w-md mx-auto py-24 px-6 min-h-[60vh]">
    <h1 class="font-display text-4xl text-on-surface mb-8 text-center">New Acquisition Account</h1>
    
    <?php if (isset($error)): ?>
        <div class="bg-red-100 text-red-800 p-4 mb-6 text-sm font-ui"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>register" class="bg-surface-container p-8 shadow-ambient space-y-6 auth-form" novalidate>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <div>
            <label class="block font-ui text-[10px] uppercase tracking-widest text-outline mb-2">Alias / Username</label>
            <input name="username" type="text" required class="w-full bg-surface py-3 px-4 border-none focus:ring-1 focus:ring-primary font-body">
        </div>
        
        <div>
            <label class="block font-ui text-[10px] uppercase tracking-widest text-outline mb-2">Email Address</label>
            <input name="email" type="email" required class="w-full bg-surface py-3 px-4 border-none focus:ring-1 focus:ring-primary font-body">
        </div>
        
        <div>
            <label class="block font-ui text-[10px] uppercase tracking-widest text-outline mb-2">Password</label>
            <input id="reg-password" name="password" type="password" required class="w-full bg-surface py-3 px-4 border-none focus:ring-1 focus:ring-primary font-body">
            
            <ul id="password-rules" class="mt-3 space-y-1 font-ui text-xs text-outline">
                <li id="rule-length" class="flex items-center gap-2"><span class="material-symbols-outlined text-[14px]">close</span> At least 6 characters</li>
                <li id="rule-upper" class="flex items-center gap-2"><span class="material-symbols-outlined text-[14px]">close</span> One uppercase letter</li>
                <li id="rule-lower" class="flex items-center gap-2"><span class="material-symbols-outlined text-[14px]">close</span> One lowercase letter</li>
                <li id="rule-number" class="flex items-center gap-2"><span class="material-symbols-outlined text-[14px]">close</span> One number</li>
                <li id="rule-special" class="flex items-center gap-2"><span class="material-symbols-outlined text-[14px]">close</span> One special (e.g. !@#$%^&*)</li>
            </ul>
        </div>
        
        <button id="register-btn" type="submit" disabled class="w-full py-4 bg-[#3D2B1F] text-[#C9A961] font-ui text-[10px] font-bold tracking-[0.2em] uppercase transition-all disabled:opacity-40 disabled:cursor-not-allowed hover:bg-primary">
            Establish Profile
        </button>
    </form>
    
    <p class="text-center mt-8 font-ui text-xs text-on-surface-variant">
        Already registered? <a href="<?= BASE_URL ?>login" class="text-primary hover:underline font-bold">Proceed to Login</a>.
    </p>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const pwd = document.getElementById('reg-password');
    const emailStr = document.querySelector('input[name="email"]');
    const userStr = document.querySelector('input[name="username"]');
    const btn = document.getElementById('register-btn');
    
    if (!pwd || !emailStr || !userStr || !btn) return;

    const rules = {
        'rule-length': v => v.length >= 6,
        'rule-upper': v => /[A-Z]/.test(v),
        'rule-lower': v => /[a-z]/.test(v),
        'rule-number': v => /[0-9]/.test(v),
        'rule-special': v => /[!@#\$%\^&\*]/.test(v)
    };

    const validateForm = () => {
        let isPwdValid = true;
        const pwdVal = pwd.value;
        for (const [id, testFn] of Object.entries(rules)) {
            if (!testFn(pwdVal)) isPwdValid = false;
        }
        
        const isUserValid = userStr.value.trim().length > 0;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isEmailValid = emailRegex.test(emailStr.value.trim());

        if (isPwdValid && isUserValid && isEmailValid) {
            btn.disabled = false;
            btn.classList.add('shadow-[0_0_20px_rgba(201,169,97,0.3)]', 'brightness-110');
            btn.classList.remove('disabled:opacity-40', 'disabled:cursor-not-allowed');
        } else {
            btn.disabled = true;
            btn.classList.remove('shadow-[0_0_20px_rgba(201,169,97,0.3)]', 'brightness-110');
        }
    };

    pwd.addEventListener('input', (e) => {
        const val = e.target.value;
        for (const [id, testFn] of Object.entries(rules)) {
            const el = document.getElementById(id);
            const icon = el.querySelector('span');
            if (testFn(val)) {
                el.classList.remove('text-outline', 'text-red-500');
                el.classList.add('text-green-600');
                icon.textContent = 'check';
            } else {
                el.classList.remove('text-green-600');
                if (val.length > 0) {
                    el.classList.add('text-red-500');
                    el.classList.remove('text-outline');
                } else {
                    el.classList.add('text-outline');
                    el.classList.remove('text-red-500');
                }
                icon.textContent = 'close';
            }
        }
        validateForm();
    });

    userStr.addEventListener('input', validateForm);
    emailStr.addEventListener('input', validateForm);
    
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
