<?php

namespace App\controllers\ys;

use App\facades\Api;

use App\models\article\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleController extends BaseController
{
    /**
     * 用户中心-》我的会员购买记录
     * 传入参数：
     * @return mixed
     */
    public function document($doc_id)
    {
        $document = Document::find($doc_id);
        return view('article.document', compact('document'));
    }

    public function aritcleList()
    {
       $articleList=DB::table('article_info')->where('is_show',1)->get();

        foreach ($articleList as $k => &$art) {
            $art->image_url_1 = $this->getFullPictureUrl($art->image_url_1);
            $art->image_url_2 = $this->getFullPictureUrl($art->image_url_2);
            $art->create_time = Carbon::createFromTimestampUTC($art->create_time)->toDateString();
        }

        return view("sd_goods.article-list", compact('articleList'));
    }

    public function articleDetaile($article_id)
    {
        $article=DB::table('article_info')->where('article_info_id',$article_id)->first();
        return view('article.articleDetaile', compact('article'));
    }

    /**
     * 将图片的相对地址转换为绝对地址(fullPictureUrl)
     * @param string $pictureUrl 要处理的图片地址
     *  （含有“http://”等字符的为绝对地址，不处理，直接返回；其它处理）
     */
    function getFullPictureUrl($pictureUrl = '')
    {
        $fullPictureUrl = trim($pictureUrl . "");
        if ($fullPictureUrl == "") {
            // 如果为空字符串，直接退出
            return $fullPictureUrl;
        }

        // 如果含有“http://”等字符的为绝对地址，不处理
        if (strpos(strtolower($pictureUrl), "http://", 0) === false) {
            $fist_str = mb_substr($pictureUrl, 0, 1, 'utf-8');
            if ($fist_str != '/') {
                $pictureUrl = '/' . $pictureUrl;
            }
            $fullPictureUrl = $this->img_domain . $pictureUrl;
        }

        return $fullPictureUrl;
    }
}
