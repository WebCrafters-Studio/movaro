<?php

add_filter(
    'wpcf7_form_elements',
    function ($content) {
        return preg_replace(
            '/<label(\s+[^>]*)/i',
            '<label$1 class="b-contact__form-checkbox">',
            $content
        );
    }
);

add_filter(
    'wpcf7_form_response_output',
    function ($output) {
        return preg_replace(
            '/class="([^"]*wpcf7-response-output[^"]*)"/i',
            'class="b-contact__form-response $1"',
            $output
        );
    },
    10,
    4
);