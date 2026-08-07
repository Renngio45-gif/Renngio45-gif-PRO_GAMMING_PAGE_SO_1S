@echo off
REM ====================================================================
REM  NivelUp - Arranca el entorno local en Windows
REM
REM  Levanta MySQL y el servidor de PHP en dos ventanas aparte y abre el
REM  sitio en el navegador. Para apagar todo, cierra las dos ventanas.
REM
REM  Esto es solo para desarrollar comodo. La entrega sigue siendo el
REM  despliegue en la maquina virtual con Ubuntu.
REM ====================================================================

set MYSQL_DIR=C:\mysql
set PHP_EXE=C:\php\php.exe
set SITIO=%~dp0..\sitio
set PUERTO=8080

echo.
echo  NivelUp - entorno local
echo  =======================
echo.

REM --- Comprobaciones previas ----------------------------------------
if not exist "%PHP_EXE%" (
    echo  ERROR: no se encontro PHP en %PHP_EXE%
    echo  Revisa la ruta al inicio de este archivo.
    pause
    exit /b 1
)

if not exist "%MYSQL_DIR%\bin\mysqld.exe" (
    echo  AVISO: no se encontro MySQL en %MYSQL_DIR%
    echo  El sitio va a funcionar igual, pero productos y blog saldran vacios.
    echo.
    goto :solo_php
)

REM --- MySQL ----------------------------------------------------------
echo  [1/2] Iniciando MySQL...
start "MySQL - NivelUp" /min "%MYSQL_DIR%\bin\mysqld.exe" --console
timeout /t 5 /nobreak >nul

:solo_php
REM --- PHP ------------------------------------------------------------
echo  [2/2] Iniciando el servidor de PHP en el puerto %PUERTO%...
start "PHP - NivelUp" "%PHP_EXE%" -S localhost:%PUERTO% -t "%SITIO%"
timeout /t 2 /nobreak >nul

REM --- Navegador ------------------------------------------------------
start http://localhost:%PUERTO%

echo.
echo  Listo. El sitio esta en http://localhost:%PUERTO%
echo.
echo  Para apagarlo, cierra las ventanas "MySQL - NivelUp" y "PHP - NivelUp".
echo.
pause
