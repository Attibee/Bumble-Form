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

namespace Attibee\Database;

class Pdo extends BaseDriver {
    private $driver;
    private $pdo;
    
    public function __construct( $options ) {
        parent::__construct( $options );
        
        if( !isset( $options['driver'] ) ) {
            throw new Exception\InvalidArgumentException( 'Key "driver" must exist in options.' );
        }
        
        $this->driver = $options['driver'];
    }
    
    public function connect() {
        $dsn = sprintf( '%s:host=%s;dbname=%s', $this->driver, $this->host, $this->database );
        $this->pdo = new \Pdo( $dsn, $this->username, $this->password );
    }
}