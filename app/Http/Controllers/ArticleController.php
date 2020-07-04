<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ArticleController extends Controller
{
    const ITEM_PER_PAGE = 15;

    public function index(Request $request)
    {
        $searchParams = $request->all();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);

        $article = Article::where('review_status', 1)->paginate($limit);
        return ArticleResource::collection($article);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->all(['status']);
        Article::where('review_status', 1)->where('id', $id)->update($data);
    }
}
