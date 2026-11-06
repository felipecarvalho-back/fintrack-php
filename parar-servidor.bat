@echo off
echo Encerrando servidor local do FinTrack (porta 8000)...
for /f "tokens=5" %%a in ('netstat -aon ^| findstr ":8000" ^| findstr "LISTENING"') do (
    taskkill /F /PID %%a >nul 2>&1
)
echo Servidor encerrado com sucesso.
pause
