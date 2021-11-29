# WordPress Docker development template

This template is made for local development only.

The following Docker images are included:
- [wordpress:latest](https://hub.docker.com/_/wordpress) (apache2 webserver included)
- [mysql:8.0](https://hub.docker.com/_/mysql)
- [phpmyadmin/phpmyadmin](https://hub.docker.com/r/phpmyadmin/phpmyadmin)
- [sj26/mailcatcher](https://hub.docker.com/r/sj26/mailcatcher)


**Project structure:**
- .docker: contains all docker-specific files and folders
- src: the source code goes here


## Install a new WordPress site

1. cd to .docker folder

2. Set your environment variables in .docker/.env (the defaults will also work)

3. Build docker containers the first time

```shell
(set -a; source .env; docker-compose up --build)
```

4. WordPress database installation with wp-cli. Run:

```shell
bin/setup
```

5. Install composer packages (composer.json is in the `/src` folder). Run

```shell
bin/composer install
```

TODO: Installing wordpress (that is already installed before) with composer returns an error. However, plugins and themes are installed properly despite of the error. Need to be resolved.

! IMPORTANT: If composer overwrites your wp-config.php, just replace it with the copy in `DB` folder (it loads the env vars from your .env file in `.docker' folder)

6. Add/update wp-config.php constants, values etc.

```shell
# List all currently defined configuration props 
wp config list

# Update salts used for hashing
wp config shuffle-salts

# Show debug information
wp config set WP_DEBUG true --raw
wp config set WP_DEBUG_LOG true --raw
wp config set WP_DEBUG_DISPLAY true --raw

# Force 'direct' filesystem method for WP (automatic detection doesn't work well in a Docker container)
wp config set FS_METHOD "'direct'" --type=constant --add --raw

# Disable core/plugin/theme modification
wp config set "DISALLOW_FILE_MODS" true --type=constant --add --raw
```


## Customisations made to wordpress:latest image

- wp-cli, and composer 2 was installed. (The official image does not hav it. There is a wordpress:cli image, but it only contains the wp-cli. In this image, apache2 is also configured. This is the reason it is used here.)
- For convinient work in the terminal, vim and nano is also installed. Use the text editor of your choice.
- php.ini setting change for mailcatcher (you can change the email to any fake one)
`sendmail_path = /usr/bin/env catchmail -f wordpress@local.test`


## Composer

Core, plugins and themes are installed/handled/updated/deleted with composer to keep track of the dependency versions.

Do not update wordpress/themes/plugins with the wp-cli, because it will lead to inconsistencies in versions defined in composer.json and the actual versions!

TODO: Make sure to disable wp-cli commands that modify themes, or plugins.

Modifying files on the wp admin can be disabled that will make changes to core, themes and plugins impossible on the admin dashboard.

```
bin/bash
wp config set "DISALLOW_FILE_MODS" true --type=constant --add --raw
```


## MySQL database management, PhpMyAdmin

- Import database: `bin/mysql-import`
- Export database: `bin/mysql-dump` 

All sql files should be put into `src/DB` folder.

Note: Auto-importing db dump is currently disabled. In docker-compose.yml, this is commented out:
`../src/DB:/docker-entrypoint-initdb.d # where to find the db dump data`

Make sure to replace urls in data tables (like https://example.com -> http://localhost:8000, or vice versa if needed)

Change wp-config.php database credentials if needed.

PhpMyAdmin service is accessible here: http://localhost:1337


## Manage docker containers

Make sure the host ports that are binded to our container's internal ports are not in use by another container or host process.

The containers need to be built for the first time:

```bash
(set -a; source .env; docker-compose up --build)
```
- Start containers: `bin/start`
- Stop containers: `bin/start`
- Down containers (this will terminate and remove containers): `bin/start`
- Restart containers (e.g. after a php.ini change): `bin/restart`


## Bash scripts in `bin` folder

Useful and makes it faster to run common tasks.


## Useful WP CLI commands

[Full documentation.](https://developer.wordpress.org/cli/commands/)

```shell
# CORE
wp help
wp core is-installed
wp core version
wp core check-update
#wp core update -> use composer instead
#wp core download -> use composer instead
wp core update-db


# REPLACE domain name - very useful
# Search and replace but skip one column
wp search-replace 'http://example.test' 'https://example.com' --skip-columns=guid

# Call get_bloginfo() to get the name of the site.
wp shell
get_bloginfo( 'name' );

# Flushes the object cache.
wp cache flush

# Flush rewrite rules
wp rewrite flush

# Update permalink structure
wp rewrite structure '/%year%/%monthnum%/%postname%'

# List rewrite rules
wp rewrite list --format=csv


# CONFIG
wp config shuffle-salts
wp config list

# show debug information
wp config set WP_DEBUG true --raw
wp config set WP_DEBUG_LOG true --raw
wp config set WP_DEBUG_DISPLAY true --raw

# force 'direct' filesystem method for WP (automatic detection doesn't work well in a Docker container)
wp config set FS_METHOD "'direct'" --type=constant --add --raw

# disable core/plugin/theme modification
wp config set "DISALLOW_FILE_MODS" true --type=constant --add --raw


# PLUGINS
# Activate plugin
wp plugin activate hello

# Deactivate plugin
wp plugin deactivate hello

# Delete plugin
# wp plugin delete hello -> use composer instead

# Install the latest version from wordpress.org and activate
# wp plugin install bbpress --activate  -> use composer instead

# Generate a new plugin with unit tests
wp scaffold plugin sample-plugin


# THEMES
# Generate theme based on _s
wp scaffold _s sample-theme --theme_name="Sample Theme" --author="John Doe"

# Generate code for post type registration in given theme
wp scaffold post-type movie --label=Movie --theme=simple-life

# Get status of theme
wp theme status twentysixteen
wp theme activate

# Install the latest version of a theme from wordpress.org and activate
# wp theme install twentysixteen --activate  -> use composer instead

# Get details of an installed theme
wp theme get twentysixteen --fields=name,title,version
```
