<?php

namespace App\Http\ViewComposers;

use App\Http\Business\UsersBusiness;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Request;

class UserCenterComposer
{

    protected $users_business = null;

    /**
     * Create a new profile composer.
     *
     * @param  UsersBusiness  $users_business
     * @return void
     */
    public function __construct(UsersBusiness $users_business)
    {
        $this->users_business = $users_business;
    }
    
    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        static $user_data = null;
        if(empty($user_data)) {
            $user_id = request()->get('user_id');
            $user_data = $this->users_business->userDetails($user_id);
        }

        $view->with('user_data',$user_data);
        
        return $view;
    }
    
}