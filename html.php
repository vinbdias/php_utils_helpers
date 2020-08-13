<?php
    abstract class HtmlHelper
    {

        static function extractImgTagsToArray($html) {

            preg_match_all("#<img(.*?)\\/?>#", $html, $img_matches);

            $html_imgs_array = [];
            $img_key = 0;
            foreach ($img_matches[1] as $key => $img_tag) {
                preg_match_all('/(alt|src|width|height|title)=["\'](.*?)["\']/i', $img_tag, $attribute_matches);
                $attributes = array_combine($attribute_matches[1], $attribute_matches[2]);
        
                
                foreach ($attributes as $attribute => $val) {                    
                    $html_imgs_array[$img_key][$attribute] = $val; 
                }

                $img_key++;
            }            

            return $html_imgs_array;
        }
    }