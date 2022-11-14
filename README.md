# <a href="https://bestmods.io/" target="_blank"><img src="https://github.com/BestMods/bestmods/blob/master/public/images/bestmods.png" data-canonical-src="https://github.com/BestMods/bestmods/blob/master/public/images/bestmods.png" /></a>
Browse the best mods in gaming from many sources on the Internet!

<a href="https://bestmods.io/" target="_blank"><img src="https://github.com/BestMods/bestmods/blob/master/preview.jpeg" data-canonical-src="https://github.com/BestMods/bestmods/blob/master/preview.jpeg" /></a>
<a href="https://bestmods.io/view/csgoesp" target="_blank"><img src="https://github.com/BestMods/bestmods/blob/master/preview2.jpeg" data-canonical-src="https://github.com/BestMods/bestmods/blob/master/preview2.jpeg" /></a>
<a href="https://bestmods.io/view/csgoesp?view=install" target="_blank"><img src="https://github.com/BestMods/bestmods/blob/master/preview3.jpeg" data-canonical-src="https://github.com/BestMods/bestmods/blob/master/preview3.jpeg" /></a>

[BestMods.io](https://bestmods.io/)

## About This Project
An open-source [website](https://bestmods.io) and project made by [Christian Deacon](https://github.com/gamemann) that helps users find their favorite mods. This project is powered by the [@modcommunity](https://github.com/modcommunity)!

Help support this project and modding in general by spreading the word!

## Road Map
A road map for the website may be found [here](https://github.com/orgs/BestMods/projects/1)! Each view represents a month and all items within that view is projected to be completed by the end of the specific month.

## Installation & Deployment
The project utilizes [Laravel](https://laravel.com/), [Tailwind CSS](https://tailwindcss.com/), [jQuery](https://jquery.com/), and [DataTables](https://datatables.net/) (for listing mods). Installation on a Linux server is fairly simple. However, I'd recommend making sure you have at least NodeJS `16.x`, Composer `2.2.x`, and NPM `8.x.x`.

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

# SETUP AUTH0 AND PUT INFO IN .env FILE.

```

## Community
Best Mods is ran by the [The Modding Community](https://ModdingCommunity.com/) which is a newer project taking modding to the next level by offering a unique marketplace, server and community browser & discovery, and forum! We have a Discord [here](https://dsc.gg/modcommunity) if you want to socialize and interact with others including talented modders and content creators.

Additionally, for Best Mods specifically, you may also use our discussions forum on GitHub [here](https://github.com/orgs/BestMods/discussions)!

### Contributions
Contributions are always welcomed! Feel free to submit pull requests if you see anything that may be improved or you want to help out with the project in general!

## Credits
* [Christian Deacon](https://github.com/gamemann) - Creator
* [The Modding Community](https://github.com/modcommunity)