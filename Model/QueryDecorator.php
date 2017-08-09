<?php


namespace ListParams\Model;


use ListParams\Exception\ListParamsException;
use ListParams\ListParamsInterface;

class QueryDecorator
{

    /**
     * Whether or not the user is allowed to modify those values
     */
    private $allowSort;
    private $allowSearch;
    private $allowPage;
    private $allowNipp;

    /**
     * Which values the user is allowed to modify
     */
    private $allowedSearchFields;
    private $allowedSortFields;


    private $defaultNipp;


    public function __construct()
    {
        $this->allowSort = true;
        $this->allowSearch = true;
        $this->allowPage = true;
        $this->allowNipp = true;
        $this->allowedSearchFields = [];
        $this->allowedSortFields = [];
        $this->defaultNipp = 20;
    }

    public static function create()
    {
        return new static();
    }


    /**
     * Potentially decorates:
     *
     * - the query with
     *          WHERE...
     *          ORDER BY...
     *          LIMIT...
     * - the countQuery with
     *          WHERE...
     *
     * - the params
     *          ->setAllowedSortFields
     *          ->setAllowedSearchFields
     *
     * @param $query
     * @param $countQuery
     * @param array $markers
     * @param ListParamsInterface|null $params
     * @throws ListParamsException
     */
    public function decorate(&$query, &$countQuery, array &$markers = [], ListParamsInterface $params = null)
    {

        if (null !== $params) {
            $params->setAllowedSortFields($this->allowedSortFields);
            $params->setAllowedSearchFields($this->allowedSearchFields);
        }


        if (true === $this->allowSearch && null !== $params) {
            $searchItems = $params->getSearchItems();

            if (is_array($searchItems)) {
                if ($searchItems) {
                    $valid = false;
                    $markerCount = 0;
                    foreach ($searchItems as $field => $searchItem) {
                        if (in_array($field, $this->allowedSearchFields)) {

                            if (false === $valid) {
                                $query .= " where ";
                                $countQuery .= " where ";
                                $valid = true;
                            }

                            if (0 !== $markerCount) {
                                $query .= " and ";
                                $countQuery .= " and ";
                            }
                            $marker = "m" . $markerCount++;
                            if (is_int($searchItem)) {
                                $query .= "$field = " . (int)$searchItem;
                                $countQuery .= "$field = " . (int)$searchItem;
                            } else {
                                $query .= "$field like :$marker";
                                $countQuery .= "$field like :$marker";
                                $markers[$marker] = '%' . str_replace('%', '\%', $searchItem) . '%';
                            }

                        } else {
                            $this->onSearchFieldNotAllowed($field);
                        }
                    }
                }
            } else {
                throw new ListParamsException("Oops, this form of searchItem is not recognized yet, you may want to upgrade the code");
            }

        }


        if (true === $this->allowSort && null !== $params) {
            $sortItems = $params->getSortItems();
            $valid = false;
            $c = 0;
            foreach ($sortItems as $field => $isAsc) {
                if (in_array($field, $this->allowedSortFields)) {
                    if (false === $valid) {
                        $query .= " order by ";
                        $valid = true;
                    }

                    if (0 !== $c++) {
                        $query .= ', ';
                    }

                    $query .= "$field ";
                    if (true === $isAsc) {
                        $query .= 'asc';
                    } else {
                        $query .= 'desc';
                    }
                } else {
                    $this->onSortFieldNotAllowed($field);
                }
            }
        }


        if (true === $this->allowPage && null !== $params) {
            $page = $params->getPage();
            if (true === $this->allowNipp) {
                $nipp = $params->getNumberOfItemsPerPage();
            } else {
                $nipp = $this->defaultNipp;
            }
            $offset = ($page - 1) * $nipp;
            $query .= " limit $offset, $nipp";
        }
    }


    //--------------------------------------------
    // SETTERS
    //--------------------------------------------
    public function setAllowSort($allowSort)
    {
        $this->allowSort = $allowSort;
        return $this;
    }

    public function setAllowSearch($allowSearch)
    {
        $this->allowSearch = $allowSearch;
        return $this;
    }

    public function setAllowPage($allowPage)
    {
        $this->allowPage = $allowPage;
        return $this;
    }

    public function setAllowNipp($allowNipp)
    {
        $this->allowNipp = $allowNipp;
        return $this;
    }

    public function setAllowedSearchFields($allowedSearchFields)
    {
        $this->allowedSearchFields = $allowedSearchFields;
        return $this;
    }

    public function setAllowedSortFields($allowedSortFields)
    {
        $this->allowedSortFields = $allowedSortFields;
        return $this;
    }

    public function setDefaultNipp($defaultNipp)
    {
        $this->defaultNipp = $defaultNipp;
        return $this;
    }



    //--------------------------------------------
    // HOOKS
    //--------------------------------------------
    protected function onSearchFieldNotAllowed($invalidSearchField) // override me
    {

    }

    protected function onSortFieldNotAllowed($invalidSortField) // override me
    {

    }
}