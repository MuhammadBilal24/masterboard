<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;

class MainController extends Controller
{
    public function dashboardpage()
    {
        return view('dashboard');
    }
    // Customization
    public function customizePage()
    {
        $data['customizeData']= DB::table('customize')->get();
        return view('customize',$data);
    }
    public function customizeInsert(request $request)
    {
        $title = $request->input('title');
        $value = $request->input('value');
        $data= array(
            'title' => $request->title,
            'value' => $request->value,
        );  
        DB::table('customize')->insert($data);
        return response()->json([
            'status'=> 200,
            'messages' => "Added",
        ]);
    }
    public function customizeEdit($id)
    {
        $data = DB::table('customize')->where(['id' => $id])->first();
        return response()->json($data);  
    }
    public function customizeUpdate(request $request)
    {
        DB::table('customize')->where(['id'=>$request->id])->
        update([
                'id'=> $request->id,
                'value' => $request->value,
            ]);
        //   return response()->json($data);  
    }
    
    // Category
    public function categoryPage()
    {
        $data['categoryData'] = DB::table('category')->get();
        return view('category', $data);
    }
    public function categoryInsert(request $request)
    {
        $name = $request->input('name');
        $data= array(
            'name'=>$request->name,
        );
        DB::table('category')->insert($data);
        return response()->json([
            'status'=>200,
            'messages'=>"Category Added",
        ]);
    }
    public function categoryDeleted(request $request)
    {
        DB::table('category')->where(['id'=>$request->id])->update([
            'id'=>$request->id,
            'deleted_category' => 1,
        ]);
        return response()->json([
            'status'=>200,
            'messages'=>"Category Deleted",
        ]);
    }
    public function categoryRestore(request $request)
    {
        $data = DB::table('category')->where(['id'=>$request->id])->update([
            'id'=>$request->id,
            'deleted_category'=>0,
        ]);
        return back();
    }
    // Category End
    
    // User 
    public function usersPage()
    {
        $activeUser = ['LogginUser' => DB::table('users')->where('id', session('loggedInUser'))->first()];
        $data['usersData'] = DB::table('users')->get();
        return view('users', $data, $activeUser);
    }
    public function userDetailsPage(request $request)
    {
        $activeUser = ['LogginUser' => DB::table('users')->where('id', session('loggedInUser'))->first()];
        $data['userDetailsData'] = DB::table('users')->where(['id' => $request->id])->first();
        // $data = ['userInfo' => DB::table('users')->where('id', session('loggedInUser'))->first()];
        // var_dump ($data);
        return view('user_details', $data , $activeUser);
    }
    public function userDelete(request $request)
    {
        // $loggin = ['userInfo' => DB::table('users')->where('id', session('loggedInUser'))->first()];
        $data = User::find($request->id);
        if($data)
        {
            $data->delete();
            return response()->json([
                'status'=> 200,
                'messages'=>"User Deleted From Master-Board"
            ]);
        }
        else
        {
            return response()->json([
                'status'=> 400,
                'messages'=>"User Not Deleted",
            ]);
            return back();
        }
    }
    public function expensePage()
    {
        $data['expenseData']= DB::table('expenses')->get();
        $sum_amount_expense['total_Expense'] = DB::select('SELECT SUM(amount) as total_sum FROM expenses');
        return view('expense', $data , $sum_amount_expense);
    }
    public function expenseStore(request $request)
    {
        $date= $request->input('date');
        $amount= $request->input('amount');
        $description= $request->input('description');
        $data=array(
            'date' => $request->date,
            'amount' => $request->amount,
            'description' => $request->description,
        );
        DB::table('expenses')->insert($data);
        // return redirect('expense');
        return response()->json([
            'status' => 200,
            'messages' =>"Expense Added",
        ]); 
    }
    public function deleteExpenses(request $request)
    {
        // This method update expense table for delete = 1 s 
        $data = DB::table('expenses')->where(['id'=>$request->id])->update([
            'id'=>$request->id,
            // 'amount' => $amount,
            // 'description'=>$description,
            'deleted_expenses'=>1,
        ]);
        return back();
    }
    public function restoreExpenses(request $request)
    {
        $data = DB::table('expenses')->where(['id'=>$request->id])->update([
            'id'=>$request->id,
            // 'amount' => $amount,
            // 'description'=>$description,
            'deleted_expenses'=>0,
        ]);
        return back();
    }
    
    // Trash {Deleted Data}
    public function trashPage()
    {
        $data['deletedCategory'] = DB::table('category')->where(['deleted_category' => 1])->get();
        $data['deletedExpense'] = DB::table('expenses')->where(['deleted_expenses' => 1])->get(); 
        // var_dump($data);
        return view('trash',$data);
    }
    public function permenantTrashDeleteExpense(request $request)
    {
        $data = DB::table('expenses')->where(['id'=>$request->id])->delete();
        return back();
    }
    public function permenantTrashDeleteCategory(request $request)
    {
        DB::table('category')->where(['id'=>$request->id])->delete();
        return back();
    }
}
