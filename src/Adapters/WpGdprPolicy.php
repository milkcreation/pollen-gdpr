<?php

declare(strict_types=1);

namespace Pollen\Gdpr\Adapters;

use Pollen\Gdpr\GdprPolicy;
use WP_Post;

class WpGdprPolicy extends GdprPolicy
{
    /**
     * @var WP_Post|null|false
     */
    protected $pageForPrivacy;

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        if ($this->text === null) {
            $this->text = ($page = $this->getPageForPrivacy())
                ? apply_filters('the_content', $page->post_content) : parent::getText();
        }

        return $this->text;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        if ($this->title === null) {
            $this->title = ($page = $this->getPageForPrivacy())
                ? apply_filters('the_title', '<h1>' . $page->post_title . '</h1>', $page->ID) : parent::getTitle();
        }

        return $this->title;
    }

    /**
     * Récupération de la page de politique de confidentialité de Wordpress.
     *
     * @return WP_Post|null
     */
    public function getPageForPrivacy(): ?WP_Post
    {
        if ($this->pageForPrivacy === null) {
            if ($id = get_option('wp_page_for_privacy_policy')) {
                $page = get_post($id);

                $this->pageForPrivacy  = $page instanceof WP_Post ? $page : false;
            } else {
                $this->pageForPrivacy = false;
            }
        }
        return $this->pageForPrivacy ?? null;
    }
}
