<?php
$pageTitle = 'Provenance Hub | ' . $pageData['title'];
include __DIR__ . '/partials/header.php';
?>
<main class="max-w-4xl mx-auto px-6 py-24 min-h-[60vh]">
    <h1 class="font-display text-5xl text-on-surface mb-8"><?= htmlspecialchars($pageData['title']) ?></h1>
    <div class="w-12 h-px bg-primary mb-8"></div>
    <div class="bg-surface-container-low p-10 shadow-ambient">
        <div class="font-body text-lg text-on-surface-variant leading-loose space-y-6">
            <p><?= htmlspecialchars($pageData['content']) ?></p>
        </div>
    </div>
</main>
<?php include __DIR__ . '/partials/footer.php'; ?>
