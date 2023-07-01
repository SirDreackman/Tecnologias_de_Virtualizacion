# Implementación de contenedores en la nube de aws

El siguiente repositorio tiene como objetivo explicar paso a paso la implementación de contenedores en AWS y las herramientas asociadas.

------------

## Paso 1: Lanzar instancia de EC2
Generar una instancia EC2 con Amazon Linux 2 AMI de tipo T2.micro.
Utilizar par de claves vockey.
En detalles avanzados, introducir un script que contenga los comandos para implementar Dockers en el servidor:

	
   ```bash
 !/bin/bash
    #Actualizar el sistema operativo
    sudo yum update -y

    #Instalar el paquete amazon-linux-extras
    sudo yum install -y amazon-linux-extras

   # Habilitar el repositorio de Docker en Amazon Linux Extras
    sudo amazon-linux-extras enable docker

    #Instalar Docker
    sudo yum install -y docker

    #Iniciar el servicio de Docker
    sudo service docker start

    #Configurar Docker para que se inicie automáticamente
   sudo chkconfig docker on

   #Agregar el usuario actual al grupo docker
   sudo usermod -a -G docker ec2-user
```
	
	

 Lanzar instancia
Una vez creada la instancia, se debe modificar el grupo de seguridad creado por defecto, en donde se debe agregar el protocolo HTTP puerto 80 en las reglas de entrada. 

------------


## Paso 2:   Creación del archivo Dockerfile
Conectado a la instancia EC2, crear una carpeta dentro de la raíz, en este caso se genero una llamada mywordpress con el comando mkdir mywordpress. 

Dentro de la carpeta creada, se debe generar un archivo llamado Dockerfile, el cual contendrá las instrucciones para la implementación del contenedor, junto a este archivo genera el archivo de configuración wp-config.php con los parámetros de configuración,  en los siguientes links se muestran los archivos Dockerfile y wp-config.php:

https://raw.githubusercontent.com/SirDreackman/Tecnologias_de_Virtualizacion/main/Dockerfile

	Dockerfile 

https://raw.githubusercontent.com/SirDreackman/Tecnologias_de_Virtualizacion/main/wp-config.php

	wp-config.php



------------



## Paso 3: Creación instancia de base de datos en RDS
En la consola de AWS, buscar y seleccionar "RDS" para acceder al servicio de base de datos relacional.

- Hacer clic en "Crear base de datos" para comenzar el proceso de creación.
- En la página "Motor de base de datos", selecciona "Aurora" como el tipo de motor.
- A continuación, elige la edición que deseas utilizar, como "Aurora (compatible con MySQL)".
- Seleccionar Creación estándar.
- Configura las opciones de la base de datos, como el nombre de la instancia, el tipo de instancia, la capacidad de almacenamiento y la configuración de la red.
- En Plantillas seleccionar “Desarrollo y pruebas”.
- En conectividad seleccionar “Conectarse a un recurso informático de EC2.
- Seleccionar la instancia EC2 creada.
- En Grupo de subredes de la base de datos seleccionar “Configuración automática”.
- Crear base de datos.

### 3.1 Instalación base de datos en la instancia EC2

En la CLI del EC2 instalar mariadb con el siguiente comando

      yum install mariadb105-server-utils.x86_64

Este comando permite conectarse a la base de datos

     mysql -h databasewordp-instance-1.cv0flcl8po3i.us-east-1.rds.amazonaws.com -P 3306 -u admin -p 
     
*Ingresar password de base de datos: Duoc.2023*

una vez dentro, crear una base de datos para la conexión de WordPress en este caso fue nombrada como “wp”

Asiganar nombre a la base de datos.

    create database wp 

Asiganar permisos en la base de datos recién creada.

    grant all privileges on wp.* to admin 

Confirmar cambio

    flush privileges; 

------------

### 4 Comandos necesarios para permitir configurar y utilizar herramientas como AWS CLI y Amazon ECS

Descargar el archivo "awscliv2.zip" con el siguiente comando:

    curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"

Descomprir el archivo descargado:

    unzip awscliv2.zip

Ejecuta el script de instalación de AWS CLI:

    sudo ./aws/install

Configuración de las credenciales de AWS CLI:

Crear el directorio ~/.aws/:

    mkdir ~/.aws/

Editar el archivo ~/.aws/credentials con el editor de tu preferencia y pegar las credenciales de AWS CLI proporcionadas en el Lerner Lab:

    vim ~/.aws/credentials

Ejemplo de lo que se debe poner en el Vim:

    [default]
    aws_access_key_id=
    aws_secret_access_key=
    aws_session_token=

------------

### Paso 5: Creación de repositorio e imágen.

Los pasos para crear y configurar un repositorio ECR son los siguientes:

1. Buscar "ECR" .
2. Haz clic en "Crear repositorio" y configura la visibilidad del repositorio como "Privado".
3. Asigna un nombre al repositorio y haz clic en "Crear". 
4. Puedes dejar las demás configuraciones por defecto.
   
Luego de crear el repositorio, subiremos la imágen de contenedor al repositorio.

Buscar Ver comandos de envío y seguir los pasos.

- Recuperar token de autenticación y autentiquer el Docker.

       aws ecr get-login-password --region us-east-1 | sudo docker login --username AWS --password-stdin

-  Crear una imagen de Docker con el siguiente comando

        docker build -t wordpress .

- Cuando se complete la creación, etiquete la imagen para poder enviarla a este repositorio.

        docker tag wordpress:latest URIREPOSITORIO/wordpress:latest
  
- Ejecute el siguiente comando para enviar esta imagen al repositorio de AWS recién creado.

        docker push URIREPOSITORIO/wordpress:latest
  
------------

### Paso 6: Creación de servicio.

Se creará una definición de tarea.

- Asignar nombre a la familia de definición de tarea.
- Asignar otro nombre  y colocamos la uri de la imagen que está en el repositorio.
-  Habilitar puertos el puerto 80 HTTP.
- Entorno de la aplicación elegir "AWS FARGATE".
- Elegir sistema operativo Linux.
- Tamaño de la tarea elegir 2 vCPU y 4 GB de memoria
- En rol de tarea y rol de ejecución de tareas"labrole".
- Elegir almacenamiento efímero de valor minímo 21 GB.

Luego, se creará el cluster.

- Asignar nombre del clúster
- En redes elegimos todas

Por último, crear el servicio como tal.

- Escoger estrategia de proveedor de capacidad.
- En Familia elegir tarea creada.
- Asignar un nombre para el servicio.
- Tipo de servicio Réplica Tareas deseadas 1
- Dejar todas las subredes.
- Escoger Grupos de seguridad (EC2 , BBDD Y EC2 de la BBDD).
- Crear un nuevo balanceador de carga tipo ALB
- Asiganar nombre del balanceador de carga
- Asignar agente escucha puerto 80 HTTP.
- Crear nuevo grupo de destino
- Crear servicio.
  
------------
### Paso 7: Verificar funcionamiento de sitio Wordpress.

Para comprobar que el servicio se esta ejecutando de forma correcta debemos copiar el dns del load balancer en algun navegador en donde se debería ver la pagina de instalación Wordpress.

Mario bravo.
