# Provenance Hub: Antique & Vintage Auction Platform

## 1. Executive Summary & Creative Vision
Provenance Hub is a premium online bidding platform designed as a "Digital Curator." It facilitates the auctioning of high-value, authenticated antique items through a bespoke digital gallery experience. Moving away from the clinical feel of standard e-commerce, the application employs a "Modern Heritage Editorial" aesthetic that mimics an archival auction catalog, utilizing intentional asymmetry, deep tonal contrast, and a meticulously crafted typographic hierarchy.

## 2. Architecture & Tech Stack
The platform enforces a strict, framework-less architectural stack to ensure absolute compliance with environment restrictions:
- **Backend Architecture & Logic**: Object-Oriented PHP 8.x natively (MVC structured). Core logic employs Namespaces, Traits, Encapsulation, and PDO Database Transactions.
- **Database**: MySQL querying through PHP PDO with prepared statements and row-level locking for secure atomic operations.
- **Frontend Technologies**: HTML5, PHP,  CSS3, Vanilla JavaScript, and jQuery 3.x.
- **Styling**: Tailwind CSS with a highly customized `tailwind.config.js` framework replacing default palettes and sizes with the "Modern Heritage" library.
- **Asynchronous Protocol**: Vanilla AJAX for form serialization and JSON payloads. Form-based interactions perform partial HTML DOM fragment reloading.

## 3. Core Functionalities

### Auction & Marketplace 
- **Dynamic Bidding System**: Real-time event-driven bidding managed by server-side constraints (checking for active statuses, time constraints, existing bids).
- **Advanced Filtering Engine**: The marketplace provides categorization by "Category", "Era", and "Price Range", with filters evaluated dynamically via prepared SQL statements.
- **Auction Timeline & Display**: Displays timelines of active auctions natively sorted by end date and highest value.

### User & Role Management
- **Authentication**: Native session-based architecture providing Registration, Login, and Password Restoration modules.
- **Role Control**: Supports tiered user roles (`user`, `admin`, `curator`). Admins/Curators have dedicated dashboards for item creation, editing, and deletion via `/admin` routes.
- **User Dashboard**: Collectors have a tailored view tracking their active engagements, leading bids, and watchlist data.

### Structural Logic & Routing
- **Native Custom Router**: Dispatches HTTP methods (GET/POST) and URIs to specific localized Controllers (e.g., `PageController`, `AuthController`, `ApiController`).
- **Data Validation & Security**: Strong XSS sanitization (`htmlspecialchars`), form protections, timezone handling, explicit PHP variable type casting (`(int)`, `(float)`), and Regex pattern checking.

## 4. Design System & Looks (Modern Heritage Editorial)
- **Palette**: Avoids `#000000` text for pure `#3D2B1F`. Bases backgrounds on soft, paper-like warmth (`#fdf9f0`, `#f7f3ea`) with precise tonal overlaps using `surface` layers.
- **Typography**: Employs bespoke Google Fonts mapping:
  - *Playfair Display* for striking Display texts.
  - *Cormorant Garamond* for detailed sub-headers.
  - *Lora* for comprehensive body descriptions.
  - *Montserrat* exclusively for UI controls.
  - *IBM Plex Mono* for all financial, bidding, and timer elements.
- **Structural Identity**: Structural boundaries use background tonal shifts (e.g., nesting `.bg-surface-container` inside `.bg-surface`). No thin defining border lines. Strict 0.5rem limit on border-radiuses to preserve framed photography.
- **Micro-interactions & UX**: Uses lightweight custom JS/jQuery to provide soft 3D tilt effects, modal triggers, and localized AJAX state swaps without disorienting the aesthetic. A subtle opacity paper-texture overlays the entire DOM for an archival look.

## 5. File & Directory Structure
- `/src/Controllers/`: Contains the logical routing glue (Page, Auth, API, Admin Profiles).
- `/src/Models/`: Data models abstracting database rows (Auction, User, Bid).
- `/src/Core/`: Base router and database connection engine.
- `/views/`: HTML + PHP templated pages corresponding to controller methods.
- `/public/` & `/assets/`: Served CSS, scripts, images.
- `/database/`: Migration and seed scripts for DB setup.
- `index.php`: The primary router and application entry point.
- `config.php` & `includes/`: Autoloading, environmental setups, and systemic rules.
