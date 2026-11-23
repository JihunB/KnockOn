FROM php:8.0-apache 
COPY ./src /opt/bitnami/apache/htdocs/src
RUN docker-php-ext-install mysqli
EXPOSE 80
