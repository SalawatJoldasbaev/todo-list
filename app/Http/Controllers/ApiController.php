<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Traits\JsonRespondController;

class ApiController extends Controller
{
    use JsonRespondController;

    /**
     * @var int
     */
    protected int $limitPerPage = 0;

    /**
     * @var string
     */
    protected string $sort = 'created_at';

    /**
     * @var ?string
     */
    protected ?string $withParameter = null;

    /**
     * @var string
     */
    protected string $sortDirection = 'asc';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($request->has('sort')) {
                $this->setSortCriteria($request->input('sort'));

                // It has a sort criteria, but is it a valid one?
                if (empty($this->getSortCriteria())) {
                    return $this->setHTTPStatusCode(400)
                        ->setErrorCode(39)
                        ->respondWithError();
                }
            }

            if ($request->has('limit')) {
                if ($request->input('limit') > config('api.max_limit_per_page')) {
                    return $this->setHTTPStatusCode(400)
                        ->setErrorCode(30)
                        ->respondWithError();
                }

                $this->setLimitPerPage($request->input('limit'));
            }

            if ($request->has('with')) {
                $this->setWithParameter($request->input('with'));
            }


            return $next($request);
        });
    }

    /**
     * Default request to the API.
     *
     * @return JsonResponse
     */
    public function success(): JsonResponse
    {
        return $this->respond([
            'success' => [
                'message' => 'Welcome to Monica',
            ],
        ]);
    }

    /**
     * @return ?string
     */
    public function getWithParameter(): ?string
    {
        return $this->withParameter;
    }

    /**
     * @param string $with
     * @return self
     */
    public function setWithParameter(string $with): static
    {
        $this->withParameter = $with;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimitPerPage(): int
    {
        return $this->limitPerPage;
    }

    /**
     * @param int $limit
     * @return self
     */
    public function setLimitPerPage(int $limit): static
    {
        $this->limitPerPage = $limit;

        return $this;
    }

    /**
     * Get the sort direction parameter.
     *
     * @return string
     */
    public function getSortDirection(): string
    {
        return $this->sortDirection;
    }

    /**
     * @return string
     */
    public function getSortCriteria(): string
    {
        return $this->sort;
    }

    /**
     * @param string $criteria
     * @return self
     */
    public function setSortCriteria(string $criteria): static
    {
        $acceptedCriteria = [
            'created_at',
            'updated_at',
            'name',
            '-created_at',
            '-updated_at',
            '-name',
        ];

        if (in_array($criteria, $acceptedCriteria)) {
            $this->setSQLOrderByQuery($criteria);

            return $this;
        }

        $this->sort = '';

        return $this;
    }

    /**
     * Set both the column and order necessary to perform an orderBy.
     */
    public function setSQLOrderByQuery($criteria)
    {
        $this->sortDirection = $criteria[0] == '-' ? 'desc' : 'asc';
        $this->sort = ltrim($criteria, '-');
    }
}
