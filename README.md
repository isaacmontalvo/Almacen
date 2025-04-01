# Sistema de Inventario desarrollado [por Isaac Montalvo]

## Descripción
Sistema de gestión de inventario desarrollado en PHP que permite controlar el stock de productos, registrar entradas y salidas, gestionar códigos de barras y generar reportes en PDF.

## Características Principales
- Gestión de productos con Part Number, descripción, lote y ubicación
- Control de stock con entradas y salidas
- Sistema de códigos de barras para productos
- Registro de movimientos con OT (Orden de Trabajo) y matrícula
- Gestión de devoluciones
- Generación de reportes en PDF
- Interfaz moderna y responsiva
- Filtros avanzados para búsqueda de productos y movimientos

## Requisitos del Sistema
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Composer para gestión de dependencias
- Extensión PHP GD para generación de códigos de barras
- Extensión PHP PDO para conexión a base de datos

## Instalación

1. Clonar el repositorio:
```bash
git clone [URL_DEL_REPOSITORIO]
cd [NOMBRE_DEL_DIRECTORIO]
```

2. Instalar dependencias:
```bash
composer install
```

3. Configurar la base de datos:
   - Crear una base de datos MySQL
   - Importar el archivo `database.sql` que se encuentra en la carpeta `database/`
   - Configurar las credenciales de la base de datos en `config/database.php`

4. Configurar permisos:
```bash
chmod 755 -R assets/
chmod 755 -R barcodes/
chmod 755 -R pdfs/
```

5. Configurar el servidor web:
   - Asegurarse que el DocumentRoot apunte a la carpeta `public/`
   - Habilitar el módulo rewrite de Apache si se usa Apache
   - Configurar el archivo `.htaccess` según sea necesario

## Estructura del Proyecto
```
├── assets/           # Archivos estáticos (CSS, JS, imágenes)
├── barcodes/         # Códigos de barras generados
├── config/           # Archivos de configuración
├── database/         # Scripts de base de datos
├── lib/              # Librerías y utilidades
├── models/           # Modelos de datos
├── pdfs/             # Reportes PDF generados
├── public/           # Punto de entrada de la aplicación
├── vendor/           # Dependencias de Composer
└── views/            # Vistas y plantillas
```

## Uso del Sistema

### Gestión de Productos
1. Agregar nuevo producto:
   - Ingresar Part Number, descripción, lote, cantidad y ubicación
   - Opcionalmente asignar código de barras
   - Guardar el producto

2. Buscar productos:
   - Usar el buscador por Part Number
   - Aplicar filtros por ubicación, lote, etc.
   - Ver detalles del producto incluyendo historial de movimientos

### Registro de Movimientos
1. Registrar salida:
   - Seleccionar producto
   - Ingresar cantidad
   - Proporcionar OT y matrícula
   - Confirmar la salida

2. Registrar devolución:
   - Buscar el movimiento original
   - Ingresar cantidad a devolver
   - Proporcionar matrícula
   - Confirmar la devolución

### Generación de Reportes
1. Reporte de producto:
   - Acceder a detalles del producto
   - Hacer clic en "Generar PDF"
   - El reporte incluye información detallada y código de barras

2. Reporte de inventario:
   - Acceder a la sección de inventario
   - Aplicar filtros si es necesario
   - Generar reporte PDF

3. Reporte de historial:
   - Acceder a la sección de historial
   - Aplicar filtros por fecha, producto, etc.
   - Generar reporte PDF

## Mantenimiento

### Limpieza de Archivos Temporales
- Los códigos de barras y PDFs generados se almacenan en sus respectivas carpetas
- Se recomienda implementar un cron job para limpiar archivos antiguos

### Respaldo de Base de Datos
- Realizar respaldos periódicos de la base de datos
- Mantener copias de seguridad en ubicación segura

## Soporte
Para reportar problemas o solicitar ayuda:
1. Revisar la documentación
2. Verificar los logs de error en `logs/`
3. Contactar al administrador del sistema

## Licencia
Este proyecto está bajo la licencia [GPL]. Ver el archivo `LICENSE` para más detalles. 