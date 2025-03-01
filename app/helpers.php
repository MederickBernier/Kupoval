<?php

/**
 * Throws a custom exception with the given message, status code, and additional data.
 *
 * @param string $message The error message.
 * @param int $statusCode The HTTP status code for the error (default is 500).
 * @param array $data Additional data to include with the error (default is an empty array).
 *
 * @throws App\Exceptions\CustomException
 */
if (!function_exists('throwError')) {
    function throwError(string $message, int $statusCode = 500, array $data = [])
    {
        throw new App\Exceptions\CustomException($message, $statusCode, $data);
    }
}

/**
 * Check if the user is allowed to access a resource.
 *
 * This function checks if the provided user has the 'admin' role.
 * If the user is not an admin or if no user is provided, it aborts
 * the request with a 403 status code and an 'Unauthorized access' message.
 *
 * @param object|null $user The user object to check. It should have a 'role' property.
 * @return bool Returns true if the user is an admin.
 * @throws \Symfony\Component\HttpKernel\Exception\HttpException If the user is not allowed.
 */
if (!function_exists('isAllowed')) {
    function isAllowed($user)
    {
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }
        return true;
    }
}

/**
 * Maps a given locale string to a Stripe-compatible locale string.
 *
 * This function checks if the provided locale exists in the predefined
 * list of locales and returns the corresponding Stripe locale. If the
 * provided locale is not found, it defaults to 'fr-CA'.
 *
 * @param string $locale The locale string to be mapped.
 *
 * @return string The corresponding Stripe-compatible locale string.
 */
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

/**
 * Extracts static content from a given page object based on the specified locale.
 *
 * This function checks if the page object exists. If it does not, it returns a default
 * array with a missing title, empty content, and empty meta description. If the page
 * object exists, it decodes the JSON-encoded title, content, and meta description fields
 * for the specified locale. If the locale-specific content is not available, it falls
 * back to the 'enca' locale.
 *
 * Additionally, it modifies the content by adding specific classes to <h2> tags.
 *
 * @param object|null $page The page object containing the static content.
 * @param string $locale The locale to extract the content for.
 * @return array An associative array containing the title, content, and meta description.
 */
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
