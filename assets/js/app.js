$(document).ready(function() {
    // jQuery Event Delegation & Event Chaining logic as required
    console.log("Provenance Hub: JS Engine Initialized");

    const App = {
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            // Event Delegation for dynamically loaded auctions (e.g. on marketplace)
            $(document).on('click', '.place-bid-btn', function(e) {
                e.preventDefault();
                const btn = $(this);
                const auctionId = btn.data('auction-id');
                const bidAmount = btn.closest('.bid-container').find('.bid-input').val();

                if (!bidAmount) {
                    App.showNotification("warning", "Please enter a bid amount.");
                    return;
                }

                App.submitBid(auctionId, bidAmount, btn);
            });

            // Event Chaining for generic interactive elements
            $('.interactive-card')
                .on('mouseenter', function() {
                    $(this).addClass('shadow-ambient transform scale-[1.02] transition-all');
                })
                .on('mouseleave', function() {
                    $(this).removeClass('shadow-ambient transform scale-[1.02]');
                });

            // Sidebar filtering logic on Marketplace
            $('.filter-btn').on('click', function() {
                const category = $(this).data('category');
                
                // Using map/filter on jQuery objects (No vanilla arrays per requirements)
                $('.auction-item').each(function() {
                    const item = $(this);
                    if (category === 'all' || item.data('category') === category) {
                        item.fadeIn();
                    } else {
                        item.fadeOut();
                    }
                });
            });

            // Premium Form Validation for Auth Forms
            $(document).on('submit', '.auth-form', function(e) {
                let isValid = true;
                const form = $(this);
                
                // Clear previous error states
                form.find('input').removeClass('ring-2 ring-red-500 shadow-[0_0_15px_rgba(239,68,68,0.2)]');

                form.find('input[required]').each(function() {
                    const val = $(this).val().trim();
                    if (!val) {
                        isValid = false;
                        $(this).addClass('ring-2 ring-red-500 shadow-[0_0_15px_rgba(239,68,68,0.2)] transition-all');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    App.showNotification('warning', 'Please provide credentials for all required fields.');
                }
            });
        },

        submitBid: function(auctionId, amount, btn) {
            const originalContent = '<span class="material-symbols-outlined text-[18px]">gavel</span> Place Bid';
            btn.html('<span class="material-symbols-outlined animate-spin text-[18px]">sync</span> Processing...').prop('disabled', true);

            $.ajax({
                url: BASE_URL + 'api/bids',
                type: 'POST',
                dataType: 'json',
                data: {
                    auction_id: auctionId,
                    amount: amount
                },
                success: function(response) {
                    if (response.success) {
                        btn.removeClass('gold-gradient').addClass('bg-green-600 shadow-[0_0_20px_rgba(22,163,74,0.4)]')
                           .html('<span class="material-symbols-outlined text-[18px]">check_circle</span> Bid Placed Successfully!');
                           
                        const priceElement = btn.closest('.bid-container').find('.current-price');
                        priceElement.fadeOut(150, function() {
                            $(this).text('$' + parseFloat(response.new_bid).toLocaleString(undefined, {minimumFractionDigits: 2}))
                                   .addClass('text-green-600 scale-110 shadow-ambient')
                                   .fadeIn(150, function() {
                                        setTimeout(() => { $(this).removeClass('text-green-600 scale-110 shadow-ambient'); }, 1000);
                                   });
                        });
                        
                        btn.closest('.bid-container').find('.bid-input').val('');
                        setTimeout(() => {
                            btn.addClass('gold-gradient').removeClass('bg-green-600 shadow-[0_0_20px_rgba(22,163,74,0.4)]').html(originalContent).prop('disabled', false);
                        }, 2500);
                    } else {
                        btn.removeClass('gold-gradient').addClass('bg-red-600 shadow-[0_0_20px_rgba(220,38,38,0.4)]')
                           .html('<span class="material-symbols-outlined text-[18px]">error</span> ' + response.error);
                           
                        setTimeout(() => {
                           btn.addClass('gold-gradient').removeClass('bg-red-600 shadow-[0_0_20px_rgba(220,38,38,0.4)]').html(originalContent).prop('disabled', false);
                        }, 2500);
                    }
                },
                error: function() {
                    btn.removeClass('gold-gradient').addClass('bg-red-600')
                       .html('<span class="material-symbols-outlined text-[18px]">wifi_off</span> Network Error');
                    setTimeout(() => {
                       btn.addClass('gold-gradient').removeClass('bg-red-600').html(originalContent).prop('disabled', false);
                    }, 2500);
                }
            });
        },

        showNotification: function(type, message) {
            const types = {
                success: {
                    containerClass: 'bg-[#F0FDF4] border-[#BBF7D0]',
                    iconBg: 'bg-[#22C55E]',
                    iconName: 'check',
                    iconShape: 'rounded-full'
                },
                info: {
                    containerClass: 'bg-[#EFF6FF] border-[#BFDBFE]',
                    iconBg: 'bg-[#3B82F6]',
                    iconName: 'info',
                    iconShape: 'rounded-xl'
                },
                warning: {
                    containerClass: 'bg-[#FEFCE8] border-[#FEF08A]',
                    iconBg: 'bg-[#F59E0B]',
                    iconName: 'warning',
                    iconShape: 'rounded-xl'
                },
                error: {
                    containerClass: 'bg-[#FEF2F2] border-[#FECACA]',
                    iconBg: 'bg-[#EF4444]',
                    iconName: 'priority_high',
                    iconShape: 'rounded-xl'
                }
            };

            const t = types[type] || types.info;
            const toastId = 'notify-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
            
            // Assign the dismiss click to global context wrapper temporarily since HTML string is compiled
            window.__dismissCustomToast = function(id) {
                App.dismissNotification(id);
            };

            const toastHtml = `
                <div id="${toastId}" class="flex items-center justify-between p-3 rounded-xl border pointer-events-auto transition-all duration-300 transform translate-x-4 opacity-0 shadow-ambient min-w-[300px] max-w-md ${t.containerClass}">
                    <div class="flex items-center gap-4">
                        <div class="${t.iconBg} ${t.iconShape} w-8 h-8 flex items-center justify-center text-white shrink-0">
                            <span class="material-symbols-outlined text-[18px] font-bold">${t.iconName}</span>
                        </div>
                        <p class="font-ui text-gray-800 text-sm font-medium mr-4">${message}</p>
                    </div>
                    <button onclick="window.__dismissCustomToast('${toastId}')" class="text-gray-400 hover:text-gray-600 transition-colors shrink-0">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>
            `;

            $('#toast-container').append(toastHtml);
            
            requestAnimationFrame(() => {
                const toastEl = $('#' + toastId);
                toastEl.removeClass('translate-x-4 opacity-0').addClass('translate-x-0 opacity-100');
                
                setTimeout(() => {
                    if (document.getElementById(toastId)) {
                        App.dismissNotification(toastId);
                    }
                }, 5000);
            });
        },

        dismissNotification: function(id) {
            const toastEl = $('#' + id);
            if(toastEl.length) {
                toastEl.removeClass('translate-x-0 opacity-100').addClass('translate-x-4 opacity-0');
                setTimeout(() => {
                    toastEl.remove();
                }, 300);
            }
        }
    };

    App.init();
});
