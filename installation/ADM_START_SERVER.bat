rem   command db_utility.pl cleandead 3  will delete dead survivors from mysql datebase also with they tents  you need file db_utility.pl for this command 
rem   put file  db_utility.pl  in folder where located file ADM_bofore_server.start.bat
rem   db_spawn_vehicles.pl --cleanup bounds  --limit 40 will spawn vehicles and will remove destroyed vehicles, --limit 40 will spawn only 40 vehicles
rem   for this command put file db_spawn_vehicles.pl  in folder where located file ADM_bofore_server.start.bat
rem   Server can be run 2 ways, with full command here or via  file restarter.exe (Warning restarter.exe will
rem   automatic restart server if server was shutdown not from admin panel) which run server by default This two commands can't be use parallel!!
rem   If you wish to use full command need to be edit according with your settings.
rem   Remove word rem to uncomment command if you wish to use it


rem    команда  db_utility.pl cleandead 3  удалит все мертвые тела если они в базе данных находятся больше 3 суток 
rem    а также удалит палатки привязанные к ним для этой команды вам понадобится файл db_utility.pl  который нужно поместить рядом с ADM_bofore_server.start.bat
rem    db_spawn_vehicles.pl --cleanup bounds  --limit 40 команда db_spawn_vehicles.pl --cleanup bounds поместит технику на ваш сервер и удалит технику которая находится за пределами карты
rem    параметр  --limit 40  указывает сколько техники помещать на карту
rem    и уберет также уничтоженную технику для этой команды вам понадобится файл db_utility.pl  который нужно поместить рядом с ADM_bofore_server.start.bat
rem    уберите слово rem перед нужной вам командой что бы команду раскомментировать и что бы команда выполнялась
rem    Сервер может быть запущен двумя способами, с полной командной для запуска и с запуском файла restarter.exe 
rem    (внимание restarter.exe перезапустит сервер если он выключится не с админ панели) который запускает сервер по умолчанию. Эти две команды не могут быть использованы параллельно!!

rem --start commmands ---начало команд----

rem perl db_utility.pl cleandead 3 
rem perl db_spawn_vehicles.pl --cleanup bounds  --limit 40
rem "Expansion\beta\arma2oaserver.exe" -port=2302 "-config=dayz_1.chernarus\config_334f358c.cfg" "-cfg=dayz_1.chernarus\basic.cfg" "-profiles=dayz_1.chernarus" -name=Bliss "-mod=@dayz;@bliss_1.chernarus" -cpuCount=2 -exThreads=1 -maxMem=2048
restarter.exe

rem --- end commands -- конец команад