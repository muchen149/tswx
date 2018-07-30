<?php

namespace App\controllers\elife;

use App\facades\Api;

use App\models\article\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleController extends BaseController
{
    /**
     * 活动详情
     * @param $article_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleDeTaiLe($article_id)
    {
        $article=DB::table('article_info')->where('article_info_id',$article_id)->where('is_show',1)->first();
        $article->image_url_2 = $this->getFullPictureUrl($article->image_url_2);
        return view('elife.article.articleDetaile', compact('article'));
    }
}
