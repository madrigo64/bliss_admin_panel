REM  first row with command D: means that game with server located on hard disk D: is important cammand change it according to your disk where installed Bliss Dayz server
D:
REM command db_utility.pl cleandead 3  will delete dead survivors from mysql datebase alsow with him tents
REM  db_spawn_vehicles.pl --bounds  will spawn vehicles 
REM  Remove word REM to uncomment command
REM  -------start commands:

REM perl db_utility.pl cleandead 3 
perl db_spawn_vehicles.pl --bounds
"Expansion\beta\arma2oaserver.exe" -port=2302 "-config=dayz_1.chernarus\config_334f358c.cfg" "-cfg=dayz_1.chernarus\basic.cfg" "-profiles=dayz_1.chernarus" -name=Bliss "-mod=@dayz;@bliss_1.chernarus" -cpuCount=2 -exThreads=1 -maxMem=2048
