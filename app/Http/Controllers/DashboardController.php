<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Member;
use App\Traits\JsonResponder;

class DashboardController extends Controller
{
    use JsonResponder;
    public function index()
    {
        $books = Book::all();
        $category = Category::all();
        $members = Member::all();

        return view('pages.dashboard.index');
    }
}