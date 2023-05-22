<?php

namespace App\Services\Category;

use App\Exceptions\AlreadyExistsException;
use App\Models\Category;
use App\Services\BaseService;
use Auth;
use Illuminate\Validation\ValidationException;

class StoreCategory extends BaseService
{
    public function rules(): array
    {
        return [
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
            ->where('name', $data['name'])->first();
        if (!$category) {
            $category = Category::create([
                'user_id' => Auth::id(),
                'name' => $data['name'],
            ]);
        } else {
            throw new AlreadyExistsException('Category Already Exists');
        }
        return $category;
    }

}
