<?php

$Config = array (
  'xml_urls' => 
  array (
  ),
  'days_to_keep' => 7,
  'manage_password' => 'admin123',
  'channel_replacements' => 
  array (
    0 => 'HD',
    1 => 'FHD',
    2 => '1080P',
    3 => '高清',
    4 => '全高清',
    5 => '-',
  ),
  'channel_mappings' => 
  array (
    'regex:/^CCTV[-\s]*(\p{Han})/iu' => '$1',
    'regex:/^(CCTV[-\s]*\d+[K\+]?)(?!美洲|欧洲).*/i' => '$1',
    'regex:/^(深圳.*?)频道$/i' => '$1',
    'CGTN英语' => 'CGTN',
    'CGTN西班牙语' => 'CGTN西语',
    'CGTN阿拉伯语' => 'CGTN阿语',
    'CGTN Documentary' => 'CGTN纪录',
  ),
);

?>