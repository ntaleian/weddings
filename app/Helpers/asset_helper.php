<?php

declare(strict_types=1);

if (! function_exists('asset_url')) {
    /**
     * Static asset URL with a cache-busting query parameter so returning visitors
     * fetch fresh CSS/JS after deploys. Uses the file's last-modified time when
     * the file exists under public/ or the project root; otherwise App::$assetVersion.
     */
    function asset_url(string $relativeUri): string
    {
        $relativeUri = ltrim($relativeUri, '/');
        $normalized = str_replace('/', DIRECTORY_SEPARATOR, $relativeUri);

        $candidates = [FCPATH . $normalized];
        if (defined('ROOTPATH')) {
            $candidates[] = ROOTPATH . $normalized;
        }

        $version = config(\Config\App::class)->assetVersion;
        foreach ($candidates as $path) {
            if (is_file($path)) {
                $version = (string) filemtime($path);
                break;
            }
        }

        $url = base_url($relativeUri);
        $separator = str_contains($url, '?') ? '&' : '?';

        return $url . $separator . 'v=' . rawurlencode($version);
    }
}
