<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [


            //administartors

            'admin',

            'admin->admin view',
            'admin->admin edit',
            'admin->admin show',

            //client

            'client',
            'client->client view',

            //hr management

            'hr',

            'hr->employee edit',
            'hr->employee view',
            'hr->employee create',
            'hr->employee delete',



            'hr->other view',


            'hr->promotion view',
            'hr->promotion create',
            'hr->promotion edit',
            'hr->promotion delete',


            'hr->probation view',
            'hr->probation create',
            'hr->probation edit',
            'hr->probation delete',


            'hr->handover view',
            'hr->handover create',
            'hr->handover edit',
            'hr->handover delete',


            'hr->termination view',
            'hr->termination create',
            'hr->termination edit',
            'hr->termination delete',


            'hr->resignation view',
            'hr->resignation create',
            'hr->resignation edit',
            'hr->resignation delete',


            'hr->role view',
            'hr->role create',
            'hr->role edit',
            'hr->role delete',
            'hr->managerole view',


            //attendance

            'attendance',
            'attendance->shiftplanner view',
            'attendance->attendance view',
            'attendance->assignleave view',
            'attendance->addleave view',
            'attendance->holiday view',


            //company login

            'login',
            'login->email edit',
            'login->email view',
            'login->email create',
            'login->email delete',

            //   'finance',


            'finance',
            'finance->tax view',
            'finance->pf view',
            'finance->salary view',
            'finance->billing view',

            //  'task',

            'task',
            'task->modulo view',
                  'task->modulo create',
      'task->modulo edit',
      'task->modulo delete',



         'task->project view',
                  'task->project create',
      'task->project edit',
      'task->project delete',




 'task->task view',
                  'task->task create',
      'task->task edit',
      'task->task delete',



       'task->subtask view',
                  'task->subtask create',
      'task->subtask edit',
      'task->subtask delete',


            //  'inventory',

            'inventory',
            'inventory->invoice view',
            'inventory->purchase view',
            'inventory->stocks view',
            'inventory->dailyexpenses view',
            'inventory->report view',

            //  'configure email',

            'email',
            'email->template view',
            'email->configure view',

            //setting

            'site',

            'site->setting view',

            'site->permission view',
            'site->permission edit',
            'site->permission create',
            'site->permission delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
