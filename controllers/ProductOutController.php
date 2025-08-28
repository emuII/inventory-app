<?php
class ProductOutController
{
    protected $model;
    public function __construct($db)
    {
        $this->model = new models_out($db);
    }
}
