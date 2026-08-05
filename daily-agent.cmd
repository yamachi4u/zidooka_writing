@echo off
setlocal enabledelayedexpansion

rem capture script dir before any shift (shift also shifts %0)
set "SCRIPTDIR=%~dp0"

set "AGENT="
set "TASK="

:parse
if "%~1"=="" goto done
if /i "%~1"=="--agent" set "AGENT=%~2"& shift & shift & goto parse
if /i "%~1"=="--task" set "TASK=%~2"& shift & shift & goto parse
rem positional fallback: first bare arg = agent, second = task
if not defined AGENT (set "AGENT=%~1") else if not defined TASK set "TASK=%~1"
shift
goto parse

:done
if not defined AGENT set "AGENT=unknown"
if not defined TASK set "TASK=no task specified"

for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /value') do set DT=%%I
set TODAY=%DT:~0,4%-%DT:~4,2%-%DT:~6,2%
set NOW=%DT:~8,2%:%DT:~10,2%

rem log filename must be YYYYMMDD.md (see daily-agent/README.md)
set "LOGFILE=%SCRIPTDIR%daily-agent\%DT:~0,8%.md"

if not exist "%SCRIPTDIR%daily-agent" mkdir "%SCRIPTDIR%daily-agent"

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
