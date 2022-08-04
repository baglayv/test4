<?php

namespace App\Repositories;

use App\Models\City;

class CityRepository
{
    protected $model = City::class;

    /**
     * Постраничный вывод городов
     * @param int $page
     * @param int $limit
     * @param string $sort
     * @param array $filter
     * @return array
     */
    public function getListCities(int $page, int $limit, string $sort = '', array $filter = []): array
    {
        if ($page < 1) {
            $page = 1;
        }

        $model = City::query();


        if (!empty($filter['id'])) {
            if (is_uuid($filter['id'])) {
                $model = $model->where('id', $filter['id']);
            } else {
                return [];
            }
        }

        if (!empty($filter['region_id'])) {
            if (is_uuid($filter['region_id'])) {
                $model = $model->where('region_id', $filter['region_id']);
            } else {
                return [];
            }
        }

        if (!empty($filter['name'])) {
            $model = $model->where('name', $filter['name']);
        }

        if (!empty($sort)) {
            $table = 'cities.';
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
