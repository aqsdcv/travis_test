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
 * @see https://github.com/FriendsOfCake/crud/blob/master/src/Error/ExceptionRenderer.php
 */

namespace App\Error;

use Exception;

/**
 * Exception renderer for ApiListener
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 */
class ExceptionRenderer extends \Cake\Error\ExceptionRenderer {

    /**
     * Renders validation errors and sends a 400 error code
     *
     * @param \Exception $error Exception instance
     * @return \Cake\Network\Response
     */
    public function validation($error) {
        $url = $this->controller->request->here();
        $status = $code = $error->getCode();
        try {
            $this->controller->response->statusCode($status);
        }
        catch (Exception $e) {
            $status = 400;
            $this->controller->response->statusCode($status);
        }
        $sets = [
          'code' => $code,
          'url' => h($url),
          'message' => $error->getMessage(),
          'error' => $error,
          'errorCount' => $error->getValidationErrorCount(),
          'errors' => $error->getValidationErrors(),
          '_serialize' => ['code', 'url', 'message', 'errorCount', 'errors']
        ];
        $this->controller->set($sets);
        return $this->_outputMessage('error400');
    }

    /**
     * Generate the response using the controller object.
     *
     * If there is no specific template for the raised error (normally there won't be one)
     * swallow the missing view exception and just use the standard
     * error format. This prevents throwing an unknown Exception and seeing instead
     * a MissingView exception
     *
     * @param string $template The template to render.
     * @return \Cake\Network\Response
     */
    protected function _outputMessage($template) {
        $viewVars = 'data';
        $this->controller->set('data', $this->_getErrorData());

        $this->controller->set('_serialize', $viewVars);

        return parent::_outputMessage($template);
    }

    /**
     * Helper method used to generate extra debugging data into the error template
     *
     * @return array debugging data
     */
    protected function _getErrorData() {
        $data = [];

        $viewVars = $this->controller->viewVars;
        if (!empty($viewVars['_serialize'])) {
            foreach ($viewVars['_serialize'] as $v) {
                $data[$v] = $viewVars[$v];
            }
        }

        return $data;
    }

}
