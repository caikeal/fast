<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/22
 * Time: 23:59
 */

namespace App\Fast\Service\Manager;


class Poster
{
    protected $typeArr = ['fruit'];

    protected $range = [
        'fruit' => 20
    ];

    protected $defaultPath = '/images/m_default';

    public function randomPoster()
    {
        $key = array_rand($this->typeArr);
        $path = $this->typeArr[$key];
        $maxLimit = $this->range[$path];
        $place = mt_rand(1, $maxLimit);

        return $this->defaultPath."/".$path."/".$place.".png";
    }
}