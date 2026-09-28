<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($staticRoutes as $entry)
        <url>
            <loc>{{ route($entry['route']) }}</loc>
            <priority>{{ $entry['priority'] }}</priority>
        </url>
    @endforeach

    @foreach ($articles as $article)
        <url>
            <loc>{{ route('education.show', $article) }}</loc>
            <lastmod>{{ $article->updated_at->toAtomString() }}</lastmod>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>
