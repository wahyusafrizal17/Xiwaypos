<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Support\MarketingSeo;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        return response(MarketingSeo::robotsTxt(), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    public function sitemap(): Response
    {
        return response()
            ->view('marketing.sitemap', [
                'entries' => MarketingSeo::sitemapEntries(),
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
