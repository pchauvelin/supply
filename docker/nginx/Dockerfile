FROM debian:jessie

MAINTAINER Philippe CHAUVELIN <philippe.chauvelin@webedia-group.com>

RUN apt-get update && apt-get install -y \
    nginx

ADD nginx.conf /etc/nginx/

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN usermod -u 1000 www-data

RUN echo 'alias ll="ls -lh"' >> ~/.bashrc

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
