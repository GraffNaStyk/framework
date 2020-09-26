<?php
namespace App\Controllers;

interface ControllerInterface
{
    public function index();
    
    public function add();
    
    public function store(\App\Facades\Http\Request $request);
    
    public function show(int $id);
    
    public function edit(int $id);
    
    public function update(\App\Facades\Http\Request $request);
    
    public function delete(int $id);
}
