<?php

namespace App\Http\Controllers;

use Seboettg\Collection\ArrayList;
use Seboettg\Forest\AVLTree;
use Seboettg\Forest\BinaryTree\BinaryNodeInterface;
use Seboettg\Forest\General\TreeTraversalInterface;
use Seboettg\Forest\Item\IntegerItem;
use Seboettg\Forest\Item\StringItem;

use Illuminate\Http\Request;

class AVLController extends Controller
{
    /**
     * Create AVL TREE Object and return height.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return integer height
     */
    public function getHeight(Request $request)
    {
        if( $request->input('toTree') && !empty($request->input('toTree')) && is_array($request->input('toTree')) ){
            $array=$request->input('toTree');
            $avl = new AVLTree(IntegerItem::class);
            foreach ($array as $value) {
                $avl->insert($value);
            }
            $out["height"]=$avl->getRootNode()->getHeight();
        }else{
            $out["error"]=403;
            $out["message"]="Params Error";
        }
        return $out;
    }


    /**
     * Create AVL TREE Object and return the neightbors of the given node.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json
     */
    public function getNeighbors(Request $request)
    {
        if( $request->input('toTree') && !empty($request->input('toTree')) && is_array($request->input('toTree')) && $request->input('node') && !empty($request->input('node')) && is_integer($request->input('node'))){
            $array=$request->input('toTree');
            $avl = new AVLTree(IntegerItem::class);
            foreach ($array as $value) {
                $avl->insert($value);
            }
            $result = $avl->search(new IntegerItem($request->input('node')));
            if($result!==null){
                if($avl->getRootNode()->getItem()->getValue()!==$request->input('node') && $result->getParent()!==null){
                    if($result->getParent()->getLeft()!==null&&$result->getParent()->getLeft()->getItem()->getValue()!==$request->input('node')){
                        $left=$result->getParent()->getLeft()->getItem()->getValue();
                    }else{
                        $left=null;
                    }
                    if($result->getParent()->getRight()!==null&&$result->getParent()->getRight()->getItem()->getValue()!==$request->input('node')){
                        $right=$result->getParent()->getRight()->getItem()->getValue();
                    }else{
                        $right=null;
                    }
                    $out["neighbors"]=array("left"=>$left,"right"=>$right);
                }else{
                    $out["error"]=411;
                    $out["message"]="Node is the root";
                }
            }else{
                $out["error"]=410;
                $out["message"]="Node does not exist";
            }
        }else{
            $out["error"]=403;
            $out["message"]="Params Error";
        }
        return $out;
    }

    /**
     * Create AVL TREE Object and return the breadth-first search.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json with the breadth-first search
     */
    public function getBfs(Request $request)
    {
        if( $request->input('toTree') && !empty($request->input('toTree')) && is_array($request->input('toTree')) ){
            $array=$request->input('toTree');
            $avl = new AVLTree(IntegerItem::class);
            foreach ($array as $value) {
                $avl->insert($value);
            }
            $list = $avl->toArrayList(TreeTraversalInterface::TRAVERSE_LEVEL_ORDER);
            $bfs=array();
            foreach ($list as $item) {
                array_push($bfs,$item->getValue());
            }
            $out["bfs"]=$bfs;
        }else{
            $out["error"]=403;
            $out["message"]="Params Error";
        }
        return $out;
    }
}
