<?php

declare(strict_types=1);

namespace Pollen\Gdpr;

use Parsedown;
use Pollen\Http\Response;
use Pollen\Http\ResponseInterface;

class GdprPolicy implements GdprPolicyInterface
{
    use GdprProxy;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $vars = [];

    /**
     * @var string
     */
    protected $url;

    /**
     * @param GdprInterface|null $gdpr
     */
    public function __construct(?GdprInterface $gdpr = null)
    {
        if ($gdpr !== null) {
            $this->setGdpr($gdpr);
        }
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        if ($this->text === null) {
            $text = file_get_contents($this->gdpr()->resources('/views/policy/text.md'));
            $this->text = $this->parseVars((new Parsedown())->text($text));
        }

        return $this->text;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        if ($this->title === null) {
            $title = file_get_contents($this->gdpr()->resources('/views/policy/title.md'));
            $this->title = (new Parsedown())->text($title);
        }

        return $this->title;
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @inheritDoc
     */
    public function parseVars(string $content): string
    {
        if (!$this->vars) {
            return $content;
        }

        $pattern = '~\{\{\s(.*)\s\}\}~';
        if (!preg_match_all($pattern, $content, $matches)) {
            return $content;
        }

        $vars = [];
        array_walk($this->vars, function ($v, $k) use(&$vars) {
            $vars['{{ '. strtoupper($k) .' }}'] = $v;
        });

        $_content = '';
        foreach ($matches[0] as $match) {
            $_content = str_replace($match, $vars[$match] ?? $match, $content);
        }

        return $_content;
    }

    /**
     * @inheritDoc
     */
    public function setParams(array $params): GdprPolicyInterface
    {
        if (isset($params['title'])) {
            $this->setTitle($params['title']);
        }

        if (isset($params['text'])) {
            $this->setText($params['text']);
        }

        if (isset($params['url'])) {
            $this->setUrl($params['url']);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setText(string $text): GdprPolicyInterface
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTitle(string $title): GdprPolicyInterface
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUrl(string $url): GdprPolicyInterface
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setVars(array $vars): GdprPolicyInterface
    {
        $this->vars = $vars;

        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function xhrResponse(): ResponseInterface
    {
        return new Response($this->getTitle() . $this->getText());
    }
}
