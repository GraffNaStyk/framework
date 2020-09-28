<?php
namespace App\Traits;

use App\Facades\Validator\Validator;

trait Response
{
    public function sendSuccess(?string $message)
    {
        return \App\Facades\Http\Response::json(['ok' => true, 'msg' => [$message ?? 'Dane zostały zapisane']], 200);
    }
    
    public function sendError()
    {
        return \App\Facades\Http\Response::json(['ok' => false, 'msg' => Validator::getErrors()], 400);
    }
}
