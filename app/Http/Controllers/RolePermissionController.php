<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use config\constants;

class RolePermissionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //$stud =Config::get('constants.roles.student');
        //echo Config::get('constants.rolees.student');
        //Config::get('constants.options');
        // or if you want a specific one
        //Config::get('constants.options.option_attachment');
        $role1=Role::create(['name' => 'Student']);
        $role2=Role::create(['name' => 'University']);
        $role3=Role::create(['name' => 'Entrepreneur']);
        $role4=Role::create(['name' => 'Mentor']);
        $role5=Role::create(['name' => 'Merchant']);
        $role6=Role::create(['name' => 'Marketeer']);
        $role6=Role::create(['name' => 'Recruiter']);
        $role7=Role::create(['name' => config('constants.rolees.student')]);
        $permission1=Permission::create(['name' => 'Can Register Login and Logout']);
        $permission2=Permission::create(['name' => 'Can Register login logout Refresh the Token and View the User']);
        $permission3=Permission::create(['name' => 'Can Invest']);
        $permission4=Permission::create(['name' => 'Can Mentor']);

        $role1->givePermissionTo($permission1);
        $role2->givePermissionTo($permission2);
        $role3->givePermissionTo($permission3);
        $role4->givePermissionTo($permission4);
    }
}

