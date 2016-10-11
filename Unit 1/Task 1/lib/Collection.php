<?php

namespace WS\Education\Unit1\Task1;

interface Collection {

    public function __construct($type = null);

    public function push($el);

    public function pop();

    public function size();
}