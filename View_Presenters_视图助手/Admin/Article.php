<?php

namespace App\Presenters\Admin;

class Article
{
    /**
     * 显示文章股票关联
     *
     * @author jilin
     * @param $stockList
     * @param $article
     * @return string
     */
    public function showStock($stockList, $article = null)
    {
        $html = '';
        if (is_array($stockList) && count($stockList)>0) {
            foreach ($stockList as $key=>$value) {
                $checked = '';
                if (!is_null($article) && isset($article['article_stock_relation'])) {
                    $articleStock = $article['article_stock_relation'];
                    $articleStock = array_column($articleStock, 'stock_id');
                    if (in_array($value['code'], $articleStock)) {
                        $checked = ' checked';
                    }
                }
                $html .= '<input type="checkbox" name="stock[]" value="'.$value['code'].'"'.$checked.'> '.$value['name'].' ';
            }
        }
        return $html;
    }

    /**
     * 显示文章大盘关联
     *
     * @author jilin
     * @param $marketList
     * @param $article
     * @return string
     */
    public function showMarket($marketList, $article = null)
    {
        $html = '';
        if (is_array($marketList) && count($marketList)>0) {
            foreach ($marketList as $key=>$value) {
                $checked = '';
                if (!is_null($article) && isset($article['article_market_relation'])) {
                    $articleMarket = $article['article_market_relation'];
                    $articleMarket = array_column($articleMarket, 'market_id');
                    if (in_array($value['code'], $articleMarket)) {
                        $checked = ' checked';
                    }
                }
                $html .= '<input type="checkbox" name="market[]" value="'.$value['code'].'"'.$checked.'> '.$value['name'].' ';
            }
        }
        return $html;
    }

    /**
     * 显示文章概念关联
     *
     * @author jilin
     * @param $conceptList
     * @param $article
     * @return string
     */
    public function showConcept($conceptList, $article = null)
    {
        $html = '';
        if (is_array($conceptList) && count($conceptList)>0) {
            foreach ($conceptList as $key=>$value) {
                $checked = '';
                if (!is_null($article) && isset($article['article_concept_relation'])) {
                    $articleConcept = $article['article_concept_relation'];
                    $articleConcept = array_column($articleConcept, 'concept_id');
                    if (in_array($value['code'], $articleConcept)) {
                        $checked = ' checked';
                    }
                }
                $html .= '<input type="checkbox" name="concept[]" value="'.$value['code'].'"'.$checked.'> '.$value['name'].' ';
            }
        }
        return $html;
    }

    /**
     * 显示文章行业关联
     *
     * @author jilin
     * @param $industryList
     * @param $article
     * @return string
     */
    public function showIndustry($industryList, $article = null)
    {
        $html = '';
        if (is_array($industryList) && count($industryList)>0) {
            foreach ($industryList as $key=>$value) {
                $checked = '';
                if (!is_null($article) && isset($article['article_industry_relation'])) {
                    $articleIndustry = $article['article_industry_relation'];
                    $articleIndustry = array_column($articleIndustry, 'industry_id');
                    if (in_array($value['code'], $articleIndustry)) {
                        $checked = ' checked';
                    }
                }
                $html .= '<input type="checkbox" name="industry[]" value="'.$value['code'].'"'.$checked.'> '.$value['name'].' ';
            }
        }
        return $html;
    }

    /**
     * 显示文章-资讯模块关联
     *
     * @author jilin
     * @param $list
     * @param $article
     * @return string
     */
    public function showInformationModule($list, $article = null, $opration = true)
    {
        $html = '';
        if (is_array($list) && count($list)>0) {
            foreach ($list as $key=>$value) {
                $checked = '';
                if (!is_null($article) && isset($article['article_module_information_relation'])) {
                    $articleInformationModule = $article['article_module_information_relation'];
                    $articleInformationModule = array_column($articleInformationModule, 'module_id');
                    if (in_array($value['id'], $articleInformationModule)) {
                        $checked = ' checked';
                    }
                }

                $disable = '';
                if (true != $opration) {
                    $disable = ' disabled="disabled"';
                }
                $html .= '<input type="checkbox" name="information_module[]" value="'.$value['id'].'"'.$checked.''.$disable.'> '.$value['display_name'].' ';
            }
        }
        return $html;
    }

    /**
     * 显示文章-行情模块关联
     *
     * @author jilin
     * @param $list
     * @param $article
     * @return string
     */
    public function showQuotationModule($list, $article = null, $opration = true)
    {
        $html = '';
        if (is_array($list) && count($list)>0) {
            foreach ($list as $key=>$value) {
                $checked = '';
                if (is_numeric($article['id']) && isset($article['article_module_quotation_relation'])) {
                    $articleQuotationModule = $article['article_module_quotation_relation'];
                    $articleQuotationModule = array_column($articleQuotationModule, 'module_id');
                    if (in_array($value['id'], $articleQuotationModule)) {
                        $checked = ' checked';
                    }
                }

                $disable = '';
                if (true != $opration) {
                    $disable = ' disabled="disabled"';
                }
                $html .= '<input type="checkbox" name="quotation_module[]" value="'.$value['id'].'"'.$checked.''.$disable.'> '.$value['display_name'].' ';
            }
        }
        return $html;
    }
}