<?php

use WebVision\WvT3unity\Utility\ClearCustomCache;
return [
    'mageblockcache' => [
        'path' => '/mageblockcache',
        'target' => ClearCustomCache::class . '::clear',
    ]
];
