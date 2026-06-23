@echo off
setlocal enabledelayedexpansion

set AGENT=%~1
set TASK=%~2
shift
shift

:parse
if "%~1"=="" goto done
if /i "%~1"=="--agent" set AGENT=%~2& shift & shift & goto parse
if /i "%~1"=="--task" set TASK=%~2& shift & shift & goto parse
shift
goto parse

:done
if "%AGENT%"=="" set AGENT=unknown
if "%TASK%"=="" set TASK=no task specified

for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /value') do set DT=%%I
set TODAY=%DT:~0,4%-%DT:~4,2%-%DT:~6,2%
set NOW=%DT:~8,2%:%DT:~10,2%

set LOGFILE=%~dp0daily-agent\%TODAY%.md

if not exist "%~dp0daily-agent" mkdir "%~dp0daily-agent"

if not exist "%LOGFILE%" (
    echo # %TODAY% Zidooka Agent Log > "%LOGFILE%"
    echo. >> "%LOGFILE%"
    echo --- >> "%LOGFILE%"
    echo. >> "%LOGFILE%"
)

echo. >> "%LOGFILE%"
echo ### %AGENT% — %NOW% >> "%LOGFILE%"
echo **Task:** %TASK% >> "%LOGFILE%"
echo. >> "%LOGFILE%"

echo Logged: %AGENT% at %NOW% - %TASK%
echo File: %LOGFILE%
