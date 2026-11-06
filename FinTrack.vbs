Option Explicit
Dim WshShell, fso, currentDir, browserExe, edgePath1, edgePath2, chromePath
Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")

currentDir = fso.GetParentFolderName(WScript.ScriptFullName)

' 1. Localizar navegador com suporte a App Mode
edgePath1 = "C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
edgePath2 = "C:\Program Files\Microsoft\Edge\Application\msedge.exe"
chromePath = "C:\Program Files\Google\Chrome\Application\chrome.exe"

If fso.FileExists(edgePath1) Then
    browserExe = edgePath1
ElseIf fso.FileExists(edgePath2) Then
    browserExe = edgePath2
ElseIf fso.FileExists(chromePath) Then
    browserExe = chromePath
Else
    browserExe = "msedge.exe"
End If

' 2. Verificar se o servidor PHP já está respondendo na porta 8000
Dim httpCheck
Set httpCheck = CreateObject("MSXML2.ServerXMLHTTP.6.0")
On Error Resume Next
httpCheck.setTimeouts 500, 500, 500, 500
httpCheck.open "GET", "http://127.0.0.1:8000", False
httpCheck.send

If Err.Number <> 0 Then
    Err.Clear
    ' Iniciar o servidor PHP de forma 100% invisível em segundo plano
    WshShell.CurrentDirectory = currentDir
    WshShell.Run "cmd.exe /c php -S 127.0.0.1:8000 -t """ & currentDir & "\public""", 0, False
    WScript.Sleep 800
End If
On Error GoTo 0

' 3. Abrir o FinTrack como aplicativo dedicado (sem barras de navegação)
Dim launchCmd
launchCmd = """" & browserExe & """ --app=http://127.0.0.1:8000 --window-size=1280,850"
WshShell.Run launchCmd, 1, False
