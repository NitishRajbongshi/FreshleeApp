<?php

namespace App\Http\Controllers\AgriNews;

use App\Http\Controllers\Controller;
use App\Models\AgriNews\AgriNews;
use App\Models\AgriNews\AgriNewsAttachment;
use App\Models\AgriNews\AgriNewsCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class AgriNewsController extends Controller
{
    //
    public function index() {
        // create data to be viewed
        // $news=AgriNews::latest()->get();

        $news = DB::table('tbl_agrinews_dtls')
            ->join('tbl_agrinews_category_master', 'tbl_agrinews_dtls.news_catg_cd', 
                    '=', 'tbl_agrinews_category_master.catg_cd')
            ->select('tbl_agrinews_dtls.*','tbl_agrinews_category_master.catg_descr')
            ->orderBy('tbl_agrinews_dtls.updated_at','DESC')
            ->get(); 
        
        $data['news'] = $news;
        // TODO: establish relationship with category and load category name
        return view('admin.agrinews.agrinews')->with($data);
    }

    public function createNews(Request $request) {
        if($request->isMethod('get')){
            // load news categories and redirect to create new news page.
            $data['newsCategories'] = AgriNewsCategory::all();
            return view('admin.agrinews.create')->with($data);
        } 
        else if( $request->isMethod('post')){
            // create the news in the database
            $request->validate([
                'newsTitle' => ['required' , 'max:255'],
                'category_cd'=> ['required'],
                'newsDescription'=> ['required'],
                'newsfile1' => ['nullable', 'mimes:jpg,jpeg,pdf,docx,odt,png', 'max:2048'],
                'newsfile2' => ['nullable', 'mimes:jpg,jpeg,pdf,docx,odt,png', 'max:2048'],
                'newsfile3' => ['nullable', 'mimes:jpg,jpeg,pdf,docx,odt,png', 'max:2048']
            ]);

            $hasAttachments = false;

            if($request->hasFile('newsFile1')) {
               $path = $request->file('newsFile1')->storePublicly('newsfiles', 'public');
               $type = $request->file('newsFile1')->getClientOriginalExtension();
               $request->merge([
                'newsfile1' => $path,
                // 'newsfile1url'=> URL::to('/').'/storage/app/'.$path
                'newsfile1type' => $type
               ]);
               $hasAttachments = true;
            }

            if($request->hasFile('newsFile2')) {
                $path = $request->file('newsFile2')->storePublicly('newsfiles', 'public');
                $type = $request->file('newsFile2')->getClientOriginalExtension();
                $request->merge([
                 'newsfile2' => $path,
                //  'newsfile2url'=> env('APP_URL').'/'.$path
                'newsfile2type' => $type
                ]);
                $hasAttachments = true;
             }

             if($request->hasFile('newsFile3')) {
                $path = $request->file('newsFile3')->storePublicly('newsfiles', 'public');
                $type = $request->file('newsFile3')->getClientOriginalExtension();
                $request->merge([
                 'newsfile3' => $path,
                //  'newsfile3url'=> env('APP_URL').'/'.$path
                'newsfile3type' => $type
                ]);
                $hasAttachments = true;
             }
             
            ($hasAttachments)?$request->merge(['hasAtachment'=>1]):$request->merge(['hasAtachment'=>0]);

            // dd($request->all());

            $agrinews_cd = date('YmdHis', strtotime(Carbon::now()));
            $news = AgriNews::create([
                'news_cd' => $agrinews_cd,
                'news_catg_cd' =>  $request->category_cd,
                'news_title' => $request->newsTitle,
                'news_descr'=> $request->newsDescription,
                'news_uploaded_by' => auth()->user()->user_id,
                'news_uploaded_on' => Carbon::now(),
                'ispublished' => '1',
                'has_attachment' => $request->hasAtachment
            ]);

            if(!$news)
                return response()->json(['status'=> 0 , 
                    'message' => 'Something went wrong. Error uploading news']);
            else {
                if($hasAttachments)
                {
                    
                    if ($request->hasFile('newsFile1'))
                    {
                        $attachmentData = [
                            'attachment_cd' => date('YmdHis', strtotime(Carbon::now()))."1",
                            'agri_news_cd'  => $agrinews_cd,
                            'uploaded_on'   => Carbon::now(),
                            'uploaded_by'   => auth()->user()->user_id,
                            'file_path'     => $request->newsfile1,
                            'doc_type'      => $request->newsfile1type
                            ];
                        if(! AgriNewsAttachment::create($attachmentData))
                            return response()->json(['status'=> 0, 'message' => 'Error uploading Attachment 1']);
                    }
                    if ($request->hasFile('newsFile2'))
                    {
                        $attachmentData = [
                            'attachment_cd' => date('YmdHis', strtotime(Carbon::now()))."2",
                            'agri_news_cd'  => $agrinews_cd,
                            'uploaded_on'   => Carbon::now(),
                            'uploaded_by'   => auth()->user()->user_id,
                            'file_path'     => $request->newsfile2,
                            'doc_type'      => $request->newsfile2type
                            ];
                        if(! AgriNewsAttachment::create($attachmentData))
                            return response()->json(['status'=> 0, 'message' => 'Error uploading Attachment 2']);
                    }
                    if ($request->hasFile('newsFile3'))
                    {
                        $attachmentData = [
                            'attachment_cd' => date('YmdHis', strtotime(Carbon::now()))."3",
                            'agri_news_cd'  => $agrinews_cd,
                            'uploaded_on'   => Carbon::now(),
                            'uploaded_by'   => auth()->user()->user_id,
                            'file_path'     => $request->newsfile3,
                            'doc_type'      => $request->newsfile3type
                            ];
                        if(! AgriNewsAttachment::create($attachmentData))
                            return response()->json(['status'=> 0, 'message' => 'Error uploading Attachment 3']);
                    }
                        
                }
            } 
                
            // dd($request->all());
            // return response(['status'=> 1, 'message' => 'News created successfully.']);
            return redirect()->route('agrinews.newsmanager')->with('success', 'News created successfully.');

        }
    }
}
