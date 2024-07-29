<?php

use voku\helper\ASCII;

function nameGenerate(array $info, int $id): string
{
    $name = $info['filename'];
    $extension = $info['extension'];
    $translateName = ASCII::to_ascii($name);
    $lowerName = strtolower($translateName);
    $okName = preg_replace('/[^a-z0-9_-]/', '_', $lowerName);

    return $okName.'_'.$id.'.'.$extension;
}
