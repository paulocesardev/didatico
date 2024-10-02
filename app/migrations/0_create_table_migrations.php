<?php

use App\Models\Migration;

return new class extends Migration
{
    public function up() {
        $this->query('
        CREATE TABLE public.users (
            id serial NOT NULL,
            "name" varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            "password" varchar(255) NOT NULL,
            created_at timestamp(0) NULL,
            updated_at timestamp(0) NULL
        );');
    }
    public function down() {

    }
};