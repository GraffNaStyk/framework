<?php

namespace App\Facades\Csrf;

use App\Facades\Faker\Faker;
use App\Facades\Http\Request;
use App\Helpers\Session;

class Csrf
{
    public function generate()
    {
        if (! Session::has('csrf') && $this->isEnabled()) {
            Session::set(['csrf' =>
                Faker::hash(60)
            ]);
        }
    }

    public function isValid(string $csrf): bool
    {
        return (string) Session::get('csrf') === $csrf;
    }
    
    private function isEnabled(): bool
    {
        return app('csrf');
    }
    
    public function valid(Request $request): bool
    {
        if (! $request->has('_csrf') && $this->isEnabled() && $request->header('HTTP_X_FETCH_HEADER')) {
            return false;
        }
        
        $result = $this->isValid($request->get('_csrf'));
        $request->remove('_csrf');
        Session::remove('csrf');
        self::generate();
        return $result;
    }
}
