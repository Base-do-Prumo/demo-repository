FROM node:22-alpine AS frontend_builder

WORKDIR /app/frontend
COPY frontend/package*.json ./
RUN npm install
COPY frontend ./
RUN npm run build

FROM nginx:1.27-alpine

WORKDIR /var/www/html
COPY --from=frontend_builder /app/frontend/dist /var/www/html/frontend/dist
COPY backend /var/www/html/backend
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
