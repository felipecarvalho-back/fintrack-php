@echo off
setlocal
cd /d "%~dp0"

echo Criando atalho do FinTrack na Area de Trabalho...

powershell -NoProfile -ExecutionPolicy Bypass -Command ^
    "$desktop = [Environment]::GetFolderPath('Desktop');" ^
    "$wsh = New-Object -ComObject WScript.Shell;" ^
    "$shortcut = $wsh.CreateShortcut(\"$desktop\FinTrack.lnk\");" ^
    "$shortcut.TargetPath = 'wscript.exe';" ^
    "$shortcut.Arguments = '\"%~dp0FinTrack.vbs\"';" ^
    "$shortcut.WorkingDirectory = '%~dp0';" ^
    "$shortcut.IconLocation = '%~dp0fintrack.ico,0';" ^
    "$shortcut.Description = 'FinTrack - Gestao Financeira Pessoal';" ^
    "$shortcut.Save();"

echo Atalho criado com sucesso na Area de Trabalho!
echo Agora voce pode dar dois cliques no icone 'FinTrack' na sua Area de Trabalho.
pause
