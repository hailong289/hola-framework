<?php
namespace App\Database\SchemaMigrate;
use Hola\Database\Structure\Table;
use Hola\Database\Structure\DBSchema;
use Hola\Database\TableMigration;

class CreateTBSchemaHasRun extends TableMigration {
    public function up() {
        DBSchema::createTable('schema_table_run', function(Table $table) {
            $table->integer('id', true);
            $table->varchar('name')->notNull();
            $table->dateTime('created_at')->null();
        });
    }
    public function down() {
        //  DBSchema::dropTable('schema_table_run');
    }
    
}