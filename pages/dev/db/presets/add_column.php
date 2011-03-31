<?
$presets = array(
	'varchar' => "ALTER TABLE $table\\n	ADD COLUMN xxxxx VARCHAR;",
	'integer' => "ALTER TABLE $table\\n	ADD COLUMN xxxxx INTEGER;",
	'smallint' => "ALTER TABLE $table\\n	ADD COLUMN xxxxx SMALLINT DEFAULT 1;\\n\\nUPDATE $table SET xxxxx = 1;\\n\\nALTER TABLE $table\\n	ALTER COLUMN xxxxx SET NOT NULL;",
	'timestampz' => "ALTER TABLE $table\\n	ADD COLUMN xxxxx TIMESTAMP WITH TIME ZONE DEFAULT now();"
);
?>