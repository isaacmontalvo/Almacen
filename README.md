# Sistema de Inventario

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

## Instalación en PC de Control

1. Instalar XAMPP:
   - Descargar desde: https://www.apachefriends.org/
   - Instalar en la ruta por defecto (C:\xampp)

2. Clonar el repositorio:
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/isaacmontalvo/Almacen.git
   ```

3. Instalar dependencias:
   ```bash
   cd Almacen
   composer install
   ```

4. Configurar la base de datos:
   - Abrir phpMyAdmin (http://localhost/phpmyadmin)
   - Crear base de datos "almacen"
   - Importar el archivo `almacen.sql`

5. Configurar permisos:
   - Dar permisos de escritura a las carpetas:
     - barcodes/
     - pdfs/

6. Configurar el archivo de conexión:
   - Editar `config/database.php`
   - Verificar que los datos de conexión sean correctos

## Instalación en PC Cliente

1. Instalar XAMPP:
   - Descargar desde: https://www.apachefriends.org/
   - Instalar en la ruta por defecto (C:\xampp)

2. Clonar el repositorio:
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/isaacmontalvo/Almacen.git
   ```

3. Instalar dependencias:
   ```bash
   cd Almacen
   composer install
   ```

4. Configurar la base de datos:
   - Abrir phpMyAdmin (http://localhost/phpmyadmin)
   - Crear base de datos "almacen"
   - Importar el archivo `almacen.sql` más reciente

5. Configurar el archivo de conexión:
   - Editar `config/database.php`
   - Verificar que los datos de conexión sean correctos

## Actualización del Sistema

### En PC de Control
1. Hacer cambios en el código
2. Subir cambios a GitHub:
   ```bash
   git add .
   git commit -m "Descripción del cambio"
   git push
   ```

### En PC Cliente
1. Actualizar código:
   ```bash
   cd C:\xampp\htdocs\Almacen
   git pull
   ```

## Respaldo del Sistema

### Respaldo Manual
1. Ejecutar el script `backup.bat`
2. El respaldo se guardará en la carpeta `backups/`
3. Guardar el archivo ZIP generado en lugar seguro

### Respaldo Automático (Programado)
1. Abrir Programador de tareas de Windows
2. Crear nueva tarea básica
3. Configurar para ejecutar `backup.bat` diariamente
4. Establecer hora de ejecución

## Mantenimiento

### Limpieza de Archivos Temporales
- Los códigos de barras y PDFs generados se almacenan en sus respectivas carpetas
- Se recomienda limpiar periódicamente archivos antiguos

### Respaldo de Base de Datos
- Realizar respaldos diarios
- Mantener copias de seguridad en ubicación segura
- Verificar periódicamente la integridad de los respaldos

## Soporte
Para reportar problemas o solicitar ayuda:
1. Revisar la documentación
2. Verificar los logs de error en `logs/`
3. Contactar al administrador del sistema

## Notas Importantes
- Mantener actualizado el sistema
- Realizar respaldos periódicos
- No compartir credenciales de acceso
- Mantener seguros los tokens de GitHub
- Verificar la conexión a internet antes de actualizar

## Licencia
Este proyecto está bajo la licencia [TIPO_DE_LICENCIA]. Ver el archivo `LICENSE` para más detalles. 