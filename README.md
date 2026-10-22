# Roadside America API

<img src="https://roadside-america.dumbprojects.com/images/roadside-america.jpg"/>

Simple API to grab a random picture from a collection of pictures of American roadside attractions.

[Check out the demo!](https://roadside-america.dumbprojects.com/demo/)

[Check out the API!](https://roadside-america.dumbprojects.com/)

## Deployment

The API runs on Coolify from the `Dockerfile` in this repository, on the official `php:8.5-cli` image with PHP's
built-in web server. `router.php` keeps the repository's internal files and the `get-images.php` download scripts off
the site.

Coolify settings for the app:

- **Build Pack:** Dockerfile
- **Ports Exposes:** 8080

The `get-images.php` scripts download the full collection from the Library of Congress. Run them by hand with
`php get-images.php` from the folder you want to fill; they are not reachable from the website.

### Keeping PHP up to date

`php:8.5-cli` is a moving tag, so each fresh build picks up the newest PHP 8.5 patch release. A running container
never updates itself: **redeploy in Coolify to pick up a new PHP release.**

The **PHP currency** workflow (`.github/workflows/php-currency.yml`) checks every Monday that the live site runs the
newest 8.5 release and fails, which sends an email, when it does not. It can also be run by hand from the Actions tab.
GitHub turns scheduled workflows off after 60 days without activity in the repository and emails a warning first;
re-enable it from the Actions tab when that happens.

## License

[MIT License](LICENSE)

## AI Assistance Disclosure

This tool was developed with assistance from AI language models.

### Note:

The pictures are all in the public domain and provided by the Library of Congress.
