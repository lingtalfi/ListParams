<?php


namespace ListParams\ListBundle;

use ListParams\Controller\InfoFrame;
use ListParams\Controller\PaginationFrame;
use ListParams\Controller\SortFrame;
use ListParams\ListParamsInterface;

class LingListBundle extends ListBundle
{


    public static function createByItems(
        array $items,
        ListParamsInterface $params = null,
        PaginationFrame $pagination = null,
        SortFrame $sort = null
    )
    {
        $list = ListBundle::create()->setItems($items);

        if (null !== $params) {
            $list->setListParams($params);
        }

        if (null !== $pagination) {
            $list->setPagination($pagination);
        }

        if (null !== $sort) {
            $list->setSort($sort);
        }

        $list->setInfo(InfoFrame::create($params));

        return $list;
    }
}