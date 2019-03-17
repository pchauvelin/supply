# See https://github.com/docker-library/php/blob/master/7.1/fpm/Dockerfile
FROM php:7.2-fpm
ARG TIMEZONE

MAINTAINER Philippe CHAUVELIN <philippe.chauvelin@webedia-group.com>

RUN mkdir /root/.ssh/
RUN mkdir -p /var/lib/php/sessions
RUN chmod 777 /var/lib/php/sessions/ -R

ADD ./conf/php.ini /usr/local/etc/php/
ADD ./conf/php.ini /usr/local/etc/php/conf.d/

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone
RUN printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini
RUN "date"

# Locales
RUN apt-get update \
    && apt-get install -y locales

RUN dpkg-reconfigure locales \
    && locale-gen C.UTF-8 \
    && /usr/sbin/update-locale LANG=C.UTF-8

RUN echo 'fr_FR.UTF-8 UTF-8' >> /etc/locale.gen \
    && locale-gen

ENV LC_ALL C.UTF-8
ENV LANG fr_FR.UTF-8
ENV LANGUAGE fr_FR.UTF-8


# PHP
# intl
RUN apt-get update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# xml
RUN apt-get update \
    && apt-get install -y \
    libxml2-dev \
    libxslt-dev \
    && docker-php-ext-install \
        dom \
        xmlrpc \
        xsl

# database
RUN docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql

# strings
RUN docker-php-ext-install \
    gettext \
    mbstring

# compression
RUN apt-get update \
    && apt-get install -y \
    libbz2-dev \
    zlib1g-dev \
    && docker-php-ext-install \
        zip \
        bz2

# ftp
RUN apt-get update \
    && apt-get install -y \
    libssl-dev \
    && docker-php-ext-install \
        ftp

# ssh2
RUN apt-get update \
    && apt-get install -y \
    libssh2-1-dev

# others
RUN docker-php-ext-install \
    soap \
    sockets \
    calendar \
    sysvmsg \
    sysvsem \
    sysvshm

# PECL




# install xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo 'alias ll="ls -lah"' >> ~/.bashrc


WORKDIR /var/www/
