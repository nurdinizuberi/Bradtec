<?php
$ROOT = '../../';
$PAGE_TITLE = 'Mining Services — BRADTEC CO. LTD';
$PAGE_DESC = 'Responsible resource extraction and mining solutions. Explore BRADTEC mining capabilities — exploration, extraction, processing and rehabilitation.';
$PAGE_OG_IMAGE = 'https://images.unsplash.com/photo-1600363503477-a8d1d6d57dfc?auto=format&fit=crop&w=1200&q=80';
$PAGE_CRUMBS = [['name' => 'Services', 'url' => '/services'], ['name' => 'Mining', 'url' => '/services/mining']];
$DIVISION_ID = 'mining';

$PAGE = [
    'overview' => [
        'heading' => 'Responsible Extraction. Lasting Value.',
        'image' => 'https://images.unsplash.com/photo-1695169152266-d9ac86fab9c5?auto=format&fit=crop&w=1200&q=80',
        'alt' => 'Large open-pit mining site with water body — aerial view',
        'cta_label' => 'Request Information',
        'cta_interest' => 'Mining',
    ],
    'capabilities' => [
        'eyebrow' => 'Mining Capabilities & Technology',
        'heading' => 'What We Deliver',
        'blurb' => 'Modern technology applied responsibly, from first survey to full rehabilitation.',
        'item_note' => 'Executed with certified safety and environmental systems.',
    ],
    'sections' => ['sustainability', 'safety'],
    'cta' => [
        'heading' => 'Responsible Resources. Sustainable Progress.',
        'copy' => 'Interested in our mining capabilities or resources? Get in touch and our team will respond promptly.',
        'primary_label' => 'Request Information',
        'primary_interest' => 'Mining',
    ],
];

require __DIR__ . '/division_page.php';
