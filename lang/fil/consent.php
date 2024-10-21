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

    'consent' => 'Sumasang-ayon ako sa ',
    'consents' => 'Sumasang-ayon ako sa mga ',
    'term_condition' => 'Tuntunin&nbsp;at&nbsp;Kundisyon',
    'privacy_policy' => 'Patakaran&nbsp;sa&nbsp;Privacy',
    'terms_privacy' => function () {
        return $this['consents'].$this['term_condition'].' at '.$this['privacy_policy'].'.';
    },
];
