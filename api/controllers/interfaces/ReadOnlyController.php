<?php

interface ReadOnlyController {
    public static function getAll($limitQueries = null);
    public static function get();
}