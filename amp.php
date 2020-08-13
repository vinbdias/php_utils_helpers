<?php
abstract class AmpHelper
{

    static function ampify($conteudo)
    {
        return self::blockquote(
            self::twitter(
                self::iframe(
                    self::script(
                        self::img($conteudo)
                    )
                )
            )
        );
    }

    static function script($conteudo)
    {
        return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $conteudo);
    }

    static function iframe($conteudo)
    {
        return str_replace(['<iframe', '/iframe>'], ['<div class="iframe-cb"><amp-iframe', '/amp-iframe></div>'], $conteudo);
    }
    static function twitter($conteudo)
    {
        preg_match_all('#<blockquote class=\"twitter-tweet\".*?>.*?<\/blockquote>#is', $conteudo, $twitter_array);
        if (count($twitter_array) > 0) {
            foreach ($twitter_array[0] as $tw => $twitter) {
                preg_match('/\/status\/(.*)\?/i', $twitter, $twitter_status);
                $status = $twitter_status[1];
                $twitter_html = '<amp-twitter width="375"  height="472"  layout="responsive" data-tweetid="' . $status  . '"></amp-twitter>';
                $conteudo = str_replace($twitter,  $twitter_html, $conteudo);
            }
        }
        return $conteudo;
    }

    static function blockquote($conteudo)
    {
        return preg_replace('#<blockquote class=\"twitter-tweet\".*?>.*?<\/blockquote>#is', '', $conteudo);
    }

    static function img($conteudo)
    {
        preg_match_all("#<img(.*?)\\/?>#", $conteudo, $img_matches);

        foreach ($img_matches[1] as $key => $img_tag) {
            preg_match_all('/(alt|src|width|height)=["\'](.*?)["\']/i', $img_tag, $attribute_matches);
            $attributes = array_combine($attribute_matches[1], $attribute_matches[2]);

            if (!array_key_exists('width', $attributes) || !array_key_exists('height', $attributes)) {
                if (array_key_exists('src', $attributes)) {
                    list($width, $height) = getimagesize($attributes['src']);
                    $attributes['width'] = $width;
                    $attributes['height'] = $height;
                }
            }

            $amp_tag = '<amp-img ';
            foreach ($attributes as $attribute => $val) {
                $amp_tag .= $attribute . '="' . $val . '" ';
            }
            $amp_tag .= 'layout="responsive"';
            $amp_tag .= '>';
            $amp_tag .= '</amp-img>';

            $conteudo = str_replace($img_matches[0][$key], $amp_tag, $conteudo);
        }

        return $conteudo;
    }
}
