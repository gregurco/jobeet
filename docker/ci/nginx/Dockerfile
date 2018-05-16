FROM nginx:alpine

WORKDIR "/application"

COPY . /application
COPY /docker/ci/nginx/nginx.conf /etc/nginx/conf.d/default.conf

