<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\RegionRepository;
use Illuminate\Http\Request;


class RegionController extends Controller
{
    protected $regionRepository;

    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @OA\Get(
     *      path="api/regions",
     *      tags={"Regions"},
     *      summary="Возвращает все регионы",
     *      description="Возвращает все регионы",
     *      security={{"token": {}}},
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Номер страницы",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="Количество регионов на странице",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="sort",
     *          in="query",
     *          description="Сортировка",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="filter",
     *          in="query",
     *          description="Фильтрация",
     *          required=false,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Regions")
     *          )
     *      ),
     *      @OA\Response(response=401, ref="#/components/responses/401")
     * )
     */
    public function index(Request $request)
    {
        $page = (int)$request->query('page', 1);
        $limit = (int)$request->query('limit', 50);
        $sort = (string)$request->get('sort', "name");
        $filter = (array)(json_decode($request->query('filter'), true));

        $regions = $this->regionRepository->getListRegions($page, $limit, $sort, $filter);

        return response()->json($regions);
    }
}
