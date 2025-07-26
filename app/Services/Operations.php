<?php

namespace App\Services;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class Operations
{
    public static function decryptId(string $id)
    {
        try {
            $id = (int) Crypt::decrypt($id);
            return $id;
        } catch (DecryptException $e){
            return redirect()->route('home');
        }
    }
}