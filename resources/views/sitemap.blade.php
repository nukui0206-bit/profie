<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($staticPages as $page)
    <url>
        <loc>{{ $page['loc'] }}</loc>
        <priority>{{ $page['priority'] }}</priority>
        <changefreq>{{ $page['changefreq'] }}</changefreq>
    </url>
@endforeach
@foreach ($profiles as $profile)
    <url>
        <loc>{{ url('/u/' . $profile->slug) }}</loc>
        <lastmod>{{ $profile->updated_at->toAtomString() }}</lastmod>
        <priority>0.7</priority>
        <changefreq>weekly</changefreq>
    </url>
@endforeach
</urlset>
