<?

$backup_directory = '/backup';

$table = $_POST['table'];

$command = "/usr/bin/pg_dump -a -b -O -t $table --disable-triggers -U $db_username $db_name > $backup_directory/$table.data.sql";
//echo shell_exec("$command 2>&1 1> /dev/null");
echo shell_exec("$command");

$command = "/usr/bin/pg_dump -s -x -t $table -U $db_username $db_name > $backup_directory/$table.schema.sql";
//echo shell_exec("$command 2>&1 1> /dev/null");
echo shell_exec("$command");


?>