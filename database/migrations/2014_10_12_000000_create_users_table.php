<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do usuário
            $table->string('email')->unique(); // Email do usuário
            $table->string('password')->nullable(false); // Campo de senha
            $table->string('cpf')->unique(); // CPF do usuário, com valor único
            $table->date('data_nascimento'); // Data de nascimento do usuário
            $table->boolean('is_admin')->default(0); // 0 = Usuário Comum, 1 = Administrador
            $table->rememberToken(); // Token de 'lembrar-me'
            $table->timestamps(); // Criado em e atualizado em (timestamps padrão do Laravel)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
