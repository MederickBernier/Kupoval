<?php

if (!function_exists('throwError')) {
    function throwError(string $message, int $statusCode = 500, array $data = [])
    {
        throw new App\Exceptions\CustomException($message, $statusCode, $data);
    }
}

if (!function_exists('isAllowed')) {
    function isAllowed($user)
    {
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }
        return true;
    }
}

if (!function_exists('mapStripeLocale')) {
    function mapStripeLocale(string $locale): string
    {
        $locales = [
            'enca' => 'en-CA',
            'frca' => 'fr-CA',
        ];

        return $locales[$locale] ?? 'fr-CA';
    }
}

if (!function_exists('extractStaticContent')) {
    function extractStaticContent($page, $locale)
    {
        if (!$page) {
            return [
                'title' => 'public/interface.about_missing',
                'content' => '',
                'meta_description' => ''
            ];
        }

        $title = json_decode($page->title, true)[$locale] ?? json_decode($page->title, true)['enca'];
        $content = json_decode($page->content, true)[$locale] ?? json_decode($page->content, true)['enca'];
        $meta_description = json_decode($page->meta_description, true)[$locale] ?? json_decode($page->meta_description, true)['enca'];

        $content = preg_replace('/<h2(.*?)>(.*?)<\/h2>/', '<h2 class="mt-8 text-3xl font-semibold text-heading"$1>$2</h2>', $content);

        return [
            'title' => $title,
            'content' => $content,
            'meta_description' => $meta_description
        ];
    }
}
