<?php

namespace Thuanvp012van\GTTS;

use Thuanvp012van\GTTS\Exceptions\LanguageNotDetectedException;
use Thuanvp012van\GTTS\Exceptions\NoAudioException;
use Thuanvp012van\GTTS\Language;
use Generator;
use Thuanvp012van\GTTS\Exceptions\HttpException;

class GTTS
{
    protected array $headers = [
        "Referer" => "http://translate.google.com/",
        "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; WOW64) ",
        "AppleWebKit/537.36 (KHTML, like Gecko) ",
        "Chrome/47.0.2526.106 Safari/537.36",
        "Content-Type" => "application/x-www-form-urlencoded;charset=utf-8",
    ];

    protected string $uri;

    protected int $maxLengthInOneRequest = 100;

    protected Language $lang;

    protected bool $autoDetection = false;

    public function __construct(
        protected string|null $text = null,
        Language $lang = Language::EN,
        protected bool $speed = GTTS_NORMAL,
        protected string $tld = 'com'
    ) {
        $this->lang($lang);
        $this->setUri();
    }

    /**
     * Set text.
     * 
     * @param string $text
     * @return $this
     */
    public function text(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get text.
     * 
     * @return string|null
     */
    public function getText(): string|null
    {
        return $this->text;
    }

    /**
     * Set language.
     * 
     * @param \Thuanvp012van\GTTS\Language $lang
     * @return $this
     */
    public function lang(Language $lang): static
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * Get language.
     * 
     * @return \Thuanvp012van\GTTS\Language
     */
    public function getLang(): Language
    {
        return $this->lang;
    }

    /**
     * Set auto detection.
     * 
     * @param bool $autoDetection
     * @return $this
     */
    public function autoDetection(bool $autoDetection): static
    {
        $this->autoDetection = $autoDetection;
        return $this;
    }

    /**
     * Is auto detection.
     * 
     * @return bool
     */
    public function isAutoDetection(): bool
    {
        return $this->autoDetection;
    }

    /**
     * Automatic language detection handling.
     * 
     * @return \Thuanvp012van\GTTS\Language
     */
    protected function handleAutoDetection(): static
    {
        $english = Language::EN;
        $parameter = [[$this->getText(), 'auto', $english->getName(), 1], [null]];
        $escapedParameter = json_encode($parameter);
        $rpc = [[["MkEWBc", $escapedParameter, null, "generic"]]];
        $espacedRpc = json_encode($rpc);
        $paramUrl = 'f.req=' . urlencode($espacedRpc) . '&';
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
            'POST',
            "{$this->uri}?$paramUrl",
            [
                'headers' => $this->headers
            ]
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode >= 400) {
            throw new HttpException($response->getReasonPhrase(), $statusCode);
        }

        $langCodes = array_map(fn ($lang) => $lang->getName(), Language::cases());
        $langs = join('|', $langCodes);
        if (preg_match('/\\\"(' . $langs . ')\\\"/', $response->getBody(), $matches)) {
            $currentLang = $matches[1];
            $find = false;
            foreach ($langCodes as $lang) {
                if ($currentLang === $lang) {
                    $this->lang(Language::getCaseByKey($currentLang));
                    $find = true;
                }
            }

            if (!$find) {
                throw new LanguageNotDetectedException;
            }

            return $this;
        }

        throw new LanguageNotDetectedException;
    }

    /**
     * Set top level domain.
     * 
     * @param string $tld
     * @return $this
     */
    public function topLevelDomain(string $tld): static
    {
        $this->tld = $tld;
        $this->setUri();
        return $this;
    }

    /**
     * Get top level domain.
     * 
     * @return string
     */
    public function getTopLevelDomain(): string
    {
        return $this->tld;
    }

    /**
     * Set slow reading speed.
     * 
     * @return $this
     */
    public function slowSpeed(): static
    {
        $this->speed = GTTS_SLOW;
        return $this;
    }

    /**
     * Set normal reading speed.
     * 
     * @return $this
     */
    public function normalSpeed(): static
    {
        $this->speed = GTTS_NORMAL;
        return $this;
    }

    /**
     * Check is slow reading speed.
     * 
     * @return bool
     */
    public function isSlowSpeed(): bool
    {
        return $this->speed;
    }

    /**
     * Check is normal reading speed.
     * 
     * @return bool
     */
    public function isNormalSpeed(): bool
    {
        return !$this->slowSpeed();
    }

    /**
     * Save voice as file.
     * 
     * @param string $fileName
     * @return bool
     */
    public function save(string $fileName): bool
    {
        if ($fp = fopen($fileName, 'wb')) {
            foreach ($this->stream() as $bytes) {
                fwrite($fp, $bytes);
            }
            fclose($fp);
            return true;
        }
        return false;
    }

    /**
     * Get segments of void.
     * 
     * @return Generator
     */
    public function stream(): Generator
    {
        if ($this->isAutoDetection()) {
            $this->handleAutoDetection();
        }
        $textParts = (array)$this->text;

        if (strlen($this->text) > $this->maxLengthInOneRequest) {
            $pattern = '/.{0,99}[\?\!\？\！\.\,\¡\(\)\[\]\¿\…\‥\،\;\:\—\。\，\、\：\n]/';
            if (preg_match_all($pattern, $this->text, $matches)) {
                $textParts = array_filter(array_map('trim', $matches[0]), fn ($buffer) => !empty($buffer));
            }
        }

        $lang = $this->lang->getName();
        foreach ($textParts as $part) {
            $parameter = [$part, $lang, $this->speed, 'null'];
            $escapedParameter = json_encode($parameter);
            $rpc = [[['jQ1olc', $escapedParameter, null, 'generic']]];
            $espacedRpc = json_encode($rpc);
            $paramUrl = 'f.req=' . urlencode($espacedRpc) . '&';
            $client = new \GuzzleHttp\Client();
            $response = $client->request(
                'POST',
                "{$this->uri}?$paramUrl",
                [
                    'headers' => $this->headers
                ]
            );

            $statusCode = $response->getStatusCode();
            if ($statusCode >= 400) {
                throw new HttpException($response->getReasonPhrase(), $statusCode);
            }

            $body = $response->getBody();
            if (preg_match('/jQ1olc\"\,"\[\\\\"(.*)\\\\"\]/', $body, $buffer)) {
                yield base64_decode($buffer[1]);
            } else {
                throw new NoAudioException;
            }
        }
    }

    /**
     * Set uri.
     * 
     * @return void
     */
    protected function setUri(): void
    {
        $this->uri = "https://translate.google.{$this->getTopLevelDomain()}/_/TranslateWebserverUi/data/batchexecute";
    }
}
