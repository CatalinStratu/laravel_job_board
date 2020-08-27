<?php

namespace App\Traits;

trait AvatarTrait
{

    /**
     * Get the model's Avatar
     *
     * @return string
     */
    public function getAvatarAttribute()
    {
        return "https://eu.ui-avatars.com/api/?rounded=true&name=$this->name&background=f1f1f1";
    }
}