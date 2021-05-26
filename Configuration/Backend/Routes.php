<?php
return [
    'mageblockcache' => [
        'path' => '/mageblockcache',
        'target' => \WebVision\WvT3unity\Utility\ClearCustomCache::class . '::clear',
    ]
];
