# Práctica 2.1 · Nginx y PHP en Docker
Autora: Manuel Rodríguez Sutil · 2.º DAW · CIFP Zonzamas

## Qué es
Dos servicios (`web` y `php`) desplegados con Docker Compose:
 
- **web** (`nginx:1.30-alpine`) Sirve el sitio estático `manuel.dpl.test` y el sitio PHP `php.manuel.dpl.test`. Cuando le piden un `.php`, se lo pasa por FastCGI al servicio `php`.
- **php** (`php:8.4-fpm-alpine`) ejecuta el código PHP y devuelve el HTML a Nginx. Escucha en el puerto 9000 de la red interna de Compose y no publica ningún puerto, así que desde fuera no es accesible.

La configuración, las webs y los límites de subida están en carpetas del proyecto montadas dentro de los contenedores, de modo que se pueden editar sin reconstruir nada.
 
## Estructura del proyecto

```
.
├── compose.yaml
├── img
├── logs
│   ├── access.log
│   ├── error.log
│   ├── estatico.access.log
│   ├── estatico.error.log
│   ├── php.access.log
│   └── php.error.log
├── nginx
│   └── conf.d
│       ├── 00-general.conf
│       ├── estatico.conf
│       └── php.conf
├── php
│   └── uploads.ini
├── README.md
└── sites
    ├── estatico
    │   └── index.html
    └── php
        ├── index.php
        └── subir.php
```

## Puesta en marcha

1. Línea para el fichero hosts (`/etc/hosts`):
```
     127.0.0.1   manuel.dpl.test   php.manuel.dpl.test
```
2. Con el puerto 80 libre y desde la carpeta del proyecto:
```bash
   docker compose up -d
   docker compose ps          # web y php en estado running; solo web con 0.0.0.0:80->80/tcp
```

## Respuestas R1–R5
 
**R1.** El cambio del paso 3 estaba en la capa de escritura del contenedor, que se borra con docker rm. En el paso 4 la carpeta está en mi disco y el contenedor solo la ve montada, así que el cambio se ve al momento y no se pierde. :ro = solo lectura para el contenedor.

**R2.** Nginx lee los HTML del disco en cada petición, pero la configuración la carga al arrancar, así que hay que hacer nginx -s reload. Un cambio en compose.yaml obliga a recrear el contenedor con docker compose up -d.

**R3.** Lo ejecuta php: su hostname coincide con el que muestra la página. El navegador resuelve el nombre por el fichero hosts y la petición llega a web por el puerto 80. Nginx elige el sitio por la cabecera Host y envía el .php por FastCGI a php:9000. PHP-FPM devuelve el HTML a Nginx, y Nginx al navegador.

**R4.** La A: no da error, devuelve un 200 con el código fuente del PHP a la vista, incluidas posibles contraseñas. B y C dejan el servicio caído, pero no exponen nada.

**R5.** En la prueba 2 la petición cabía en post_max_size (8M), pero el fichero superaba upload_max_filesize (2M), así que PHP ejecutaba el script y descartaba el fichero. PHP necesitó up -d porque añadí un montaje nuevo y PHP-FPM solo lee los .ini al arrancar. A Nginx le bastó un reload porque su configuración ya estaba montada.

## Conclusiones
Con esta práctica he aprendido a montar un servidor web completo con Docker Compose, separando cada pieza en su propio contenedor y conectándolas por una red interna. He entendido la diferencia entre lo que vive dentro de un contenedor y lo que se monta desde el disco, y qué cambios se aplican con una simple recarga y cuáles obligan a recrear el contenedor.