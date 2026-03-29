    <footer class="bg-[#3D2B1F] dark:bg-[#1A120D] text-[#C9A961] mt-32 relative z-30">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 px-12 py-16 w-full max-w-7xl mx-auto">
            <div class="space-y-6">
                <div class="text-xl font-serif text-[#C9A961] font-display">Provenance Hub</div>
                <p class="font-body text-xs text-[#FDF9F0]/60 leading-relaxed">
                    Dedicated to the preservation and transition of historical artifacts. We bridge the gap between heritage and modern collection.
                </p>
            </div>
            <div class="space-y-4">
                <h4 class="font-ui text-xs font-bold uppercase tracking-widest text-[#FDF9F0]">Explore</h4>
                <ul class="space-y-2 text-sm font-ui opacity-70">
                    <li><a href="<?= BASE_URL ?>marketplace?category=Fine+Art" class="hover:text-[#C9A961] transition-all">Fine Art</a></li>
                    <li><a href="<?= BASE_URL ?>marketplace?category=Furniture" class="hover:text-[#C9A961] transition-all">Rare Furniture</a></li>
                    <li><a href="<?= BASE_URL ?>marketplace?category=Accessories" class="hover:text-[#C9A961] transition-all">Estate Jewelry</a></li>
                </ul>
            </div>
            <div class="space-y-4">
                <h4 class="font-ui text-xs font-bold uppercase tracking-widest text-[#FDF9F0]">Sell</h4>
                <ul class="space-y-2 text-sm font-ui opacity-70">
                    <li><a href="<?= BASE_URL ?>page?id=consign" class="hover:text-[#C9A961] transition-all">Consign an Item</a></li>
                    <li><a href="<?= BASE_URL ?>page?id=valuation" class="hover:text-[#C9A961] transition-all">Valuation Services</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-[#FDF9F0]/5 px-12 py-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-[10px] font-ui opacity-50 uppercase tracking-widest">
                © <?= date('Y') ?> Provenance Auction Hub. All rights reserved.
            </div>
            <div class="flex gap-8 text-[10px] font-ui uppercase tracking-widest opacity-50">
                <a href="<?= BASE_URL ?>page?id=legal" class="hover:text-[#C9A961]">Legal</a>
                <a href="<?= BASE_URL ?>page?id=privacy-policy" class="hover:text-[#C9A961]">Privacy Policy</a>
                <a href="<?= BASE_URL ?>page?id=cookies" class="hover:text-[#C9A961]">Cookies</a>
            </div>
        </div>
    </footer>
    
    <!-- Global Toast Container -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3 pointer-events-none"></div>

    <script src="<?= BASE_URL ?>assets/js/app.js?v=<?= time() ?>"></script>
</body>
</html>
