<?php
/*
 * This file is part of the PasswordStrength package.
 *
 * (c) Dave Blake <me@daveblake.co.uk>
 * Based on yii2-password (c) Kartik Visweswaran, Krajee.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/


namespace PasswordStrength\Enums;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class Rules
 * @package PasswordStrength\Enums
 *
 *          The available rule constants for the
 *          PasswordStrength package
 */
final class Rule extends AbstractEnumeration
{
  const RULE_MIN   = 'minLength';
  const RULE_MAX   = 'maxLength';
  const RULE_USER  = 'checkUsername';
  const RULE_EMAIL = 'checkEmail';
  const RULE_LOW   = 'minLower';
  const RULE_UP    = 'minUpper';
  const RULE_NUM   = 'minNumeric';
  const RULE_SPL   = 'minSpecial';
}
