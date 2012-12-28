rem   command db_utility.pl cleandead 3  will delete dead survivors from mysql datebase also with they tents  you need file db_utility.pl for this command 
rem   put file  db_utility.pl  in folder where located file ADM_bofore_server.start.bat
rem   db_spawn_vehicles.pl --bounds  --limit 40 will spawn vehicles and will remove destroyed vehicles, --limit 40 will spawn only 40 vehicles
rem   for this command put file db_spawn_vehicles.pl  in folder where located file ADM_bofore_server.start.bat
rem   Server can be run with 2 ways  with full command here or with file restarter.exe (Warning restarter.exe will automatic restart server if server was shutdown not from admin panel) which run server by default This twho commands can't be use parallel!!
rem   If you wish to use full command need to be edit according with you settings.
rem   Remove word rem to uncomment command if you wish to use it


rem    команда  db_utility.pl cleandead 3  удалит все мертвые тела если они в базе данных находятся больше 3 суток 
rem    а также удалит палатки привязанные к ним для этой команды вам понадобится файл db_utility.pl  который нужно поместить рядом с ADM_bofore_server.start.bat
rem    db_spawn_vehicles.pl --bounds  --limit 40 команда db_spawn_vehicles.pl --bounds поместит технику на ваш сервер и удалит технику которая находится за пределами карты
rem    параметр  --limit 40  указывает сколько техники помещать на карту
rem    и уберет тажке уничтоженную технику для этой команды вам понадобится файл db_utility.pl  который нужно поместить рядом с ADM_bofore_server.start.bat
rem    уберите слоово rem перед нужной вам командой что бы комнду расскоментировать и что бы команда выполнялась
Rem    Сервер может быть запущен двумя способами, с полной командной для запуска и с запуском файла restarter.exe (внимание restarter.exe перезапустит сервер если он выключится не с админ панели) который запускает сервер по умолчанию. Эти две команды не могут быть использованы паралельно!!

rem --start commmands ---начала команд----

rem perl db_utility.pl cleandead 3 
rem perl db_spawn_vehicles.pl --bounds  --limit 40
rem "Expansion\beta\arma2oaserver.exe" -port=2302 "-config=dayz_1.chernarus\config_334f358c.cfg" "-cfg=dayz_1.chernarus\basic.cfg" "-profiles=dayz_1.chernarus" -name=Bliss "-mod=@dayz;@bliss_1.chernarus" -cpuCount=2 -exThreads=1 -maxMem=2048
restarter.exe

rem --- end commands -- конец команад


rem you can also run file command Expansion\beta\arma2oaserver.exe" -port=2302..... instead of cammand restarter.exe just comment command restarter.exe put before word rem or delete row  and remove world rem in row rem Expansion\beta\arma2oaserver.exe" -port=2302....
rem  Вы можете также запускать полную команду для запуска сервера которая начинается со слова "Expansion\beta\arma2oaserver.exe" вместо команды restarter.exe  ,  команду restarter.exe придется закометировать написав команду rem в начале строки либо удалите строку полностью 



rem restarter.exe