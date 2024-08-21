<?php

namespace App\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Policy;

class CustomSpatiePolicy extends Policy
{
    public function configure()
    {
        $this
            ->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::CONNECT, Keyword::SELF)
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, Keyword::SELF)
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            ->addDirective(Directive::SCRIPT, Keyword::SELF)
            ->addDirective(Directive::STYLE, Keyword::SELF)
            ->addNonceForDirective(Directive::SCRIPT)
            ->addNonceForDirective(Directive::STYLE);
    }

    public function removeDirective(string $directive, string|array|bool $values, string $mode = 'exact'): self
    {
        $this->guardAgainstInvalidDirectives($directive);

        if (isset($this->directives[$directive])) {
            $valuesToRemove = (array) $values;

            foreach ($valuesToRemove as $value) {
                $sanitizedValue = $this->sanitizeValue($value);

                // Determine the removal mode
                $this->directives[$directive] = array_filter(
                    $this->directives[$directive],
                    function ($existingValue) use ($sanitizedValue, $mode) {
                        $existingValue = trim($existingValue, "'");
                        if ($mode === 'exact') {
                            return $existingValue !== $sanitizedValue;
                        } elseif ($mode === 'starts_with') {
                            return !str_starts_with($existingValue, $sanitizedValue);
                        }
                        return true; // In case of an unsupported mode, do nothing
                    }
                );
            }
        }

        return $this;
    }
}
