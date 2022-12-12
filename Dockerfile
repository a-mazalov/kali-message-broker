###########################################
# 
# APP
# 
# For development in Dev Container VScode
#
###########################################

FROM mazalov/php-8.1-cli:v1.0.2 as app

# Install git
RUN apt-get update && apt-get install -y git \
	&& rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
