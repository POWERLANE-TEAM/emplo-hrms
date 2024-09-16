<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */


    'consent' => 'I agree to the',
    'consents' => function () {
        return $this['consent'];
    },
    'term_condition' => 'Terms&nbsp;&&nbsp;Conditions',
    'privacy_policy' => 'Privacy&nbsp;Policy',
    'terms_privacy' => function () {
        return $this['consents'] . ' ' . $this['term_condition'] . ' at ' . $this['privacy_policy'] . '.';
    },
];
