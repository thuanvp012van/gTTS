<?php

namespace Thuanvp012van\GTTS;

use Generator;
use Thuanvp012van\GTTS\Language;

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

    protected string $lang;

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
        $this->lang = strtolower($lang->name);
        return $this;
    }

    /**
     * Get language.
     * 
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * Set top level domain.
     * 
     * @param string $tld
     * @return $this
     */
    public function setTopLevelDomain(string $tld): static
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
        $textParts = (array)$this->text;

        if (strlen($this->text) > $this->maxLengthInOneRequest) {
            $textParts = mb_split("[\?\!\？\！\.\,\¡\(\)\[\]\¿\…\‥\،\;\:\—\。\，\、\：\n]+", $this->text);
        }

        foreach ($textParts as $part) {
            $parameter = [$part, $this->lang, $this->speed, "null"];
            $escapedParameter = json_encode($parameter);
            $rpc = [[["jQ1olc", $escapedParameter, null, "generic"]]];
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
            $body = $response->getBody();
            if (preg_match('/jQ1olc\"\,"\[\\\\"(.*)\\\\"\]/', $body, $matches)) {
                yield base64_decode($matches[1]);
            }
        }
    }

    protected function setUri(): void
    {
        $this->uri = "https://translate.google.{$this->getTopLevelDomain()}/_/TranslateWebserverUi/data/batchexecute";
    }
}
