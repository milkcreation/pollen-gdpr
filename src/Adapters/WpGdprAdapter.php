<?php

declare(strict_types=1);

namespace Pollen\Gdpr\Adapters;

use Pollen\Gdpr\AbstractGdprAdapter;

class WpGdprAdapter extends AbstractGdprAdapter
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        if (!$this->isBooted()) {
            $this->gdpr()->setPolicy(new WpGdprPolicy($this->gdpr()));

            /** @todo * /
            if ($this->gdpr()->config('in_footer', true) === true) {
                add_action('wp_footer', function () {
                    //echo $this->gdpr()->render();
                });
            }
            /**/

            $this->setBooted();
        }
    }

    /**
     * @inheritDoc
     */
    public function parseConfig(): void
    {
        $conf = $this->gdpr()->config();

        /** @todo * /
        if (!$conf->has('page-hook')) {
            $conf->set('page-hook', true);
        }

        if (!$conf->has('in_footer')) {
            $conf->set('in_footer', true);
        }

        if ($page_hook = $conf->get('page-hook')) {
            $defaults = [
                'admin'               => true,
                'id'                  => get_option('wp_page_for_privacy_policy') ?: 0,
                'desc'                => '',
                'display_post_states' => false,
                'edit_form_notice'    => __(
                    'Vous éditez actuellement la page d\'affichage de politique de confidentialité.', 'pollen-gdpr'
                ),
                'listorder'           => 'menu_order, title',
                'object_type'         => 'post',
                'object_name'         => 'page',
                'option_name'         => 'wp_page_for_privacy_policy',
                'show_option_none'    => '',
                'title'               => __('Page d\'affichage de politique de confidentialité', 'pollen-gdpr'),
            ];
            PageHook::set('cookie-law', is_array($page_hook) ? array_merge($defaults, $page_hook) : $defaults);

            if (($hook = PageHook::get('cookie-law')) && ($post = $hook->post())) {
                $conf->set('privacy_policy', [
                    'content'   => $post->getContent(),
                    'title'     => $post->getTitle(),
                    'permalink' => $post->getPermalink(),
                ]);
            }
        }
        /**/
    }
}
