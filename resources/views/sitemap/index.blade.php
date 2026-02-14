@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ url('/sitemap/products.xml') }}</loc>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemap/categories.xml') }}</loc>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemap/pages.xml') }}</loc>
    </sitemap>
</sitemapindex>
