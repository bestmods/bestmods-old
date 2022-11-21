# <a href="https://bestmods.io/" target="_blank"><img src="https://github.com/BestMods/bestmods/blob/master/public/images/bestmods.png" data-canonical-src="https://github.com/BestMods/bestmods/blob/master/public/images/bestmods.png" /></a>
Browse the best mods in gaming from many sources on the Internet!

<a href="https://bestmods.io/" target="_blank"><img src="https://github.com/BestMods/bestmods/blob/master/preview.jpeg" data-canonical-src="https://github.com/BestMods/bestmods/blob/master/preview.jpeg" /></a>

## About This Project
An open-source [website](https://bestmods.io) made by [Christian Deacon](https://github.com/gamemann) that helps users find their favorite mods.

Please also check out the [@modcommunity](https://github.com/modcommunity)! They are doing things that will change the direction of gaming on a large scale by using modding and open source like never seen before!

## Road Map
A road map for the website may be found [here](https://github.com/bestmods/roadmap/issues)!

Each GitHub project represents a quarter and lists all things we're hoping to get completed by the end of said quarter.

## Contributing
Any help from the open source community is highly appreciated on this project! We utilize the following.

* [Laravel](https://laravel.com/) (PHP and Back-End).
* [Tailwind CSS](https://tailwindcss.com/) (HTML, CSS, and Front-End).
* [jQuery](https://jquery.com/) (JavaScript and Front-End).
* [DataTables](https://datatables.net/) (awesome jQuery library for loading millions of entries in a table).

Please take a look at our [road map](https://github.com/bestmods/roadmap/issues) and join our [Discord server](https://discord.moddingcommunity.com/) for communication!

## Our Community
[Best Mods](https://bestmods.io) is ran by the [The Modding Community](https://moddingcommunity.com/) which is a newer project taking modding to the next level by offering a unique marketplace, server and community browser & discovery, and forum! We have a Discord [here](https://discord.moddingcommunity.com/) if you want to socialize and interact with others including talented modders and content creators.

Additionally, you may also use our discussions forum [here](https://github.com/orgs/BestMods/discussions)!

### Social Media
* [TikTok](https://tiktok.com/@bestmodsio) (@bestmodsio)
* [Twitter](https://twitter.com/bestmodsio) (@bestmodsio)
* [Instagram](https://instagram.com/bestmodsio) (@bestmodsio)
* [Facebook](https://facebook.com/bestmodsio)
* [Linkedin](https://linkedin.com/company/bestmods)
* [Steam](https://steamcommunity.com/groups/best-mods)
* [Reddit](https://reddit.com/r/bestmods)

## Installation & Deployment
The project utilizes [Laravel](https://laravel.com/), [Tailwind CSS](https://tailwindcss.com/), [jQuery](https://jquery.com/), and [DataTables](https://datatables.net/) (for listing mods). Installation on a Linux server is fairly simple. However, I'd recommend making sure you have at least NodeJS `16.x`, Composer `2.2.x`, and NPM `8.x.x`.

Additionally, we currently use [Auth0](https://auth0.com/) for authentication with a custom role-based system. Users with the `Admin` role may create mods, seeds, and games through the `/create` route.

```bash
# Install PHP and required packages.
sudo apt install php8.1-common php8.1-curl php8.1-mysql php8.1-xml php8.1-dom

# Clone the repository.
git clone https://github.com/BestMods/bestmods.git

# Change directory.
cd bestmods/

# Clear Composer cache in-case.
composer clear-cache

# Composer install.
composer install

# NPM install.
npm install

# Copy environment file.
cp .env.example .env

# SETUP DATABASE.
# MODIFY .env FILE :: Change database details, URL, and application name.

# Generate a key.
php artisan key:generate

# Migrate database.
php artisan migrate

# Seed database.
php artisan db:seed

# Build CSS and JS files.
npm run build

# Finally, serve to http://localhost:8000 for testing.
php artisan serve

# For production, set debug to false in .env file and use a proper web server such as NGINX or Apache!

# SETUP AUTH0 AND ADJUST DETAILS IN .env FILE.
```

## Showcase
<a href="https://bestmods.io/view/cs-dynamicslots" target="_blank"><img src="https://github.com/BestMods/bestmods/blob/master/preview2.jpeg" data-canonical-src="https://github.com/BestMods/bestmods/blob/master/preview2.jpeg" /></a>
<a href="https://bestmods.io/view/cs-dynamicslots/install" target="_blank"><img src="https://github.com/BestMods/bestmods/blob/master/preview3.jpeg" data-canonical-src="https://github.com/BestMods/bestmods/blob/master/preview3.jpeg" /></a>
<a href="https://bestmods.io/view/cs-dynamicslots/downloads" target="_blank"><img src="https://github.com/BestMods/bestmods/blob/master/preview4.jpeg" data-canonical-src="https://github.com/BestMods/bestmods/blob/master/preview4.jpeg" /></a>

## Credits
* [Christian Deacon](https://github.com/gamemann) - Creator
* [The Modding Community](https://github.com/modcommunity)