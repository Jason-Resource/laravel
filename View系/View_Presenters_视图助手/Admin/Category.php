<?php

namespace App\Presenters\Admin;

class Category
{
    public function showChannel($channel_list = [],$category_info = '')
    {
        $html = '';
        foreach ($channel_list as $item) {
            $checked = '';
            $sort = 0;
            $name = 'app_sort';
            $className = '';
            if (!empty($category_info)) {
                $channel_ids = array_column($category_info['category_pushchannel'], 'channel_id');
                if(in_array($item['id'],$channel_ids)){
                    $checked = ' checked';
                }
            } else {
                $checked = ' checked';
            }
            if($item['id'] == 1000) {
                $sort = !empty($category_info['web_sort']) ? $category_info['web_sort'] : 100 ;
                $className = 'web';
                $name = 'web_sort';
            } elseif ($item['id'] == 1001) {
                $sort = !empty($category_info['app_sort']) ? $category_info['app_sort'] : 100 ;
                $className = 'app';
                $name = 'app_sort';
            }
            $html .= '<div><label class="channel-label"><input type="checkbox" class="'.$className.'"  name="channel" '.$checked.' value="'.$item['id'].'">'.$item['display_name'].'</label><input type="number" name="'.$name.'" value="'.$sort.'"></div>';
            //$html .= '<label> <input name="channel[]" type="checkbox" '.$checked.' value="'.$item['id'].'"/> <span class="lbl"> '.$item['display_name'].' </span> </label> ';
        }
        return $html;
    }

    /**
     * 获取显示模块
     * @param array $platform_list
     * @param string $category_info
     * @return string
     */
    public function getShowModule($show_module = [],$category_info = [])
    {
        $_id = '';
        if (!empty($category_info)) {
            $_id = $category_info['id'];
        }

        $html = '<select name="show_module" id="show_module'.$_id.'"><option value="">-选择模块-</option>';
        foreach ($show_module as $item) {
            $selected = '';
            if (!empty($category_info)) {
                $category_show_module = array_filter(explode(',',$category_info['show_module']));
                if(in_array($item['alias_name'],$category_show_module) && count($category_show_module)>0){
                    $selected = ' selected';
                }
            }
            $html .= '<option value="'.$item['alias_name'].'" '.$selected.'>'.$item['display_name'].'</option>';
        }
        $html .= '</select>';
        return $html;
    }
}