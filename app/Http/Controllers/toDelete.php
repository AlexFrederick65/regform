<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class toDelete extends Controller
{
    public function toDeleteUser($id)
    {
        $user=User::where(['id'=> $id])->delete();

       return $user;

    }
}
