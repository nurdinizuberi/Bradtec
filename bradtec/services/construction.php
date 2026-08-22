<?php
$ROOT = '../../';
$PAGE_TITLE = 'Construction Services — BRADTEC CO. LTD';
$PAGE_DESC = 'Building infrastructure and development solutions. Explore BRADTEC construction services — commercial, residential, roads, civil works and engineering services.';
$PAGE_OG_IMAGE = 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1200&q=80';
$PAGE_CRUMBS = [['name' => 'Services', 'url' => '/services'], ['name' => 'Construction', 'url' => '/services/construction']];
$DIVISION_ID = 'construction';

$PAGE = [
    'overview' => [
        'heading' => 'Engineering Structures That Last Generations',
        'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=80',
        'alt' => 'Architecture blueprints and construction planning',
        'cta_label' => 'Request a Quote',
        'cta_interest' => 'Construction',
    ],
    'capabilities' => [
        'eyebrow' => 'Construction Capabilities',
        'heading' => 'What We Deliver',
        'blurb' => 'Full-scope construction services managed with engineering discipline.',
        'item_note' => 'Delivered to the highest professional and safety standards.',
    ],
    'equipment' => [
        'eyebrow' => 'Equipment & Technology',
        'heading' => 'Modern Machinery. Modern Methods.',
        'image' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1200&q=80',
        'alt' => 'BRADTEC heavy machinery and construction equipment on site',
    ],
    'cta' => [
        'heading' => 'Building Today. Shaping Tomorrow.',
        'copy' => 'Tell us about your construction project — we\'ll respond with a professional proposal and quote.',
        'primary_label' => 'Request a Quote',
        'primary_interest' => 'Construction',
        'secondary_label' => 'See Our Projects',
        'secondary_href' => $ROOT . 'projects',
    ],
];

require __DIR__ . '/division_page.php';
