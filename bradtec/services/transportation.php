<?php
$ROOT = '../../';
$PAGE_TITLE = 'Transportation, Logistics & Import/Export — BRADTEC CO. LTD';
$PAGE_DESC = 'Connecting markets and moving opportunities. Explore BRADTEC transportation services — freight, cargo, shipping, logistics, import and export solutions.';
$PAGE_OG_IMAGE = 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1200&q=80';
$PAGE_CRUMBS = [['name' => 'Services', 'url' => '/services'], ['name' => 'Transportation', 'url' => '/services/transportation']];
$DIVISION_ID = 'transportation';

$channels = [
    ['name' => 'Logistics', 'desc' => 'End-to-end supply chain coordination, warehousing and distribution.'],
    ['name' => 'Import', 'desc' => 'Full import support — clearance, compliance and delivery to your door.'],
    ['name' => 'Export', 'desc' => 'Export documentation, consolidation and international routing.'],
    ['name' => 'Freight', 'desc' => 'Sea, air and road freight forwarding with tracking on every leg.'],
    ['name' => 'Cargo', 'desc' => 'Secure cargo handling, haulage and last-mile delivery.'],
    ['name' => 'Shipping', 'desc' => 'Container and bulk shipping coordination across trade routes.'],
];

$PAGE = [
    'overview' => [
        'heading' => 'Keeping Supply Chains Moving',
        'image' => 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?auto=format&fit=crop&w=1200&q=80',
        'alt' => 'Shipping containers at a busy port',
        'cta_label' => 'Request a Quote',
        'cta_interest' => 'Transportation',
    ],
    'capabilities' => [
        'id' => 'channels',
        'eyebrow' => 'How We Move',
        'heading' => 'Logistics · Import · Export · Freight · Cargo · Shipping',
        'blurb' => 'Six coordinated capabilities that keep goods moving reliably across borders and markets.',
        'items' => $channels,
    ],
    'equipment' => [
        'eyebrow' => 'Fleet & Network',
        'heading' => 'Built for Reliability',
        'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1200&q=80',
        'alt' => 'BRADTEC logistics fleet and warehousing',
    ],
    'cta' => [
        'heading' => 'Connecting Markets. Moving Opportunities.',
        'copy' => 'Get a quote for your freight, import, export or logistics needs — fast and reliable.',
        'primary_label' => 'Request a Quote',
        'primary_interest' => 'Transportation',
        'secondary_label' => 'Import / Export Inquiry',
        'secondary_href' => $ROOT . 'contact?interest=Import/Export',
    ],
];

require __DIR__ . '/division_page.php';
