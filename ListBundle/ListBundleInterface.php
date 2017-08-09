<?php


namespace ListParams\ListBundle;

use ListParams\Controller\PaginationFrame;
use ListParams\Controller\SortFrame;
use ListParams\ListParamsInterface;

interface ListBundleInterface
{


    /**
     * @return array, rows representing the list items
     */
    public function getListItems();

    /**
     * @return ListParamsInterface|null
     */
    public function getListParams();

    /**
     * @return PaginationFrame|null
     */
    public function getPaginationFrame();

    /**
     * @return SortFrame|null
     */
    public function getSortFrame();



//    /**
//     * @return SearchExpressionFrame|null
//     */
//    public function getSearchExpressionFrame();
//
//
//    /**
//     * @return SearchItemsFrame|null
//     */
//    public function getSearchItemsFrame();

}