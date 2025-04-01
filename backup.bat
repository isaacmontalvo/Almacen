@echo off
echo Iniciando respaldo del sistema...

:: Crear directorio de respaldo si no existe
if not exist "backups" mkdir backups

:: Obtener fecha actual
set FECHA=%date:~-4,4%-%date:~-7,2%-%date:~-10,2%

:: Crear directorio con la fecha
mkdir backups\%FECHA%

:: Respaldar base de datos
echo Respaldando base de datos...
"C:\xampp\mysql\bin\mysqldump.exe" -u root almacen > backups\%FECHA%\almacen.sql

:: Respaldar archivos importantes
echo Respaldando archivos...
xcopy /E /I /Y "barcodes" "backups\%FECHA%\barcodes"
xcopy /E /I /Y "pdfs" "backups\%FECHA%\pdfs"
xcopy /Y "config\database.php" "backups\%FECHA%\database.php"

:: Comprimir el respaldo
echo Comprimiendo respaldo...
"C:\Program Files\7-Zip\7z.exe" a "backups\%FECHA%.zip" "backups\%FECHA%\*"

:: Eliminar directorio temporal
rmdir /S /Q "backups\%FECHA%"

echo Respaldo completado exitosamente!
echo El archivo de respaldo se encuentra en: backups\%FECHA%.zip
pause 