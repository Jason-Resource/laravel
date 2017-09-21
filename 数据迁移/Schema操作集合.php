<?php

Schema::create('test', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->timestamps();
});