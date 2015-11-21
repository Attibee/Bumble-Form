<?php

/* 
 * Copyright 2015 Attibee (http://attibee.com)
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Bumble\Database\Transaction\Binding;

use Bumble\Database\Symbols\SymbolAwareAbstract;

/**
 * A configurable object that is bound to a SQL statement.
 */
abstract class Binding extends SymbolAwareAbstract {
    public function __construct() {
        $this->setOptions( func_get_args() );
    }
    
    abstract public function setOptions();
    abstract public function toString();
    
    public function __toString() {
        return $this->toString();
    }
}