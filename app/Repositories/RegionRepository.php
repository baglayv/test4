<?php

namespace App\Repositories;

use App\Models\Region;

//use App\Helpers\Uuid;

class RegionRepository
{
    protected $model = Region::class;

    /**
     * Постраничный вывод регионов
     * @param int $page
     * @param int $limit
     * @param string $sort
     * @param array $filter
     * @return array
     */
    public function getListRegions(int $page, int $limit, string $sort = '', array $filter = []): array
    {
        if ($page < 1) {
            $page = 1;
        }

        $model = Region::query();

        if (!empty($filter['id'])) {
            if (is_uuid($filter['id'])) {
                $model = $model->where('id', $filter['id']);
            } else {
                return [];
            }
        }
        if (!empty($filter['name'])) {
            $model = $model->where('name', $filter['name']);
        }

        if (!empty($sort)) {
            $table = 'regions.';
            $orderType = 'orderBy';

            if (strpos($sort, '-') !== false) {
                $orderType = 'orderByDesc';
                $sort = substr($sort, 1);
            }

            $model = $model->$orderType($table . $sort);
        }

        $regions = $model->paginate($limit);

        return [
            'total' => $regions->total(),
            'regions' => $regions->items(),
        ];
    }

}
