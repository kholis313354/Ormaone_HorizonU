<?php

if (!function_exists('clean_html')) {
    /**
     * Sanitizes HTML content to prevent XSS while allowing basic formatting.
     * This is a "Layered Security" measure for rich text inputs.
     *
     * @param string|null $content
     * @return string
     */
    function clean_html($content)
    {
        if (empty($content)) {
            return '';
        }

        // 1. Remove script tags and their content
        $content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);

        // 2. Remove potentially dangerous attributes (onEvent, javascript:, vbscript:)
        // This regex looks for on[a-z]+ handler attributes
        $content = preg_replace('/(<[^>]+) on[a-z]+\s*=\s*(?:".*?"|\'.*?\'|[^\s>]+)([^>]*>)/i', '$1$2', $content);

        // Remove javascript: pseudo-protocol in href/src
        $content = preg_replace('/(<[^>]+) (href|src)\s*=\s*(?:"\s*javascript:[^"]*"|\'\s*javascript:[^\']*\')([^>]*>)/i', '$1$3', $content);

        // 3. Strip tags except allowed ones (Whitelist approach)
        // Allowed: p, b, strong, i, em, u, br, ul, ol, li, h1-h6, blockquote, img, a, span, div, table, tr, td, th
        $allowedTypes = '<b><strong><i><em><u><br><p><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><img><a><span><div><table><tr><td><th><thead><tbody>';
        $content = strip_tags($content, $allowedTypes);

        return $content;
    }
}
