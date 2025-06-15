@echo off
cd /d C:\xampp\htdocs\TESIS
:menu
cls
echo ---------------------------------------------
echo         MENU GIT - PROYECTO TESIS
echo ---------------------------------------------
echo 1. Subir repositorio (add, commit, push)
echo 2. Validar cambios (git status)
echo 3. Salir
echo ---------------------------------------------
set /p opcion=Seleccione una opcion: 

if "%opcion%"=="1" goto subir
if "%opcion%"=="2" goto status
if "%opcion%"=="3" exit
goto menu

:subir
cls
echo ---------------------------------------------
echo     VERIFICANDO CAMBIOS EN EL REPOSITORIO
echo ---------------------------------------------

:: Verificar cambios sin modificar nada
set changes=0
for /f %%i in ('git status --porcelain ^| find /c /v ""') do set changes=%%i

if "%changes%"=="0" (
    echo Todo esta actualizado. No hay cambios para subir.
    pause
    goto menu
)

echo Se detectaron %changes% cambio(s). Procediendo...
echo.
git status

:: Ejecutar comandos solo si hay cambios
git add .
set /p mensaje=Escriba el mensaje del commit: 
git commit -m "%mensaje%"
git push -u origin main
pause
goto menu

:status
cls
echo ---------------------------------------------
echo           VALIDANDO CAMBIOS
echo ---------------------------------------------
git status
pause
goto menu
