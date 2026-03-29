<?php
$pageTitle = $auction ? 'Edit Auction' : 'Create Auction';
include BASE_PATH . '/views/partials/header.php';
?>

<main class="max-w-4xl mx-auto px-8 py-12">
    <div>
        <span class="font-ui text-xs uppercase tracking-[0.3em] text-primary mb-4 block">Administration</span>
        <h1 class="font-display text-4xl text-on-surface mb-8"><?= $pageTitle ?></h1>
    </div>

    <form method="POST" action="<?= BASE_URL ?>admin/auction/<?= $auction ? 'edit' : 'create' ?>" enctype="multipart/form-data" class="bg-surface-container p-8 shadow-ambient space-y-8 rounded-sm transition-all duration-300">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <?php if ($auction): ?>
            <input type="hidden" name="id" value="<?= $auction['id'] ?>">
            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($auction['image_url']) ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="group">
                <label class="block font-ui text-[10px] uppercase tracking-widest text-outline group-focus-within:text-primary transition-colors mb-2">Title</label>
                <input name="title" type="text" value="<?= $auction ? htmlspecialchars($auction['title']) : '' ?>" required minlength="3" maxlength="150" placeholder="e.g. Victorian Mahogany Dining Table" class="w-full bg-surface hover:bg-surface-container-high py-3 px-4 border border-outline-variant/30 focus:border-primary focus:ring-1 focus:ring-primary font-body text-on-surface transition-all duration-300 outline-none rounded-sm">
            </div>
            <div class="group">
                <label class="block font-ui text-[10px] uppercase tracking-widest text-outline group-focus-within:text-primary transition-colors mb-2">Lot Number</label>
                <input name="lot_number" type="text" value="<?= $auction ? htmlspecialchars($auction['lot_number']) : '' ?>" required pattern="[A-Za-z0-9\-]+" title="Alphanumeric and dashes only" placeholder="e.g. LOT-42" class="w-full bg-surface hover:bg-surface-container-high py-3 px-4 border border-outline-variant/30 focus:border-primary focus:ring-1 focus:ring-primary font-body text-on-surface transition-all duration-300 outline-none rounded-sm leading-tight">
            </div>
            <div class="group">
                <label class="block font-ui text-[10px] uppercase tracking-widest text-outline group-focus-within:text-primary transition-colors mb-2">Category</label>
                <div class="relative">
                    <select name="category" required class="w-full appearance-none bg-surface hover:bg-surface-container-high py-3 px-4 border border-outline-variant/30 focus:border-primary focus:ring-1 focus:ring-primary font-body text-on-surface transition-all duration-300 outline-none rounded-sm cursor-pointer">
                        <option value="Furniture" <?= ($auction && $auction['category'] === 'Furniture') ? 'selected' : '' ?>>Furniture</option>
                        <option value="Horology" <?= ($auction && $auction['category'] === 'Horology') ? 'selected' : '' ?>>Horology</option>
                        <option value="Fine Art" <?= ($auction && $auction['category'] === 'Fine Art') ? 'selected' : '' ?>>Fine Art</option>
                        <option value="Silverware" <?= ($auction && $auction['category'] === 'Silverware') ? 'selected' : '' ?>>Silverware</option>
                        <option value="Instruments" <?= ($auction && $auction['category'] === 'Instruments') ? 'selected' : '' ?>>Instruments</option>
                        <option value="Technology" <?= ($auction && $auction['category'] === 'Technology') ? 'selected' : '' ?>>Technology</option>
                        <option value="Accessories" <?= ($auction && $auction['category'] === 'Accessories') ? 'selected' : '' ?>>Accessories</option>
                        <option value="Ceramics" <?= ($auction && $auction['category'] === 'Ceramics') ? 'selected' : '' ?>>Ceramics</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-outline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
            <div class="group">
                <label class="block font-ui text-[10px] uppercase tracking-widest text-outline group-focus-within:text-primary transition-colors mb-2">Era</label>
                <input name="era" type="text" value="<?= $auction ? htmlspecialchars($auction['era']) : '' ?>" required maxlength="50" placeholder="e.g. 19th Century" class="w-full bg-surface hover:bg-surface-container-high py-3 px-4 border border-outline-variant/30 focus:border-primary focus:ring-1 focus:ring-primary font-body text-on-surface transition-all duration-300 outline-none rounded-sm">
            </div>
            <div class="group">
                <label class="block font-ui text-[10px] uppercase tracking-widest text-outline group-focus-within:text-primary transition-colors mb-2">Starting Price ($)</label>
                <input name="starting_price" type="number" step="0.01" min="0.01" value="<?= $auction ? $auction['starting_price'] : '' ?>" required placeholder="0.00" class="w-full bg-surface hover:bg-surface-container-high py-3 px-4 border border-outline-variant/30 focus:border-primary focus:ring-1 focus:ring-primary font-body text-on-surface transition-all duration-300 outline-none rounded-sm">
            </div>
            <div class="group">
                <label class="block font-ui text-[10px] uppercase tracking-widest text-outline group-focus-within:text-primary transition-colors mb-2">Closes At (UTC)</label>
                <input name="ends_at" type="datetime-local" value="<?= $auction ? date('Y-m-d\TH:i', strtotime($auction['ends_at'])) : '' ?>" required min="<?= date('Y-m-d\TH:i') ?>" class="w-full bg-surface hover:bg-surface-container-high py-3 px-4 border border-outline-variant/30 focus:border-primary focus:ring-1 focus:ring-primary font-body text-on-surface transition-all duration-300 outline-none rounded-sm cursor-pointer">
            </div>
        </div>

        <div class="group">
            <label class="block font-ui text-[10px] uppercase tracking-widest text-outline group-focus-within:text-primary transition-colors mb-2">Description</label>
            <textarea name="description" rows="5" required minlength="10" placeholder="Provide a detailed description of the artwork or antique..." class="w-full bg-surface hover:bg-surface-container-high py-3 px-4 border border-outline-variant/30 focus:border-primary focus:ring-1 focus:ring-primary font-body text-on-surface transition-all duration-300 outline-none resize-none rounded-sm"><?= $auction ? htmlspecialchars($auction['description']) : '' ?></textarea>
        </div>

        <div class="group bg-surface-container-low p-6 rounded-sm border border-outline-variant/20">
            <div class="flex items-center justify-between mb-4">
                <label class="block font-ui text-[10px] uppercase tracking-widest text-outline group-focus-within:text-primary transition-colors">Cover Image</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer font-ui text-[10px] uppercase tracking-wider text-on-surface-variant hover:text-primary transition-colors">
                        <input type="radio" name="image_mode" value="upload" checked class="text-primary focus:ring-primary bg-surface border-outline-variant/30" onchange="toggleImageMode()">
                        <span>Upload File</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer font-ui text-[10px] uppercase tracking-wider text-on-surface-variant hover:text-primary transition-colors">
                        <input type="radio" name="image_mode" value="url" class="text-primary focus:ring-primary bg-surface border-outline-variant/30" onchange="toggleImageMode()">
                        <span>Image URL</span>
                    </label>
                </div>
            </div>
            
            <div id="image_upload_container" class="transition-all duration-300 relative overflow-hidden">
                <div class="relative flex items-center justify-center w-full">
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-outline-variant/30 border-dashed rounded-sm cursor-pointer bg-surface hover:bg-surface-container-high hover:border-primary transition-all duration-300">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-6 h-6 mb-3 text-outline group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="mb-2 text-xs text-on-surface-variant"><span class="font-bold text-primary">Click to upload</span> or drag and drop</p>
                            <p class="text-[10px] font-ui uppercase tracking-widest text-outline">PNG, JPG, WEBP (MAX. 5MB)</p>
                        </div>
                        <input name="image" type="file" accept="image/*" class="hidden" onchange="updateFileName(this)">
                    </label>
                </div>
                <p id="file_name_display" class="mt-2 text-xs text-primary text-center hidden font-mono"></p>
            </div>

            <div id="image_url_container" class="hidden transition-all duration-300">
                <input name="image_url_input" type="url" placeholder="https://example.com/image.jpg" class="w-full bg-surface hover:bg-surface-container-high py-4 px-4 border border-outline-variant/30 focus:border-primary focus:ring-1 focus:ring-primary font-body text-on-surface transition-all duration-300 outline-none rounded-sm">
            </div>

            <?php if ($auction && $auction['image_url']): ?>
                <div class="mt-4 flex items-center gap-4 bg-surface p-3 rounded-sm border border-outline-variant/10">
                    <img src="<?= str_starts_with($auction['image_url'], 'http') ? htmlspecialchars($auction['image_url']) : BASE_URL . htmlspecialchars($auction['image_url']) ?>" alt="Current cover" class="w-12 h-12 object-cover rounded-sm border border-outline-variant/20">
                    <p class="text-[10px] font-ui uppercase tracking-widest text-outline">Current Image Installed</p>
                </div>
            <?php endif; ?>
        </div>
        
        <script>
            function toggleImageMode() {
                const mode = document.querySelector('input[name="image_mode"]:checked').value;
                const uploadContainer = document.getElementById('image_upload_container');
                const urlContainer = document.getElementById('image_url_container');
                
                if (mode === 'upload') {
                    uploadContainer.classList.remove('hidden');
                    urlContainer.classList.add('hidden');
                    document.querySelector('input[name="image_url_input"]').value = '';
                } else {
                    uploadContainer.classList.add('hidden');
                    urlContainer.classList.remove('hidden');
                    document.querySelector('input[name="image"]').value = '';
                    document.getElementById('file_name_display').classList.add('hidden');
                }
            }
            
            function updateFileName(input) {
                const display = document.getElementById('file_name_display');
                if (input.files && input.files[0]) {
                    display.textContent = 'Selected: ' + input.files[0].name;
                    display.classList.remove('hidden');
                } else {
                    display.classList.add('hidden');
                }
            }
        </script>

        <div class="pt-6 border-t border-outline-variant/20 flex justify-between items-center group">
            <a href="<?= BASE_URL ?>admin" class="text-[10px] uppercase font-ui tracking-widest text-outline hover:text-red-500 transition-colors flex items-center gap-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Discard & Return
            </a>
            <button type="submit" class="py-4 px-10 gold-gradient text-white font-ui text-[10px] font-bold tracking-[0.2em] uppercase hover:brightness-110 shadow-lg hover:shadow-primary/20 transition-all rounded-sm flex items-center gap-3 active:scale-95">
                <?= $auction ? 'Update Consignment' : 'Publish Consignment' ?>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </button>
        </div>
    </form>
</main>
<?php include BASE_PATH . '/views/partials/footer.php'; ?>
