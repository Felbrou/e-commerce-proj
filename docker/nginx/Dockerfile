FROM nginx:alpine

# Copy nginx configuration
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Create log directory
RUN mkdir -p /var/log/nginx

WORKDIR /var/www/html
# Set permissions
# RUN chown -R www-data:www-data /var/www/html