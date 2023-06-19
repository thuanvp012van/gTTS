### Install
```
composer require thuanvp012van/gtts
```
### Usage
1. Use with php
    ```php
    use Thuanvp012van\GTTS\GTTS;
    use Thuanvp012van\GTTS\Language;

    require './vendor/autoload.php';

    $gtts = new GTTS('Xin chào mọi người', Language::VI); // or $gtts = (new GTTS('Xin chào mọi người'))->autoDetection(true);
    $gtts->save('helloworld.mp3');
    ```

2. Use with command-line
    ```bash
    php ./vendor/bin/gtts languages # Show all languages
    ```
    ```bash
    php ./vendor/bin/gtts save --file=helloworld.mp3 --language=vi 'Xin chào mọi người' # Convert text to speech.
    ```
    OR
    ```bash
    php ./vendor/bin/gtts save --file=helloworld.mp3 --auto-detection 'Xin chào mọi người' # Automatic language detection.
    ```

### Methods
* `text(string $text)`: Set text.
* `getText()`: Get text.
* `lang(\Thuanvp012van\GTTS\Language $lang)`: Set language.
* `getLang()`: Get language.
* `autoDetection(bool $autoDetection)`: Set auto detection.
* `isAutoDetection()`: Check is auto detection..
* `setLevelDomain()`: Set top level domain.
* `getLevelDomain()`: Get top level domain.
* `slowSpeed()`: Set slow reading speed.
* `normalSpeed()`: Set normal reading speed.
* `isSlowSpeed()`: Check is slow reading speed.
* `isNormalSpeed()`: Check is normal reading speed.
* `save(string $fileName)`: Save speech as file.
* `stream()`: Get parts of speech.
