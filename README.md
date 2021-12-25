# WordPress Docker development template

This template is made for local development only.

There are 2 versions of the environment:
- Recommended: WordPress Core files moved into a separate folder (referred to as **wp_core_separate**)
- Also works: The WordPress Core files in the root folder (referred to as **wp_core_default**)

The following Docker images are included:
- [wordpress:latest](https://hub.docker.com/_/wordpress) (apache2 webserver included)
- [mysql:8.0](https://hub.docker.com/_/mysql)
- [phpmyadmin/phpmyadmin](https://hub.docker.com/r/phpmyadmin/phpmyadmin)
- [sj26/mailcatcher](https://hub.docker.com/r/sj26/mailcatcher)


**Project structure:**
- .docker: contains all docker-specific files and folders
- src: the source code goes here


## Install a new, or setup an existing WordPress site

### HTTPS

Use the `master` branch which is the default!

1. Set your environment variables in `.env`, change `APP_NAME`
2. Setup ssl for your custom domain `${APP_NAME}.local`
```shell
bin/setup-ssl
```
You may need to make scripts in the `bin` folder, and the `.docker/images/nginx/ssl/mkcert-v1.4.3-linux-amd64` executable. For Mac or Windows you will need a different mkcert executable / installer. Links:

- Windows: https://github.com/FiloSottile/mkcert#windows
- Mac: https://github.com/FiloSottile/mkcert#macos
- Linux: https://github.com/FiloSottile/mkcert#linux or use pre-build binaries: https://github.com/FiloSottile/mkcert/releases

The supplied mkcert version is for Debian/Ubuntu.

3. Modify `.docker/images/nginx/conf.d/default.conf`:
Change `server_name` to your custom domain! It is not automatically rewritten because nginx container gets the file from volume binding.

3. Add domain alias for 127.0.0.1 (e.g. `vim /etc/hosts`)
4. Build docker project:
```shell
(set -a;source .env;docker-compose -f docker-compose-ssl.yml up --build)
```

5. WordPress database installation with wp-cli, import db for existing sites

To put WP core to separate folder set `WP_CORE_SEPARATE` var to `"false"`

- For a new site run:
```shell
bin/setup-wp
```

- For an existing site, import db dump:

```shell
bin/mysql-import
```
The db dump should be placed into this folder with the same filename: `db/db.sql`

For existing sites, replace domain in database tables (enter container first with bash):

```shell
bin/bash
wp search-replace 'https://example.com' 'https://example.local' --skip-columns=guid
```
Overwrite wp-content/ with yours.

No need to change any additional files. The `wp-config.php` loads all credentials from the `.env` file. Every bash script in bin folder loads environment variables from `.env`.

Create a new admin user if needed, example:

```shell
bin/bash
wp user create andras andras.gulacsi@drb.services --role=administrator --user_pass=Andras123!
```

### HTTP

Installation is almost the same as for https. We just need to use another docker-compose file

```shell
(set -a;source .env;docker-compose -f docker-compose.yml up --build)
```

## Configure WordPress local site

1. Install composer packages (composer.json is in the `/src` folder). Run

```shell
bin/composer install
```
! NOTICE: Installing or updating WordPress with composer returns an non-breaking error. However, plugins and themes are installed properly despite of the error. Need to be resolved.

! IMPORTANT: (**wp_core_default**) -> modify WordPress installation dir from `src/wp` to `src`!

! IMPORTANT: (**wp_core_default**) -> If composer overwrites your `wp-config.php`, just replace it with the copy in `.docker/images/wordpress` folder (it loads the env vars from your .env file in `.docker` folder). This does not apply for the **wp_core_separate** case (install path is the wp folder, but the wp-config file used is in the root (customized with `bin/setup-wp` script).


2. Add/update wp-config.php constants, values etc. (optional, not needed generally)

```bash
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

- wp-cli, and composer 2 was installed. (The official image does not have it. There is a wordpress:cli image, but it only contains the wp-cli. In this image, apache2 is also configured. This is the reason it is used here.)
- For convenient work in the terminal, vim and nano is also installed. Use the text editor of your choice.
- php.ini setting change for mailcatcher (you can change the email to any fake one)
`sendmail_path = /usr/bin/env catchmail -f wordpress@local.test`
- upload_max_filesize = 20M


## Composer

Core, plugins and themes are installed/handled/updated/deleted with composer to keep track of the dependency versions.

Do not update wordpress/themes/plugins with the wp-cli, because it will lead to inconsistencies in versions defined in composer.json and the actual versions installed!

Modifying files on the wp-admin can be disabled that will make changes to core, themes and plugins impossible on the admin dashboard.

```bash
bin/bash
wp config set "DISALLOW_FILE_MODS" true --type=constant --add --raw
```



## MySQL database management, PhpMyAdmin

- Import database: `bin/mysql-import`
- Export database: `bin/mysql-dump` 

All sql files should be put into the `db` folder.

Note: Auto-importing db dump is currently disabled. In docker-compose.yml, this is commented out:
`./db:/docker-entrypoint-initdb.d # where to find the db dump data`

It is a better idea to use the `bin/mysql-import` script instead.

Make sure to replace urls in data tables (like https://example.com -> https://example.local). The easiest way is to use the wp-cli:

```shell
bin/bash
wp search-replace 'https://example.com' 'https://example.local' --skip-columns=guid
```

PhpMyAdmin service is accessible here: http://localhost:1337


## Manage docker containers

Make sure the host ports that are binded to our container's internal ports are not in use by another container or host process.

The containers need to be built for the first time:

```bash
(set -a;source .env;docker-compose -f docker-compose.yml up --build)
```
```bash
(set -a;source .env;docker-compose -f docker-compose-ssl.yml up --build)
```

- Start containers: `bin/start`
- Stop containers: `bin/stop`
- Down containers (this will remove and destroy containers): `bin/down`
- Restart containers: `bin/restart`


## Bash scripts in `bin` folder

Useful and makes it faster to run common tasks. Check them out.


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
