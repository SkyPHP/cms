<?
$presets = array(
	'' => "
CREATE TABLE $table (
  id  SERIAL, 
  name VARCHAR,
  active  SMALLINT DEFAULT 1 NOT NULL, 
  insert_time  TIMESTAMP WITH TIME ZONE DEFAULT now(),
  update_time  TIMESTAMP WITH TIME ZONE,
  mod__person_id  INTEGER, 
  CONSTRAINT {$table}_pkey PRIMARY KEY(id)
) WITHOUT OIDS;

COMMENT ON TABLE $table
  IS '{\"codebase\":\"\"}';"
);
?>