################################################################################
# Base image
# Based on https://github.com/andrewmclagan/nginx-hhvm-docker
################################################################################

FROM nginx:1.11.3
MAINTAINER tim@savaslabs.com

################################################################################
# Install supervisor, PHP & tools
################################################################################

RUN apt-get clean && apt-get install debian-archive-keyring
RUN apt-get update -o Retries=25
RUN apt-get install -my -o Retries=25 \
	php5-fpm \
	php5-sqlite \
	php5-curl \
    curl \
	wget \
	sqlite3 \
	supervisor \
    && apt-get clean

################################################################################
# Install tools
################################################################################

RUN cd $HOME && \
    wget http://getcomposer.org/composer.phar && \
    chmod +x composer.phar && \
    mv composer.phar /usr/local/bin/composer && \
    wget https://phar.phpunit.de/phpunit.phar && \
    chmod +x phpunit.phar && \
    mv phpunit.phar /usr/local/bin/phpunit

################################################################################
# Configuration
##############################################################################

COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY ./docker/php-fpm.conf /etc/php-fpm.conf

COPY ./docker/nginx.conf /etc/nginx/nginx.conf

COPY ./docker/conf.d/config-1.conf /etc/nginx/conf.d/config-1.conf

COPY ./docker/sites-enabled/default /etc/nginx/sites-enabled/default

# Set clearenv = no in php-fpm pool so that environment variables persist.
RUN sed -i 's/;clear_env/clear_env/' /etc/php5/fpm/pool.d/www.conf

################################################################################
# Copy source and install dependencies
##############################################################################

COPY ./source/ /var/www/html
RUN mkdir /var/www/.composer; chown www-data:www-data /var/www/.composer; chown -R www-data:www-data /var/www/html
WORKDIR /var/www/html
USER www-data
RUN composer global require "hirak/prestissimo:^0.3"
RUN composer install --no-dev --no-plugins --no-scripts

################################################################################
# Boot
################################################################################

EXPOSE 80 443

USER root
CMD ["/usr/bin/supervisord"]
