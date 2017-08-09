<?php


namespace ListParams\ListBundle;

use ListParams\Controller\PaginationFrame;
use ListParams\Controller\SortFrame;
use ListParams\ListParamsInterface;

class ListBundle implements ListBundleInterface
{

    private $items;
    private $pagination;
    private $sort;
    private $listParams;

    public function __construct()
    {
        $this->items = [];
    }

    public static function create()
    {
        return new static();
    }

    public function getListItems()
    {
        return $this->items;
    }


    public function getListParams()
    {
        return $this->listParams;
    }

    public function getPaginationFrame()
    {
        return $this->pagination;
    }

    public function getSortFrame()
    {
        return $this->sort;
    }



    //--------------------------------------------
    // SETTERS
    //--------------------------------------------
    public function setItems(array $items)
    {
        $this->items = $items;
        return $this;
    }

    public function setPagination(PaginationFrame $pagination)
    {
        $this->pagination = $pagination;
        return $this;
    }

    public function setSort(SortFrame $sort)
    {
        $this->sort = $sort;
        return $this;
    }

    public function setListParams($listParams)
    {
        $this->listParams = $listParams;
        return $this;
    }


}