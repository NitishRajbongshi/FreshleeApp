<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    public function usermanagement()
    {
        // Fetch user-role mappings with user details and all roles for each user
        $data = DB::table('user_role_mapping as u')
            ->join('roles as l', 'u.role_id', '=', 'l.role_title')
            ->join('users as d', 'u.user_id', '=', 'd.user_id')
            ->select('u.id', 'u.user_id', 'u.role_id', 'd.address', 'd.email', 'd.name')
            ->whereIn('u.role_id', ['F', 'TR'])
            ->get();

        $roles = DB::table('roles')
            ->pluck('role_title', 'role_title');

        return view('admin.usermanagement.usermanagement', ['roles' => $roles, 'data' => $data]);
    }

    public function updateUserRoles(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'roles' => 'required|array',
                'user_id' => 'required|string|exists:users,user_id',
            ]);

            $roles = $validated['roles'];
            $userId = $validated['user_id'];

            // Fetch existing roles for the user
            $existingRoles = DB::table('user_role_mapping')
                ->where('user_id', $userId)
                ->pluck('role_id')
                ->toArray();

            // Ensure Role F is always present
            if (!in_array('F', $roles)) {
                $roles[] = 'F';
            }

            // Determine roles to insert (excluding 'F')
            $rolesToInsert = array_filter($roles, function ($role) {
                return $role !== 'F';
            });

            // Determine roles to delete (only those that are not 'F' and not in rolesToInsert)
            $rolesToDelete = array_diff(
                array_diff($existingRoles, ['F']),
                $rolesToInsert
            );

            DB::transaction(function () use ($userId, $rolesToInsert, $rolesToDelete) {
                if (!empty($rolesToDelete)) {
                    DB::table('user_role_mapping')
                        ->where('user_id', $userId)
                        ->whereIn('role_id', $rolesToDelete)
                        ->delete();
                }

                $existingRoles = DB::table('user_role_mapping')
                    ->where('user_id', $userId)
                    ->pluck('role_id')
                    ->toArray();

                $rolesToAdd = array_diff($rolesToInsert, $existingRoles);

                if (!empty($rolesToAdd)) {
                    $insertData = array_map(function ($role) use ($userId) {
                        return ['user_id' => $userId, 'role_id' => $role];
                    }, $rolesToAdd);

                    DB::table('user_role_mapping')->insert($insertData);
                }
            });

            return redirect()->route('admin.usermanagement')
                ->with('success', 'User roles updated successfully');
        }
    }
}
