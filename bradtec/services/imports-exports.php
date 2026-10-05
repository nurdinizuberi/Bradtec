<?php
$ROOT = '../../';
$PAGE_TITLE = 'Imports, Exports & Distribution — BRADTEC CO. LTD';
$PAGE_DESC = 'Connecting global markets. Explore BRADTEC imports, exports and distribution services — customs clearance, trade compliance, warehousing and logistics.';
$PAGE_OG_IMAGE = 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?auto=format&fit=crop&w=1200&q=80';
$PAGE_CRUMBS = [['name' => 'Services', 'url' => '/services'], ['name' => 'Imports/Exports & Distribution', 'url' => '/services/imports-exports']];
$DIVISION_ID = 'imports-exports';

$channels = [
    ['name' => 'Import Management', 'desc' => 'Full import support — sourcing, procurement, clearance and delivery to your facility.'],
    ['name' => 'Export Solutions', 'desc' => 'Export documentation, compliance, consolidation and international routing.'],
    ['name' => 'Customs Clearance', 'desc' => 'Import clearance, tariff classification and regulatory compliance.'],
    ['name' => 'Warehousing & Storage', 'desc' => 'Secure warehouse facilities with inventory management and order fulfilment.'],
    ['name' => 'Distribution', 'desc' => 'Domestic and regional distribution networks with last-mile delivery.'],
    ['name' => 'Trade Compliance', 'desc' => 'Advisory on trade regulations, permits, documentation and customs protocols.'],
];

$PAGE = [
    'overview' => [
        'heading' => 'Connecting Global Markets',
        'image' => 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?auto=format&fit=crop&w=1200&q=80',
        'alt' => 'Shipping containers and cranes at a global port',
        'cta_label' => 'Request a Quote',
        'cta_interest' => 'Imports/Exports',
    ],
    'capabilities' => [
        'id' => 'channels',
        'eyebrow' => 'What We Handle',
        'heading' => 'Import · Export · Customs · Warehousing · Distribution · Compliance',
        'blurb' => 'Six coordinated capabilities that keep your goods moving smoothly across borders and into the right hands.',
        'items' => $channels,
    ],
    'equipment' => [
        'eyebrow' => 'Infrastructure & Network',
        'heading' => 'Built for Global Reach',
        'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1200&q=80',
        'alt' => 'BRADTEC warehousing and distribution facilities',
    ],
    'cta' => [
        'heading' => 'Global Trade. Seamless Distribution.',
        'copy' => 'Get a quote for your import, export or distribution needs — fast, reliable and compliant.',
        'primary_label' => 'Request a Quote',
        'primary_interest' => 'Imports/Exports',
        'secondary_label' => 'Trade Inquiry',
        'secondary_href' => $ROOT . 'contact?interest=Imports/Exports',
    ],
];

require __DIR__ . '/division_page.php';
