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
use PasswordStrength\Enums\Rule;

class PasswordStrength
{
  /**
   * @var boolean check whether password contains the username
   */
  private $_checkUsername = true;
  /**
   * @var boolean check whether password contains an email
   */
  private $_checkEmail = true;
  /**
   * @var int minimum number of characters. If not set, defaults to 4.
   */
  private $_minLength = 4;
  /**
   * @var int maximum length. If not set, it means no maximum length limit.
   */
  private $_maxLength;
  /**
   * @var int minimal number of lower case characters
   */
  private $_minLower = 2;
  /**
   * @var int minimal number of upper case characters
   */
  private $_minUpper = 2;
  /**
   * @var int minimal number of numeric digit characters
   */
  private $_minNumeric = 2;
  /**
   * @var int minimal number of special characters
   */
  private $_minSpecial = 2;

  /**
   * @var array any password errors
   */
  private $_errors = [];

  /**
   * @var Preset - one of the preset constants,
   * @see $_presets
   * If this is not null, the preset parameters will override
   * the validator level params
   */
  private $_preset;
  /**
   * @var string presets configuration source file
   * defaults to [[presets.php]] in the current directory
   */
  private $_presetsSource;

  /**
   * @var array the list of inbuilt presets and their parameter settings
   */
  private $_presets;

  /**
   * @var array the default rule settings
   */
  private static $_rules = [
    Rule::RULE_MIN   => [
      'msg' => 'Password should contain at least {n} character{plural}
      ({found} found)!',
      'int' => true
    ],
    Rule::RULE_MAX   => [
      'msg' => 'Password should contain at most {n} character{plural}
      ({found} found)!',
      'int' => true
    ],
    Rule::RULE_USER  => [
      'msg'  => 'Password cannot contain the username',
      'bool' => true
    ],
    Rule::RULE_EMAIL => [
      'msg'   => 'Password cannot contain an email address',
      'match' => '/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i',
      'bool'  => true
    ],
    Rule::RULE_LOW   => [
      'msg'   => 'Password should contain at least {n} lower case
      character{plural} ({found} found)!',
      'match' => '![a-z]!',
      'int'   => true
    ],
    Rule::RULE_UP    => [
      'msg'   => 'Password should contain at least {n} upper case
      character{plural} ({found} found)!',
      'match' => '![A-Z]!',
      'int'   => true
    ],
    Rule::RULE_NUM   => [
      'msg'   => 'Password should contain at least {n} numeric
      character{plural} ({found} found)!',
      'match' => '![\d]!',
      'int'   => true
    ],
    Rule::RULE_SPL   => [
      'msg'   => 'Password should contain at least {n} special
      character{plural} ({found} found)!',
      'match' => '![\W]!',
      'int'   => true
    ]
  ];

  public function __construct()
  {
    $this->checkParams();
  }

  public static function create(Preset $Preset = null)
  {
    $PasswordStrength = new PasswordStrength();
    if(isset($Preset))
    {
      $PasswordStrength->setPreset($Preset);
    }
    return $PasswordStrength;
  }

  /**
   * @return Preset
   */
  public function getPreset()
  {
    return $this->_preset;
  }

  public function setPreset(Preset $preset)
  {
    if(!isset($this->_presetsSource))
    {
      $this->_presetsSource = __DIR__ . '/presets.php';
    }

    $this->_presets = require($this->_presetsSource);

    if(!array_key_exists($preset->value(), $this->_presets))
    {
      throw new \ErrorException("Invalid preset '{$this->preset}'.");
    }
    $this->_preset = $preset;

    foreach($this->_presets[$this->_preset->value()] as $param => $value)
    {
      $this->$param = $value;
    }
  }

  /**
   * @return bool
   */
  public function getCheckUsername()
  {
    return $this->_checkUsername;
  }

  /**
   * @param bool $checkUsername
   *
   * @throws \InvalidArgumentException
   */
  public function setCheckUsername($checkUsername)
  {
    if(!is_bool($checkUsername))
    {
      throw new \InvalidArgumentException('checkUsername should be bool');
    }

    $this->checkUsername = $checkUsername;
  }

  /**
   * @return bool
   */
  public function getCheckEmail()
  {
    return $this->_checkEmail;
  }

  /**
   * @param bool $checkUsername
   *
   * @throws \InvalidArgumentException
   */
  public function setCheckEmail($checkEmail)
  {
    if(!is_bool($checkEmail))
    {
      throw new \InvalidArgumentException('checkEmail should be bool');
    }

    $this->_checkUsername = $checkEmail;
  }

  public function getMinLength()
  {
    return $this->_minLength;
  }

  public function setMinLength($minLength)
  {

    $this->validateNumeric($minLength, 'minLength', 0);
    $this->_minLength = $minLength;
  }

  public function getMaxLength()
  {
    return $this->_maxLength;
  }

  public function setMaxLength($maxLength)
  {
    if(!is_null($maxLength))
    {
      $this->validateNumeric($maxLength, 'maxLength', 1);
    }

    $this->_maxLength = $maxLength;
  }

  public function getMinLower()
  {
    return $this->_minLower;
  }

  public function setMinLower($minLower)
  {
    $this->validateNumeric($minLower, 'minLower', 0);
    $this->_minLower = $minLower;
  }

  public function getMinUpper()
  {
    return $this->_minUpper;
  }

  public function setMinUpper($minUpper)
  {
    $this->validateNumeric($minUpper, 'minUpper', 0);
    $this->_minUpper = $minUpper;
  }

  public function getMinNumeric()
  {
    return $this->_minNumeric;
  }

  public function setMinNumeric($minNumeric)
  {
    $this->validateNumeric($minNumeric, 'minNumeric', 0);
    $this->_minNumeric = $minNumeric;
  }

  public function getMinSpecial()
  {
    return $this->_minSpecial;
  }

  public function setMinSpecial($minSpecial)
  {
    $this->validateNumeric($minSpecial, 'minSpecial', 0);
    $this->_minSpecial = $minSpecial;
  }

  /**
   * Validates the right threshold for 'max' chars.
   *
   * @throw InvalidConfigException if validation is invalid
   */
  protected function checkParams()
  {

    $maxLength = $this->getMaxLength();
    if(isset($maxLength))
    {
      $totalChars = $this->getMinLower() +
        $this->getMinUpper() +
        $this->getMinNumeric() +
        $this->getMinSpecial();
      if($totalChars > $maxLength)
      {
        throw new \InvalidArgumentException(
          "Total number of required characters {$totalChars} is greater" .
          "than maximum allowed {$maxLength}. Validation is impossible!");
      }
    }
  }

  private function validateNumeric($number, $label = 'number', $min = null)
  {
    if(!is_int($number))
    {
      throw new \InvalidArgumentException($label . ' must be an integer');
    }
    if(!is_null($min) && $number < $min)
    {
      throw new \InvalidArgumentException($label . ' must be at least ' . $min);
    }

    return true;
  }

  /**
   * Reset the errors array
   */
  private function clearErrors()
  {
    $this->_errors = [];
  }

  /**
   * Return the last set of errors as an array of strings.
   *
   * @return array
   */
  public function getErrors()
  {
    return $this->_errors;
  }

  /**
   * Add an error to the errors array and perform some basic formatting
   *
   * @param       $message
   * @param array $params
   *
   * @return mixed
   */
  private function addError($message, $params = [])
  {
    if(count($params) == 0)
    {
      $this->_errors[] = $message;
      return;
    }

    $p = [];
    foreach($params as $name => $value)
    {
      $p['{' . $name . '}'] = $value;
    }

    $this->_errors[] = strtr($message, $p);
  }

  /**
   * Validate a password and set any errors if required.
   *
   * @param      $password
   * @param null $testUsername
   *
   * @return bool
   */
  public function validate($password, $testUsername = null)
  {
    $this->clearErrors();

    foreach(self::$_rules as $rule => $setup)
    {
      switch($rule)
      {
        case Rule::RULE_MIN()->value():
          if(strlen($password) < $this->getMinLength())
          {
            $this->addError($setup['msg']);
          }
          break;
        case Rule::RULE_MAX()->value():
          if($this->getMaxLength() !== null &&
            strlen($password) > $this->getMaxLength()
          )
          {
            $this->addError($setup['msg']);
          }
          break;
        case Rule::RULE_USER()->value():
          if($this->getCheckUsername() &&
            !is_null($testUsername)
            && stristr($password, $testUsername) !== false
          )
          {
            $this->addError($setup['msg']);
          }
          break;
        default:

          //Are we testing this rule?
          $test = $this->{'get' . $rule}();
          if(!$test)
          {
            continue;
          }

          //Find any matches to the rule.
          preg_match($setup['match'], $password, $matches);

          if(isset($setup['bool']) && count($matches) == $setup['bool'])
          {
            $this->addError(
              $setup['msg'],
              [
                'found' => count($matches)
              ]
            );
          }
          if(isset($setup['int']) && count($matches) < $setup['int'])
          {
            $this->addError(
              $setup['msg'],
              [
                'n'      => $setup['int'],
                'found'  => count($matches),
                'plural' => $setup['int'] == 1 ? '' : 's'
              ]
            );
          }
      }
    }
    return count($this->_errors) == 0;
  }
}