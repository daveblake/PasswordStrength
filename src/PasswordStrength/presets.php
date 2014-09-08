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

namespace PasswordStrength;

use PasswordStrength\Enums\Preset;

return [
  Preset::SIMPLE => [
    'minLength' => 6,
    'minUpper' => 0,
    'minLower' => 1,
    'minNumeric' => 1,
    'minSpecial' => 0,
    'checkUsername' => false,
    'checkEmail' => false
  ],
  Preset::NORMAL => [
    'minLength' => 8,
    'minUpper' => 1,
    'minLower' => 1,
    'minNumeric' => 1,
    'minSpecial' => 0,
    'checkUsername' => true,
    'checkEmail' => true
  ],
  Preset::FAIR => [
    'minLength' => 10,
    'minUpper' => 1,
    'minLower' => 1,
    'minNumeric' => 1,
    'minSpecial' => 1,
    'checkUsername' => true,
    'checkEmail' => true
  ],
  Preset::MEDIUM => [
    'minLength' => 10,
    'minUpper' => 1,
    'minLower' => 1,
    'minNumeric' => 2,
    'minSpecial' => 1,
    'checkUsername' => true,
    'checkEmail' => true
  ],
  Preset::STRONG => [
    'minLength' => 12,
    'minUpper' => 2,
    'minLower' => 2,
    'minNumeric' => 2,
    'minSpecial' => 2,
    'checkUsername' => true,
    'checkEmail' => true
  ],
];
