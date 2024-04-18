<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class GrammarController extends Controller
{
   public function getGrammar(){
    $grammar = DB::table('grammars')->get();
      return response()->json($grammar);
   }
}
