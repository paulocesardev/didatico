FROM php:7.2-apache

ARG	MAIN_USERNNAME
ARG	MAIN_UID

RUN apt-get update && apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
		libpq-dev \
		libbz2-dev \
		libicu-dev \
		libxml2-dev \
		libzip-dev \
	&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
	&& docker-php-ext-install -j$(nproc) gd \
	&& docker-php-ext-install  mysqli \
    && docker-php-ext-install  pdo pgsql pdo_pgsql \
    && docker-php-ext-install  bcmath \
    && docker-php-ext-install  bz2 \
    && docker-php-ext-install  intl \
    && docker-php-ext-install  xml \
    && docker-php-ext-install  zip 
	
RUN a2enmod rewrite
RUN sed -i 's/Options -Indexes/Options Indexes FollowSymLinks/' /etc/apache2/conf-available/docker-php.conf 

# allow devUser use sudo without password
RUN mkdir /etc/sudoers.d \
	&& echo $MAIN_USERNNAME ALL=\(root\) NOPASSWD:ALL > /etc/sudoers.d/$MAIN_USERNNAME \
    && chmod 0440 /etc/sudoers.d/$MAIN_USERNNAME


RUN useradd -ms /bin/bash -u $MAIN_UID $MAIN_USERNNAME

RUN curl  -L getcomposer.org/download/latest-stable/composer.phar -o composer.phar
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

EXPOSE 80