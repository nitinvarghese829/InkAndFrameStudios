<?php

namespace App\Service;

use App\Repository\ServicesRepository;
use App\Repository\BlogRepository;
use Symfony\Component\Routing\RouterInterface;

class SitemapGenerator
{
    private $servicesRepo;
    private $blogRepo;
    private $router;

    public function __construct(ServicesRepository $servicesRepo, BlogRepository $blogRepo, RouterInterface $router)
    {
        $this->servicesRepo = $servicesRepo;
        $this->blogRepo = $blogRepo;
        $this->router = $router;
    }

    public function generate(): void
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset/>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xml->addAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 
                        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

        $urls = [];

        // Static routes
        foreach ($this->getAllRoutes() as $route) {
            $urls[] = [
                'loc' => 'https://inkandframestudios.com' . $route,
                'lastmod' => null
            ];
        }

        // Services
        foreach ($this->servicesRepo->findBy(['isActive' => true]) as $service) {
            $urls[] = [
                'loc' => 'https://inkandframestudios.com/service/' . $service->getSlug(),
                'lastmod' => $service->getUpdatedAt() ? $service->getUpdatedAt()->format('Y-m-d') : null,
            ];
        }

        // Blogs
        foreach ($this->blogRepo->findBy(['isActive' => true]) as $blog) {
            $urls[] = [
                'loc' => 'https://inkandframestudios.com/blog/' . $blog->getSlug(),
                'lastmod' => $blog->getCreatedAt() ? $blog->getCreatedAt()->format('Y-m-d') : null,
            ];
        }

        // Add to XML
        foreach ($urls as $item) {
            $url = $xml->addChild('url');
            $url->addChild('loc', htmlspecialchars($item['loc'], ENT_QUOTES, 'UTF-8'));
            if (!empty($item['lastmod'])) {
                $url->addChild('lastmod', $item['lastmod']);
            }
            $url->addChild('changefreq', 'weekly');
        }

        // Save to public folder
        $xml->asXML(__DIR__ . '/../../public/sitemap.xml');
    }

    public function getAllRoutes(): array
    {
        $allRoutes = $this->router->getRouteCollection();
        $urls = [];

        foreach ($allRoutes as $name => $route) {
            $path = $route->getPath();

            // Skip admin, debug, or system routes
            if (
                str_starts_with($path, '/admin') ||
                str_starts_with($path, '/_') || // e.g., _profiler, _wdt, etc.
                str_contains($name, 'debug') || str_contains($path, '{')
            ) {
                continue;
            }

            $urls[] = $path;
        }

        return array_unique($urls);
    }
}
