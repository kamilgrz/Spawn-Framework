<?php
$config['{foreach (.*) as (.*)}']='<? foreach($1 as $2): ?>';
$config['{foreach (.*) as (.*) => (.*)}']='<? foreach($1 as $2 => $3): ?>';
$config['{/foreach}']='<? endforeach;  ?>';

$config['{if (.*)}']='<? if($1): ?>';
$config['{elseif (.*)}']='<? elseif($1): ?>';
$config['{else}']='<? else: ?>';
$config['{/if}']='<? endif; ?>';

$config['{while (.*)}']='<? while($1): ?>';
$config['{/while}']='<? endwhile; ?>';

$config['{for (.*)}']='<? for ($1): ?>';
$config['{/for}']='<? endfor; ?>';

$config['{break}']='<? break; ?>';
$config['{break ([0-9]+)}']='<? break $1; ?>';

$config['{continue}']='<? continue; ?>';
$config['{continue ([0-9]+)}']='<? continue $1; ?>';

$config['{switch (.*)}']='<? switch($1): ?>';
$config['{case (.*)}']='<?  case $1: ?>';
$config['{default}']='<? default: ?>';
$config['{/switch}']='<? endswitch; ?>';

$config['{include (.*)}']='<? include(\'$1\'); ?>';
$config['{include_once (.*)}']='<? include_once(\'$1\'); ?>';

$config['{(.*)\+\+}']='<? $1++; ?>';
$config['{(.*)--}']='<? $1--; ?>';

$config['{(.*)=(.*)}']='<? $1=$2; ?>';
$config['{f (.*)}']='<? echo sf_filter::xss($1); ?>';
$config['{(.*)}']='<? echo $1; ?>';
return $config;
