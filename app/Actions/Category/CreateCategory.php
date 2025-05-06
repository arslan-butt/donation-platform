<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Models\User;

class CreateCategory
{
    public function execute(array $data, User $user): Category
    {
        return Category::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
    }
}
