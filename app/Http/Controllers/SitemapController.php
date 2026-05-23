<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticPages = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('terms'), 'priority' => '0.3', 'changefreq' => 'monthly'],
            ['loc' => route('privacy'), 'priority' => '0.3', 'changefreq' => 'monthly'],
            ['loc' => route('contact'), 'priority' => '0.3', 'changefreq' => 'monthly'],
        ];

        $profiles = Profile::where('is_published', true)
            ->whereHas('user', fn ($q) => $q->where('status', User::STATUS_ACTIVE))
            ->select('slug', 'updated_at')
            ->orderByDesc('updated_at')
            ->get();

        $xml = view('sitemap', compact('staticPages', 'profiles'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
