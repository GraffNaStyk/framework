<?php

interface ControllerInterface
{
    public function index();
    
    public function store(\App\Facades\Http\Request $request);
    
    public function show(int $id);
    
    public function delete(int $id);
    
    public function update(\App\Facades\Http\Request $request);
}
