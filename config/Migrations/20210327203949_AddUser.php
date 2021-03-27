<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddUser extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('users');
        $table->addColumn('oioubl_file_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            'after' => 'pdf_file_name',
        ]);

        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);

        $table->addColumn('password', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);

        $table->addColumn('firstname', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);

        $table->addColumn('lastname', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);

        $table->addColumn('created_at', 'datetime', [
            'default' => null,
            'null' => false,
        ]);

        $table->addIndex([
            'email',
        ], [
            'name' => 'BY_EMAIL',
            'unique' => false,
        ]);

        $table->addIndex([
            'email',
            'password'
        ], [
            'name' => 'BY_EMAIL_PASSWORD',
            'unique' => false,
        ]);

        $table->addPrimaryKey([
            'id',
        ]);

        $table->create();
    }

    public function down()
    {
        $table = $this->table('users');
        $table->drop();
    }
}
