# Imagen base en wordpress
FROM wordpress:latest

# Directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Copiar el archivo wp-config.php personalizado
COPY wp-config.php .

# Permisos para el archivo wp-config.php
RUN chown www-data:www-data wp-config.php && chmod 600 wp-config.php

# Puerto expuesto por el contenedor
EXPOSE 80
