<?php

    $file = file_get_contents(__DIR__."/app-spec.abcd");

    $lines = explode("\n",$file);
    $lines = array_filter($lines,function($v){return strlen(trim($v))>0; });
    $lineshtml = array_map(function($v){return htmlentities($v);},$lines);
    $result = array();
    $context =& $result;
    $indent = 0;
    $spaces = array();

    foreach($lines as $l){
        preg_match("/^(\s)*/",$l,$m);
        $spaces[] = strlen($m[0]);
    }


    foreach($lines as $l){
        if(preg_match("/&([a-zA-Z0-9]+)/",$l,$m)){
            $context['classes'][$m[1]] = array();
            $context &= $context['classes'][$m[1]];
        }elseif(preg_match("/&([a-zA-Z0-9]+)/",$l,$m)){
            $parts = explode(":",$m[1]);
            $context["fields"][trim($parts[0])]["type"] = trim($parts[1]);
        }
    }

    echo '<pre>';
    echo 'lines:';
    print_r($lineshtml);
    echo 'spaces:';
    print_r($spaces);
    echo 'result:';
    print_r($result);
    echo '</pre>';

    //echo nl2br(htmlentities($file));