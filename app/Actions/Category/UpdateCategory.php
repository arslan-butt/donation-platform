<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Models\User;

class UpdateCategory
{
    public function execute(Category $category, array $data, User $user): Category
    {
        $category->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'updated_by' => $user->id,
        ]);

        return $category->fresh();
    }
}
