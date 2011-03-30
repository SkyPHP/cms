<?
$presets = array(
	'type' => "ALTER TABLE $table\\n	ALTER COLUMN xxxxx TYPE varchar;",
	'rename' => "ALTER TABLE $table\\n	RENAME COLUMN xxxxx TO new_xxxxx;",
	'set default' => "ALTER TABLE $table\\n	ALTER COLUMN xxxxx SET DEFAULT expression;",
	'drop default' => "ALTER TABLE $table\\n	ALTER COLUMN xxxxx DROP DEFAULT;",
	'set not null' => "ALTER TABLE $table\\n	ALTER COLUMN xxxxx SET NOT NULL;",
	'drop not null' => "ALTER TABLE $table\\n	ALTER COLUMN xxxxx DROP NOT NULL;",
	'drop column' => "ALTER TABLE $table\\n	DROP COLUMN xxxxx;"
);
?>