# imagen a base de wordpress
FROM wordpress:latest

# Copiar el archivo de configuración personalizado a la imagen de WordPress
COPY wp-config.php /var/www/html/wp-config.php

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y php-cli

# Ofuscar el archivo wp-config.php
RUN php -f /var/www/html/wp-config.php -- --obfuscate --replace-string-names

# Establecer permisos adecuados
RUN chown www-data:www-data /var/www/html/wp-config.php && chmod 400 /var/www/html/wp-config.php
