<?php

namespace App\Concerns;

use App\Role;
use App\Permission;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasPermissions
{
    /**
     * The roles this user is attached to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Assign the user to the specified roles.
     *
     * @param  string|array ...$roles
     * @return void
     * @throws \Throwable
     */
    public function assignRole(...$roles): void
    {
        $roles = Arr::flatten($roles);
        $roles = $this->getRoles($roles);

        if ($roles) {
            $this->roles()->syncWithoutDetaching($roles);
            $this->save();
        }
    }

    /**
     * Remove the specified roles from the user.
     *
     * @param  string|array ...$roles
     * @return void
     * @throws \Throwable
     */
    public function removeRole(...$roles): void
    {
        $roles = Arr::flatten($roles);
        $roles = $this->getRoles($roles);

        $this->roles()->detach($roles);
    }

    /**
     * Determine that the user has access to one or more permissions.
     *
     * @param  string|array ...$permissions
     * @return bool
     */
    public function hasAccess(...$permissions): bool
    {
        $permissions = Arr::flatten($permissions);

        foreach ($permissions as $permission) {
            $hasPermission = $this->hasAccessToPermission($permission);

            if ($hasPermission) {
                return true;
            }
        }

        return false;
    }

    public function forceAccess(Role $role, ...$permissions)
    {
        $permissions = Arr::flatten($permissions);

        foreach ($permissions as $permission) {
            $model = Permission::create([
                'slug' => $permission,
                'name' => $permission,
            ]);

            $role->assignPermission($model->slug);
        }
    }

    /**
     * Determine that the user has access to a permission.
     *
     * @param  string $permission
     * @return bool
     */
    protected function hasAccessToPermission(string $permission): bool
    {
        foreach ($this->roles as $role) {
            $hasPermission = $role->permissions->where('slug', $permission)->count();

            if ($hasPermission) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the roles by the given array of slugs.
     *
     * @param  array $roles
     * @return mixed
     * @throws \Throwable
     */
    protected function getRoles(array $roles)
    {
        if ($this->teams->isEmpty()) {
            return;
        }

        return Role::query()
            ->select('id')
            ->where('team_id', team()->id)
            ->whereIn('id', $roles)
            ->get();
    }
}
