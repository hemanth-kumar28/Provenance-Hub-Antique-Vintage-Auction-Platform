# AI Generative Prompts For Provenance Hub

This document contains a master prompt for recreating or fundamentally extending the overarching architecture of the "Provenance Hub", alongside smaller refinement prompts for modular expansions.

## 1. Master System Prompt

**Role & Context:**
You are a Senior Systems Architect and UI/UX artisan building a premium online auction platform called "Provenance Hub" aimed at high-net-worth historical collectors. Your task is to generate and expand this application adhering absolutely to the designated stack and thematic guidelines.

**Strict Technology Stack:**
- **Backend:** Object-Oriented PHP 8.x (No frameworks like Laravel). Code must use Traits, Interfaces, strict typing, Null Coalescing, and the Spaceship operator.
- **Database:** MySQL via PHP PDO exclusively. Use pessimistic row-level locking (`SELECT ... FOR UPDATE`) and atomic DB transactions for bids.
- **Frontend:** HTML5, CSS3, Vanilla JS, and jQuery 3.x.
- **Routing:** Use a native custom PHP Router mapping `$_SERVER['REQUEST_METHOD']` to namespaced Controllers.
- **Asynchronous Logic:** Use JSON-based AJAX for forms and fragment DOM reloading. No full-page postbacks for high-frequency interactions.

**Design System & Aesthetics ("Modern Heritage Editorial"):**
- **Vibe:** "The Digital Curator". Think sun-drenched private library, an archival catalog, elegant asymmetry. No clinical e-commerce markers.
- **Styling:** Tailwind CSS with a strictly overridden configuration.
- **Palette:** `background: #fdf9f0`, `text: #3D2B1F`, `primary: #745b1b`, `surface-container: #f1eee5`. NO `#000000` text. NO default Tailwind gray scales.
- **Typography:** Playfair Display (Display H1), Cormorant Garamond (Headers), Lora (Body), Montserrat (UI), IBM Plex Mono (Prices/Timers).
- **Rules:** No 1px border lines to separate sections; use tonal background shifts instead. Maximum `border-radius` is `0.5rem` (except user avatars). Include a fixed 3% opacity noise grain `div` over the page for an archival paper texture.
- **Animations:** Use subdued, tactile animations (e.g., 3D tilt cards tracked via mousemove) and discrete fade transitions, maintaining the sophisticated gravitas.

**Goal:**
Using the criteria above, [INSERT TASK HERE - e.g., "Implement an Auction Checkout Gateway and Invoice Generation Controller, complete with the view mapping and required AJAX polling logic."].

---

## 2. Refinement Prompts

### Refinement: Adding a Micro-Interaction
> "Using Vanilla JS/jQuery 3.x and our 'Modern Heritage Editorial' Tailwind configurations, create a dynamic sticky header for the Provenance Hub. The header should start completely transparent over the hero image, but as the user scrolls past 100px, smoothly fade in a `bg-surface-container-highest` background and drop an ambient shadow. Ensure the font classes `font-ui` and `uppercase` for the navigation links gracefully shift text color from `text-white` to `text-on-surface` without jarring visually. Provide the JS snippet and required markup classes."

### Refinement: Database & Server Ajax Logic
> "Draft a new public method in the `ApiController.php` to handle 'Watchlist' toggles. The method must verify the user's session via `$_SESSION['user_id']`. Use PHP PDO with prepared parameters to `INSERT` or `DELETE` a row in the `watchlists` table safely. Reply to the AJAX request with a strict JSON format containing `{"success": true|false, "is_watching": true|false}`. Ensure strict typing is maintained and catch PDOExceptions."

### Refinement: Building a Component View
> "Design a 'Similar Items' grid block to be included in `views/auction.php`. Follow the 'No-Line' rule of the environment. Build 3 item cards utilizing the `bg-surface-container-low` background. Ensure thumbnail images have a maximum `rounded-md` radius. Include the 'Price' natively styled with `font-price text-primary` and title styled with `font-headline text-2xl text-on-surface`. Make sure they sit on the main `#fdf9f0` background gracefully using tonal distinction instead of harsh shadows."

---

## 3. Browser Compatibility Requirements

Because this application enforces a framework-less architecture but utilizes modern front-end layout features (CSS Grid, Flexbox, Custom Property overrides, `mix-blend-mode` for paper textures) and native modern JavaScript features (`const`, `let`, arrow functions, alongside jQuery), it is compatible with all **Modern Browsers**:

- **Google Chrome:** Version 90+ (Fully Supported)
- **Mozilla Firefox:** Version 88+ (Fully Supported)
- **Apple Safari:** Version 14+ (Fully Supported - Desktop & iOS)
- **Microsoft Edge:** Version 90+ (Chromium-based - Fully Supported)
- **Opera:** Version 76+ (Fully Supported)

*Note: Internet Explorer 11 and legacy Microsoft Edge are strictly **Out of Scope**. The usage of CSS Blend Modes, modern Tailwind properties, and the backend infrastructure assuming modern client execution capacities explicitly drops support for IE11.*
