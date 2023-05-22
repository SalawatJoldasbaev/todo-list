<?php

namespace App\Services\Category;

use App\Models\Category;
use App\Services\BaseService;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class IndexCategory extends BaseService
{
    public function rules(): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     */
    public function execute(array $data): Collection|array
    {
        $this->validate($data);
        return Category::where('user_id', Auth::id())->get();
    }
}
