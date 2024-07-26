<?php

$Config = array (
  'xml_urls' => 
  array (
  ),
  'days_to_keep' => 7,
  'gen_xml' => '1',
  'start_time' => '00:00',
  'end_time' => '23:59',
  'interval_time' => 0,
  'manage_password' => '',
  'channel_replacements' => 
  array (
    0 => 'HEVC',
    1 => 'HD',
    2 => 'FHD',
    3 => '1080P',
    4 => '标清',
    5 => '高清',
    6 => '全高清',
    7 => '-',
  ),
  'channel_mappings' => 
  array (
    'regex:/^CCTV[-\\s]*(\\p{Han})/iu' => '$1',
    'regex:/^CCTV[-\\s]*(\\d+[K\\+]?)(?!美洲|欧洲).*/i' => 'CCTV$1',
    '[CGTN Documentary, CGTN 档案]' => 'CGTN纪录',
    'CGTN英语' => 'CGTN',
  ),
);

?>