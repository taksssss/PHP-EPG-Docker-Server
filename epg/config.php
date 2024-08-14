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
    0 => 'HEVC',
    1 => 'HD',
    2 => 'FHD',
    3 => '1080P',
    4 => '标清',
    5 => '高清',
    6 => '全高清',
    7 => '-',
    8 => '\\s',
    9 => '50FPS',
  ),
  'channel_mappings' => 
  array (
    'regex:/^CCTV[-\\s]*(\\p{Han})/iu' => '$1',
    'regex:/^CCTV[-\\s]*(\\d+(\\s*P(LUS)?|[K\\+])?)(?!美洲|欧洲).*/i' => 'CCTV$1',
    'regex:/^(深圳.*?)频道$/i' => '$1',
    'CGTN Documentary, CGTN档案, CGTN记录' => 'CGTN纪录',
    'CGTN英语' => 'CGTN',
  ),
);

?>