<?php

declare(strict_types=1);

namespace WebVision\WvT3unity\UserFunc;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use WebVision\WvT3unity\Event\ManipulateHeadDataEvent;

#[Autoconfigure(public: true)]
class ContentJson
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public function getHeadData(string $content, array $configuration, ServerRequestInterface $request): string
    {
        $pageData = $this->getPageDataFromRequest($request);

        $seoData = [
            'title' => $pageData['title'] ?? '',
            'subtitle' => $pageData['subtitle'] ?? '',
            'description' => $pageData['description'] ?? '',
            'abstract' => $pageData['abstract'] ?? '',
            'keywords' => $pageData['keywords'] ?? '',
            'author' => $pageData['author'] ?? '',
            'author_email' => $pageData['author_email'] ?? '',
            'seo_title' => $pageData['seo_title'] ?? '',
            'canonical_link' => $pageData['canonical_link'] ?? '',
            // This seems custom from t3_unity and should probably be removed in the future
            'canonical_url' => $pageData['canonical_url'] ?? '',
            'og_title' => $pageData['og_title'] ?? '',
            'og_description' => $pageData['og_description'] ?? '',
            'og_image' => $pageData['og_image'] ?? '',
            'twitter_title' => $pageData['twitter_title'] ?? '',
            'twitter_description' => $pageData['twitter_description'] ?? '',
            'twitter_image' => $pageData['twitter_image'] ?? '',
            'twitter_card' => $pageData['twitter_card'] ?? '',
            'robots' => $pageData['robots'] ?? '',
        ];

        $manipulateHeadDataEvent = $this->eventDispatcher->dispatch(new ManipulateHeadDataEvent($seoData, $request, $configuration));

        return $this->encodeToJson($manipulateHeadDataEvent->headData);
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public function getPageData(string $content, array $configuration, ServerRequestInterface $request): string
    {
        $pageData = $this->getPageDataFromRequest($request);
        return $this->encodeToJson($pageData);
    }

    /**
     * @return array<string, mixed>
     */
    private function getPageDataFromRequest(ServerRequestInterface $request): array
    {
        $frontendController = $request->getAttribute('frontend.controller');
        if (!$frontendController instanceof TypoScriptFrontendController) {
            return [];
        }
        return $frontendController->cObj->data;
    }
    /**
     * @param array<string, string> $data
     */
    private function encodeToJson(array $data): string
    {
        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->logger->error('JSON encoding error: ' . $e->getMessage());
            return '';
        }
    }
}
