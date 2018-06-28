<?php

namespace App\Api\V1\Controllers;

use Config;
use Mail;
use Carbon\Carbon;
use App\User2;
use App\Cigarettes2;
use App\ATM2;
use App\Inventory2;
use App\Stores2;
use App\Sales2;
use App\ExpenseItems2;
use App\Payments2;
use App\Vendors2;
use App\Items2;
use App\Lottery2;
use App\Expense2;
use App\Messages2;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Api\V1\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Redirect;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UsersController2 extends Controller
{
    /**
     * @param Request $request
     * @param JWTAuth $JWTAuth
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request, JWTAuth $JWTAuth)
    {
        $credentials = $request->only(['email', 'password']);
		$email = $request->get('email');
		$count = User2::where("email","=",$email)->count();
		if($count>0){
			$status = User2::where("email","=",$email)->first();
			if($status['status']==0){
				return response()->json(["status" => "0", "error_msg" => "This account is blocked!", "data"=> ""]);
			}
			else if($status['status']==2){
				return response()->json(["status" => "0", "error_msg" => "This account is deleted!", "data"=> ""]);
			}
			else if($status['status']==1){
				$token = $JWTAuth->attempt($credentials);
            if(!$token) {
                return response()->json(["status" => "0", "error_msg" => "Invalid password!", "data"=> ""]);
            }else{
				$data = array("status"=>1,"email"=>$credentials['email']);
				$user = User2::where($data)->get();
				User2::where($data)->update(array("api_token"=>$token));
				return response()->json(['status' => '1','token' => $token,'User' => $user,'error_msg'=>'']);
			}
			}
		}else{
			return response()->json(["status" => "0", "error_msg" => "Invalid email!", "data"=> ""]);
		}
    }
	public function forgotPassword(Request $request)
	{
		 $email = $request->get('email');
		 $data = array("status"=>1,"email"=>$email);
		 $count = User2::where($data)->count();
		 $user = User2::where($data)->get();
		 if($count>0)
		 {
			$data1['email'] = $email;
			$data1['name'] = $user[0]->name;
			$token = str_random(50);
			$data1['link']= url('reset_password')."/".$token;
			Mail::send('admin.forgot_pass_email',$data1, function($message) use ($data1){
								$message->from('support@aaryagroup.com', 'Aarya Group');
								$message->to($data1['email']);
								$message->subject('Reset Password');
			});
			$update = array('password_token'=>$token);
			$success = User2::where('email','=',$email)->update($update);
			return response()->json(['status' => '1', 'data'=>'Mail successfully sent for password reset!']);
		 }
		 else
		 {
			return response()
            ->json([
				'status' => '0',
                'error_msg' => 'This email does not belong to any account!',
            ]);
		 }
	}
	public function editProfile(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if($user || true)
		{
			return response()->json(['User' =>$user, 'status' =>'1']);
		}
		else
		{
			return response()->json(['error_msg' =>'Token is invalid or expired!', 'status' =>'0']);
		}
	}
	public function updateProfileData(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if($user || true)
		{
				$user_data = $request->all();
				$id = $user_data['id'];
				$exist = User2::where("id",'!=',$request->get('id'))->where("email",'=',$request->get('email'))->count();
				if($exist>0){
					return response()->json(['status'=>'0', 'error_msg' =>'Email already exists in database!']);
				}else{
					$update = User2::where(array("id"=>$id))->update($user_data);
					if(!empty($update))
					{
						return response()->json(['status' =>'1', 'data'=>'Profile details successfully updated!']);
					}
					else
					{
						return response()->json(['status' =>'0', 'error_msg' =>'Profile details not updated!']);
					}
				}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function logout(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if($user || true)
		{
			$JWTAuth->invalidate($JWTAuth->getToken());
			return response()->json(['status' =>'1', 'data'=>'Successfully logged out!']);
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function user_list(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$user_id = $request->get('user_id');
			$user = User2::where(array("id"=>$user_id))->first();
			$store_access = $user['store_access'];
			
			if(!empty($store_access)){
				$user_data = DB::table('users')
						->select(DB::raw(
									'users.id,
									users.status,
									IFNULL(users.name,"") as name,
									IFNULL(users.email,"") as email,
									IFNULL(users.address,"") as address,
									IFNULL(users.mobile,"") as mobile,
									stores.name as store_name,
									stores.id as store_id
									'))
						->leftJoin('stores', 'users.store_id', '=', 'stores.id')
						->whereRaw('users.store_id IN('.$store_access.') and users.role = "staff" and (users.status="1" or users.status="0")')
						//->where('users.role','=','staff')
						//->Where('users.status','=','1')
						//->orWhere('users.status','=','0')
						->orderBy("users.name",'ASc')
						->get();
			}else{
				$user_data = DB::table('users')
						->select(DB::raw(
									'users.id,
									users.status,
									IFNULL(users.name,"") as name,
									IFNULL(users.email,"") as email,
									IFNULL(users.address,"") as address,
									IFNULL(users.mobile,"") as mobile,
									stores.name as store_name,
									stores.id as store_id
									'))
						->leftJoin('stores', 'users.store_id', '=', 'stores.id')
						->whereRaw('users.role = "staff" and (users.status="1" or users.status="0")')
						//->where('users.role','=','staff')
						//->Where('users.status','=','1')
						//->orWhere('users.status','=','0')
						->orderBy("users.name",'ASc')
						->get();
			}
			//$user_data = User2::where(array("role"=>"staff"))->get();
			return response()->json(['status' =>'1', 'User_list'=>$user_data]);
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function add_user(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$exist = User2::where("email",'=',$request->get('email'))->count();
			if($exist>0){
				return response()->json(['status'=>'0', 'error_msg' =>'Email already exists in database!']);
			}else{
			$original_pass = $request->get('password');
			$password = Hash::make($request->get('password'));
			$data1=array(
				"email"=>$request->get('email'),
     	 		"name"=>$request->get('name'),
				"password"=>$password,
				"role"=>$request->get('role'),
				"mobile"=>$request->get('mobile'),
				"store_id"=>$request->get('store_id'),
				"created_by"=>$request->get('user_id'),
     	 		);
     	 		$new_user = new User2($data1);
				$new_user->save();
				
     	 		if($new_user) {
					
					$data1['email'] = $request->get('email');
					$data1['name'] = $request->get('name');
					$data1['password'] = $original_pass;
					Mail::send('admin.account_password_email',$data1, function($message) use ($data1){
								$message->from('support@aaryagroup.com', 'Aarya Group');
								$message->to($data1['email']);
								$message->subject('Account Password');
					});
				return response()->json(['status'=>'1', 'data' =>'User successfully added!']);
        	   }else {
        	   return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
        	   	}
		}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function edit_user(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$email = $request->get('email');
			$count = User2::where(array("email"=>$email))->count();
			if($count>0){
				//$current_data = User2::where(array("email"=>$email))->first();
				$current_data = DB::table('users')
						->select(DB::raw(
									'users.id,
									users.status,
									IFNULL(users.name,"") as name,
									IFNULL(users.email,"") as email,
									IFNULL(users.address,"") as address,
									IFNULL(users.mobile,"") as mobile,
									stores.name as store_name,
									stores.id as store_id
									'))
						->leftJoin('stores', 'users.store_id', '=', 'stores.id')
						->where('users.status','=','1')
						->where('users.email','=',$email)
						->first();
				return response()->json(['status' =>'1', 'current_data'=>$current_data]);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Invalid email!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function update_user_info(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		$email = User2::where(array("id"=>$request->get('id')))->first();
		if(($user && $user->role == "admin")||true)
		{
				$exist = User2::where("id",'!=',$request->get('id'))->where("email",'=',$request->get('email'))->count();
				if($exist>0){
					return response()->json(['status'=>'0', 'error_msg' =>'Email already exists in database!']);
				}
			else{
				$user_data = $request->all();
				$user_data['updated_by'] = $request->get('user_id');
				$id = $user_data['id'];
				$update = User2::where('id','=',$id)->update($user_data);
				if($update)
				{
					return response()->json(['status' =>'1', 'data' =>'User details updated!']);
				}
				else
				{
					return response()->json(['status' =>'0', 'error_msg' =>'User details not updated!']);
				}
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function delete_user(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			if(!empty($id)){
				$user = User2::where(array("id"=>$id))->first();
				if(!empty($user)){
					if($user->status == 1){
						
						$block = User2::where("id","=",$id)->first();
						//$credentials = array("email"=>$block['email'], "password"=>$block['password']);
						//$token = $JWTAuth->attempt($credentials);
						//$JWTAuth->invalidate($token);
						$up = array("status"=>0, "blocked_by"=>$request->get('user_id'));
						$update = User2::where(array("id"=>$id))->update($up);
						
						return response()->json(['status'=>'1', 'data' =>'User successfully blocked!', 'b_status'=>'0']);
					}else if($user->status == 0){
						$update = User2::where(array("id"=>$id))->update(array("status"=>1));
						return response()->json(['status'=>'1', 'data' =>'User successfully unblocked!', 'b_status'=>'1']);
					}
				}else{
					return response()->json(['status'=>'0', 'error_msg' =>'No user found with this id!']);
				}
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Provide id of the user!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function change_password(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if($user || true)
		{
			$id = $request->get('id');
			$password = $request->get('password');
			if(!empty($id) && !empty($password)){
				$user = User2::where(array("id"=>$id))->first();
				if(!empty($user)){
					$new_password = Hash::make($password);
					User2::where(array("id"=>$id))->update(array("password"=>$new_password));
					
				$user1 = User2::where(array("id"=>$id))->first();
				$data2['name'] = $user1['name'];
				$data2['email'] = $user1['email'];
				$data2['password'] = $password;
					Mail::send('admin.password_changed_email',$data2, function($message) use ($data2){
								$message->from('support@aaryagroup.com', 'Aarya Group');
								$message->to($data2['email']);
								$message->subject('Account password changed');
				});
				
				
				if( count(Mail::failures()) > 0 ) {
					echo "There was one or more failures. They were: <br />";
					print_R(Mail::failures()); die();
				}
					
					return response()->json(['status'=>'1', 'data' =>'Password successfully changed!']);
					
				}else{
					return response()->json(['status'=>'0', 'error_msg' =>'No user found with this id!']);
				}
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Provide id of the user and new password!']);
			}
			
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function store_list(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$user_id = $request->get('user_id');
			$user = User2::where(array("id"=>$user_id))->first();
			$store_access = $user['store_access'];
			
			if(!empty($store_access)){
				$store_data = DB::table('stores')
						->select(DB::raw(
									'stores.*,
									(SELECT IFNULL(ROUND(SUM(total),2),"0")  FROM daily_payment_report WHERE daily_payment_report.store_id=stores.id) AS payments,
									(SELECT IFNULL(ROUND(SUM(total_sales),2),"0")  FROM daily_sales_entry WHERE daily_sales_entry.store_id=stores.id) AS sales,
									(SELECT IFNULL(ROUND(SUM(total_price),2),"0")  FROM inventory WHERE inventory.store_id=stores.id) AS inventory
									'))
						//->leftJoin('daily_payment_report', 'daily_payment_report.store_id', '=', 'stores.id')
						//->leftJoin('daily_sales_entry', 'daily_sales_entry.store_id', '=', 'stores.id')
						//->leftJoin('inventory', 'inventory.store_id', '=', 'stores.id')
						->where('stores.status','=','1')
						->whereRaw('stores.id IN('.$store_access.')')
						->groupBy('stores.id')
						->get();
			}else{
				//echo "here"; die();
				$store_data = DB::table('stores')
						->select(DB::raw(
									'stores.*,
									(SELECT IFNULL(ROUND(SUM(total),2),"0")  FROM daily_payment_report WHERE daily_payment_report.store_id=stores.id) AS payments,
									(SELECT IFNULL(ROUND(SUM(total_sales),2),"0")  FROM daily_sales_entry WHERE daily_sales_entry.store_id=stores.id) AS sales,
									(SELECT IFNULL(ROUND(SUM(total_price),2),"0")  FROM inventory WHERE inventory.store_id=stores.id) AS inventory
									'))
						//->leftJoin('daily_payment_report', 'daily_payment_report.store_id', '=', 'stores.id')
						//->leftJoin('daily_sales_entry', 'daily_sales_entry.store_id', '=', 'stores.id')
						//->leftJoin('inventory', 'inventory.store_id', '=', 'stores.id')
						->where('stores.status','=',1)
						->groupBy('stores.id')
						->get();
			}
			return response()->json(['status' =>'1', 'Store_list'=>$store_data]);
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function add_store(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$data1=array(
				"name"=>$request->get('name'),
     	 		"address"=>$request->get('address'),
				"contact_no"=>$request->get('contact_no'),
				"created_by"=>$request->get('user_id'),
     	 		);
     	 		$new_store = new Stores2($data1);
				$new_store->save();
     	 		if($new_store) {
				return response()->json(['status'=>'1', 'data' =>'Store successfully added!']);
        	   }else {
        	   return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
        	   	}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function edit_store(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$current_data = Stores2::where(array("id"=>$id, "status"=>1))->first();
			return response()->json(['status' =>'1', 'store_data'=>$current_data]);
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function update_store_info(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$store_data = $request->all();
			$store_data['updated_by'] = $request->get('user_id');
			$update = Stores2::where('id','=',$id)->update($store_data);
				if($update) 
				{
					return response()->json(['status' =>'1', 'data' =>'Store details updated!']);
				}
				else
				{
					return response()->json(['status' =>'0', 'error_msg' =>'Store details not updated!']);
				}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function delete_store(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$up = array("status"=>0, "deleted_by"=>$request->get('user_id'));
			$del = Stores2::where(array("id"=>$id))->update($up);
			if($del){
				return response()->json(['status'=>'1', 'data' =>'Store successfully deleted!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function item_list(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if($user || true)
		{
			$item_data = Items2::where(array("status"=>1))->get();
			return response()->json(['status' =>'1', 'item_list'=>$item_data]);
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function add_item(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$data1=array(
				"name"=>$request->get('name'),
     	 		"status"=>$request->get('status'),
     	 		);
     	 		$new_item = new Items2($data1);
				$new_item->save();
     	 		if($new_item) {
				return response()->json(['status'=>'1', 'data' =>'Item successfully added!']);
        	   }else {
        	   return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
        	   	}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function edit_item(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$current_data = Items2::where(array("id"=>$id, "status"=>1))->first();
			return response()->json(['status' =>'1', 'item_data'=>$current_data]);
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function update_item_info(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$store_data = $request->all();
			$update = Items2::where('id','=',$id)->update($store_data);
				if($update) 
				{
					return response()->json(['status' =>'1', 'data' =>'Item details updated!']);
				}
				else
				{
					return response()->json(['status' =>'0', 'error_msg' =>'Item details not updated!']);
				}
			
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function delete_item(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$del = Items2::where(array("id"=>$id))->update(array("status"=>0));
			if($del){
				return response()->json(['status'=>'1', 'data' =>'Item successfully deleted!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function vendor_list(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if($user || true)
		{
			$item_data = Vendors2::where(array("status"=>1))->get();
			return response()->json(['status' =>'1', 'vendor_list'=>$item_data]);
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function add_vendor(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$data1=array(
				"name"=>$request->get('name'),
     	 		"status"=>$request->get('status'),
     	 		);
     	 		$new_item = new Vendors2($data1);
				$new_item->save();
     	 		if($new_item) {
				return response()->json(['status'=>'1', 'data' =>'Vendor successfully added!']);
        	   }else {
        	   return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
        	   	}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function edit_vendor(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$current_data = Vendors2::where(array("id"=>$id, "status"=>1))->first();
			return response()->json(['status' =>'1', 'vendor_data'=>$current_data]);
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function update_vendor_info(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$store_data = $request->all();
			$update = Vendors2::where('id','=',$id)->update($store_data);
				if($update) 
				{
					return response()->json(['status' =>'1', 'data' =>'Vendors details updated!']);
				}
				else
				{
					return response()->json(['status' =>'0', 'error_msg' =>'Vendor details not updated!']);
				}
			
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function delete_vendor(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$del = Vendors2::where(array("id"=>$id))->update(array("status"=>0));
			if($del){
				return response()->json(['status'=>'1', 'data' =>'Vendor successfully deleted!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'some error occured!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function get_store_data(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$item_data = DB::table('stores')
						->select(DB::raw(
									'stores.*,
									(SELECT IFNULL(SUM(total),"0")  FROM daily_payment_report WHERE daily_payment_report.store_id=stores.id) AS payments,
									(SELECT IFNULL(SUM(total_sales),"0")  FROM daily_sales_entry WHERE daily_sales_entry.store_id=stores.id) AS sales,
									(SELECT IFNULL(SUM(total_price),"0")  FROM inventory WHERE inventory.store_id=stores.id) AS inventory,
									
									(SELECT IFNULL(SUM(daily_sales_entry.inside),"0")  FROM daily_sales_entry WHERE daily_sales_entry.store_id=stores.id) AS inside,
									(SELECT IFNULL(SUM(daily_sales_entry.tax),"0")  FROM daily_sales_entry WHERE daily_sales_entry.store_id=stores.id) AS tax,
									(SELECT IFNULL(SUM(daily_sales_entry.gas),"0")  FROM daily_sales_entry WHERE daily_sales_entry.store_id=stores.id) AS gas,
									(SELECT IFNULL(SUM(daily_sales_entry.phone_card),"0")  FROM daily_sales_entry WHERE daily_sales_entry.store_id=stores.id) AS phone_card,
									(SELECT IFNULL(SUM(daily_sales_entry.lottery),"0")  FROM daily_sales_entry WHERE daily_sales_entry.store_id=stores.id) AS lottery,
									
									(SELECT IFNULL(SUM(daily_payment_report.total_cash),"0")  FROM daily_payment_report WHERE daily_payment_report.store_id=stores.id) AS total_cash,
									(SELECT IFNULL(SUM(daily_payment_report.total_credit),"0")  FROM daily_payment_report WHERE daily_payment_report.store_id=stores.id) AS total_credit,
									(SELECT IFNULL(SUM(daily_payment_report.total_debit),"0")  FROM daily_payment_report WHERE daily_payment_report.store_id=stores.id) AS total_debit,
									(SELECT IFNULL(SUM(daily_payment_report.lottery_out),"0")  FROM daily_payment_report WHERE daily_payment_report.store_id=stores.id) AS lottery_out
									'))
						->leftJoin('daily_payment_report', 'daily_payment_report.store_id', '=', 'stores.id')
						->leftJoin('daily_sales_entry', 'daily_sales_entry.store_id', '=', 'stores.id')
						->leftJoin('inventory', 'inventory.store_id', '=', 'stores.id')
						->where('stores.id','=',$id)
						->where('stores.status','=','1')
						->groupBy('stores.id')
						->first();
			return response()->json(['status' =>'1', 'store_data'=>$item_data]);
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
		public function store_in_profit(Request $request, JWTAuth $JWTAuth)
	{
		
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$user_id = $request->get('user_id');
			$user = User2::where(array("id"=>$user_id))->first();
			$store_access = $user['store_access'];
			
			$id = $request->get('id');
			$date_from = $request->get('from');
			$date_to = $request->get('to');
			if(!empty($date_from) && !empty($date_to) && $id !=0){
				$stores = DB::table('stores')
						->select(DB::raw(
									"stores.*,
									(SELECT IFNULL(ROUND(SUM(total),2),'0')  FROM daily_payment_report WHERE daily_payment_report.status=1 and daily_payment_report.store_id=stores.id and DATE(daily_payment_report.date)>='$date_from' and DATE(daily_payment_report.date)<='$date_to') AS payments,
									(SELECT IFNULL(ROUND(SUM(total_sales),2),'0')  FROM daily_sales_entry WHERE daily_sales_entry.status=1 and daily_sales_entry.store_id=stores.id and daily_sales_entry.date>='$date_from' and daily_sales_entry.date<='$date_to') AS sales,
									(SELECT IFNULL(ROUND(SUM(add_money),2),'0')  FROM ATM WHERE ATM.status=1 and ATM.store_id=stores.id and DATE(ATM.date)>='$date_from' and DATE(ATM.date)<='$date_to') AS atm,
									(SELECT IFNULL(ROUND(SUM(price),2),'0')  FROM inventory WHERE inventory.status=1 and  inventory.store_id=stores.id and DATE(inventory.date)>='$date_from' and DATE(inventory.date)<='$date_to') AS inventory,
									(SELECT IFNULL(ROUND(SUM(available_stock),2),'0')  FROM cigarettes WHERE cigarettes.status=1 and  cigarettes.store_id=stores.id and DATE(cigarettes.date)>='$date_from' and DATE(cigarettes.date)<='$date_to') AS cigarettes,
									(SELECT IFNULL(ROUND(SUM(price),2),'0')  FROM expense WHERE expense.status=1 and expense.store_id=stores.id and DATE(expense.date)>='$date_from' and DATE(expense.date)<='$date_to') AS expense,
									IFNULL( (SELECT     IFNULL(ROUND((total), 2), '0')   FROM    lottery   WHERE lottery.status = 1     AND lottery.store_id = stores.id      AND DATE(lottery.date) >= '$date_from'    AND DATE(lottery.date) <= '$date_to' ORDER BY lottery.`date` DESC LIMIT 0,1) ,0)AS lottery,
									(SELECT IFNULL(ROUND(SUM(weekly_commission),2),'0')  FROM lottery WHERE lottery.status=1 and lottery.store_id=stores.id and DATE(lottery.date)>='$date_from' and DATE(lottery.date)<='$date_to') AS weekly_commission,
									(SELECT IFNULL(ROUND(SUM(weekly_eft_ammount),2),'0')  FROM lottery WHERE lottery.status=1 and lottery.store_id=stores.id and DATE(lottery.date)>='$date_from' and DATE(lottery.date)<='$date_to') AS weekly_eft_ammount
									"))
						->where('stores.id','=',$id)
						->where('stores.status','=','1')
						->get();
				if($stores->count()<=0){
					return response()->json(['status' =>'0', 'error_msg'=>'No records found for given date range!']);
				}else{
					$cnt=0;
					$total=0;
					$total_payments=0;
					$total_sales=0;
					$total_inventory=0;
					$total_atm=0;
					$total_cigarettes=0;
					$total_expense=0;
					$total_lottery=0;
					$total_weekly_eft_ammount=0;
					$total_weekly_commission=0;
					$ttt = 0;
					$oversort=0;
						foreach($stores as $s){
						$total += $s->payments+$s->sales+$s->inventory+$s->atm+$s->cigarettes+$s->expense+$s->lottery;
						$ttt = $s->payments - $s->sales;
						$total_payments += $s->payments;
						$total_sales +=$s->sales;
						$total_inventory += $s->inventory;
						$total_atm += $s->atm;
						$total_cigarettes += $s->cigarettes;
						$total_expense += $s->expense;
						$total_lottery += $s->lottery;
						$total_weekly_commission += $s->weekly_commission;
						$total_weekly_eft_ammount += $s->weekly_eft_ammount;
						$oversort += $ttt;
							$cnt++;
						}
						$total_payments = round($total_payments,2);
						$total_sales = round($total_sales,2);
						$total_inventory = round($total_inventory,2);
						$total_atm = round($total_atm,2);
						$total_cigarettes = round($total_cigarettes,2);
						$total_expense = round($total_expense,2);
						$total_lottery = round($total_lottery,2);
						$total_weekly_commission = round($total_weekly_commission,2);
						$total_weekly_eft_ammount = round($total_weekly_eft_ammount,2);
						$oversort = round($oversort,2);
						$op = array("total_payments"=>strval($total_payments),"total_sales"=>strval($total_sales),"total_inventory"=>strval($total_inventory),"oversort"=>strval($oversort),"total_atm"=>strval($total_atm),"total_cigarettes"=>strval($total_cigarettes),"total_expense"=>strval($total_expense),"total_lottery"=>strval($total_lottery),"total_weekly_commission"=>strval($total_weekly_commission),"total_weekly_eft_ammount"=>strval($total_weekly_eft_ammount));
					return response()->json(['status' =>'1', 'stores'=>$stores, 'total'=>$op]);
				}
			}else if($id == 0){
				if(!empty($store_access)){
					$stores = DB::table('stores')
							->select(DB::raw(
									"stores.*,
									(SELECT IFNULL(ROUND(SUM(total),2),'0')  FROM daily_payment_report WHERE daily_payment_report.status=1 and daily_payment_report.store_id=stores.id and DATE(daily_payment_report.date)>='$date_from' and DATE(daily_payment_report.date)<='$date_to') AS payments,
									(SELECT IFNULL(ROUND(SUM(total_sales),2),'0')  FROM daily_sales_entry WHERE daily_sales_entry.status=1 and daily_sales_entry.store_id=stores.id and daily_sales_entry.date>='$date_from' and daily_sales_entry.date<='$date_to') AS sales,
									(SELECT IFNULL(ROUND(SUM(add_money),2),'0')  FROM ATM WHERE ATM.status=1 and ATM.store_id=stores.id and DATE(ATM.date)>='$date_from' and DATE(ATM.date)<='$date_to') AS atm,
									(SELECT IFNULL(ROUND(SUM(price),2),'0')  FROM inventory WHERE inventory.status=1 and  inventory.store_id=stores.id and DATE(inventory.date)>='$date_from' and DATE(inventory.date)<='$date_to') AS inventory,
									(SELECT IFNULL(ROUND(SUM(available_stock),2),'0')  FROM cigarettes WHERE cigarettes.status=1 and  cigarettes.store_id=stores.id and DATE(cigarettes.date)>='$date_from' and DATE(cigarettes.date)<='$date_to') AS cigarettes,
									(SELECT IFNULL(ROUND(SUM(price),2),'0')  FROM expense WHERE expense.status=1 and expense.store_id=stores.id and DATE(expense.date)>='$date_from' and DATE(expense.date)<='$date_to') AS expense,
									IFNULL( (SELECT     IFNULL(ROUND((total), 2), '0')   FROM    lottery   WHERE lottery.status = 1     AND lottery.store_id = stores.id      AND DATE(lottery.date) >= '$date_from'    AND DATE(lottery.date) <= '$date_to' ORDER BY lottery.`date` DESC LIMIT 0,1) ,0)AS lottery,
									(SELECT IFNULL(ROUND(SUM(weekly_commission),2),'0')  FROM lottery WHERE lottery.status=1 and lottery.store_id=stores.id and DATE(lottery.date)>='$date_from' and DATE(lottery.date)<='$date_to') AS weekly_commission,
									(SELECT IFNULL(ROUND(SUM(weekly_eft_ammount),2),'0')  FROM lottery WHERE lottery.status=1 and lottery.store_id=stores.id and DATE(lottery.date)>='$date_from' and DATE(lottery.date)<='$date_to') AS weekly_eft_ammount
									"))
							->where('stores.status','=','1')
							->whereRaw('stores.id IN('.$store_access.')')
							->get();
				}else{
					$stores = DB::table('stores')
							->select(DB::raw(
									"stores.*,
									(SELECT IFNULL(ROUND(SUM(total),2),'0')  FROM daily_payment_report WHERE daily_payment_report.status=1 and daily_payment_report.store_id=stores.id and DATE(daily_payment_report.date)>='$date_from' and DATE(daily_payment_report.date)<='$date_to') AS payments,
									(SELECT IFNULL(ROUND(SUM(total_sales),2),'0')  FROM daily_sales_entry WHERE daily_sales_entry.status=1 and daily_sales_entry.store_id=stores.id and daily_sales_entry.date>='$date_from' and daily_sales_entry.date<='$date_to') AS sales,
									(SELECT IFNULL(ROUND(SUM(add_money),2),'0')  FROM ATM WHERE ATM.status=1 and ATM.store_id=stores.id and DATE(ATM.date)>='$date_from' and DATE(ATM.date)<='$date_to') AS atm,
									(SELECT IFNULL(ROUND(SUM(price),2),'0')  FROM inventory WHERE inventory.status=1 and  inventory.store_id=stores.id and DATE(inventory.date)>='$date_from' and DATE(inventory.date)<='$date_to') AS inventory,
									(SELECT IFNULL(ROUND(SUM(available_stock),2),'0')  FROM cigarettes WHERE cigarettes.status=1 and  cigarettes.store_id=stores.id and DATE(cigarettes.date)>='$date_from' and DATE(cigarettes.date)<='$date_to') AS cigarettes,
									(SELECT IFNULL(ROUND(SUM(price),2),'0')  FROM expense WHERE expense.status=1 and expense.store_id=stores.id and DATE(expense.date)>='$date_from' and DATE(expense.date)<='$date_to') AS expense,
									IFNULL( (SELECT     IFNULL(ROUND((total), 2), '0')   FROM    lottery   WHERE lottery.status = 1     AND lottery.store_id = stores.id      AND DATE(lottery.date) >= '$date_from'    AND DATE(lottery.date) <= '$date_to' ORDER BY lottery.`date` DESC LIMIT 0,1) ,0)AS lottery,
									(SELECT IFNULL(ROUND(SUM(weekly_commission),2),'0')  FROM lottery WHERE lottery.status=1 and lottery.store_id=stores.id and DATE(lottery.date)>='$date_from' and DATE(lottery.date)<='$date_to') AS weekly_commission,
									(SELECT IFNULL(ROUND(SUM(weekly_eft_ammount),2),'0')  FROM lottery WHERE lottery.status=1 and lottery.store_id=stores.id and DATE(lottery.date)>='$date_from' and DATE(lottery.date)<='$date_to') AS weekly_eft_ammount
									"))
							->where('stores.status','=','1')
							->get();
				}
						
				if($stores->count()<=0){
					return response()->json(['status' =>'0', 'error_msg'=>'No records found for given date range!']);
				}else{
					$cnt=0;
					$total=0;
					$total_payments=0;
					$total_sales=0;
					$total_inventory=0;
					$total_atm=0;
					$total_cigarettes=0;
					$total_expense=0;
					$total_lottery=0;
					$total_weekly_eft_ammount=0;
					$total_weekly_commission=0;
					$ttt =0;
					$oversort=0;
						foreach($stores as $s){
						$total += $s->payments+$s->sales+$s->inventory+$s->atm+$s->cigarettes+$s->expense+$s->lottery;
						$ttt = $s->payments - $s->sales;
						$total_payments += $s->payments;
						$total_sales +=$s->sales;
						$total_inventory += $s->inventory;
						$total_atm += $s->atm;
						$total_cigarettes += $s->cigarettes;
						$total_expense += $s->expense;
						$total_lottery += $s->lottery;
						$total_weekly_commission += $s->weekly_commission;
						$total_weekly_eft_ammount += $s->weekly_eft_ammount;
						$oversort += $ttt;
							$cnt++;
						}
						$total_payments = round($total_payments,2);
						$total_sales = round($total_sales,2);
						$total_inventory = round($total_inventory,2);
						$total_atm = round($total_atm,2);
						$total_cigarettes = round($total_cigarettes,2);
						$total_expense = round($total_expense,2);
						$total_lottery = round($total_lottery,2);
						$total_weekly_commission = round($total_weekly_commission,2);
						$total_weekly_eft_ammount = round($total_weekly_eft_ammount,2);
						$oversort = round($oversort,2);
						$op = array("total_payments"=>strval($total_payments),"total_sales"=>strval($total_sales),"total_inventory"=>strval($total_inventory),"oversort"=>strval($oversort),"total_atm"=>strval($total_atm),"total_cigarettes"=>strval($total_cigarettes),"total_expense"=>strval($total_expense),"total_lottery"=>strval($total_lottery),"total_weekly_commission"=>strval($total_weekly_commission),"total_weekly_eft_ammount"=>strval($total_weekly_eft_ammount));
					return response()->json(['status' =>'1', 'stores'=>$stores, 'total'=>$op]);
				}
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Provide store id and date or provide store id as 0 to view all store data!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	
	public function store_in_profit2(Request $request, JWTAuth $JWTAuth)
	{
		
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$user_id = $request->get('user_id');
			$user = User2::where(array("id"=>$user_id))->first();
			$store_access = $user['store_access'];
			
			$id = $request->get('id');
			$date_from = $request->get('from');
			$date_to = $request->get('to');
			//instant+lottery  lottery_cash+lottery_online_cashout+lottery_instant_cashout
			if(!empty($date_from) && !empty($date_to) && $id !=0){
				$stores = DB::table('stores')
						->select(DB::raw(
									"stores.*,
									(SELECT IFNULL(ROUND(SUM(lottery_online_cashout+lottery_instant_cashout),2),'0')  FROM daily_payment_report WHERE daily_payment_report.status=1 and daily_payment_report.store_id=stores.id and DATE(daily_payment_report.date)>='$date_from' and DATE(daily_payment_report.date)<='$date_to') AS payments_total,
									(SELECT IFNULL(ROUND(SUM(instant+lottery),2),'0')  FROM daily_sales_entry WHERE daily_sales_entry.status=1 and daily_sales_entry.store_id=stores.id and daily_sales_entry.date>='$date_from' and daily_sales_entry.date<='$date_to') AS sales_total,
									(SELECT IFNULL(ROUND(SUM(total),2),'0')  FROM daily_payment_report WHERE daily_payment_report.status=1 and daily_payment_report.store_id=stores.id and DATE(daily_payment_report.date)>='$date_from' and DATE(daily_payment_report.date)<='$date_to') AS payments,
									(SELECT IFNULL(ROUND(SUM(total_sales),2),'0')  FROM daily_sales_entry WHERE daily_sales_entry.status=1 and daily_sales_entry.store_id=stores.id and daily_sales_entry.date>='$date_from' and daily_sales_entry.date<='$date_to') AS sales,
									(SELECT IFNULL(ROUND(SUM(add_money),2),'0')  FROM ATM WHERE ATM.status=1 and ATM.store_id=stores.id and DATE(ATM.date)>='$date_from' and DATE(ATM.date)<='$date_to') AS atm,
									(SELECT IFNULL(ROUND(SUM(price),2),'0')  FROM inventory WHERE inventory.status=1 and  inventory.store_id=stores.id and DATE(inventory.date)>='$date_from' and DATE(inventory.date)<='$date_to') AS inventory,
									(SELECT IFNULL(ROUND(SUM(available_stock),2),'0')  FROM cigarettes WHERE cigarettes.status=1 and  cigarettes.store_id=stores.id and DATE(cigarettes.date)>='$date_from' and DATE(cigarettes.date)<='$date_to') AS cigarettes,
									(SELECT IFNULL(ROUND(SUM(price),2),'0')  FROM expense WHERE expense.status=1 and expense.store_id=stores.id and DATE(expense.date)>='$date_from' and DATE(expense.date)<='$date_to') AS expense,
									IFNULL( (SELECT     IFNULL(ROUND((total), 2), '0')   FROM    lottery   WHERE lottery.status = 1     AND lottery.store_id = stores.id      AND DATE(lottery.date) >= '$date_from'    AND DATE(lottery.date) <= '$date_to' ORDER BY lottery.`date` DESC LIMIT 0,1) ,0)AS lottery,
									(SELECT IFNULL(ROUND(SUM(weekly_commission),2),'0')  FROM lottery WHERE lottery.status=1 and lottery.store_id=stores.id and DATE(lottery.date)>='$date_from' and DATE(lottery.date)<='$date_to') AS weekly_commission,
									(SELECT IFNULL(ROUND(SUM(weekly_eft_ammount),2),'0')  FROM lottery WHERE lottery.status=1 and lottery.store_id=stores.id and DATE(lottery.date)>='$date_from' and DATE(lottery.date)<='$date_to') AS weekly_eft_ammount
									"))
						->where('stores.id','=',$id)
						->where('stores.status','=','1')
						->get();
				if($stores->count()<=0){
					return response()->json(['status' =>'0', 'error_msg'=>'No records found for given date range!']);
				}else{
					$cnt=0;
					$total=0;
					$total_payments=0;
					$total_sales=0;
					$total_inventory=0;
					$total_atm=0;
					$total_cigarettes=0;
					$total_expense=0;
					$total_lottery=0;
					$total_weekly_eft_ammount=0;
					$weekly_tottaly_cashout=0;
					$total_weekly_commission=0;
					$ttt = 0;
$opstore=array();					$oversort=0;
						foreach($stores as $s){
						$total += $s->payments+$s->sales+$s->inventory+$s->atm+$s->cigarettes+$s->expense+$s->lottery;
						$ttt = $s->payments - $s->sales;
						$total_payments += $s->payments;
						$total_sales +=$s->sales;
						$total_inventory += $s->inventory;
						$total_atm += $s->atm;
						$total_cigarettes += $s->cigarettes;
						$total_expense += $s->expense;
						$total_lottery += $s->lottery;
						$total_weekly_commission += $s->weekly_commission;
						$total_weekly_eft_ammount += $s->weekly_eft_ammount;
						$oversort += $ttt;
$s1=(array)$s;
						$s1['weekly_cash_out']=$s->sales_total-$s->payments_total;
						$weekly_tottaly_cashout +=$s1['weekly_cash_out'];
						
						$opstore[]=$s1;						
						$cnt++;
						}
						$total_payments = round($total_payments,2);
						$total_sales = round($total_sales,2);
						$total_inventory = round($total_inventory,2);
						$total_atm = round($total_atm,2);
						$total_cigarettes = round($total_cigarettes,2);
						$total_expense = round($total_expense,2);
						$total_lottery = round($total_lottery,2);
						$total_weekly_commission = round($total_weekly_commission,2);
						$total_weekly_eft_ammount = round($total_weekly_eft_ammount,2);
						$oversort = round($oversort,2);
						$op = array("total_payments"=>strval($total_payments),"total_sales"=>strval($total_sales),"total_inventory"=>strval($total_inventory),"oversort"=>strval($oversort),"total_atm"=>strval($total_atm),"total_cigarettes"=>strval($total_cigarettes),"total_expense"=>strval($total_expense),"total_lottery"=>strval($total_lottery),"total_weekly_commission"=>strval($total_weekly_commission),"total_weekly_eft_ammount"=>strval($total_weekly_eft_ammount),"total_weekly_cash_out"=>$weekly_tottaly_cashout);
					return response()->json(['status' =>'1', 'stores'=>$opstore, 'total'=>$op]);
				}
			}else if($id == 0){
				if(!empty($store_access)){
					$stores = DB::table('stores')
							->select(DB::raw(
									"stores.*,
									(SELECT IFNULL(ROUND(SUM(total),2),'0')  FROM daily_payment_report WHERE daily_payment_report.status=1 and daily_payment_report.store_id=stores.id and DATE(daily_payment_report.date)>='$date_from' and DATE(daily_payment_report.date)<='$date_to') AS payments,
									(SELECT IFNULL(ROUND(SUM(lottery_online_cashout+lottery_instant_cashout),2),'0')  FROM daily_payment_report WHERE daily_payment_report.status=1 and daily_payment_report.store_id=stores.id and DATE(daily_payment_report.date)>='$date_from' and DATE(daily_payment_report.date)<='$date_to') AS payments_total,
									(SELECT IFNULL(ROUND(SUM(instant+lottery),2),'0')  FROM daily_sales_entry WHERE daily_sales_entry.status=1 and daily_sales_entry.store_id=stores.id and daily_sales_entry.date>='$date_from' and daily_sales_entry.date<='$date_to') AS sales_total,
									(SELECT IFNULL(ROUND(SUM(total_sales),2),'0')  FROM daily_sales_entry WHERE daily_sales_entry.status=1 and daily_sales_entry.store_id=stores.id and daily_sales_entry.date>='$date_from' and daily_sales_entry.date<='$date_to') AS sales,
									
									(SELECT IFNULL(ROUND(SUM(add_money),2),'0')  FROM ATM WHERE ATM.status=1 and ATM.store_id=stores.id and DATE(ATM.date)>='$date_from' and DATE(ATM.date)<='$date_to') AS atm,
									(SELECT IFNULL(ROUND(SUM(price),2),'0')  FROM inventory WHERE inventory.status=1 and  inventory.store_id=stores.id and DATE(inventory.date)>='$date_from' and DATE(inventory.date)<='$date_to') AS inventory,
									(SELECT IFNULL(ROUND(SUM(available_stock),2),'0')  FROM cigarettes WHERE cigarettes.status=1 and  cigarettes.store_id=stores.id and DATE(cigarettes.date)>='$date_from' and DATE(cigarettes.date)<='$date_to') AS cigarettes,
									(SELECT IFNULL(ROUND(SUM(price),2),'0')  FROM expense WHERE expense.status=1 and expense.store_id=stores.id and DATE(expense.date)>='$date_from' and DATE(expense.date)<='$date_to') AS expense,
									IFNULL( (SELECT     IFNULL(ROUND((total), 2), '0')   FROM    lottery   WHERE lottery.status = 1     AND lottery.store_id = stores.id      AND DATE(lottery.date) >= '$date_from'    AND DATE(lottery.date) <= '$date_to' ORDER BY lottery.`date` DESC LIMIT 0,1) ,0)AS lottery,
									(SELECT IFNULL(ROUND(SUM(weekly_commission),2),'0')  FROM lottery WHERE lottery.status=1 and lottery.store_id=stores.id and DATE(lottery.date)>='$date_from' and DATE(lottery.date)<='$date_to') AS weekly_commission,
									(SELECT IFNULL(ROUND(SUM(weekly_eft_ammount),2),'0')  FROM lottery WHERE lottery.status=1 and lottery.store_id=stores.id and DATE(lottery.date)>='$date_from' and DATE(lottery.date)<='$date_to') AS weekly_eft_ammount
									"))
							->where('stores.status','=','1')
							->whereRaw('stores.id IN('.$store_access.')')
							->get();
				}else{
					$stores = DB::table('stores')
							->select(DB::raw(
									"stores.*,
									(SELECT IFNULL(ROUND(SUM(lottery_online_cashout+lottery_instant_cashout),2),'0')  FROM daily_payment_report WHERE daily_payment_report.status=1 and daily_payment_report.store_id=stores.id and DATE(daily_payment_report.date)>='$date_from' and DATE(daily_payment_report.date)<='$date_to') AS payments_total,
									(SELECT IFNULL(ROUND(SUM(instant+lottery),2),'0')  FROM daily_sales_entry WHERE daily_sales_entry.status=1 and daily_sales_entry.store_id=stores.id and daily_sales_entry.date>='$date_from' and daily_sales_entry.date<='$date_to') AS sales_total,
									(SELECT IFNULL(ROUND(SUM(total),2),'0')  FROM daily_payment_report WHERE daily_payment_report.status=1 and daily_payment_report.store_id=stores.id and DATE(daily_payment_report.date)>='$date_from' and DATE(daily_payment_report.date)<='$date_to') AS payments,
									(SELECT IFNULL(ROUND(SUM(total_sales),2),'0')  FROM daily_sales_entry WHERE daily_sales_entry.status=1 and daily_sales_entry.store_id=stores.id and daily_sales_entry.date>='$date_from' and daily_sales_entry.date<='$date_to') AS sales,
									(SELECT IFNULL(ROUND(SUM(add_money),2),'0')  FROM ATM WHERE ATM.status=1 and ATM.store_id=stores.id and DATE(ATM.date)>='$date_from' and DATE(ATM.date)<='$date_to') AS atm,
									(SELECT IFNULL(ROUND(SUM(price),2),'0')  FROM inventory WHERE inventory.status=1 and  inventory.store_id=stores.id and DATE(inventory.date)>='$date_from' and DATE(inventory.date)<='$date_to') AS inventory,
									(SELECT IFNULL(ROUND(SUM(available_stock),2),'0')  FROM cigarettes WHERE cigarettes.status=1 and  cigarettes.store_id=stores.id and DATE(cigarettes.date)>='$date_from' and DATE(cigarettes.date)<='$date_to') AS cigarettes,
									(SELECT IFNULL(ROUND(SUM(price),2),'0')  FROM expense WHERE expense.status=1 and expense.store_id=stores.id and DATE(expense.date)>='$date_from' and DATE(expense.date)<='$date_to') AS expense,
									IFNULL( (SELECT     IFNULL(ROUND((total), 2), '0')   FROM    lottery   WHERE lottery.status = 1     AND lottery.store_id = stores.id      AND DATE(lottery.date) >= '$date_from'    AND DATE(lottery.date) <= '$date_to' ORDER BY lottery.`date` DESC LIMIT 0,1) ,0)AS lottery,
									(SELECT IFNULL(ROUND(SUM(weekly_commission),2),'0')  FROM lottery WHERE lottery.status=1 and lottery.store_id=stores.id and DATE(lottery.date)>='$date_from' and DATE(lottery.date)<='$date_to') AS weekly_commission,
									(SELECT IFNULL(ROUND(SUM(weekly_eft_ammount),2),'0')  FROM lottery WHERE lottery.status=1 and lottery.store_id=stores.id and DATE(lottery.date)>='$date_from' and DATE(lottery.date)<='$date_to') AS weekly_eft_ammount
									"))
							->where('stores.status','=','1')
							->get();
				}
						
				if($stores->count()<=0){
					
					
					return response()->json(['status' =>'0', 'error_msg'=>'No records found for given date range!']);
				}else{
					$cnt=0;
					$total=0;
					$total_payments=0;
					$total_sales=0;
					$total_inventory=0;
					$total_atm=0;
					$total_cigarettes=0;
					$total_expense=0;
					$total_lottery=0;
					$total_weekly_eft_ammount=0;
					$total_weekly_commission=0;
					$ttt =0;
					$weekly_tottaly_cashout=0;
					$oversort=0;
					$opstore=array();
						foreach($stores as $s){
						$total += $s->payments+$s->sales+$s->inventory+$s->atm+$s->cigarettes+$s->expense+$s->lottery;
						$ttt = $s->payments - $s->sales;
						$total_payments += $s->payments;
						$total_sales +=$s->sales;
						$total_inventory += $s->inventory;
						$total_atm += $s->atm;
						$total_cigarettes += $s->cigarettes;
						$total_expense += $s->expense;
						$total_lottery += $s->lottery;
						$total_weekly_commission += $s->weekly_commission;
						$total_weekly_eft_ammount += $s->weekly_eft_ammount;
						$oversort += $ttt;
						$s1=(array)$s;
						$s1['weekly_cash_out']=$s->sales_total-$s->payments_total;
						$weekly_tottaly_cashout +=$s1['weekly_cash_out'];
						
						$opstore[]=$s1;
						$cnt++;
						}
						$total_payments = round($total_payments,2);
						$total_sales = round($total_sales,2);
						$total_inventory = round($total_inventory,2);
						$total_atm = round($total_atm,2);
						$total_cigarettes = round($total_cigarettes,2);
						$total_expense = round($total_expense,2);
						$total_lottery = round($total_lottery,2);
						$total_weekly_commission = round($total_weekly_commission,2);
						$total_weekly_eft_ammount = round($total_weekly_eft_ammount,2);
						$oversort = round($oversort,2);
						$op = array("total_payments"=>strval($total_payments),"total_sales"=>strval($total_sales),"total_inventory"=>strval($total_inventory),"oversort"=>strval($oversort),"total_atm"=>strval($total_atm),"total_cigarettes"=>strval($total_cigarettes),"total_expense"=>strval($total_expense),"total_lottery"=>strval($total_lottery),"total_weekly_commission"=>strval($total_weekly_commission),"total_weekly_eft_ammount"=>strval($total_weekly_eft_ammount),"total_weekly_cash_out"=>$weekly_tottaly_cashout );
					return response()->json(['status' =>'1', 'stores'=>$opstore, 'total'=>$op]);
				}
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Provide store id and date or provide store id as 0 to view all store data!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function user_entry_details(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$date = $request->get('date');
			//echo $date; die();
			if(!empty($date) && !empty($id) && $id !=0){
				
				$sales = DB::table('daily_sales_entry')
						->select(DB::raw(
									"DATE_FORMAT(daily_sales_entry.date ,'%d-%m-%Y')as sdate,
									daily_sales_entry.id as sales_id,
									ROUND(daily_sales_entry.inside,2) as inside,
									ROUND(daily_sales_entry.gas,2) as gas,
									ROUND(daily_sales_entry.tax,2) as tax,
									ROUND(daily_sales_entry.phone_card,2) as phone_card,
									ROUND(daily_sales_entry.instant,2) as instant,
									ROUND(daily_sales_entry.lottery,2) as lottery,
									ROUND(daily_sales_entry.total_sales,2) as total_sales 
									"))
						->leftJoin('users','daily_sales_entry.user_id','=','users.id')
						->where('users.id', '=',$id)
						->where('daily_sales_entry.status', '=',1)
						->whereDate('daily_sales_entry.date', '=',$date)
						//->groupBy('daily_sales_entry.date')
						//->groupBy('daily_sales_entry.user_id')
						//->groupBy('stores.id')
						->get();
						
					$payment = DB::table('daily_payment_report')
						->select(DB::raw(
									"DATE_FORMAT(daily_payment_report.date ,'%d-%m-%Y')as pdate,
									daily_payment_report.id as payment_id,
									ROUND(daily_payment_report.total_cash,2) as total_cash,
									ROUND(daily_payment_report.total_credit,2) as total_credit,
									ROUND(daily_payment_report.total_debit,2) as total_debit,
									ROUND(daily_payment_report.lottery_cash,2) as lottery_cash,
									ROUND(daily_payment_report.lottery_online_cashout,2) as lottery_online_cashout,
									ROUND(daily_payment_report.lottery_out,2) as lottery_out,
									ROUND(daily_payment_report.lottery_instant_cashout,2) as lottery_instant_cashout,
									ROUND(daily_payment_report.total,2) as total
									"))
						
						->leftJoin('users','users.id','=','daily_payment_report.user_id')
						->where('users.id', '=',$id)
						->where('daily_payment_report.status', '=',1)
						->whereDate('daily_payment_report.date', '=',$date)
						//->groupBy('daily_payment_report.date')
						//->groupBy('daily_payment_report.user_id')
						//->groupBy('stores.id')
						->get();
						
					$inventory = DB::table('inventory')
						->select(DB::raw(
						"DATE_FORMAT(inventory.date ,'%d-%m-%Y')as sdate,
									inventory.id as inventory_id,
									inventory.invoice_no,
									inventory.item,
									inventory.vendor,
									inventory.quantity,
									ROUND(inventory.price,2) as price,
									ROUND(inventory.total_price,2) as total_price,
									items.name as item_name
									"))
						
						->leftJoin('users','users.id','=','inventory.user_id')
						->leftJoin('items','items.id','=','inventory.item')
						->where('users.id', '=',$id)
						->where('inventory.status', '=',1)
						->whereDate('inventory.date', '=',$date)
						//->groupBy('inventory.date')
						//->groupBy('inventory.user_id')
						//->groupBy('stores.id')
						->get();
						
						
				$atm = DB::table('ATM')
						->select(DB::raw(
						"DATE_FORMAT(ATM.date ,'%d-%m-%Y')as atmdate,
									ATM.id as atmid,
									ROUND(ATM.money,2) as money,
									ROUND(ATM.add_money,2) as add_money,
									ROUND(ATM.total_money,2) as total_money,
									ATM.store_id,
									ATM.user_id
									"))
						->leftJoin('users','users.id','=','ATM.user_id')
						->where('users.id', '=',$id)
						->where('ATM.status', '=',1)
						->whereDate('ATM.date', '=',$date)
						->get();
				
				$cigarette = DB::table('cigarettes')
						->select(DB::raw(
						"DATE_FORMAT(cigarettes.date ,'%d-%m-%Y')as cigdate,
									cigarettes.id as cigid,
									cigarettes.inventory,
									ROUND(cigarettes.add_cigarette,2) as add_cigarette,
									ROUND(cigarettes.sale_cigarette,2) as sale_cigarette,
									ROUND(cigarettes.available_stock,2) as available_stock,
									cigarettes.store_id,
									cigarettes.user_id
									"))
						->leftJoin('users','users.id','=','cigarettes.user_id')
						->where('users.id', '=',$id)
						->where('cigarettes.status', '=',1)
						->whereDate('cigarettes.date', '=',$date)
						->get();
				$lottery = DB::table('lottery')
						->select(DB::raw(
						"DATE_FORMAT(lottery.date ,'%d-%m-%Y')as lotdate,
									lottery.id as lotid,
									ROUND(lottery.inventory,2) as inventory,
									ROUND(lottery.add_book,2) as add_book,
									ROUND(lottery.weekly_eft_ammount,2) as weekly_eft_ammount,
									ROUND(lottery.weekly_commission,2) as weekly_commission,
									ROUND(lottery.active_book,2) as active_book,
									ROUND(lottery.total,2) as total,
									lottery.store_id,
									lottery.user_id
									"))
						->leftJoin('users','users.id','=','lottery.user_id')
						->where('users.id', '=',$id)
						->where('lottery.status', '=',1)
						->whereDate('lottery.date', '=',$date)
						->get();
				$expense = DB::table('expense')
						->select(DB::raw(
						"DATE_FORMAT(expense.date ,'%d-%m-%Y')as expdate,
									expense.id as expid,
									expense.title,
									ROUND(expense.price,2) as price,
									expense.user_id,
									expense.store_id,
									expense.item_id,
									expense_items.expense_item as item_name
									"))
						->leftJoin('users','users.id','=','expense.user_id')
						->leftJoin('expense_items','expense_items.id','=','expense.item_id')
						->where('users.id', '=',$id)
						->where('expense.status', '=',1)
						->whereDate('expense.date', '=',$date)
						->get();
						$output = array("sales"=>$sales,"payment"=>$payment,"inventory"=>$inventory,"cigarettes"=>$cigarette, "atm"=>$atm, "lottery"=>$lottery, "expense"=>$expense);
						
				
					return response()->json(['status' =>'1', 'data'=>$output]);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function latest(Request $request, JWTAuth $JWTAuth)
	{
		$data = array();
		$data[]=array("date"=>"28-12-2016","user_name"=>"hiten chauhan","use_fk"=>"3","sales"=>"3000","payement"=>"500","inventory"=>"2000");
		$data[]=array("date"=>"28-12-2016","user_name"=>"Pinkesh Patel","use_fk"=>"4","sales"=>"3000","payement"=>"500","inventory"=>"2000");
		return response()->json(['status'=>'1', 'data' =>$data]);
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$date_from = $request->get('from');
			$date_to = $request->get('to');
			//echo $date_to; die();
			if(!empty($date_from) && !empty($date_to) && $id !=0){
				$stores = DB::table('stores')
						->select(DB::raw(
									'stores.*,
									(SELECT IFNULL(SUM(total),"0")  FROM daily_payment_report WHERE daily_payment_report.store_id=stores.id AND users.id=daily_payment_report.user_id) AS payments,
									(SELECT IFNULL(SUM(total_sales),"0")  FROM daily_sales_entry WHERE daily_sales_entry.store_id=stores.id) AS sales,
									(SELECT IFNULL(SUM(total_price),"0")  FROM inventory WHERE inventory.store_id=stores.id) AS inventory
									'))
						->leftJoin('daily_payment_report', 'daily_payment_report.store_id', '=', 'stores.id')
						->leftJoin('daily_sales_entry', 'daily_sales_entry.store_id', '=', 'stores.id')
						->leftJoin('inventory', 'inventory.store_id', '=', 'stores.id')
						
						->where('stores.id','=',$id)
						->where('daily_sales_entry.date', '>=',$date_from)
						->where('daily_sales_entry.date', '<=',$date_to)
						->where('daily_payment_report.date', '>=',$date_from)
						->where('daily_payment_report.date', '<=',$date_to)
						->where('inventory.date', '>=',$date_from)
						->where('inventory.date', '<=',$date_to)
						//->groupBy('stores.id')
						->get();
				if($stores->count()<=0){
					return response()->json(['status' =>'0', 'error_msg'=>'No records found for given date range!']);
				}else{
					return response()->json(['status' =>'1', 'stores'=>$stores]);
				}
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function store_info(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$date_from = $request->get('from');
			$date_to = $request->get('to');
			
			$user_id = $request->get('user_id');
			$user = User2::where(array("id"=>$user_id))->first();
			$store_access = $user['store_access'];
			
			if(!empty($date_from) && !empty($date_to) && $id !=0){
				$sales = DB::table('daily_sales_entry')
						->select(DB::raw(
									"DATE_FORMAT(daily_sales_entry.date ,'%d-%m-%Y')as sdate,
									daily_sales_entry.user_id,users.name,
									IFNULL(ROUND(sum(daily_sales_entry.total_sales),2),'0') as total_sales,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','daily_sales_entry.user_id')
						->leftJoin('stores','stores.id','=','daily_sales_entry.store_id')
						->where('stores.id', '=',$id)
						->where('daily_sales_entry.status', '=',1)
						->whereDate('daily_sales_entry.date', '>=',$date_from)
						->whereDate('daily_sales_entry.date', '<=',$date_to)
						->groupBy('daily_sales_entry.date')
						->groupBy('daily_sales_entry.user_id')
						->orderBy('daily_sales_entry.date','DESC')
						//->groupBy('stores.id')
						->get();
						$op=array();
						foreach($sales as $s){
							$op[$s->sdate][$s->user_id]['store_name']=$s->store_name;
							$op[$s->sdate][$s->user_id]['sales']=$s->total_sales;
							$op[$s->sdate][$s->user_id]['name']=$s->name;
							$op[$s->sdate][$s->user_id]['user_id']=$s->user_id;
							//$op[$s->sdate][$s->user_id]['store_name']=$s->store_name;
						}
					$payment = DB::table('daily_payment_report')
						->select(DB::raw(
									"DATE_FORMAT(daily_payment_report.date ,'%d-%m-%Y')as pdate,
									daily_payment_report.user_id,users.name,
									IFNULL(ROUND(sum(daily_payment_report.total),2),'0') as total_payment,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','daily_payment_report.user_id')
						->leftJoin('stores','stores.id','=','daily_payment_report.store_id')
						->where('stores.id', '=',$id)
						->where('daily_payment_report.status', '=',1)
						->whereDate('daily_payment_report.date', '>=',$date_from)
						->whereDate('daily_payment_report.date', '<=',$date_to)
						->groupBy('daily_payment_report.date')
						->groupBy('daily_payment_report.user_id')
						->orderBy('daily_payment_report.date','DESC')
						//->groupBy('stores.id')
						->get();
						foreach($payment as $s){
							$op[$s->pdate][$s->user_id]['store_name']=$s->store_name;
							$op[$s->pdate][$s->user_id]['payment']=$s->total_payment;
							$op[$s->pdate][$s->user_id]['name']=$s->name;
								$op[$s->pdate][$s->user_id]['user_id']=$s->user_id;
					}
					$stores = Inventory2::select(DB::raw(
						"DATE_FORMAT(inventory.date ,'%d-%m-%Y')as sdate,
									
									users.name,
									inventory.user_id,
									IFNULL(ROUND(sum(inventory.price),2),'0') as price,
									stores.name as store_name
									"))
						
						->leftJoin('users','users.id','=','inventory.user_id')
						->leftJoin('stores','stores.id','=','inventory.store_id')
						->where('stores.id', '=',$id)
						->where('inventory.status', '=',1)
						->whereDate('inventory.date', '>=',$date_from)
						->whereDate('inventory.date', '<=',$date_to)
						->groupBy('inventory.date')
						->groupBy('inventory.user_id')
						->orderBy('inventory.date','DESC')
						//->groupBy('stores.id')
						->get();
						foreach($stores as $s){
							$op[$s->sdate][$s->user_id]['store_name']=$s->store_name;
							$op[$s->sdate][$s->user_id]['inventory']=$s->price;
							$op[$s->sdate][$s->user_id]['name']=$s->name;
							$op[$s->sdate][$s->user_id]['user_id']=$s->user_id;
						}
						
			$atm = DB::table('ATM')
						->select(DB::raw(
									"DATE_FORMAT(ATM.date ,'%d-%m-%Y')as atmdate,
									ATM.user_id,users.name,
									IFNULL(ROUND(sum(ATM.add_money),2),'0') as total_atm,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','ATM.user_id')
						->leftJoin('stores','stores.id','=','ATM.store_id')
						->where('stores.id', '=',$id)
						->where('ATM.status', '=',1)
						->whereDate('ATM.date', '>=',$date_from)
						->whereDate('ATM.date', '<=',$date_to)
						->groupBy('ATM.date')
						->groupBy('ATM.user_id')
						->orderBy('ATM.date','DESC')
						//->groupBy('stores.id')
						->get();
						foreach($atm as $s){
							$op[$s->atmdate][$s->user_id]['store_name']=$s->store_name;
							$op[$s->atmdate][$s->user_id]['atm']=round($s->total_atm,2);
							$op[$s->atmdate][$s->user_id]['name']=$s->name;
							$op[$s->atmdate][$s->user_id]['user_id']=$s->user_id;
					}
			$cigarettes = DB::table('cigarettes')
						->select(DB::raw(
									"DATE_FORMAT(cigarettes.date ,'%d-%m-%Y')as cigdate,
									cigarettes.user_id,users.name,
									IFNULL(ROUND(sum(cigarettes.available_stock),2),'0') as total_cigarettes,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','cigarettes.user_id')
						->leftJoin('stores','stores.id','=','cigarettes.store_id')
						->where('stores.id', '=',$id)
						->where('cigarettes.status', '=',1)
						->whereDate('cigarettes.date', '>=',$date_from)
						->whereDate('cigarettes.date', '<=',$date_to)
						->groupBy('cigarettes.date')
						->groupBy('cigarettes.user_id')
						->orderBy('cigarettes.date','DESC')
						//->groupBy('stores.id')
						->get();
						foreach($cigarettes as $s){
							$op[$s->cigdate][$s->user_id]['store_name']=$s->store_name;
							$op[$s->cigdate][$s->user_id]['cigarettes']=round($s->total_cigarettes,2);
							$op[$s->cigdate][$s->user_id]['name']=$s->name;
							$op[$s->cigdate][$s->user_id]['user_id']=$s->user_id;
					}
			$lottery = DB::table('lottery')
						->select(DB::raw(
									"DATE_FORMAT(lottery.date ,'%d-%m-%Y')as lotdate,
									lottery.user_id,users.name,
									IFNULL(ROUND(sum(lottery.total),2),'0') as total_lottery,
									IFNULL(ROUND(sum(lottery.weekly_commission),2),'0') as weekly_commission,
									IFNULL(ROUND(sum(lottery.weekly_eft_ammount),2),'0') as weekly_eft_ammount,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','lottery.user_id')
						->leftJoin('stores','stores.id','=','lottery.store_id')
						->where('stores.id', '=',$id)
						->where('lottery.status', '=',1)
						->whereDate('lottery.date', '>=',$date_from)
						->whereDate('lottery.date', '<=',$date_to)
						->groupBy('lottery.date')
						->groupBy('lottery.user_id')
						->orderBy('lottery.date','DESC')
						//->groupBy('stores.id')
						->get();
						foreach($lottery as $s){
							$op[$s->lotdate][$s->user_id]['store_name']=$s->store_name;
							$op[$s->lotdate][$s->user_id]['lottery']=round($s->total_lottery,2);
							$op[$s->lotdate][$s->user_id]['weekly_commission']=round($s->weekly_commission,2);
							$op[$s->lotdate][$s->user_id]['weekly_eft_ammount']=round($s->weekly_eft_ammount,2);
							$op[$s->lotdate][$s->user_id]['name']=$s->name;
							$op[$s->lotdate][$s->user_id]['user_id']=$s->user_id;
					}
			$expense = DB::table('expense')
						->select(DB::raw(
									"DATE_FORMAT(expense.date ,'%d-%m-%Y')as expdate,
									expense.user_id,users.name,
									IFNULL(ROUND(sum(expense.price),2),'0') as total_expense,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','expense.user_id')
						->leftJoin('stores','stores.id','=','expense.store_id')
						->where('stores.id', '=',$id)
						->where('expense.status', '=',1)
						->whereDate('expense.date', '>=',$date_from)
						->whereDate('expense.date', '<=',$date_to)
						->groupBy('expense.date')
						->groupBy('expense.user_id')
						->orderBy('expense.date','DESC')
						//->groupBy('stores.id')
						->get();
						foreach($expense as $s){
							$op[$s->expdate][$s->user_id]['store_name']=$s->store_name;
							$op[$s->expdate][$s->user_id]['expense']=round($s->total_expense,2);
							$op[$s->expdate][$s->user_id]['name']=$s->name;
							$op[$s->expdate][$s->user_id]['user_id']=$s->user_id;
					}
						$output=array();
						foreach($op as  $key => $v){
				
					foreach($v as  $k => $value){
						
						$output[]=array("date"=>$key,"user_name"=>isset($value["name"])?$value["name"]:"","store_name"=>$value["store_name"],"user_fk"=>isset($value["user_id"])?$value["user_id"]:"","sales"=>isset($value["sales"])?strval(round($value["sales"],2)):"0","payement"=>isset($value["payment"])?strval(round($value["payment"],2)):"0","inventory"=>isset($value["inventory"])?strval(round($value["inventory"],2)):"0", "atm"=>isset($value["atm"])?strval(round($value["atm"],2)):"0", "cigarettes"=>isset($value["cigarettes"])?strval(round($value["cigarettes"],2)):"0", "lottery"=>isset($value["lottery"])?strval(round($value["lottery"],2)):"0", "expense"=>isset($value["expense"])?strval(round($value["expense"],2)):"0", "weekly_commission"=>isset($value["weekly_commission"])?strval(round($value["weekly_commission"],2)):"0", "weekly_eft_ammount"=>isset($value["weekly_eft_ammount"])?strval(round($value["weekly_eft_ammount"],2)):"0");
					}
				}
				usort($output, function($a, $b) {
				return  $b['date'] - $a['date'];
				});
					return response()->json(['status' =>'1', 'data'=>$output]);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}


	public function store_info2(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$date_from = $request->get('from');
			$date_to = $request->get('to');
			
			$user_id = $request->get('user_id');
			$user = User2::where(array("id"=>$user_id))->first();
			$store_access = $user['store_access'];
			//instant+lottery  lottery_cash+lottery_online_cashout+lottery_instant_cashout
			if(!empty($date_from) && !empty($date_to) && $id !=0){
				$sales = DB::table('daily_sales_entry')
						->select(DB::raw(
									"DATE_FORMAT(daily_sales_entry.date ,'%d-%m-%Y')as sdate,
									daily_sales_entry.user_id,users.name, instant+lottery as total_s,
									IFNULL(ROUND(sum(daily_sales_entry.total_sales),2),'0') as total_sales,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','daily_sales_entry.user_id')
						->leftJoin('stores','stores.id','=','daily_sales_entry.store_id')
						->where('stores.id', '=',$id)
						->where('daily_sales_entry.status', '=',1)
						->whereDate('daily_sales_entry.date', '>=',$date_from)
						->whereDate('daily_sales_entry.date', '<=',$date_to)
						->groupBy('daily_sales_entry.date')
						->groupBy('daily_sales_entry.user_id')
						->orderBy('daily_sales_entry.date','DESC')
						//->groupBy('stores.id')
						->get();
						$op=array();
						foreach($sales as $s){
							$op[$s->sdate][$s->user_id]['store_name']=$s->store_name;
							$op[$s->sdate][$s->user_id]['sales']=$s->total_sales;
							$op[$s->sdate][$s->user_id]['name']=$s->name;
							$op[$s->sdate][$s->user_id]['user_id']=$s->user_id;
							$op[$s->sdate][$s->user_id]['weekly_sales_total']=$s->total_s;
							//$op[$s->sdate][$s->user_id]['store_name']=$s->store_name;
						}
					$payment = DB::table('daily_payment_report')
						->select(DB::raw(
									"DATE_FORMAT(daily_payment_report.date ,'%d-%m-%Y')as pdate,
									lottery_online_cashout+lottery_instant_cashout as total_p,
									daily_payment_report.user_id,users.name,
									IFNULL(ROUND(sum(daily_payment_report.total),2),'0') as total_payment,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','daily_payment_report.user_id')
						->leftJoin('stores','stores.id','=','daily_payment_report.store_id')
						->where('stores.id', '=',$id)
						->where('daily_payment_report.status', '=',1)
						->whereDate('daily_payment_report.date', '>=',$date_from)
						->whereDate('daily_payment_report.date', '<=',$date_to)
						->groupBy('daily_payment_report.date')
						->groupBy('daily_payment_report.user_id')
						->orderBy('daily_payment_report.date','DESC')
						//->groupBy('stores.id')
						->get();
						foreach($payment as $s){
							$op[$s->pdate][$s->user_id]['store_name']=$s->store_name;
							$op[$s->pdate][$s->user_id]['payment']=$s->total_payment;
							$op[$s->pdate][$s->user_id]['name']=$s->name;
								$op[$s->pdate][$s->user_id]['user_id']=$s->user_id;
								$op[$s->pdate][$s->user_id]['weekly_payment_total']=$s->total_p;
					}
					$stores = Inventory2::select(DB::raw(
						"DATE_FORMAT(inventory.date ,'%d-%m-%Y')as sdate,
									
									users.name,
									inventory.user_id,
									IFNULL(ROUND(sum(inventory.price),2),'0') as price,
									stores.name as store_name
									"))
						
						->leftJoin('users','users.id','=','inventory.user_id')
						->leftJoin('stores','stores.id','=','inventory.store_id')
						->where('stores.id', '=',$id)
						->where('inventory.status', '=',1)
						->whereDate('inventory.date', '>=',$date_from)
						->whereDate('inventory.date', '<=',$date_to)
						->groupBy('inventory.date')
						->groupBy('inventory.user_id')
						->orderBy('inventory.date','DESC')
						//->groupBy('stores.id')
						->get();
						foreach($stores as $s){
							$op[$s->sdate][$s->user_id]['store_name']=$s->store_name;
							$op[$s->sdate][$s->user_id]['inventory']=$s->price;
							$op[$s->sdate][$s->user_id]['name']=$s->name;
							$op[$s->sdate][$s->user_id]['user_id']=$s->user_id;
						}
						
			$atm = DB::table('ATM')
						->select(DB::raw(
									"DATE_FORMAT(ATM.date ,'%d-%m-%Y')as atmdate,
									ATM.user_id,users.name,
									IFNULL(ROUND(sum(ATM.add_money),2),'0') as total_atm,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','ATM.user_id')
						->leftJoin('stores','stores.id','=','ATM.store_id')
						->where('stores.id', '=',$id)
						->where('ATM.status', '=',1)
						->whereDate('ATM.date', '>=',$date_from)
						->whereDate('ATM.date', '<=',$date_to)
						->groupBy('ATM.date')
						->groupBy('ATM.user_id')
						->orderBy('ATM.date','DESC')
						//->groupBy('stores.id')
						->get();
						foreach($atm as $s){
							$op[$s->atmdate][$s->user_id]['store_name']=$s->store_name;
							$op[$s->atmdate][$s->user_id]['atm']=round($s->total_atm,2);
							$op[$s->atmdate][$s->user_id]['name']=$s->name;
							$op[$s->atmdate][$s->user_id]['user_id']=$s->user_id;
					}
			$cigarettes = DB::table('cigarettes')
						->select(DB::raw(
									"DATE_FORMAT(cigarettes.date ,'%d-%m-%Y')as cigdate,
									cigarettes.user_id,users.name,
									IFNULL(ROUND(sum(cigarettes.available_stock),2),'0') as total_cigarettes,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','cigarettes.user_id')
						->leftJoin('stores','stores.id','=','cigarettes.store_id')
						->where('stores.id', '=',$id)
						->where('cigarettes.status', '=',1)
						->whereDate('cigarettes.date', '>=',$date_from)
						->whereDate('cigarettes.date', '<=',$date_to)
						->groupBy('cigarettes.date')
						->groupBy('cigarettes.user_id')
						->orderBy('cigarettes.date','DESC')
						//->groupBy('stores.id')
						->get();
						foreach($cigarettes as $s){
							$op[$s->cigdate][$s->user_id]['store_name']=$s->store_name;
							$op[$s->cigdate][$s->user_id]['cigarettes']=round($s->total_cigarettes,2);
							$op[$s->cigdate][$s->user_id]['name']=$s->name;
							$op[$s->cigdate][$s->user_id]['user_id']=$s->user_id;
					}
			$lottery = DB::table('lottery')
						->select(DB::raw(
									"DATE_FORMAT(lottery.date ,'%d-%m-%Y')as lotdate,
									lottery.user_id,users.name,
									IFNULL(ROUND(sum(lottery.total),2),'0') as total_lottery,
									IFNULL(ROUND(sum(lottery.weekly_commission),2),'0') as weekly_commission,
									IFNULL(ROUND(sum(lottery.weekly_eft_ammount),2),'0') as weekly_eft_ammount,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','lottery.user_id')
						->leftJoin('stores','stores.id','=','lottery.store_id')
						->where('stores.id', '=',$id)
						->where('lottery.status', '=',1)
						->whereDate('lottery.date', '>=',$date_from)
						->whereDate('lottery.date', '<=',$date_to)
						->groupBy('lottery.date')
						->groupBy('lottery.user_id')
						->orderBy('lottery.date','DESC')
						//->groupBy('stores.id')
						->get();
						foreach($lottery as $s){
							$op[$s->lotdate][$s->user_id]['store_name']=$s->store_name;
							$op[$s->lotdate][$s->user_id]['lottery']=round($s->total_lottery,2);
							$op[$s->lotdate][$s->user_id]['weekly_commission']=round($s->weekly_commission,2);
							$op[$s->lotdate][$s->user_id]['weekly_eft_ammount']=round($s->weekly_eft_ammount,2);
							$op[$s->lotdate][$s->user_id]['name']=$s->name;
							$op[$s->lotdate][$s->user_id]['user_id']=$s->user_id;
					}
			$expense = DB::table('expense')
						->select(DB::raw(
									"DATE_FORMAT(expense.date ,'%d-%m-%Y')as expdate,
									expense.user_id,users.name,
									IFNULL(ROUND(sum(expense.price),2),'0') as total_expense,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','expense.user_id')
						->leftJoin('stores','stores.id','=','expense.store_id')
						->where('stores.id', '=',$id)
						->where('expense.status', '=',1)
						->whereDate('expense.date', '>=',$date_from)
						->whereDate('expense.date', '<=',$date_to)
						->groupBy('expense.date')
						->groupBy('expense.user_id')
						->orderBy('expense.date','DESC')
						//->groupBy('stores.id')
						->get();
						foreach($expense as $s){
							$op[$s->expdate][$s->user_id]['store_name']=$s->store_name;
							$op[$s->expdate][$s->user_id]['expense']=round($s->total_expense,2);
							$op[$s->expdate][$s->user_id]['name']=$s->name;
							$op[$s->expdate][$s->user_id]['user_id']=$s->user_id;
					}
						$output=array();
						foreach($op as  $key => $v){
				
					foreach($v as  $k => $value){
						
						$output[]=array("date"=>$key,"user_name"=>isset($value["name"])?$value["name"]:"","store_name"=>$value["store_name"],"user_fk"=>isset($value["user_id"])?$value["user_id"]:"","sales"=>isset($value["sales"])?strval(round($value["sales"],2)):"0","payement"=>isset($value["payment"])?strval(round($value["payment"],2)):"0","inventory"=>isset($value["inventory"])?strval(round($value["inventory"],2)):"0", "atm"=>isset($value["atm"])?strval(round($value["atm"],2)):"0", "cigarettes"=>isset($value["cigarettes"])?strval(round($value["cigarettes"],2)):"0", "lottery"=>isset($value["lottery"])?strval(round($value["lottery"],2)):"0", "expense"=>isset($value["expense"])?strval(round($value["expense"],2)):"0", "weekly_commission"=>isset($value["weekly_commission"])?strval(round($value["weekly_commission"],2)):"0", "weekly_eft_ammount"=>isset($value["weekly_eft_ammount"])?strval(round($value["weekly_eft_ammount"],2)):"0", "weekly_lottery_cash"=>isset($value["weekly_eft_ammount"])?strval(round($value["weekly_sales_total"]-$value["weekly_payment_total"],2)):"0");
					}
				}
				usort($output, function($a, $b) {
				return  $b['date'] - $a['date'];
				});
					return response()->json(['status' =>'1', 'data'=>$output]);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}

	public function add_inventory(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if($user || true)
		{
			$validator = Validator::make($request->all(), [
            'date' => 'required',
			'invoice_no' => 'required',
			'item' => 'required',
			'vendor' => 'required',
			'quantity' => 'required',
			'price' => 'required',
			'total_price' => 'required',
			'store_id' => 'required',
			'user_id' => 'required',
        ]);
		if ($validator->fails()) {
				return response()->json(['status'=>'0', 'error_msg' =>'Please provide valid values for all fields!']);
			}else{
				$data1 = $request->all();
				$store = User2::where(array("id"=>$data1['user_id']))->first();
				$count1 = Stores2::where(array("id"=>$store['store_id']))->first();
				if($count1['status']==0){
					return response()->json(['status'=>'0', 'error_msg' =>'Sorry! Your store is no longer active!']);
				}
				$data1['store_id'] = $store['store_id'];
				$count = inventory2::whereDate("inventory.date", "=", $data1['date'])->where(array("user_id"=>$data1['user_id'],"status"=>"1"))->count();
				if(false){
					return response()->json(['status'=>'0', 'error_msg' =>'Inventory report already added for this date by this user!']);
				}else{
					$data1['total_price']=$data1['price'];
					$new_item = new Inventory2($data1);
				$new_item->save();
     	 		if($new_item) {
				return response()->json(['status'=>'1', 'data' =>'Inventory successfully added!']);
        	   }else {
        	   return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
        	   	}
				}
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function add_sales_report(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "staff")||true)
		{
			$validator = Validator::make($request->all(), [
			'date' => 'required',
            'inside' => 'required',
			'tax' => 'required',
			'gas' => 'required',
			'phone_card' => 'required',
			'instant' => 'required',
			'lottery' => 'required',
			'store_id' => 'required',
			'user_id' => 'required',
			'total_sales' => 'required',
        ]);
		if ($validator->fails()) {
			return response()->json(['status'=>'0', 'error_msg' =>'Provide valid values for all fields!']);
			}else{
				$data1 = $request->all();
				$store = User2::where(array("id"=>$data1['user_id']))->first();
				$count1 = Stores2::where(array("id"=>$store['store_id']))->first();
				if($count1['status']==0){
					return response()->json(['status'=>'0', 'error_msg' =>'Sorry! Your store is no longer active!']);
				}
				$data1['store_id'] = $store['store_id'];
				$count = Sales2::whereDate("daily_sales_entry.date", "=", $data1['date'])->where(array("user_id"=>$data1['user_id'],"status"=>"1"))->count();
				if($count>0){
					return  response()->json(['status'=>'0', 'error_msg' =>'Daily sales entry already added for this date by this user!']);
				}else{
					$new_item = new Sales2($data1);
				$new_item->save();
     	 		if($new_item) {
				return response()->json(['status'=>'1', 'data' =>'Daily sales entry successfully added!']);
        	   }else {
        	   return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
        	   	}
				}
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired']);
		}
	}
	public function add_payment_report(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "staff")||true)
		{
			$validator = Validator::make($request->all(), [
			'date' => 'required',
            'total_cash' => 'required',
			'total_credit' => 'required',
			'total_debit' => 'required',
			'total' => 'required',
			'lottery_out' => 'required',
			'store_id' => 'required',
			'user_id' => 'required',
			'lottery_online_cashout' => 'required',
			'lottery_instant_cashout' => 'required',
			'lottery_cash' => 'required',
	
        ]);
		if ($validator->fails()) {
			 //return $validator->errors()->all();
				return response()->json(['status'=>'0', 'error_msg' =>'Please provide valid values for all fields!']);
			}else{
				$data1 = $request->all();
				$store = User2::where(array("id"=>$data1['user_id']))->first();
				$count1 = Stores2::where(array("id"=>$store['store_id']))->first();
				if($count1['status']==0){
					return response()->json(['status'=>'0', 'error_msg' =>'Sorry! Your store is no longer active!']);
				}
				$data1['store_id'] = $store['store_id'];
				$count = Payments2::whereDate("daily_payment_report.date", "=", $data1['date'])->where(array("user_id"=>$data1['user_id'],"status"=>"1"))->count();
				if($count>0){
					return response()->json(['status'=>'0', 'error_msg' =>'Daily payment report already added for this date by this user!']);
				}else{
					$new_item = new Payments2($data1);
				$new_item->save();
     	 		if($new_item) {
				return response()->json(['status'=>'1', 'data' =>'Daily payment report successfully added!']);
        	   }else {
        	   return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
        	   	}
				}
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function get_vendor_items(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if($user || true)
		{
			$items = Items2::where(array("status"=>1))->get();
			$vendors = Vendors2::where(array("status"=>1))->get();
			return response()->json(['status'=>'1', 'items' =>$items, 'vendors'=>$vendors]);
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function all_daily_report(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if($user || true)
		{
			$day = $request->get('day');
			if(!empty($day)){
				$sales = Sales2::whereDate("daily_sales_entry.date", "=", date('Y-m-d',strtotime($day)))->get();
				$payments = Payments2::whereDate("daily_payment_report.date", "=", date('Y-m-d',strtotime($day)))->get();
				$inventory = inventory2::whereDate("inventory.date", "=", date('Y-m-d',strtotime($day)))->get();
				return response()->json(['status'=>'1', 'sales' =>$sales, 'payments'=>$payments, 'inventory'=>$inventory]);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Provide date to get reports!']);
			}
			
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function add_lottery(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "staff")||true)
		{
			$validator = Validator::make($request->all(), [
			'date' => 'required',
			'inventory' => 'required',
            'add_book' => 'required',
			'active_book' => 'required',
			'total' => 'required',
			'store_id' => 'required',
			'user_id' => 'required',
			'weekly_eft_ammount' => 'required',
			'weekly_commission' => 'required',
        ]);
		if ($validator->fails()) {
				return response()->json(['status'=>'0', 'error_msg' =>'Please provide valid values for all fields!']);
			}else{
				$data1 = $request->all();
				$store = User2::where(array("id"=>$data1['user_id']))->first();
				$count1 = Stores2::where(array("id"=>$store['store_id']))->first();
				if($count1['status']==0){
					return response()->json(['status'=>'0', 'error_msg' =>'Sorry! Your store is no longer active!']);
				}
				$data1['store_id'] = $store['store_id'];
				$count = Lottery2::whereDate("lottery.date", "=", $data1['date'])->where(array("user_id"=>$data1['user_id'],"status"=>"1"))->count();
				if($count>0){
					return response()->json(['status'=>'0', 'error_msg' =>'Lottery entry already added for this date by this user!']);
				}else{
					$new_item = new Lottery2($data1);
				$new_item->save();
     	 		if($new_item) {
				return response()->json(['status'=>'1', 'data' =>'Lottery details successfully added!']);
        	   }else {
        	   return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
        	   	}
				}
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function add_expense(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "staff")||true)
		{
			$validator = Validator::make($request->all(), [
			'item_id' => 'required',
			'title' => 'required',
            'price' => 'required',
			'store_id' => 'required',
			'user_id' => 'required',
			'date' => 'required'
        ]);
		if ($validator->fails()) {
				return response()->json(['status'=>'0', 'error_msg' =>'Please provide valid values for all fields!']);
			}else{
					$data1 = $request->all();
					$store = User2::where(array("id"=>$data1['user_id']))->first();
					$count1 = Stores2::where(array("id"=>$store['store_id']))->first();
					if($count1['status']==0){
						return response()->json(['status'=>'0', 'error_msg' =>'Sorry! Your store is no longer active!']);
					}
					$data1['store_id'] = $store['store_id'];
					$new_item = new Expense2($data1);
				$new_item->save();
     	 		if($new_item) {
				return response()->json(['status'=>'1', 'data' =>'Expense details successfully added!']);
        	   }else {
        	   return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
        	   	}
				
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}

	}
	public function send_message(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "staff")||true)
		{
			$validator = Validator::make($request->all(), [
			'subject' => 'required',
            'description' => 'required',
			'user_id' => 'required',
			'date' => 'required'
        ]);
		if ($validator->fails()) {
				return response()->json(['status'=>'0', 'error_msg' =>'Please provide valid values for all fields!']);
			}else{
				$data1 = $request->all();		
				$data2['subject'] = $data1['subject'];
				$data2['description'] = $data1['description'];
				$data2['username'] = $data1['username'];
				$data2['email'] = "aaryagroupofcompany2016@gmail.com";
				Mail::send('admin.message_email',$data2, function($message) use ($data2){
								$message->from('support@aaryagroup.com', 'Aarya Group');
								$message->to($data2['email']);
								$message->subject('New message');
				});
				$data2['email'] = "rajan@virtualheight.com";
				Mail::send('admin.message_email',$data2, function($message) use ($data2){
								$message->from('support@aaryagroup.com', 'Aarya Group');
								$message->to($data2['email']);
								$message->subject('New message');
				});
				
				if( count(Mail::failures()) > 0 ) {
					echo "There was one or more failures. They were: <br />";
					print_R(Mail::failures()); die();
				}
     	 		$new_item = new Messages2($data1);
				$new_item->save();
     	 		if($new_item) {
				return response()->json(['status'=>'1', 'data' =>'Message successfully sent to admin!']);
        	   }else {
        	   return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
        	   	}
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function get_expense_items(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "staff")||true)
		{
			$expenseitems = ExpenseItems2::where(array("status"=>1))->get();
			return response()->json(['status' =>'1', 'expenseitems'=>$expenseitems]);
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function get_config(Request $request, JWTAuth $JWTAuth)
	{
		return response()->json(['compulsary'=>'1', 'androidversion' =>'15', 'message'=>'A new version of this app is available. Update your app now to continue using this app.']);
	}
	public function admin_sales_edit(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$data = $request->all();
			$update = Sales2::where("id","=",$id)->update($data);
			if($update){
				return response()->json(['status' =>'1', 'data'=>'Sales report successfully updated!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Unknown error occured while updating sales report. please try again!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function admin_payment_edit(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$data = $request->all();
			$update = Payments2::where("id","=",$id)->update($data);
			if($update){
				return response()->json(['status' =>'1', 'data'=>'Payment report successfully updated!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Unknown error occured while updating payment report. please try again!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function admin_inventory_edit(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$data = $request->all();
			$data2=array();
			$data2['date']=$data['date'];
			$data2['invoice_no']=$data['invoice_no'];
			$data2['item']=$data['item'];
			$data2['vendor']=$data['vendor'];
			$data2['quantity']=$data['quantity'];
			$data2['price']=$data['price'];
			$data2['total_price']=$data['price'];
			$update = inventory2::where("id","=",$id)->update($data2);
			if($update){
				return response()->json(['status' =>'1', 'data'=>'Inventory report successfully updated!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Unknown error occured while updating inventory report. please try again!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function admin_atm_edit(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$data = $request->all();
			$update = ATM2::where("id","=",$id)->update($data);
			if($update){
				return response()->json(['status' =>'1', 'data'=>'ATM report successfully updated!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Unknown error occured while updating ATM report. please try again!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function admin_cigarette_edit(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$data = $request->all();
			$update = Cigarettes2::where("id","=",$id)->update($data);
			if($update){
				return response()->json(['status' =>'1', 'data'=>'Cigarettes report successfully updated!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Unknown error occured while updating Cigarettes report. please try again!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function admin_lottery_edit(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$data = $request->all();
			$update = Lottery2::where("id","=",$id)->update($data);
			if($update){
				return response()->json(['status' =>'1', 'data'=>'Lottery report successfully updated!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Unknown error occured while updating Lottery report. please try again!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function admin_expense_edit(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$data = $request->all();
			$update = Expense2::where("id","=",$id)->update($data);
			if($update){
				return response()->json(['status' =>'1', 'data'=>'Expense report successfully updated!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Unknown error occured while updating Expense report. please try again!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function admin_sales_delete(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$update = Sales2::where("id","=",$id)->update(array("status"=>0));
			if($update){
				return response()->json(['status' =>'1', 'data'=>'Sales report successfully deleted!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Unknown error occured while deleting sales report. please try again!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function admin_payment_delete(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$update = Payments2::where("id","=",$id)->update(array("status"=>0));
			if($update){
				return response()->json(['status' =>'1', 'data'=>'Payment report successfully deleted!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Unknown error occured while deleting payment report. please try again!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function admin_inventory_delete(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$update = inventory2::where("id","=",$id)->update(array("status"=>0));
			if($update){
				return response()->json(['status' =>'1', 'data'=>'Inventory report successfully deleted!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Unknown error occured while deleting inventory report. please try again!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function admin_atm_delete(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$update = ATM2::where("id","=",$id)->update(array("status"=>0));
			if($update){
				return response()->json(['status' =>'1', 'data'=>'ATM report successfully deleted!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Unknown error occured while deleting ATM report. please try again!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function admin_cigarette_delete(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$update = Cigarettes2::where("id","=",$id)->update(array("status"=>0));
			if($update){
				return response()->json(['status' =>'1', 'data'=>'Cigarettes report successfully deleted!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Unknown error occured while deleting Cigarettes report. please try again!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function admin_lottery_delete(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$update = Lottery2::where("id","=",$id)->update(array("status"=>0));
			if($update){
				return response()->json(['status' =>'1', 'data'=>'Lottery report successfully deleted!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Unknown error occured while deleting Lottery report. please try again!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function admin_expense_delete(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			$update = Expense2::where("id","=",$id)->update(array("status"=>0));
			if($update){
				return response()->json(['status' =>'1', 'data'=>'Expense report successfully deleted!']);
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Unknown error occured while deleting Expense report. please try again!']);
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function store_list_new(Request $request, JWTAuth $JWTAuth)
    {
        $user = $JWTAuth->parseToken()->authenticate();
        if(($user && $user->role == "admin")||true)
        {
            $store_data = DB::table('stores')
                ->select(DB::raw(
                    'stores.*,
									(SELECT IFNULL(SUM(total),"0")  FROM daily_payment_report WHERE daily_payment_report.store_id=stores.id) AS payments,
									(SELECT IFNULL(SUM(total_sales),"0")  FROM daily_sales_entry WHERE daily_sales_entry.store_id=stores.id) AS sales,
									(SELECT IFNULL(SUM(total_price),"0")  FROM inventory WHERE inventory.store_id=stores.id) AS inventory
									'))
                ->leftJoin('daily_payment_report', 'daily_payment_report.store_id', '=', 'stores.id')
                ->leftJoin('daily_sales_entry', 'daily_sales_entry.store_id', '=', 'stores.id')
                ->leftJoin('inventory', 'inventory.store_id', '=', 'stores.id')
                ->where('stores.status','=','1')
                ->groupBy('stores.id')
                ->get();
            return response()->json(['status' =>'1', 'Store_list'=>$store_data]);
        }
        else
        {
            return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
        }
    }
	public function del_user(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$id = $request->get('id');
			if(!empty($id)){
				$user = User2::where(array("id"=>$id))->first();
				if(!empty($user)){
						$block = User2::where("id","=",$id)->first();
						//$credentials = array("email"=>$block['email'], "password"=>$block['password']);
						//$token = $JWTAuth->attempt($credentials);
						//$JWTAuth->invalidate($token);
						$up = array("status"=>2, "deleted_by"=>$request->get('user_id'));
						$update = User2::where(array("id"=>$id))->update($up);
						return response()->json(['status'=>'1', 'data' =>'User successfully deleted!']);
					
				}else{
					return response()->json(['status'=>'0', 'error_msg' =>'No user found with this id!']);
				}
			}else{
				return response()->json(['status'=>'0', 'error_msg' =>'Provide id of the user!']);
			}
			
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function add_atm(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "staff")||true)
		{
			$validator = Validator::make($request->all(), [
			'date' => 'required',
            'money' => 'required',
			'add_money' => 'required',
			'total_money' => 'required',
			'store_id' => 'required',
			'user_id' => 'required',
        ]);
		if ($validator->fails()) {
			return response()->json(['status'=>'0', 'error_msg' =>'Provide valid values for all fields!']);
				return response()->json(['status'=>'0', 'error_msg' =>'Please provide valid values for all fields!']);
			}else{
				$data1 = $request->all();
				$store = User2::where(array("id"=>$data1['user_id']))->first();
				$count1 = Stores2::where(array("id"=>$store['store_id']))->first();
				if($count1['status']==0){
					return response()->json(['status'=>'0', 'error_msg' =>'Sorry! Your store is no longer active!']);
				}
				$data1['store_id'] = $store['store_id'];
				$count = ATM2::whereDate("ATM.date", "=", $data1['date'])->where(array("user_id"=>$data1['user_id'],"status"=>"1"))->count();
				if($count>0){
					return  response()->json(['status'=>'0', 'error_msg' =>'ATM entry already added for this date by this user!']);
				}else{
				//$store = User2::where(array("id"=>$data1['user_id']))->first();
				//$data1['store_id'] = $store['store_id'];
				$new_item = new ATM2($data1);
				$new_item->save();
     	 		if($new_item) {
				return response()->json(['status'=>'1', 'data' =>'ATM entry successfully added!']);
        	   }else {
        	   return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
        	   	}
				}
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
		
	}
	public function add_cigarette(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "staff")||true)
		{
			$validator = Validator::make($request->all(), [
			'date' => 'required',
            'inventory' => 'required',
			'add_cigarette' => 'required',
			'sale_cigarette' => 'required',
			'available_stock' => 'required',
			'store_id' => 'required',
			'user_id' => 'required',
        ]);
		if ($validator->fails()) {
			return response()->json(['status'=>'0', 'error_msg' =>'Provide valid values for all fields!']);
				return response()->json(['status'=>'0', 'error_msg' =>'Please provide valid values for all fields!']);
			}else{
				
				$data1 = $request->all();
				$store = User2::where(array("id"=>$data1['user_id']))->first();
				$count1 = Stores2::where(array("id"=>$store['store_id']))->first();
				if($count1['status']==0){
					return response()->json(['status'=>'0', 'error_msg' =>'Sorry! Your store is no longer active!']);
				}
				$data1['store_id'] = $store['store_id'];
				$count = Cigarettes2::whereDate("cigarettes.date", "=", $data1['date'])->where(array("user_id"=>$data1['user_id'],"status"=>"1"))->count();
				if($count>0){
					return  response()->json(['status'=>'0', 'error_msg' =>'Daily cigarette entry already added for this date by this user!']);
				}else{
					$new_item = new Cigarettes2($data1);
				$new_item->save();
     	 		if($new_item) {
				return response()->json(['status'=>'1', 'data' =>'Cigarette entry successfully added!']);
        	   }else {
        	   return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
        	   	}
				}
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
		
	}
	public function get_all_messages(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if($user || true)
		{
			$item_data = Messages2::where(array("status"=>1))->get();
			return response()->json(['status' =>'1', 'messages'=>$item_data]);
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!']);
		}
	}
	public function get_stores(Request $request, JWTAuth $JWTAuth)
	{
		$stores = Stores2::where(array("status"=>1))->get();
		return response()->json(['status' =>'1', 'stores'=>$stores]);
	}
	public function signup(Request $request, JWTAuth $JWTAuth)
	{
			$exist = User2::where("email",'=',$request->get('email'))->count();
			if($exist>0){
				return response()->json(['status'=>'0', 'error_msg' =>'Email already exists in database!']);
			}else{
			$original_pass = $request->get('password');
			$password = Hash::make($request->get('password'));
			$data1=array(
				"email"=>$request->get('email'),
     	 		"name"=>$request->get('name'),
				"password"=>$password,
				"role"=>$request->get('role'),
				"mobile"=>$request->get('mobile'),
				"store_id"=>$request->get('store_id'),
     	 		);
     	 		$new_user = new User2($data1);
				$new_user->save();
     	 		if($new_user) {
					
					$data1['email'] = $request->get('email');
					$data1['name'] = $request->get('name');
					$data1['password'] = $original_pass;
					Mail::send('admin.account_password_email',$data1, function($message) use ($data1){
								$message->from('support@aaryagroup.com', 'Aarya Group');
								$message->to($data1['email']);
								$message->subject('Account Password');
					});
				return response()->json(['status'=>'1', 'data' =>'You have succesfully registered!']);
        	   }else {
        	   return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!']);
        	   	}
		}
	}
	public function add_sales_payment(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "staff")||true)
		{
			$validator = Validator::make($request->all(), [
			'date' => 'required',
            'inside' => 'required',
			'tax' => 'required',
			'gas' => 'required',
			'phone_card' => 'required',
			'instant' => 'required',
			'lottery' => 'required',
			'store_id' => 'required',
			'user_id' => 'required',
			'total_sales' => 'required',
            'total_cash' => 'required',
			'total_credit' => 'required',
			'total_debit' => 'required',
			'total' => 'required',
			'lottery_out' => 'required',
			'lottery_online_cashout' => 'required',
			'lottery_instant_cashout' => 'required',
			'lottery_cash' => 'required',
	
        ]);
		if ($validator->fails()) {
			 //return $validator->errors()->all();
				return response()->json(['status'=>'0', 'error_msg' =>json_encode($validator->errors()->all()),'data'=>'']);
			}else{
				$data1 = $request->all();
				$store = User2::where(array("id"=>$data1['user_id']))->first();
				$count1 = Stores2::where(array("id"=>$store['store_id']))->first();
				if($count1['status']==0){
					return response()->json(['status'=>'0', 'error_msg' =>'Sorry! Your store is no longer active!']);
				}
				$data1['store_id'] = $store['store_id'];
				
				$count1 = Payments2::whereDate("daily_payment_report.date", "=", $data1['date'])->where(array("user_id"=>$data1['user_id'],"status"=>"1"))->count();
				$count2 = Sales2::whereDate("daily_sales_entry.date", "=", $data1['date'])->where(array("user_id"=>$data1['user_id'],"status"=>"1"))->count();
				if($count1>0 && $count2>0){
					return response()->json(['status'=>'0', 'error_msg' =>'Daily payment report and daily sales report are already added for this date by this user!','data'=>'']);
				}else if($count1>0){
					return response()->json(['status'=>'0', 'error_msg' =>'Daily payment report already added for this date by this user!','data'=>'']);
				}else if($count2>0){
					return response()->json(['status'=>'0', 'error_msg' =>'Daily sales report already added for this date by this user!','data'=>'']);
				}
				else{
					
				$sales_data = array(
				"date"=>$data1['date'],
				"inside"=>$data1['inside'],
				"tax"=>$data1['tax'],
				"gas"=>$data1['gas'],
				"phone_card"=>$data1['phone_card'],
				"instant"=>$data1['instant'],
				"lottery"=>$data1['lottery'],
				"store_id"=>$data1['store_id'],
				"user_id"=>$data1['user_id'],
				"total_sales"=>$data1['total_sales'],
				);
				
				$payment_data = array(
				"date"=>$data1['date'],
				"total_cash"=>$data1['total_cash'],
				"total_credit"=>$data1['total_credit'],
				"total_debit"=>$data1['total_debit'],
				"total"=>$data1['total'],
				"lottery_out"=>$data1['lottery_out'],
				"store_id"=>$data1['store_id'],
				"user_id"=>$data1['user_id'],
				"lottery_online_cashout"=>$data1['lottery_online_cashout'],
				"lottery_instant_cashout"=>$data1['lottery_instant_cashout'],
				"lottery_cash"=>$data1['lottery_cash'],
				);
				$sales_save = new Sales2($sales_data);
				$payment_save = new Payments2($payment_data);
				$sales_save->save();
				$payment_save->save();
     	 		if($sales_save && $payment_save) {
				return response()->json(['status'=>'1', 'data' =>'Daily payment report and sales report successfully added!', 'error_msg'=>'']);
        	   }else {
        	   return response()->json(['status'=>'0', 'error_msg' =>'Some error occured!','data'=>'']);
        	   	}
				}
			}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!','data'=>'']);
		}
	}
	public function inventory_filter(Request $request, JWTAuth $JWTAuth)
	{
		$item_id = $request->get('id');
		$store_id = $request->get('store_id');
		$date_from = $request->get('from');
		$date_to = $request->get('to');
		//echo $item_id; die();
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			if($item_id != 0 && $store_id != 0){
				$inventory = DB::table('inventory')
						->select(DB::raw(
						"DATE_FORMAT(inventory.date ,'%d-%m-%Y')as sdate,
									inventory.id as inventory_id,
									inventory.invoice_no,
									inventory.item,
									inventory.vendor,
									inventory.quantity,
									ROUND(inventory.price,2) as price,
									
									items.name as item_name,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','inventory.user_id')
						->leftJoin('items','items.id','=','inventory.item')
						->leftJoin('stores','stores.id','=','inventory.store_id')
						->where('inventory.item', '=',$item_id)
						->where('stores.id', '=',$store_id)
						->where('stores.status', '=',1)
						->where('inventory.status', '=',1)
						->whereDate('inventory.date', '>=',$date_from)
						->whereDate('inventory.date', '<=',$date_to)
						->get();
			return response()->json(['status'=>'1', 'error_msg' =>'','data'=>$inventory]);
			}else if($item_id == 0 && $store_id == 0){
				$inventory = DB::table('inventory')
						->select(DB::raw(
						"DATE_FORMAT(inventory.date ,'%d-%m-%Y')as sdate,
									inventory.id as inventory_id,
									inventory.invoice_no,
									inventory.item,
									inventory.vendor,
									inventory.quantity,
									ROUND(inventory.price,2) as price,
									
									items.name as item_name,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','inventory.user_id')
						->leftJoin('items','items.id','=','inventory.item')
						->leftJoin('stores','stores.id','=','inventory.store_id')
						->where('inventory.status', '=',1)
						->where('stores.status', '=',1)
						->whereDate('inventory.date', '>=',$date_from)
						->whereDate('inventory.date', '<=',$date_to)
						->get();
			return response()->json(['status'=>'1', 'error_msg' =>'','data'=>$inventory]);
			
		}else if($item_id == 0 && $store_id != 0){
				$inventory = DB::table('inventory')
						->select(DB::raw(
						"DATE_FORMAT(inventory.date ,'%d-%m-%Y')as sdate,
									inventory.id as inventory_id,
									inventory.invoice_no,
									inventory.item,
									inventory.vendor,
									inventory.quantity,
									ROUND(inventory.price,2) as price,
									
									items.name as item_name,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','inventory.user_id')
						->leftJoin('items','items.id','=','inventory.item')
						->leftJoin('stores','stores.id','=','inventory.store_id')
						->where('stores.id', '=',$store_id)
						->where('inventory.status', '=',1)
						->where('stores.status', '=',1)
						->whereDate('inventory.date', '>=',$date_from)
						->whereDate('inventory.date', '<=',$date_to)
						->get();
			return response()->json(['status'=>'1', 'error_msg' =>'','data'=>$inventory]);
			
		}else if($item_id != 0 && $store_id == 0){
				$inventory = DB::table('inventory')
						->select(DB::raw(
						"DATE_FORMAT(inventory.date ,'%d-%m-%Y')as sdate,
									inventory.id as inventory_id,
									inventory.invoice_no,
									inventory.item,
									inventory.vendor,
									inventory.quantity,
									ROUND(inventory.price,2) as price,
									
									items.name as item_name,
									stores.name as store_name
									"))
						->leftJoin('users','users.id','=','inventory.user_id')
						->leftJoin('items','items.id','=','inventory.item')
						->leftJoin('stores','stores.id','=','inventory.store_id')
						->where('inventory.item', '=',$item_id)
						->where('inventory.status', '=',1)
						->where('stores.status', '=',1)
						->whereDate('inventory.date', '>=',$date_from)
						->whereDate('inventory.date', '<=',$date_to)
						->get();
			return response()->json(['status'=>'1', 'error_msg' =>'','data'=>$inventory]);
			
		}
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!','data'=>'']);
		}
	}
	public function del_multiple(Request $request, JWTAuth $JWTAuth)
	{
		$user = $JWTAuth->parseToken()->authenticate();
		if(($user && $user->role == "admin")||true)
		{
			$sales_id = $request->get('sales_id');
			$payment_id = $request->get('payment_id');
			$atm_id = $request->get('atm_id');
			$cigarette_id = $request->get('cigarette_id');
			$lottery_id = $request->get('lottery_id');
			
			$expense_id = $request->get('expense_id');
			$inventory_id = $request->get('inventory_id');
			
			$del_sales = Sales2::where(array("id"=>$sales_id))->update(array("status"=>0));
			$del_payment = Payments2::where(array("id"=>$payment_id))->update(array("status"=>0));
			$del_atm = ATM2::where(array("id"=>$atm_id))->update(array("status"=>0));
			$del_cigarette = Cigarettes2::where(array("id"=>$cigarette_id))->update(array("status"=>0));
			$del_lottery = Lottery2::where(array("id"=>$lottery_id))->update(array("status"=>0));
			
			if(!empty($expense_id)){
				$del_expense = Expense2::whereRaw('expense.id IN('.$expense_id.')')->update(array("status"=>0));
			}
			if(!empty($inventory_id)){
				$del_inventory = inventory2::whereRaw('inventory.id IN('.$inventory_id.')')->update(array("status"=>0));
			}
			
			return response()->json(['status'=>'1', 'error_msg' =>'', 'data'=>'Marked entries succesfully deleted!']);
		}
		else
		{
			return response()->json(['status'=>'0', 'error_msg' =>'Token is invalid or expired!', 'data'=>'']);
		}
	}
}