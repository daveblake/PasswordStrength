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
 *          The available preset constants for the
 *          PasswordStrength package
 */
final class Preset extends AbstractEnumeration
{
  const SIMPLE = 'simple';
  const NORMAL = 'normal';
  const FAIR   = 'fair';
  const MEDIUM = 'medium';
  const STRONG = 'strong';
}
