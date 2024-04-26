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
        $books = Book::count();
        $category = Category::count();
        $members = Member::count();

        return view('pages.dashboard.index', compact('books', 'category', 'members'));
    }
}
