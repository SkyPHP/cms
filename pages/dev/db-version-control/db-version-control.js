function execute(){
	var sql = $('#sql_statement').val();
	var codebase = $('#codebase').val();
	$.post('/dev/db-version-control/ajax/functions',({
																		codebase:codebase,
																		sql:sql
																	}),function(data){
																		if(data=='success'){
																			$('#output').html("<div class='aql_saved'>Success</div>");
																		}else{
																			$('#output').html("<div class='aql_error'>Error:<br><br>"+data+"</div>");
																		}
																	
																	});
}

function paste(func){
	var statement = '';
	if(func == 'create_table'){
		statement = "CREATE TABLE \"public\".\"table_name\"(\n\t\"id\" SERIAL,\n\t\"mod_time\" TIMESTAMP WITH TIME ZONE DEFAULT now() NOT NULL,\n\t\"mod__person_id\" INTEGER,\n\t\"active\" SMALLINT DEFAULT 1 NOT NULL,\n\t\CONSTRAINT \"table_name_pkey\" PRIMARY KEY(\"id\")\n)WITHOUT OIDS;";
	}
	else if(func == 'alter_column'){
		statement = "ALTER TABLE \"public\".\"table_name\"\n\tALTER COLUMN \"column_name\" TYPE TYPE_NAME;"
	}
	else if(func == 'add_colulmn'){
		statement = "ALTER TABLE \"public\".\"table_name\"\n\tADD COLUMN \"column_name\" TYPE_NAME;";
	}
	else if(func == 'clear'){
		//do nothing
	}
	$('#sql_statement').val(statement);
}