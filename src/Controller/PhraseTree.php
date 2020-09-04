<?php

namespace App\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PhraseTree
{
    /**
     * @var array|null
     */
    public $children;
    /**
     * @var array
     */
    public $names;

    public function __construct(array $names = [], array $children = null)
    {
        $this->names = $names;
        $this->children = $children;
    }

    public function toString($order)
    {
        $current = $this;
        $result = $current->names[$order[0]];
        for ($i = 1; $i < count($order); $i += 2) {
            if (!array_key_exists($order[$i], $current->children)) {
                throw new NotFoundHttpException('The ID was not found');
            }
            $current = $current->children[$order[$i]];
            if ($i < count($order) - 1) {
                $result .= $current->names[$order[$i + 1]];
            } else {
                $result .= $current;
            }
        }
        if (isset($current->children)) {
            throw new NotFoundHttpException('The ID was not found');
        }
        return $result;
    }
}