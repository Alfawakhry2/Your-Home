<?php
namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Role;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure role_id is always present:
        if (empty($data['role_id'])) {
            $data['role_id'] = $this->record->roles()->first()?->id;
        }
        // Keep type in sync
        if (! empty($data['role_id'])) {
            $data['type'] = Role::find($data['role_id'])?->name;
        }
        return $data;
    }

    protected function afterSave(): void
    {
        /** @var User $user */
        $user = $this->record;

        // 1) Sync the Role
        $role = Role::find($this->data['role_id']);
        if ($role) {
            $user->syncRoles([$role->name]);
        }

        // 2) Always sync exactly what was submitted in "permissions",
        //    even if it's a subset of the role's permissions:
        if (array_key_exists('permissions', $this->data)) {
            $user->syncPermissions($this->data['permissions']);
        }
        // no fallback to $role->permissions() here!
    }
}
