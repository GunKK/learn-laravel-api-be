<?php

namespace App\Traits;

trait HasGravatar
{
    /**
     * The attribute name containing the email address.
     *
     * @var string
     */
    public $gravatarEmail = 'email';

    /**
     * Get the model's gravatar
     *
     * @return string
     */
    public function getGravatarAttribute(): string
    {
        $hash = md5(strtolower(trim($this->attributes[$this->gravatarEmail])));

        return "https://www.gravatar.com/avatar/$hash"; // use: Auth::user()->gravatar
    }
}
