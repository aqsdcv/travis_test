<?php

/**
 * The MIT License (MIT)
 * 
 * Copyright (c) 2013 Christian "Jippi" Winther
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * @see https://github.com/FriendsOfCake/crud/blob/master/src/Error/Exception/ValidationException.php
 */

namespace App\Network\Exception;

use Cake\Network\Exception\BadRequestException;
use Cake\ORM\Entity;
use Cake\Utility\Hash;

/**
 * Exception containing validation errors from the model. Useful for API
 * responses where you need an error code in response
 */
class ValidationException extends BadRequestException {

  /**
   * List of validation errors that occurred in the model
   *
   * @var array
   */
  protected $_validationErrors = [];

  /**
   * How many validation errors are there?
   *
   * @var int
   */
  protected $_validationErrorCount = 0;

  /**
   * Constructor
   *
   * @param \Cake\ORM\Entity $entity Entity
   * @param int $code code to report to client
   */
  public function __construct(Entity $entity, $code = 400) {
    $this->_validationErrors = array_filter((array) $entity->errors());
    $flat = Hash::flatten($this->_validationErrors);

    $errorCount = $this->_validationErrorCount = count($flat);
    $this->message = __n(
        'A validation error occurred', '{0} validation errors occurred', $errorCount, [$errorCount]
    );

    parent::__construct($this->message, $code);
  }

  /**
   * Returns the list of validation errors
   *
   * @return array
   */
  public function getValidationErrors() {
    return $this->_validationErrors;
  }

  /**
   * How many validation errors are there?
   *
   * @return int
   */
  public function getValidationErrorCount() {
    return $this->_validationErrorCount;
  }

}
