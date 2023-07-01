### Implementación de contenedores en la nube de aws

El siguiente repositorio tiene como objetivo explicar paso a paso la implementación de contenedores en AWS y las herramientas asociadas.

------------

## 1° Paso: Lanzar instancia de EC2
Generar una instancia EC2 con Amazon Linux 2 AMI de tipo T2.micro.
Utilizar par de claves vockey.
En detalles avanzados, introducir un script que contenga los comandos para implementar Dockers en el servidor:

		!/bin/bash
	 Actualizar el sistema operativo
		sudo yum update -y
	 Instalar el paquete amazon-linux-extras
		sudo yum install -y amazon-linux-extras
	Habilitar el repositorio de Docker en Amazon Linux Extras
		sudo amazon-linux-extras enable docker
	 Instalar Docker
		sudo yum install -y docker
	Iniciar el servicio de Docker
		sudo service docker start
	Configurar Docker para que se inicie automáticamente
		sudo chkconfig docker on
	Agregar el usuario actual al grupo docker
		sudo usermod -a -G docker ec2-user	
	

 Lanzar instancia
Una vez creada la instancia, se debe modificar el grupo de seguridad creado por defecto, en donde se debe agregar el protocolo HTTP puerto 80 en las reglas de entrada. 

------------


#Paso 2:   Creación del archivo Dockerfile
Conectado a la instancia EC2, crear una carpeta dentro de la raíz, en este caso se genero una llamada mywordpress con el comando mkdir mywordpress. 

Dentro de la carpeta creada, se debe generar un archivo llamado Dockerfile, el cual contendrá las instrucciones para la implementación del contenedor, junto a este archivo genera el archivo de configuración wp-config.php con los parámetros de configuración,  en los siguientes links se muestran los archivos Dockerfile y wp-config.php:

	Dockerfile 
**https://raw.githubusercontent.com/SirDreackman/Tecnologias_de_Virtualizacion/main/Dockerfile
**

	wp-config.php
**https://raw.githubusercontent.com/SirDreackman/Tecnologias_de_Virtualizacion/main/wp-config.php**


------------



#Paso 3: Creación instancia de base de datos en RDS
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

Conexión a la base de datos creada previamente.
En la CLI agregamos el siguiente comando:

     mysql -h databasewordp-instance-1.cv0flcl8po3i.us-east-1.rds.amazonaws.com -P 3306 -u admin -p 
este comando permite conectarse a la base de datos
ingresar password de base de datos: Duoc.2023

una vez dentro, crear una base de datos para la conexión de WordPress en este caso fue nombrada como “wp”
create database wp #Asiganar nombre a la base de datos.
grant all privileges on wp.* to admin #Asiganar permisos en la base de datos recién creada.
flush privileges; #Confirmar cambio

