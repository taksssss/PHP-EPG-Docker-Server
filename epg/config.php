<?php

$Config = array (
  'xml_urls' => 
  array (
  ),
  'days_to_keep' => 7,
  'gen_xml' => '1',
  'include_future_only' => 1,
  'proc_chname' => 1,
  'start_time' => '00:00',
  'end_time' => '23:59',
  'interval_time' => 0,
  'manage_password' => '',
  'channel_replacements' => 
  array (
    0 => '50 FPS',
    1 => 'HEVC',
    2 => 'HD',
    3 => 'FHD',
    4 => '1080P',
    5 => '标清',
    6 => '高清',
    7 => '全高清',
    8 => '-',
    9 => '\\s',
  ),
  'channel_mappings' => 
  array (
    'regex:/^CCTV[-\\s]*(\\p{Han})/iu' => '$1',
    'regex:/^CCTV[-\\s]*(\\d+(\\s*PLUS|[K\\+])?)(?!美洲|欧洲).*/i' => 'CCTV$1',
    'regex:/^(深圳.*?)频道$/i' => '$1',
    '[CGTN Documentary, CGTN 档案, CGTN 记录]' => 'CGTN纪录',
    'CGTN英语' => 'CGTN',
  ),
);

?>