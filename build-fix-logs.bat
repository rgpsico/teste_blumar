@echo off
echo ====================================
echo Compilando correção dos logs
echo ====================================
echo.

cd /d "c:\laragon\www\imoveis"

echo Executando build...
call npm run build

echo.
echo ====================================
echo Build concluído!
echo ====================================
echo.
echo Agora:
echo 1. Recarregue a página no navegador (Ctrl+F5)
echo 2. Vá para o painel admin
echo 3. Clique em "Logs do Sistema"
echo 4. Os logs devem aparecer agora!
echo.
pause
