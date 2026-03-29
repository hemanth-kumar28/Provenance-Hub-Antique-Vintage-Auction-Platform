<?php
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/autoload.php';

use App\Core\Database;

$pdo = Database::getConnection();

echo "Starting Database Seed...\n";

// Clear existing tables honoring foreign key constraints
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE audit_logs; TRUNCATE TABLE bids; TRUNCATE TABLE auctions; TRUNCATE TABLE users; SET FOREIGN_KEY_CHECKS = 1;");

$hash = password_hash('password', PASSWORD_DEFAULT);
$pdo->exec("INSERT INTO users (username, email, password_hash, role) VALUES ('Julian Vane', 'julian@example.com', '$hash', 'curator')");

// Create Auctions
$auctions = [
    [
        'lot_number' => 'L-442',
        'title' => 'Victorian Rosewood Writing Desk',
        'era' => 'Victorian',
        'category' => 'Furniture',
        'description' => 'Circa 1860, featuring hand-carved mahogany scrollwork and original brass hardware. Provenance: Highclere Estate.',
        'provenance_history' => 'Commissioned for Sir Alistair Penrose in 1885.',
        'condition_report' => 'Exceptional antique condition. Minor age-appropriate surface wear to the lower legs.',
        'specifications' => json_encode(['Dimensions' => '115cm H x 120cm W x 65cm D', 'Materials' => 'Solid Mahogany, Calf Leather, Brass']),
        'starting_price' => 1500.00,
        'reserve_price' => 3000.00,
        'current_bid' => 2450.00,
        'ends_at' => date('Y-m-d H:i:s', strtotime('+4 days 12 hours')),
        'status' => 'active',
        'image_url' => 'images/furniture.jpg'
    ],
    [
        'lot_number' => 'L-443',
        'title' => 'Gentleman\'s 18k Gold Chronometer',
        'era' => '19th Century',
        'category' => 'Horology',
        'description' => 'Fully restored movement, rare double-cased Swiss engineering. Exemplary condition.',
        'provenance_history' => 'Private Zurich collection since 1920.',
        'condition_report' => 'Movement serviced perfectly. Minor scratch on caseback.',
        'specifications' => json_encode(['Diameter' => '52mm', 'Material' => '18k Gold', 'Movement' => 'Manual Wind']),
        'starting_price' => 5000.00,
        'reserve_price' => 8000.00,
        'current_bid' => 8100.00,
        'ends_at' => date('Y-m-d H:i:s', strtotime('+2 days 5 hours')),
        'status' => 'active',
        'image_url' => 'images/timething.jpg'
    ],
    [
        'lot_number' => 'L-444',
        'title' => '\'The Gilded Tempest\' Oil Study',
        'era' => '18th Century',
        'category' => 'Fine Art',
        'description' => 'Anonymous Flemish school masterpiece, original 18th-century gilt frame with minor patina.',
        'provenance_history' => 'Acquired from a Paris estate sale in 1968.',
        'condition_report' => 'Canvas recently relined. Frame has minor gilt losses.',
        'specifications' => json_encode(['Dimensions' => '80cm x 110cm', 'Medium' => 'Oil on Canvas']),
        'starting_price' => 10000.00,
        'reserve_price' => 15000.00,
        'current_bid' => 12400.00,
        'ends_at' => date('Y-m-d H:i:s', strtotime('+6 days')),
        'status' => 'active',
        'image_url' => 'images/mirrorthing.jpg'
    ],
    [
        'lot_number' => 'L-445',
        'title' => 'Art Deco Silver Tea Service',
        'era' => '1930s',
        'category' => 'Silverware',
        'description' => 'A stunning 5-piece silver tea service designed by Jean Puiforcat.',
        'provenance_history' => 'Paris exposition 1932.',
        'condition_report' => 'Excellent. Freshly polished. Teapot hinge slightly stiff.',
        'specifications' => json_encode(['Material' => 'Sterling Silver', 'Weight' => '2.4kg']),
        'starting_price' => 3000.00,
        'reserve_price' => 4500.00,
        'current_bid' => 4850.00,
        'ends_at' => date('Y-m-d H:i:s', strtotime('+12 hours')),
        'status' => 'active',
        'image_url' => 'images/trinkles.jpg'
    ],
    [
        'lot_number' => 'L-446',
        'title' => 'Ancient Brass Hourglass',
        'era' => 'Renaissance',
        'category' => 'Instruments',
        'description' => 'A beautifully preserved navigation hourglass. Features intricate brass turnings.',
        'provenance_history' => 'Recovered from a shipwreck off the coast of Spain in 1912.',
        'condition_report' => 'Glass is completely intact. Sand flows freely. Brass has a deep, unpolished patina.',
        'specifications' => json_encode(['Height' => '25cm', 'Material' => 'Brass, Glass, Obsidian Sand']),
        'starting_price' => 800.00,
        'reserve_price' => 1200.00,
        'current_bid' => 950.00,
        'ends_at' => date('Y-m-d H:i:s', strtotime('+1 days 4 hours')),
        'status' => 'active',
        'image_url' => 'images/hourglass.jpg'
    ],
    [
        'lot_number' => 'L-447',
        'title' => 'Candlestick Telephone',
        'era' => 'Edwardian',
        'category' => 'Technology',
        'description' => 'A rare, functional Western Electric candlestick rotary telephone.',
        'provenance_history' => 'Used in the grand lobby of the Waldorf Astoria from 1915 to 1935.',
        'condition_report' => 'Fully restored to working condition for modern landlines. Original bakelite mouthpiece.',
        'specifications' => json_encode(['Height' => '32cm', 'Material' => 'Brass, Bakelite, Cloth Cord']),
        'starting_price' => 450.00,
        'reserve_price' => 600.00,
        'current_bid' => 450.00,
        'ends_at' => date('Y-m-d H:i:s', strtotime('+3 days 1 hour')),
        'status' => 'active',
        'image_url' => 'images/phonething.webp'
    ],
    [
        'lot_number' => 'L-448',
        'title' => 'Hand-Carved Ebony Walking Stick',
        'era' => 'Victorian',
        'category' => 'Accessories',
        'description' => 'A gentleman\'s ebony walking cane with a solid silver lion\'s head pommel.',
        'provenance_history' => 'Bespoke creation for a London parliament member.',
        'condition_report' => 'Silver pommel shows heavy handling wear. Wooden shaft is pristine without cracks.',
        'specifications' => json_encode(['Length' => '94cm', 'Material' => 'Macassar Ebony, Sterling Silver']),
        'starting_price' => 300.00,
        'reserve_price' => 500.00,
        'current_bid' => 340.00,
        'ends_at' => date('Y-m-d H:i:s', strtotime('+5 days')),
        'status' => 'active',
        'image_url' => 'images/stickthing.jpg'
    ],
    [
        'lot_number' => 'L-449',
        'title' => 'Ming Style Porcelain Vase',
        'era' => 'Qing Dynasty',
        'category' => 'Ceramics',
        'description' => 'Exquisite blue and white large temple vase depicting dragons chasing the flaming pearl.',
        'provenance_history' => 'Acquired by a Dutch merchant family in the 18th century.',
        'condition_report' => 'Perfect pristine condition. Fired crackle under the glaze is inherent to production.',
        'specifications' => json_encode(['Height' => '65cm', 'Material' => 'Porcelain, Cobalt Glaze']),
        'starting_price' => 6000.00,
        'reserve_price' => 9000.00,
        'current_bid' => 6500.00,
        'ends_at' => date('Y-m-d H:i:s', strtotime('+2 days 10 hours')),
        'status' => 'active',
        'image_url' => 'images/vase.jpg'
    ],
    [
        'lot_number' => 'L-450',
        'title' => 'Symphonion Music Box',
        'era' => 'Late 19th Century',
        'category' => 'Instruments',
        'description' => 'Walnut-cased disc music box including 12 original steel discs.',
        'provenance_history' => 'Manufactured in Leipzig, Germany. Held in a private Vienna estate.',
        'condition_report' => 'Mechanism winds and plays flawlessly. Comb teeth are all present and tuned.',
        'specifications' => json_encode(['Dimensions' => '40cm W x 35cm D x 25cm H', 'Material' => 'Walnut, Brass, Steel']),
        'starting_price' => 2000.00,
        'reserve_price' => 3500.00,
        'current_bid' => 2100.00,
        'ends_at' => date('Y-m-d H:i:s', strtotime('+12 hours')),
        'status' => 'active',
        'image_url' => 'images/musicthing.jpg'
    ]
];

$stmt = $pdo->prepare("INSERT INTO auctions (lot_number, title, era, category, description, provenance_history, condition_report, specifications, starting_price, reserve_price, current_bid, ends_at, status, image_url) VALUES (:lot, :title, :era, :cat, :desc, :prov, :cond, :spec, :start, :res, :cur, :ends, :status, :img)");

foreach ($auctions as $a) {
    $stmt->execute([
        'lot' => $a['lot_number'],
        'title' => $a['title'],
        'era' => $a['era'],
        'cat' => $a['category'],
        'desc' => $a['description'],
        'prov' => $a['provenance_history'],
        'cond' => $a['condition_report'],
        'spec' => $a['specifications'],
        'start' => $a['starting_price'],
        'res' => $a['reserve_price'],
        'cur' => $a['current_bid'],
        'ends' => $a['ends_at'],
        'status' => $a['status'],
        'img' => $a['image_url']
    ]);
}

echo "Database Seeded successfully!\n";
