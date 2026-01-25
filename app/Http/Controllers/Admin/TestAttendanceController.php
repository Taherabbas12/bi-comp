<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Models\User;

class TestAttendanceController extends Controller
{
    public function testUserData()
    {
        $user = User::first();

        if (!$user) {
            return response()->json(['error' => 'No users found']);
        }

        return response()->json([
            'user_id' => $user->id,
            'name' => $user->name,
            'salary' => $user->salary,
            'salary_currency' => $user->salary_currency,
            'employment_type' => $user->employment_type,
            'department' => $user->department,
            'position' => $user->position,
            'hire_date' => $user->hire_date,
            'has_salary_field' => isset($user->salary),
            'all_attributes' => array_keys($user->getAttributes()),
        ]);
    }

    public function testDatabaseSchema()
    {
        $schema = \Illuminate\Support\Facades\Schema::getColumnListing('users');

        return response()->json([
            'users_table_columns' => $schema,
            'has_salary' => in_array('salary', $schema),
            'has_employment_type' => in_array('employment_type', $schema),
            'has_department' => in_array('department', $schema),
            'has_position' => in_array('position', $schema),
        ]);
    }
}
