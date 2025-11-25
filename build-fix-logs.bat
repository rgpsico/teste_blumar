@echo off
echo ====================================
echo Compilando atualizações do sistema
echo ====================================
echo.
echo Atualizações incluídas:
echo - Correção da renderização de logs
echo - Novo dashboard com estatísticas
echo - Contadores de visitantes e registros
echo.

cd /d "c:\laragon\www\imoveis"

echo Executando build...
call npm run build

echo.
echo ====================================
echo Build concluído!
echo ====================================
echo.
echo Novidades:
echo.
echo [Dashboard]
echo - Total de visitantes (últimos 30 dias)
echo - Visitantes hoje
echo - Proprietários e inquilinos
echo - Novos registros deste mês
echo.
echo [Logs]
echo - Logs de visitantes agora aparecem corretamente
echo - Logs de registro funcionando
echo.
echo Próximos passos:
echo 1. Recarregue a página no navegador (Ctrl+F5)
echo 2. Acesse o painel admin
echo 3. Veja as novas estatísticas no Dashboard
echo 4. Confira os logs em "Logs do Sistema"
echo.
pause
