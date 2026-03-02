<?php

namespace App\Services\Csp\Policies;

use Spatie\Csp\Policies\Policy;
use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;

class CustomPolicies extends Policy
{
    public function configure()
    {
        $this->setDefaultPolicies();
        $this->addGoogleFontPolicies();
        $this->addDataImgPolicies();
    }

    private function setDefaultPolicies()
    {
        return $this->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::CONNECT, Keyword::SELF)
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            // ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, Keyword::SELF)
            // ->addDirective(Directive::MEDIA, Keyword::SELF)
            // ->addDirective(Directive::OBJECT, Keyword::SELF)
            ->addDirective(Directive::FONT, Keyword::SELF)

            // Scripts: nonce + strict-dynamic, NO unsafe-inline
            ->addDirective(Directive::SCRIPT, [
                Keyword::SELF,
                Keyword::STRICT_DYNAMIC,
            ])

            // Styles: keep unsafe-inline for Google Fonts / inline styles
            ->addDirective(Directive::STYLE, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE
            ])

            ->addNonceForDirective(Directive::SCRIPT)
            ->addNonceForDirective(Directive::STYLE);
    }

    private function addGoogleFontPolicies()
    {
        $this->addDirective(Directive::FONT, [
                'fonts.gstatic.com',
                'fonts.googleapis.com',
                'fontawesome.com',
                'cdnjs.cloudflare.com',
                'data:'
            ])
            ->addDirective(Directive::STYLE, [
                'fonts.googleapis.com',
                'fontawesome.com',
                'cdnjs.cloudflare.com',
                'cdn.jsdelivr.net'
            ]);
    }

    private function addDataImgPolicies()
    {
        $this->addDirective(Directive::IMG, [
                'data:',
                'blob:'
            ]);
    }
}
