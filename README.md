### Install
```
composer require thuanvp012van/gtts
```
### Usage

```php
use Thuanvp012van\GTTS\GTTS;
use Thuanvp012van\GTTS\Language;

require './vendor/autoload.php';

$gtts = new GTTS('Xin chào mọi người', Language::VI);
$gtts->save('thuan.mp3');
```
### Methods
* `text(string $text)`: Set text.
* `getText()`: Get text.
* `lang(\Thuanvp012van\GTTS\Language $lang)`: Set language.
* `getLang()`: Get language.
* `setLevelDomain()`: Set top level domain.
* `getLevelDomain()`: Get top level domain.
* `slowSpeed()`: Set slow reading speed.
* `normalSpeed()`: Set normal reading speed.
* `isSlowSpeed()`: Check is slow reading speed.
* `isNormalSpeed()`: Check is normal reading speed.
* `save(string $fileName)`: Save speech as file.
* `stream()`: Get parts of speech.
