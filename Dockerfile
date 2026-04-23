FROM nginx:1.27-alpine

WORKDIR /var/www/html

# Copia a aplicação para ser servida pelo Nginx.
COPY . /var/www/html
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
