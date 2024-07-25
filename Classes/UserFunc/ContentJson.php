<?php

declare(strict_types=1);

namespace WebVision\WvT3unity\UserFunc;
use Psr\Http\Message\ServerRequestInterface;

class ContentJson
{
    public function getPageData(string $content, array $configuration, ServerRequestInterface $request): string | bool
    {
        $frontendController = $request->getAttribute('frontend.controller');
        $data['pageData'] = $frontendController->cObj->data;

        return json_encode($data);
    }
}
