# Antique Auction Hub: Comprehensive System Requirements (Rev. 5 - Strict Stack Integrated)

## 1. Executive Summary & Creative North Star
The "Antique & Vintage Provenance Auction Hub" is a premium online bidding platform designed to facilitate the auctioning of high-value, authenticated antique items. 
**Creative North Star:** "The Digital Curator." The platform embraces intentional asymmetry, negative space, and tonal depth, strictly built utilizing a classic, framework-less technology stack.

## 2. Strict Technology Stack Authorization
To comply with exact environment restrictions, the application absolutely forbids external frameworks (e.g., Laravel, Vue, React, Node). Development is constrained to:
- **Frontend Architecture:** HTML5, CSS3, Vanilla JavaScript, and **jQuery 3.x**.
- **Backend Architecture:** **Object-Oriented PHP (PHP 8.x)** natively. No routing frameworks.
- **Database Engine:** **MySQL**, interacting exclusively via PHP PDO.
- **Asynchronous Protocol:** Vanilla **AJAX** requesting/receiving pure JSON payloads.

## 3. Frontend Engineering Requirements (Module 3)
- **DOM & Selections:** All application logic must initialize post-DOM Ready. Selection of dynamic auction elements must use Attribute Filtering (`data-*` attributes) and DOM Traversal methods (finding relative parents/siblings) rather than static IDs.
- **Filtering:** Lists of auctions are filtered client-side based on status (Active, Sold, Pending) using array-like filtering methods on jQuery objects (e.g., `.filter()`, `.each()`), not vanilla JS arrays.
- **Event Chaining:** Multiple event types (mouse entry, mouse leave, click) are managed in a single configuration object for cleaner code within event handlers.
- **Event Delegation:** High-frequency interactive elements (Bids, Watchlists) must employ event delegation bound to the main document body to ensure AJAX-loaded HTML fragments function immediately.
- **Animation UX:** The UI must use custom jQuery animation queues for fading element transitions (e.g., replacing outbid prices) and dynamic inline CSS manipulation for immediate feedback (flashing red on outbid error).
- **Communication:** Form serialization logic will dispatch JSON via AJAX. The server will respond with `{ "success": bool, "error": string, "message": mixed }`. Partial DOM loading (HTML fragment injection) will independently refresh the auction grids and live bid prices without full page loads.

## 4. Backend Logic & OOP Requirements (Modules 4 & 5)
- **Advanced PHP Constructs:**
  - Logic must employ Magic Constants (`__FILE__`, `__LINE__`) for strict error logging paths.
  - Sorting algorithms must utilize the Spaceship Operator (`<=>`).
  - Implement Null Coalescing (`??`) for robust parameter defaulting.
- **Type Safety:** All incoming numeric data is explicitly cast `(float)`, `(int)` before calculation.
- **Function Architecture:** Functions use default parameter values. Anonymous functions are utilized for callback logic. Ternary Logic is used for conditional assignments.
- **Object-Oriented Architecture:**
  - **Namespaces:** Codebase organized into logical namespaces (e.g., `App\Core`, `App\Models`).
  - **Encapsulation & Interfaces:** Properties encapsulated behind getters/setters. Classes implement explicit Interfaces (e.g., `BidAcceptable`).
  - **Inheritance & Abstraction:** Specialized classes inherit base functionality. Abstract base classes define controller structures.
  - **Traits:** Reusable logic (like `AuditLogging`) extracted into PHP Traits.
  - **Static Members:** Database connections managed via static properties (Singleton pattern).
- **Image Processing (GD Library):**
  - Images resized in-memory (`imagecreatetruecolor`), resampled, filtered (grayscale for sold items), and watermarked.
  - **Memory Management:** Image resources explicitly destroyed (`imagedestroy`) after processing.
- **File I/O:**
  - Critical events append to a secure, text-based log file following file existence checks.

## 5. Data Persistence & Security (Module 6)
- **Atomic Operations:** Bidding flows exist within PDO Database Transactions.
- **Pessimistic Locking:** High-volume queries employ row-level locking (`SELECT ... FOR UPDATE`).
- **Defenses:**
  - All DB requests require PDO Prepared Statements with Explicit Parameter Binding.
  - All output requires strict XSS sanitization (`htmlspecialchars`).
  - Session IDs regenerated post-authentication. Cookies enforce secure flags.
- **Time Management:**
  - Default timezone set explicitly. Dates converted to Unix timestamps for calculations.

## 6. Validation & Error Handling (Modules 4, 5, 6)
- **Input Validation:** Regular Expressions validate artwork titles. Built-in filters validate emails.
- **Error Management:** Global error handler captures warnings to audit log. Try-Catch Blocks wrap critical operations.
