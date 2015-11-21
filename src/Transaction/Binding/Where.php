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

use Bumble\Database\Transaction\Binding\Operator\OperatorSet;

/**
 * Binds operators to a WHERE statement.
 * 
 * The Where binding allows operators to be bound to the SQL WHERE statement. The binding
 * allows operators, such as equals, greater than, and some functions to be separated
 * by AND and OR statements.
 */
class Where extends OperatorSet {}
