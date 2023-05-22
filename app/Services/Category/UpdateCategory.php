<?php

namespace App\Services\Category;

use App\Exceptions\AlreadyExistsException;
use App\Models\Category;
use App\Services\BaseService;
use Auth;
use Illuminate\Validation\ValidationException;

class UpdateCategory extends BaseService
{
    public function rules(): array
    {
        return [
            'id' => 'required|exists:categories,id',
            'name' => 'required',
        ];
    }

    /**
     * @throws ValidationException
     * @throws AlreadyExistsException
     */
    public function execute(array $data): Category
    {
        $this->validate($data);
        $category = Category::where('user_id', Auth::id())
            ->where('name', $data['name'])
            ->whereNot('id', $data['id'])
            ->first();
        if ($category) {
            throw new AlreadyExistsException('Category Already Exists');
        } else {
            $category = Category::find($data['id']);
            $category->update([
                'name' => $data['name'],
            ]);
        }
        return $category;
    }

}
