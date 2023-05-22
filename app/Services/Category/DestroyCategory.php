<?php

namespace App\Services\Category;

use App\Exceptions\NotFoundException;
use App\Models\Category;
use App\Models\Task;
use App\Services\BaseService;
use Auth;
use Illuminate\Validation\ValidationException;

class DestroyCategory extends BaseService
{
    public function rules(): array
    {
        return [
            'id' => 'required|exists:categories,id',
        ];
    }

    /**
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function execute(array $data): bool
    {
        $this->validate($data);
        $category = Category::where('user_id', Auth::id())
            ->where('id', $data['id'])
            ->first();
        if (!$category) {
            throw new NotFoundException('Category not found');
        } else {
            Task::where('category_id', $data['id'])->delete();
            $category->delete();
        }
        return true;
    }

}
