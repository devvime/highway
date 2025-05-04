<?php

namespace Highway\Modules\User;

class UestController
{
    public function index()
    {
        echo "GET OK";
    }

    public function show($request)
    {
        echo "SHOW OK";
        echo json_encode($request);
    }

    public function store($request)
    {
        echo "POST OK";
        echo json_encode($request);
    }

    public function update($request)
    {
        echo "PUT OK";
        echo json_encode($request);
    }

    public function delete($request)
    {
        echo "DELETE OK";
        echo json_encode($request);
    }
}
