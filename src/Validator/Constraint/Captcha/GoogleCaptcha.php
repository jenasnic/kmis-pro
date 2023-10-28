<?php

namespace App\Validator\Constraint\Captcha;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class GoogleCaptcha extends Constraint
{
    public string $message = 'form.errors.invalid_captcha';
}
