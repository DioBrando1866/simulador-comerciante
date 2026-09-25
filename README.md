# Simulador de Comerciante (Proyecto DAW)

Simulador web de gestión y empeños desarrollado con **PHP 8.2**, **MySQL 8.0** y **Docker**, diseñado para ofrecer un entorno de desarrollo portable, reproducible y aislado.

---

## Requisitos previos

Asegúrate de tener instalado en tu sistema operativo:
* [Docker](https://www.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/)

---

## Cómo iniciar el proyecto

El proyecto está completamente contenerizado, por lo que no necesitas instalar PHP ni MySQL de forma local en tu máquina.

1. Clona o sitúate en la carpeta raíz del proyecto.
2. Levanta todos los servicios en segundo plano ejecutando el siguiente comando:

   ```bash
   docker compose up -d
3. Una vez iniciado los servicios accedemos a las siguientes URLs:

http://localhost:8000/