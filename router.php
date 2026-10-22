<?php
// Request router for the built-in PHP server.
//
// The site runs `php -S` from the repository root, which means every file in the
// image is a URL. That includes the get-images.php scripts, which download the
// whole Library of Congress collection (11,704 images) to disk: one visit to
// /images/collection/large/get-images.php used to start that loop on the server.
// They are tools to run by hand, not part of the site.
//
// Deny a fixed set of paths and hand everything else back to the server
// unchanged by returning false, so static files, directory indexes and
// api/index.php behave exactly as before.

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = is_string($path) ? rawurldecode($path) : '/';

// Collapse repeated separators so //router.php cannot slip past a test.
$path = preg_replace('#/+#', '/', $path);

$internal = [
    // The image download scripts.
    '#/get-images\.php$#i',

    // Any dot-prefixed segment: .git, .gitignore, .htaccess, .env.
    '#/\.#',

    // Build and deployment files.
    '#^/(Dockerfile|router\.php)$#i',
    '#^/docker(/|$)#i',
];

foreach ($internal as $pattern) {
    if (preg_match($pattern, $path)) {
        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
        echo "Not Found\n";
        return true;
    }
}

return false;
