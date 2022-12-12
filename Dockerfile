###########################################
# 
# COMPOSER STAGE
# We need to build the Composer base to reuse packages we've installed
#
###########################################

FROM composer:2.1 as composer_base

# First, create the application directory, and some auxilary directories for scripts and such
RUN mkdir -p /opt/apps/laravel

# Next, set our working directory
WORKDIR /opt/apps/laravel

# We need to create a composer group and user, and create a home directory for it, so we keep the rest of our image safe,
# And not accidentally run malicious scripts
RUN addgroup -S composer \
    && adduser -S composer -G composer \
    && chown -R composer /opt/apps/laravel

# Next we want to switch over to the composer user before running installs.
# This is very important, so any extra scripts that composer wants to run,
# don't have access to the root filesystem.
# This especially important when installing packages from unverified sources.
USER composer

# Copy in our dependency files.
# We want to leave the rest of the code base out for now,
# so Docker can build a cache of this layer,
# and only rebuild when the dependencies of our application changes.
COPY --chown=composer composer.json composer.lock ./

# Install all the dependencies without running any installation scripts.
# We skip scripts as the code base hasn't been copied in yet and script will likely fail,
# as `php artisan` available yet.
# This also helps us to cache previous runs and layers.
# As long as comoser.json and composer.lock doesn't change the install will be cached.
RUN composer install --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs

# Copy in our actual source code so we can run the installation scripts we need
# At this point all the PHP packages have been installed, 
# and all that is left to do, is to run any installation scripts which depends on the code base
COPY --chown=composer . .

# Now that the code base and packages are all available,
# we can run the install again, and let it run any install scripts.
RUN composer install --prefer-dist --ignore-platform-reqs

###########################################
# 
# APP STAGE
# 
# For development in Dev Container VScode
#
###########################################

FROM mazalov/php-8.1-cli:v1.0.2 as app

# Install git
RUN apt-get update && apt-get install -y git \
	&& rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
