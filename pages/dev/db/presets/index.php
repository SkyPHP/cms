<?
$presets = array(
	'index' => "CREATE INDEX {$table}_idx ON $table\\n	(column_or_expression, column_or_expression, ...);",
	'unique' => "CREATE UNIQUE INDEX {$table}_idx ON $table\\n	(column_or_expression, column_or_expression, ...);",
	'partial index' => "CREATE UNIQUE INDEX {$table}_idx ON $table\\n	(column_or_expression, column_or_expression, ...)\\n	WHERE predicate;",
	'concurrently' => "CREATE INDEX CONCURRENTLY {$table}_idx ON $table\\n	(column_or_expression, column_or_expression, ...);",
	'drop index' => "DROP INDEX {$table}_idx;",
);
?>