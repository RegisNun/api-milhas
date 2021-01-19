<?php

    if (isset($router)) {

        // $router->get("/", "FidelidadeController@index");
        // $router->get("/api/fidelidade/estados", "EstadosController@index");
        // $router->get("/api/fidelidade/individuos", "IndividuosController@index");

        // $router->get("/api/fidelidade/clientes/{regis}", "ClientesController@index");

        /**
         * Agrupamento de Router
         */
        // $router->group(['prefix' => "/api/fidelidade"], function () use ($router) {
        // });

        $router->group(['prefix' => "/api"], function () use ($router) {
    
            $router->group(['prefix' => "/desafio"], function () use ($router) {
                $router->get("/", "VooController@index");
               
            });
        });
    }
