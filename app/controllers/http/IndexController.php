<?php

namespace App\Controllers\Http;

use App\Core\Controller;
use App\Db\Eloquent\Value;
use App\Model\Item;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        pd(Item::debug()->distinct()->as('halo')
            ->select(['halo.name as test', 'halo.cid', 'img.path', new Value('COUNT(halo.cid) as ile')])
            ->join('images as img', 'img.cid', '=', 'halo.cid')
            ->raw('AND img.cid IS NOT NULL')
            ->where('halo.name', 'like', 'green ball')
            ->orWhere('halo.name', '=', 'small stone')
            ->orWhere('halo.name', '=', 'janemba sword')
            ->orWhereNull('halo.name')
            ->whereIn([3,4,5,6], 'halo.name')
            ->group('halo.name')
            ->order(['halo.name'])
            ->get());
        exit;
    }

    public function index()
    {
        return $this->render();
    }
}
