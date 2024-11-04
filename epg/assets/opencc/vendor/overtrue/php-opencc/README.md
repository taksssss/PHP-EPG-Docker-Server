# PHP OpenCC

中文简繁转换，支持词汇级别的转换、异体字转换和地区习惯用词转换（中国大陆、台湾、香港、日本新字体）。基于 [BYVoid/OpenCC](https://github.com/BYVoid/OpenCC) 数据实现。

[![Build Status](https://github.com/overtrue/php-opencc/actions/workflows/test.yml/badge.svg)](https://github.com/overtrue/php-opencc/actions/workflows/test.yml)
[![Latest Stable Version](https://poser.pugx.org/overtrue/php-opencc/v/stable)](https://packagist.org/packages/overtrue/php-opencc)
[![Total Downloads](https://poser.pugx.org/overtrue/php-opencc/downloads)](https://packagist.org/packages/overtrue/php-opencc)
[![License](https://poser.pugx.org/overtrue/php-opencc/license)](https://packagist.org/packages/overtrue/php-opencc)

## 安装

```shell
$ composer require overtrue/php-opencc -vvv
```

## 使用

```php
use Overtrue\OpenCC\OpenCC;

echo OpenCC::convert('服务器', 'SIMPLIFIED_TO_TAIWAN_WITH_PHRASE'); 
// output: 伺服器
```

### 使用策略别名

```php
use Overtrue\OpenCC\OpenCC;
use Overtrue\OpenCC\Strategy;

// 以下方法等价：

// 方法
echo OpenCC::s2tw('服务器');
echo OpenCC::simplifiedToTaiwan('服务器');

// 字符串
echo OpenCC::convert('服务器', 's2tw');
echo OpenCC::convert('服务器', 'S2TW');
echo OpenCC::convert('服务器', 'SIMPLIFIED_TO_TAIWAN');

// 常量
echo OpenCC::convert('服务器', Strategy::S2TW);
echo OpenCC::convert('服务器', Strategy::SIMPLIFIED_TO_TAIWAN);
```

### 转换策略

| 策略 （别名）                                   | 说明              |
|-------------------------------------------|-----------------|
| `SIMPLIFIED_TO_TRADITIONAL(S2T)`          | 简体到繁体           |
| `SIMPLIFIED_TO_HONGKONG(S2HK)`            | 简体到香港繁体         |
| `SIMPLIFIED_TO_JAPANESE(S2JP)`            | 简体到日文           |
| `SIMPLIFIED_TO_TAIWAN(S2TW)`              | 简体到台湾正体         |
| `SIMPLIFIED_TO_TAIWAN_WITH_PHRASE(2TWP)`  | 简体到台湾正体, 带词汇本地化 |
| `HONGKONG_TO_TRADITIONAL(HK2T)`           | 香港繁体到正体         |
| `HONGKONG_TO_SIMPLIFIED(HK2S)`            | 香港繁体到简体         |
| `TAIWAN_TO_SIMPLIFIED(TW2S)`              | 台湾正体到简体         |
| `TAIWAN_TO_TRADITIONAL(TW2T)`             | 台湾正体到繁体         |
| `TAIWAN_TO_SIMPLIFIED_WITH_PHRASE(TW2SP)` | 台湾正体到简体, 带词汇本地化 |
| `TRADITIONAL_TO_HONGKONG(T2HK)`           | 正体到香港繁体         |
| `TRADITIONAL_TO_SIMPLIFIED(T2S)`          | 繁体到简体           |
| `TRADITIONAL_TO_TAIWAN(T2TW)`             | 繁体到台湾正体         |
| `TRADITIONAL_TO_JAPANESE(T2JP)`           | 繁体到日文           |
| `JAPANESE_TO_TRADITIONAL(JP2T)`           | 日文到繁体           |
| `JAPANESE_TO_SIMPLIFIED(JP2S)`            | 日文到简体           |


### 在命令行使用

```shell
$ php vendor/bin/opencc "汉字" s2tw
```

说明：

```bash
$ php vendor/bin/opencc --help
Description:
  中文简繁转换，支持词汇级别的转换、异体字转换和地区习惯用词转换（中国大陆、台湾、香港、日本新字体）。

Usage:
  convert <string> [<strategy>]

Arguments:
  string                待转换的字符串
  strategy              转换策略 [default: "SIMPLIFIED_TO_TRADITIONAL"]
```

## :heart: 赞助我 

如果你喜欢我的项目并想支持它，[点击这里 :heart:](https://github.com/sponsors/overtrue)

## Project supported by JetBrains

Many thanks to Jetbrains for kindly providing a license for me to work on this and other open-source projects.

[![](https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.svg)](https://www.jetbrains.com/?from=https://github.com/overtrue)


## 参与贡献

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/overtrue/php-opencc/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/overtrue/php-opencc/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
