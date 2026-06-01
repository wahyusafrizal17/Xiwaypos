<?php

namespace App\Support;

class MarketingSeo
{
    public static function siteUrl(): string
    {
        $configured = config('xiway.seo.site_url');

        if (is_string($configured) && $configured !== '') {
            return rtrim($configured, '/');
        }

        $domain = config('xiway.marketing_domain');

        if (is_string($domain) && $domain !== '') {
            $host = str_contains($domain, '://') ? $domain : 'https://'.$domain;

            return rtrim($host, '/');
        }

        return rtrim((string) config('app.url'), '/');
    }

    public static function canonical(string $path = '/'): string
    {
        $path = '/'.ltrim($path, '/');

        if ($path === '/') {
            return self::siteUrl();
        }

        return self::siteUrl().$path;
    }

    /** @return list<string> */
    public static function keywords(): array
    {
        return config('xiway.seo.keywords', []);
    }

    public static function keywordsString(): string
    {
        return implode(', ', self::keywords());
    }

    public static function ogImage(): string
    {
        $image = config('xiway.seo.og_image');

        if (is_string($image) && $image !== '') {
            return str_starts_with($image, 'http')
                ? $image
                : self::siteUrl().'/'.ltrim($image, '/');
        }

        return self::siteUrl().'/images/marketing/hero-slide-pos.png';
    }

    public static function robotsTxt(): string
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
        ];

        if (! config('xiway.marketing_domain')) {
            foreach (config('xiway.seo.disallow_paths', []) as $path) {
                $lines[] = 'Disallow: '.(str_starts_with($path, '/') ? $path : '/'.$path);
            }
        }

        $lines[] = '';
        $lines[] = 'Sitemap: '.self::siteUrl().'/sitemap.xml';

        return implode("\n", $lines)."\n";
    }

    /** @return list<array{loc: string, changefreq: string, priority: string}> */
    public static function sitemapEntries(): array
    {
        $entries = [
            ['path' => '/', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['path' => '/privacy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['path' => '/terms', 'changefreq' => 'yearly', 'priority' => '0.3'],
        ];

        return array_map(fn (array $entry) => [
            'loc' => self::canonical($entry['path']),
            'changefreq' => $entry['changefreq'],
            'priority' => $entry['priority'],
        ], $entries);
    }
}
