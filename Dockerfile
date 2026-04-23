FROM nginx:1.27-alpine

WORKDIR /usr/share/nginx/html

# Mantém o build simples para validar deploy no Coolify.
COPY index.html ./index.html
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
