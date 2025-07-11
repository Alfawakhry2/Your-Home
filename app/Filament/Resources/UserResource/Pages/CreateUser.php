<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // خزن نوع المستخدم قبل الإنشاء
        if (! empty($data['role_id'])) {
            $data['type'] = Role::find($data['role_id'])?->name;
        }
        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var User $user */
        $user = $this->record;

        // assign role
        if (! empty($this->data['role_id'])) {
            $role = Role::find($this->data['role_id']);
            $user->assignRole($role->name);
        }

        // sync permissions
        $perms = $this->data['permissions'] ?? [];
        if (! empty($perms)) {
            $user->syncPermissions($perms);
        } elseif (! empty($role)) {
            $user->syncPermissions($role->permissions->pluck('name')->toArray());
        }
    }
}
