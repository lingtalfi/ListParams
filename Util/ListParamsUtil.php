<?php


namespace ListParams\Util;


use ListParams\ListParamsInterface;

class ListParamsUtil
{

    public static function removeNonPersistentParams(array &$pool, ListParamsInterface $params, $except = null)
    {
        if (false === $params->hasPersistentPage() && 'page' !== $except) {
            unset($pool[$params->getNamePage()]);
            unset($pool[$params->getNameNipp()]);
        }
        if (false === $params->hasPersistentSort() && 'sort' !== $except) {
            unset($pool[$params->getNameSort()]);
            unset($pool[$params->getNameSortDir()]);
        }
        if (false === $params->hasPersistentSearch() && 'search' !== $except) {
            unset($pool[$params->getNameSearchExpression()]);
            unset($pool[$params->getNameSearchItems()]);
        }
    }


    public static function getFormTrail(array $pool, ListParamsInterface $params, $except = null)
    {
        $s = '';
        if (true === $params->hasPersistentPage() && 'page' !== $except) {
            $namePage = $params->getNamePage();
            $nameNipp = $params->getNameNipp();

            if (array_key_exists($namePage, $pool)) {
                $s .= self::getHiddenInput($namePage, $pool[$namePage]);
            }
            if (array_key_exists($nameNipp, $pool)) {
                $s .= self::getHiddenInput($nameNipp, $pool[$nameNipp]);
            }
        }
        if (true === $params->hasPersistentSort() && 'sort' !== $except) {
            $nameSort = $params->getNameSort();
            $nameSortDir = $params->getNameSortDir();

            if (array_key_exists($nameSort, $pool)) {
                $s .= self::getHiddenInput($nameSort, $pool[$nameSort]);
            }
            if (array_key_exists($nameSortDir, $pool)) {
                $s .= self::getHiddenInput($nameSortDir, $pool[$nameSortDir]);
            }
        }
        if (true === $params->hasPersistentSearch() && 'search' !== $except) {
            $nameSearch = $params->getNameSearchExpression();
            $nameSearchItems = $params->getNameSearchItems();

            if (array_key_exists($nameSearch, $pool)) {
                $s .= self::getHiddenInput($nameSearch, $pool[$nameSearch]);
            }
            if (array_key_exists($nameSearchItems, $pool)) {
                $s .= self::getHiddenInput($nameSearchItems, $pool[$nameSearchItems]);
            }
        }
        return $s;
    }



    //--------------------------------------------
    //
    //--------------------------------------------
    private static function getHiddenInput($name, $value)
    {
        // name and value don't contain html special chars
        return '<input type="hidden" name="' . $name . '" value="' . $value . '">' . PHP_EOL;
    }
}