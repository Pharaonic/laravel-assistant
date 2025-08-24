<?php

return [

    /**
     * Enable pagination feature for Json Responses.
     */
    'pagination' => true,

    /**
     * Enable timeable feature for Json Resources.
     */
    'timeable' => true,

    /**
     * Enable fileable feature for Json Resources.
     */
    'fileable' => trait_exists('Pharaonic\Laravel\Files\Traits\HasFiles'),

    /**
     * Enable translatable feature for Json Resources.
     */
    'translatable' => trait_exists('Pharaonic\Laravel\Translatable\Translatable') && class_exists('Pharaonic\Laravel\Localization\Classes\Localization'),
];
