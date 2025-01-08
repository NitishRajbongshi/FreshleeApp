<?php

namespace App\Http\Controllers\QueryHandler;

use App\Http\Controllers\Controller;
use App\Models\Query\Query;
use App\Models\Query\QueryAnswer;
use App\Models\Query\QueryAttachment;
use App\Models\Query\QueryCategory;
use App\Models\Query\QueryReject;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryController extends Controller
{
    public function dashboard(){
        return view('moderator.dashboard');
    }

    public function index(){
        try{
            DB::enableQueryLog();
            // $data['queries'] = Query::with('queryCategory')->where('is_moderated', '0')->orderby('query_submitted_on', 'desc')->get();
            $data['queries'] = Query::where('is_moderated', '0')->orderby('query_submitted_on', 'desc')->get();
            $data['categories'] = QueryCategory::get();
            //  $data['attachment'] = QueryAttachment::get();
            $queries = DB::getQueryLog();
            Log::info($queries);
            Log::info($data['queries']);
            return view('moderator.moderatequery')->with($data);
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getCode());
        }
        
    }

    public function acceptQuery(Request $request) {

        $validator = Validator::make($request->all(),[
            'queryCategorySelect' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['status' => 0,'message' => $validator->errors()->first() ]);
        }

        $dec_id = Crypt::decrypt($request->accept_query_id);
        $query = Query::find($dec_id);

        $query->moderated_on = Carbon::now();
        $query->moderated_by = auth()->user()->user_id;
        $query->is_moderated = 1;
        $query->moderate_status = 1; // Accepted
        $query->query_catg = $request->queryCategorySelect;
        if( !empty($request->queryAnswer) ) {
            $query->is_answered = 1;
        }

        $update = $query->update();

        if(!$update){
            return response()->json(['status'=>0, 'message'=>'Error moderating query!']);
        }

        if( !empty($request->queryAnswer) ) {
            $answer = QueryAnswer::create([
                'query_id'      => $dec_id,
                'query_ack_no'  => $query->ack_no,
                'ans_ack_no'    => date('YmdHis', strtotime(Carbon::now())),//$query->ack_no,
                'query_ans'     => $request->queryAnswer,
                'ans_by'        => auth()->user()->user_id,
                'ans_on'        => Carbon::now()
            ]);
            if(!$answer) {
                return response()->json(['status'=>0, 'message'=>"Answer Error"]);
            }
            else {
                return response()->json(['status'=>1, 'message'=>"Answered"]);
            }
        }
        return response()->json(['status'=>1, 'message'=>'Query moderated successfully']);
    }

    public function rejectQuery(Request $request) {
        $dec_id = Crypt::decrypt($request->reject_query_id);

        $validator = Validator::make($request->all(),[
            'queryRejectReason' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['status' => 0,'message' => $validator->errors()->first() ]);
        }

        $reason = $request->queryRejectReason; 
       
        $reason_create = QueryReject::create([
            'reason' => $reason,
            'query_id'=> $dec_id,
        ]);
        
        if( $reason_create ) {
            $query = Query::find($dec_id);

            $query->moderated_on = Carbon::now();
            $query->moderated_by = auth()->user()->user_id;
            $query->is_moderated = 1;
            $query->moderate_status = 0; // Rejected 
            
            $update = $query->update();
            if(!$update) 
                return response()->json(['status'=>0, 'message'=>'Query moderation failed(1)!']);
            else
                return response()->json(['status'=>1, 'message'=>'Query moderated successfully.']);            
        }
        else {
            return response()->json(['status'=>0, 'message'=>'Query moderation failed(1)!']);
        }

    }

    public function linkParentQuery(Request $request, $id) {
        $decrypted_id = Crypt::decrypt($id);
        $data['selectedQuery'] = Query::find($decrypted_id);

        $data['queries'] = Query::with('queryCategory')->where('parent_ack_no', null)
        ->where('query_id', '<>', $decrypted_id)->where('is_answered',1)
        ->orderby('query_submitted_on', 'desc')->get();
        // $data['categories'] = QueryCategory::get();

        return view('moderator.linkparentquery')->with($data);
    }

    public function setParentQuery(Request $request)
    {
        $parent_query_id = Crypt::decrypt($request->parent_id);
        $child_query_id = Crypt::decrypt($request->child_id);

        $parent_query = Query::find($parent_query_id);
        $child_query = Query::find($child_query_id);

        $child_query->moderated_on = Carbon::now();
        $child_query->moderated_by = auth()->user()->user_id;
        $child_query->is_moderated = 1;
        $child_query->moderate_status = 1; // Accepted
        $child_query->query_catg = $parent_query->query_catg;
        $child_query->parent_ack_no = $parent_query->query_id;
        // if( !empty($request->queryAnswer) ) {
        //     $query->is_answered = 1;
        // }

        $update = $child_query->update();

        if(!$update){
            return response()->json(['status'=>0, 'message'=>'Error linking parent!']);
        }

        return response()->json(['status'=>1, 'message'=>'Query moderated successfully']);
        // return response(['status'=>0, 'message'=>$request->parent_id]);
    }

// ******************************************************************************************
    // Code for Query Answer (AGRIExpert)
    public function agriexpert_dashboard(){
        return view('agriexpert.dashboard');
    }

    public function queriesToAnswer($category=-1) {
        $data['categories'] = QueryCategory::all();
        if($category==-1)
            $data['queries'] = Query::with('queryCategory')
            ->whereNotNull("moderated_on")->where('moderate_status',1)->orderby('query_submitted_on', 'desc')->paginate(10);
        else {
            $category_id = Crypt::decrypt($category);
            $data['selectedId'] = $category_id;
            $data['queries'] = Query::with('queryCategory')
                ->whereNotNull("moderated_on")->where('query_catg', $category_id)
                ->orderby('query_submitted_on', 'desc')->paginate(10);        
        }
        return view('agriexpert.queryviewer')->with($data);
    }

    public function answerQuery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'queryAnswer' => ['required']
        ]);

        if($validator->fails()) {
            return response()->json( ['status'=>0,'message' => $validator->errors()->first()] );
        }

        $dec_id = Crypt::decrypt($request->answer_query_id);
        $query = Query::find($dec_id);

        if( !empty($request->queryAnswer) ) {
            $query->is_answered = 1;
        }

        $update = $query->update();
        if(!$update){
            return response()->json(['status'=>0, 'message'=>'Error updating the query status!']);
        }

        if( !empty($request->queryAnswer) ) {
            $answer = QueryAnswer::create([
                'query_id'      => $dec_id,
                'query_ack_no'  => $query->ack_no,
                'ans_ack_no'    => date('YmdHis', strtotime(Carbon::now())), // $query->ack_no,
                'query_ans'     => $request->queryAnswer,
                'ans_by'        => auth()->user()->user_id,
                'ans_on'        => Carbon::now()
            ]);
            if(!$answer) {
                return response()->json(['status'=>0, 'message'=>"Answer Error"]);
            }
            else {
                return response()->json(['status'=>1, 'message'=>"Answered Successfully."]);
            }
        }

    }

    public function answerThread($id)
    {
        try{
            $query_id = Crypt::decrypt($id);
            $query = Query::find($query_id);
            $data['query'] = $query;

            if($query->has_attachment ==1)
            {
                // load the attachment paths from tbl_standard_queries_attachment
            }

            if($query->is_answered == 1)
            {
                $answers = QueryAnswer::where('query_id', $query->query_id)->get();
                $data['answers'] = $answers;
            }
        }
        catch (Exception $e){
            $data['message'] = "Error loading query.";
            // return redirect()->back()->with('message', 'Regisration failed');
        }
        return view('agriexpert.thread')->with($data);
    }
}
