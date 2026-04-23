FROM nginx:1.27-alpine

WORKDIR /var/www/html

# Copia o frontend e backend já separados.
COPY frontend /var/www/html/frontend
COPY backend /var/www/html/backend
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
