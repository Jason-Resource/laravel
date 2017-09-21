<?php

namespace App\Http\ViewComposers;

use App\Http\Business\CategoryBusiness;
use Illuminate\Contracts\View\View;

class ProfileComposer
{
    
    private $category_business;
    
    /**
     * Create a new profile composer.
     *
     * @param  CategoryBusiness  $category_business
     * @return void
     */
    public function __construct(CategoryBusiness $category_business)
    {
        // Dependencies automatically resolved by service container...
        $this->category_business = $category_business;
    }
    
    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        //获取PC端导航列表
        $arr = array();
        $arr['nav_list'] = $this->category_business->getPcNav();
        foreach($arr as $ak=>$av){
            $view->with($ak,$av);
        }
        
        return $view;
    }
    
}