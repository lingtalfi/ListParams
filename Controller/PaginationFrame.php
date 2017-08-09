<?php


namespace ListParams\Controller;


use Bat\UriTool;
use ListParams\ListParamsInterface;
use ListParams\Util\ListParamsUtil;


/**
 *
 * This class is basically an adapted implementation of the PaginationModel
 * (https://github.com/lingtalfi/Models/blob/master/Pagination/PaginationModel.php
 * for the ListParams environment.
 *
 *
 *
 *
 * == WARNING ============= WARNING ==========
 * THIS FRAME **REQUIRES** THE TOTAL NUMBER OF ITEMS (FROM THE MODEL)
 * ==========
 *
 *
 *
 * The model used is the following:
 *
 * - currentPage: int
 * - items: array
 *      - (itemIndex)
 *          - number: int
 *          - link: string
 *          - selected: bool
 *
 *
 *
 *
 */
class PaginationFrame
{
    private $currentPage;
    private $items;

    public function __construct()
    {
        $this->currentPage = 1;
        $this->items = [];
    }


    public static function createByParams(ListParamsInterface $params, callable $linkFormatter = null)
    {


        $namePage = $params->getNamePage();
        $nipp = $params->getNumberOfItemsPerPage();
        $nbItems = $params->getTotalNumberOfItems();
        $pool = $params->getPool();


        if (null === $linkFormatter) {
            $uriParams = $pool;
            $uriParams[$namePage] = '%s';
            ListParamsUtil::removeNonPersistentParams($uriParams, $params, 'page');
            $uri = UriTool::uri(null, $uriParams, true);
            $linkFormatter = function ($n) use ($uri) {
                return sprintf($uri, $n);
            };
        }

        $nbPages = ceil($nbItems / $nipp);

        $o = new static();
        if (array_key_exists($namePage, $pool)) {
            $currentPage = (int)$pool[$namePage];
            if ($currentPage < 1) {
                $currentPage = 1;
            } elseif ($currentPage > $nbPages) {
                $currentPage = $nbPages;
            }
        } else {
            $currentPage = 1;
        }

        for ($i = 1; $i <= $nbPages; $i++) {
            $o->items[] = [
                'number' => $i,
                'link' => call_user_func($linkFormatter, $i),
                'selected' => ($i === $currentPage),
            ];
        }
        $o->currentPage = $currentPage;
        return $o;
    }


    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    //--------------------------------------------
    //
    //--------------------------------------------
    public function getArray()
    {
        return [
            'currentPage' => $this->currentPage,
            'items' => $this->items,
        ];
    }
}