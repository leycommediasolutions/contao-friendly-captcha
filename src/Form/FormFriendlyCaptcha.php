<?php

declare(strict_types=1);

namespace Leycommediasolutions\ContaoFriendlyCaptcha\Form;

use Contao\BackendTemplate;
use Contao\StringUtil;
use Contao\Widget;
use Leycommediasolutions\ContaoFriendlyCaptcha\Service\FriendlyCaptchaVerifier;

class FormFriendlyCaptcha extends Widget
{
    protected $blnSubmitInput = true;
    protected $blnForAttribute = true;
    protected $strTemplate = 'form_friendlycaptcha';
    protected $strPrefix = 'widget widget-friendlycaptcha mandatory';

    protected bool $canUseFriendlyCaptcha = true;

    public function __construct($arrAttributes = null)
    {
        parent::__construct($arrAttributes);

        $this->useRawRequestData = true;
        $this->arrAttributes['required'] = true;
        $this->arrConfiguration['mandatory'] = true;
    }

    public function generate(): string
    {
        $theme = strtolower(trim((string) $this->friendlyCaptchaTheme));
        $classes = ['frc-captcha'];

        if ('dark' === $theme)
        {
            $classes[] = 'dark';
        }
        elseif ('light' === $theme)
        {
            $classes[] = 'light';
        }

        $attributes = [
            'class="'.StringUtil::specialchars(implode(' ', $classes)).'"',
            'data-sitekey="'.StringUtil::specialchars((string) $this->friendlyCaptchaSiteKey).'"',
            'data-solution-field-name="'.StringUtil::specialchars((string) $this->name).'"',
        ];

        if (\in_array($theme, ['auto', 'light', 'dark'], true))
        {
            $attributes[] = 'data-theme="'.StringUtil::specialchars($theme).'"';
        }

        if ($this->friendlyCaptchaLang)
        {
            $attributes[] = 'data-lang="'.StringUtil::specialchars((string) $this->friendlyCaptchaLang).'"';
        }

        if ($this->friendlyCaptchaPuzzleEndpoint)
        {
            $attributes[] = 'data-puzzle-endpoint="'.StringUtil::specialchars((string) $this->friendlyCaptchaPuzzleEndpoint).'"';
        }

        return '<div '.implode(' ', $attributes).'></div>';
    }

    public function parse($arrAttributes = null): string
    {
        $request = $this->getContainer()->get('request_stack')->getCurrentRequest();

        if ($request && $this->getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
        {
            $template = new BackendTemplate('be_wildcard');
            $template->title = $this->label ?: 'Friendly Captcha';

            return $template->parse();
        }

        $this->canUseFriendlyCaptcha = $request?->isSecure() ?? true;

        if (!$this->canUseFriendlyCaptcha && $request)
        {
            $host = $request->getHost();
            $this->canUseFriendlyCaptcha = \in_array($host, ['127.0.0.1', 'localhost'], true) || str_ends_with($host, '.localhost');
        }

        $this->friendlyCaptchaMarkup = $this->generate();

        return parent::parse($arrAttributes);
    }

    protected function validator($varInput): mixed
    {
        if (!$this->canUseFriendlyCaptcha)
        {
            $this->addError($GLOBALS['TL_LANG']['ERR']['friendlyCaptchaInsecureConnection']);

            return $varInput;
        }

        if (!$varInput)
        {
            $this->addError($GLOBALS['TL_LANG']['ERR']['friendlyCaptchaMissing']);

            return $varInput;
        }

        $verifier = $this->getContainer()->get(FriendlyCaptchaVerifier::class);

        if (!$verifier->verify((string) $varInput))
        {
            $this->addError($GLOBALS['TL_LANG']['ERR']['friendlyCaptchaFailed']);
        }

        return $varInput;
    }
}
