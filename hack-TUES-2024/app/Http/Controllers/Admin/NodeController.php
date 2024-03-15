<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Node;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NodeController extends Controller
{
    public function index()
    {
        return view('admin.nodes.index');
    }

    public function getDatatable()
    {
        $nodes = Node::all();

        return DataTables::of($nodes)
            ->addColumn('name', function ($node) {
                return $node->name;
            })
            ->addColumn('type', function ($node) {
                return $node->nodeType->name;
            })
            ->addColumn('action', function ($node) {
                $editRoute = route('admin.nodes.edit', $node);
                $deleteRoute = route('admin.nodes.destroy', $node);
                $rowId = 'node-' . $node->id;
                return view('components.admin.datatables.actions', compact("editRoute", "rowId", "deleteRoute"));
            })
            ->rawColumns(['action', 'status', 'tracking'])
            ->make(true);
    }
}
