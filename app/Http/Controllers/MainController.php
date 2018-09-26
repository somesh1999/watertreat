<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\agriculturesubmenu;
use App\agriculturecrop;
use App\cultivationmethod;
use App\landdetail;
use App\product;
use App\waterdetail;
use App\season;
use App\maindetail;

class MainController extends Controller
{


	public function main(){
		if(session('sessionId')){
			return redirect("dashboard");
        }
        else{
			return view('login');
		}
	}



    
    public function login(Request $request){
    try{

    	$email=$request->input('userid');
    	$password=$request->input('password');

    	$checkemail=DB::table("users")->where(["email"=>$email])->get();
    	$checkuser=DB::table("users")->where(["email"=>$email,"password"=>$password])->get();
    	if(count($checkemail)<1){

    		echo "Email does not exits";
    	}
    	else if(count($checkuser)>0){
    		echo "Redirecting to dashboard";
    		$request->session()->push('sessionId', $email);
    	}
    	else{
    		echo "You have entered wrong password";	
    	}

    }
    catch(Exception $e){
    	echo "Something went wrong. Please try again";
    }	


    }

    public function dashboard(){
    	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
            return view('index')->with('name',$name);

    }
    else{
    	return redirect('/');
    }
}



public function logout(Request $request){

	$request->session()->flush();
   return redirect('/')->with('response','Successfully logged out');
}


public function admin_new_sub_menu(){
	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
	return view('admin_new_sub_menu')->with('name',$name);
	}
	else{
	return redirect('/');	
	}


}

public function sendsubagriculture(Request $request){

	try{

		$name=$request->input('name');
		if($request->hasFile('image_sub')){
    						$filenamewithext=$request->file('image_sub')->getClientOriginalName();
    						$filename=pathinfo($filenamewithext, PATHINFO_FILENAME);
    						$extension =$request->file('image_sub')->getClientOriginalExtension();
    						//$fileNametoabstract= $filename.'_'.time().'.'.$extension;
                            $fileNametoabstract= time().'.'.$extension;
    						$path=$request->file('image_sub')->storeAs('assets/others',$fileNametoabstract);
                            $path1="http://ec2-13-126-162-143.ap-south-1.compute.amazonaws.com/watertreatment/storage/app/assets/others/".$fileNametoabstract;
                            $encoded_data1=base64_encode(file_get_contents($path1));                            
    						}else{
    						$fileNametoabstract='nofile';
    						}
		$agriculturesubmenu=new agriculturesubmenu;
		$agriculturesubmenu->name=$name;
        $agriculturesubmenu->image=$encoded_data1;
		$agriculturesubmenu->imageweb=$fileNametoabstract;
		$agriculturesubmenu->type="Agriculture";
        $agriculturesubmenu->status="Active";
		$agriculturesubmenu->save();
		echo "Data Saved Successfully";

	}
	catch(Exception $e){
		echo "Something went wrong";
	}
}


public function admin_new_sub_menu_view(){

	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $checkmenu=DB::table('agriculturesubmenus')->orderBy('id', 'DESC')->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
            

	return view('admin_new_sub_menu_view')->with('name',$name)->with('dataview',$checkmenu);
	}
	else{
	return redirect('/');	
	}

}

public function admin_new_sub_menu_view_edit(){
	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
            $id=$_GET['id'];
            $checkid=DB::table('agriculturesubmenus')->where(["id"=>$id])->get();
            foreach($checkid as $i){
            	$nameedit=$i->name;  
            	$image=$i->imageweb;
                $status=$i->status;          	
            }
            

	return view('admin_new_sub_menu_view_edit')->with('name',$name)->with('nameedit',$nameedit)->with('editid',$id)->with('image',$image)->with('status',$status);
	}
	else{
	return redirect('/');	
	}
}


public function sendsubagricultureedit(Request $request){

	try{

		$name=$request->input('name');
        $status=$request->input('status');
		$editid=$request->input('id');
		if($request->hasFile('image_sub')){
    						$filenamewithext=$request->file('image_sub')->getClientOriginalName();
    						$filename=pathinfo($filenamewithext, PATHINFO_FILENAME);
    						$extension =$request->file('image_sub')->getClientOriginalExtension();
    						//$fileNametoabstract= $filename.'_'.time().'.'.$extension;
                            $fileNametoabstract= time().'.'.$extension;
    						$path=$request->file('image_sub')->storeAs('assets/others',$fileNametoabstract);
                           $path1="http://ec2-13-126-162-143.ap-south-1.compute.amazonaws.com/watertreatment/storage/app/assets/others/".$fileNametoabstract;
                            $encoded_data1=base64_encode(file_get_contents($path1));
    						
							DB::update('update agriculturesubmenus set name= ? ,imageweb=? ,image=? ,status=? where id=?',[$name,$fileNametoabstract,$encoded_data1,$status,$editid]);
		}
		else{
							DB::update('update agriculturesubmenus set name= ? ,status=? where id=?',[$name,$status,$editid]);

		}
		echo "Data Updated Successfully. Please refresh the page to see the changes";


	}
	catch(Exception $e){
		echo "Something went wrong";
	}
}

public function admin_new_crop_type(){
	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $topic=DB::table('agriculturesubmenus')->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
	return view('admin_new_crop_type')->with('name',$name)->with('topic',$topic);
	}
	else{
	return redirect('/');	
	}
}

public function sendagriculture(Request $request){

	try{
		$topic=$request->input('topic');
		$name=$request->input('name');
		if($request->hasFile('image_sub')){
    						$filenamewithext=$request->file('image_sub')->getClientOriginalName();
    						$filename=pathinfo($filenamewithext, PATHINFO_FILENAME);
    						$extension =$request->file('image_sub')->getClientOriginalExtension();
    						$fileNametoabstract= $filename.'_'.time().'.'.$extension;
    						$path=$request->file('image_sub')->storeAs('assets/others',$fileNametoabstract);
    						}else{
    						$fileNametoabstract='nofile';
    						}
		$agriculturesubmenu=new agriculturecrop;
		$agriculturesubmenu->topic=$topic;
		$agriculturesubmenu->name=$name;
		$agriculturesubmenu->image=$fileNametoabstract;
        $agriculturesubmenu->status="Active";
		$agriculturesubmenu->save();
		echo "Data Saved Successfully";

	}
	catch(Exception $e){
		echo "Something went wrong";
	}
}


public function admin_new_crop_type_view(){
	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $checkmenu=DB::table('agriculturecrops')->orderBy('id', 'DESC')->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
            

	return view('admin_new_crop_type_view')->with('name',$name)->with('dataview',$checkmenu);
	}
	else{
	return redirect('/');	
	}
}

public function admin_new_crop_type_view_edit(){

	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
            $id=$_GET['id'];
            $checkid=DB::table('agriculturecrops')->where(["id"=>$id])->get();
            foreach($checkid as $i){
            	$nameedit=$i->name;
            	$topicedit=$i->topic;  
            	$image=$i->image;  
                $status=$i->status;        	
            }
            $topic=DB::table('agriculturesubmenus')->where('name','<>',$topicedit)->get();

	return view('admin_new_crop_type_view_edit')->with('name',$name)->with('nameedit',$nameedit)->with('editid',$id)->with('topic',$topic)->with('topicedit',$topicedit)->with('image',$image)->with('status',$status);
	}
	else{
	return redirect('/');	
	}

}


public function sendagricultureedit(Request $request){
	
		try{

		$name=$request->input('name');
        $status=$request->input('status');
		$topic=$request->input('topic');
		$editid=$request->input('id');
		if($request->hasFile('image_sub')){
    						$filenamewithext=$request->file('image_sub')->getClientOriginalName();
    						$filename=pathinfo($filenamewithext, PATHINFO_FILENAME);
    						$extension =$request->file('image_sub')->getClientOriginalExtension();
    						$fileNametoabstract= $filename.'_'.time().'.'.$extension;
    						$path=$request->file('image_sub')->storeAs('assets/others',$fileNametoabstract);
    						
							DB::update('update agriculturecrops set name= ? ,image=?, status=?, topic=? where id=?',[$name,$fileNametoabstract,$status,$topic,$editid]);
		}
		else{
							DB::update('update agriculturecrops set name= ? ,topic=? ,status=? where id=?',[$name,$topic,$status,$editid]);

		}
		echo "Data Updated Successfully. Please refresh the page to see the changes";


	}
	catch(Exception $e){
		echo "Something went wrong";
	}

}


public function admin_new_method_cultivation_detail(){
	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $topic=DB::table('agriculturecrops')->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
	return view('admin_new_method_cultivation_detail')->with('name',$name)->with('topic',$topic);
	}
	else{
	return redirect('/');	
	}
}

public function methodofcultivation(Request $request){

	try{
		$topic=$request->input('topic');
		$name=$request->input('name');		
		$agriculturesubmenu=new cultivationmethod;
		$agriculturesubmenu->topic=$topic;
		$agriculturesubmenu->name=$name;
        $agriculturesubmenu->status="Active";
		$agriculturesubmenu->save();
		echo "Data Saved Successfully";

	}
	catch(Exception $e){
		echo "Something went wrong";
	}
}


public function admin_new_method_cultivation_detail_view(){

	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $checkmenu=DB::table('cultivationmethods')->orderBy('id', 'DESC')->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
            

	return view('admin_new_method_cultivation_detail_view')->with('name',$name)->with('dataview',$checkmenu);
	}
	else{
	return redirect('/');	
	}

}

public function admin_new_method_cultivation_detail_view_edit(){
	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
            $id=$_GET['id'];
            $checkid=DB::table('cultivationmethods')->where(["id"=>$id])->get();
            foreach($checkid as $i){
            	$nameedit=$i->name;
            	$topicedit=$i->topic; 
                $status=$i->status;           	
            }
            $topic=DB::table('agriculturecrops')->where('name','<>',$topicedit)->get();

	return view('admin_new_method_cultivation_detail_view_edit')->with('name',$name)->with('nameedit',$nameedit)->with('editid',$id)->with('topic',$topic)->with('topicedit',$topicedit)->with('status',$status);
	}
	else{
	return redirect('/');	
	}
}

public function methodofcultivationedit(Request $request){
	
		try{

		$name=$request->input('name');
		$topic=$request->input('topic');
        $status=$request->input('status');
		$editid=$request->input('id');
		
		DB::update('update cultivationmethods set name= ? ,topic=? ,status=? where id=?',[$name,$topic,$status,$editid]);

	
		echo "Data Updated Successfully. Please refresh the page to see the changes";


	}
	catch(Exception $e){
		echo "Something went wrong";
	}

}

public function admin_new_land_detail(){
if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $topic=DB::table('agriculturecrops')->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
	return view('admin_new_land_detail')->with('name',$name)->with('topic',$topic);
	}
	else{
	return redirect('/');	
	}

}

public function landdetails(Request $request){

	try{
		$name=$request->input('name');		
		$agriculturesubmenu=new landdetail;
		$agriculturesubmenu->name=$name;
        $agriculturesubmenu->status="Active";
		$agriculturesubmenu->save();
		echo "Data Saved Successfully";

	}
	catch(Exception $e){
		echo "Something went wrong";
	}

}

public function admin_new_land_detail_view(){

	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $checkmenu=DB::table('landdetails')->orderBy('id', 'DESC')->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
            

	return view('admin_new_land_detail_view')->with('name',$name)->with('dataview',$checkmenu);
	}
	else{
	return redirect('/');	
	}
}

public function admin_new_land_detail_view_edit(Request $request){
	
	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
            $id=$_GET['id'];
            $checkid=DB::table('landdetails')->where(["id"=>$id])->get();
            foreach($checkid as $i){
            	$nameedit=$i->name;  
                $status=$i->status;          	
            }
            

	return view('admin_new_land_detail_view_edit')->with('name',$name)->with('nameedit',$nameedit)->with('editid',$id)->with('status',$status);
	}
	else{
	return redirect('/');	
	}	

}


public function landdetailedit(Request $request){
	try{

		$name=$request->input('name');
		$editid=$request->input('id');
        $status=$request->input('status');
		
		DB::update('update landdetails set name= ? ,status=? where id=?',[$name,$status,$editid]);

	
		echo "Data Updated Successfully. Please refresh the page to see the changes";


	}
	catch(Exception $e){
		echo "Something went wrong";
	}

}
public function homedetails(){

	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $checkversion=DB::table('homepages')->where(["KeyName"=>"Version"])->get();
            $checkheader1=DB::table('homepages')->where(["KeyName"=>"Header1"])->get();
            $checkheader2=DB::table('homepages')->where(["KeyName"=>"Header2"])->get();
            $checkbody1=DB::table('homepages')->where(["KeyName"=>"Body1"])->get();
            $checkbody2=DB::table('homepages')->where(["KeyName"=>"Body2"])->get();
            $checkimage1=DB::table('homepages')->where(["KeyName"=>"webimage1"])->get();
            $checkimage2=DB::table('homepages')->where(["KeyName"=>"webimage2"])->get();
            $checkimage3=DB::table('homepages')->where(["KeyName"=>"webimage3"])->get();
            $checkimage4=DB::table('homepages')->where(["KeyName"=>"webimage4"])->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
            foreach($checkversion as $version){
            	$versionid=$version->Value;
            }
            foreach($checkheader1 as $header1){
            	$header1=$header1->Value;
            }
            foreach($checkheader2 as $header2){
            	$header2=$header2->Value;
            }
            foreach($checkbody1 as $body1){
            	$body1=$body1->Value;
            }
            foreach($checkbody2 as $body2){
            	$body2=$body2->Value;
            }
            foreach($checkimage1 as $image1){
            	//$image1=base64_decode($image1->Value);
                $image1=$image1->Value;
            }
            foreach($checkimage2 as $image2){
            	//$image2=base64_decode($image2->Value);
                $image2=$image2->Value;
            }
            foreach($checkimage3 as $image3){
            	//$image3=base64_decode($image3->Value);
                $image3=$image3->Value;
            }
            foreach($checkimage4 as $image4){
            	//$image4=base64_decode($image4->Value);
                $image4=$image4->Value;
            }
	return view('homedetails')->with('name',$name)->with('version',$versionid)->with('header1',$header1)->with('header2',$header2)->with('body1',$body1)->with('body2',$body2)->with('image1',$image1)->with('image2',$image2)->with('image3',$image3)->with('image4',$image4);
	}
	else{
	return redirect('/');	
	}

}

	public function homedetailsedit(Request $request){
		try{
		$version=$request->input('version');
		$header1=$request->input('header1');
		$header2=$request->input('header2');
		$body1=$request->input('body1');
		$body2=$request->input('body2');
		$data=DB::table('homepages')->where(['KeyName'=>'Version'])->get();
		foreach($data as $d){
			$dver=$d->Value;
		}
		$valincre=$dver+1;

		if($request->hasFile('image_sub1')){
    						$filenamewithext=$request->file('image_sub1')->getClientOriginalName();
    						$filename=pathinfo($filenamewithext, PATHINFO_FILENAME);
    						$extension =$request->file('image_sub1')->getClientOriginalExtension();
    						$image1= time().'a.'.$extension;
    						//$path=$request->file('image_sub1')->storeAs('assets/home',$image1);
    						$path=$request->file('image_sub1')->storeAs('assets/home',$image1);
                            //sleep(5);
                            $path1="http://ec2-13-126-162-143.ap-south-1.compute.amazonaws.com/watertreatment/storage/app/assets/home/".$image1;
                            $encoded_data1=base64_encode(file_get_contents($path1));
    						DB::update('update homepages set Value= ? where KeyName=?',[$encoded_data1,"Image1"]);
                            DB::update('update homepages set Value= ? where KeyName=?',[$image1,"webimage1"]);
    					}


    	if($request->hasFile('image_sub2')){
    						$filenamewithext=$request->file('image_sub2')->getClientOriginalName();
    						$filename=pathinfo($filenamewithext, PATHINFO_FILENAME);
    						$extension =$request->file('image_sub2')->getClientOriginalExtension();
    						$image2= time().'b.'.$extension;
    						//$path=$request->file('image_sub2')->storeAs('assets/home',$image2);
    						$path=$request->file('image_sub2')->storeAs('assets/home',$image2);
                            $path1="http://ec2-13-126-162-143.ap-south-1.compute.amazonaws.com/watertreatment/storage/app/assets/home/".$image2;
                            $encoded_data2=base64_encode(file_get_contents($path1));
    						DB::update('update homepages set Value= ? where KeyName=?',[$encoded_data2,"Image2"]);
                            DB::update('update homepages set Value= ? where KeyName=?',[$image2,"webimage2"]);
    						}

    	if($request->hasFile('image_sub3')){
    						$filenamewithext=$request->file('image_sub3')->getClientOriginalName();
    						$filename=pathinfo($filenamewithext, PATHINFO_FILENAME);
    						$extension =$request->file('image_sub3')->getClientOriginalExtension();
    						$image3=time().'c.'.$extension;
    						$path=$request->file('image_sub3')->storeAs('assets/home',$image3);
                            $path1="http://ec2-13-126-162-143.ap-south-1.compute.amazonaws.com/watertreatment/storage/app/assets/home/".$image3;
                            $encoded_data3=base64_encode(file_get_contents($path1));
    						//$path1=$request->file('image_sub3')->storeAs('assets/home',base64_decode($image3));
    						DB::update('update homepages set Value= ? where KeyName=?',[$encoded_data3,"Image3"]);
                            DB::update('update homepages set Value= ? where KeyName=?',[$image3,"webimage3"]);
    						}

    	if($request->hasFile('image_sub4')){
    						$filenamewithext=$request->file('image_sub4')->getClientOriginalName();
    						$filename=pathinfo($filenamewithext, PATHINFO_FILENAME);
    						$extension =$request->file('image_sub4')->getClientOriginalExtension();
    						$image4= time().'d.'.$extension;
    						$path=$request->file('image_sub4')->storeAs('assets/home',$image4);
    						//$path1=$request->file('image_sub4')->storeAs('assets/home',base64_decode($image4));
                            $path1="http://ec2-13-126-162-143.ap-south-1.compute.amazonaws.com/watertreatment/storage/app/assets/home/".$image4;
                            $encoded_data4=base64_encode(file_get_contents($path1));
    						DB::update('update homepages set Value= ? where KeyName=?',[$encoded_data4,"Image4"]);
                            DB::update('update homepages set Value= ? where KeyName=?',[$image4,"webimage4"]);
    						}



		DB::update('update homepages set Value= ? where KeyName=?',[$valincre,"Version"]);
		DB::update('update homepages set Value= ? where KeyName=?',[$header1,"Header1"]);
		DB::update('update homepages set Value= ? where KeyName=?',[$header2,"Header2"]);
		DB::update('update homepages set Value= ? where KeyName=?',[$body1,"body1"]);
		DB::update('update homepages set Value= ? where KeyName=?',[$body2,"body2"]);
		
		echo "Data Updated Successfully. Please refresh the page to see the changes";


	}
	catch(Exception $e){
		echo "Something went wrong";
	}


	}

	public function main_menu_add(){
		if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
	return view('main_menu_add')->with('name',$name);
	}
	else{
	return redirect('/');	
	}
	}



	public function adddetails(Request $request){
		try{

		$name=$request->input('name');
		$status=$request->input('status');
		if($request->hasFile('image_sub')){
    						$filenamewithext=$request->file('image_sub')->getClientOriginalName();
    						$filename=pathinfo($filenamewithext, PATHINFO_FILENAME);
    						$extension =$request->file('image_sub')->getClientOriginalExtension();
    						//$fileNametoabstract= $filename.'_'.time().'.'.$extension;
                            $fileNametoabstract= time().'.'.$extension;
    						$path=$request->file('image_sub')->storeAs('assets/others',$fileNametoabstract);
    						 $path1="http://ec2-13-126-162-143.ap-south-1.compute.amazonaws.com/watertreatment/storage/app/assets/others/".$fileNametoabstract;
                            $encoded_data1=base64_encode(file_get_contents($path1));
    						}else{
    						$fileNametoabstract='nofile';
    						}

		$agriculturesubmenu=new product;
		$agriculturesubmenu->Name=$name;
		$agriculturesubmenu->webImage=$fileNametoabstract;
		$agriculturesubmenu->Image=$encoded_data1;
		$agriculturesubmenu->status=$status;
		$agriculturesubmenu->save();
		echo "Data Saved Successfully";
		

	}
	catch(Exception $e){
		echo "Something went wrong";
	}
}


public function main_menu_view(){

	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $checkmenu=DB::table('products')->orderBy('id', 'DESC')->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
            

	return view('main_menu_view')->with('name',$name)->with('dataview',$checkmenu);
	}
	else{
	return redirect('/');	
	}

}
	

public function main_menu_edit(){
	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
            $id=$_GET['id'];
            $checkid=DB::table('products')->where(["id"=>$id])->get();
            foreach($checkid as $i){
            	$nameedit=$i->Name; 
            	$status=$i->status;    
            	$webImage=$i->webImage;       	
            }
            

	return view('main_menu_edit')->with('name',$name)->with('nameedit',$nameedit)->with('editid',$id)->with('status',$status)->with('image',$webImage);
	}
	else{
	return redirect('/');	
	}
}


public function editmainmenudata(Request $request){

	try{

		$name=$request->input('name');
		$status=$request->input('status');
		$editid=$request->input('id');
		if($request->hasFile('image_sub')){
    						$filenamewithext=$request->file('image_sub')->getClientOriginalName();
    						$filename=pathinfo($filenamewithext, PATHINFO_FILENAME);
    						$extension =$request->file('image_sub')->getClientOriginalExtension();
    						//$fileNametoabstract= $filename.'_'.time().'.'.$extension;
                            $fileNametoabstract= time().'.'.$extension;
    						$path=$request->file('image_sub')->storeAs('assets/others',$fileNametoabstract);
                            $path1="http://ec2-13-126-162-143.ap-south-1.compute.amazonaws.com/watertreatment/storage/app/assets/others/".$fileNametoabstract;
                            $encoded_data1=base64_encode(file_get_contents($path1));
    						
							DB::update('update products set Name= ? ,webImage=?,Image=? ,status=? where id=?',[$name,$fileNametoabstract,$encoded_data1,$status,$editid]);
		}
		else{
							DB::update('update products set Name= ? ,status=? where id=?',[$name,$status,$editid]);

		}
		echo "Data Updated Successfully. Please refresh the page to see the changes";


	}
	catch(Exception $e){
		echo "Something went wrong";
	}

}


public function admin_new_water_detail(){
	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $topic=DB::table('landdetails')->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
	return view('admin_new_water_detail')->with('name',$name)->with('topic',$topic);
	}
	else{
	return redirect('/');	
	}
}


public function waterdetail(Request $request){

	try{
		$topic=$request->input('topic');
		$name=$request->input('name');		
		$agriculturesubmenu=new waterdetail;
		$agriculturesubmenu->topic=$topic;
		$agriculturesubmenu->name=$name;
        $agriculturesubmenu->status="Active";
		$agriculturesubmenu->save();
		echo "Data Saved Successfully";

	}
	catch(Exception $e){
		echo "Something went wrong";
	}

}


public function admin_new_water_detail_view() {
	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $checkmenu=DB::table('waterdetails')->orderBy('id', 'DESC')->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
            

	return view('admin_new_water_detail_view')->with('name',$name)->with('dataview',$checkmenu);
	}
	else{
	return redirect('/');	
	}
}


public function admin_new_water_detail_view_edit(){
	if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
            $id=$_GET['id'];
            $checkid=DB::table('waterdetails')->where(["id"=>$id])->get();
            foreach($checkid as $i){
            	$nameedit=$i->name;
            	$topicedit=$i->topic;  
                $status=$i->status;          	
            }
            $topic=DB::table('landdetails')->where('name','<>',$topicedit)->get();

	return view('admin_new_water_detail_view_edit')->with('name',$name)->with('nameedit',$nameedit)->with('editid',$id)->with('topic',$topic)->with('topicedit',$topicedit)->with('status',$status);
	}
	else{
	return redirect('/');	
	}
}


public function waterdetailedit(Request $request){
	try{

		$name=$request->input('name');
		$topic=$request->input('topic');
		$editid=$request->input('id');
        $status=$request->input('status');
		
		DB::update('update waterdetails set name= ? ,topic=? ,status=? where id=?',[$name,$topic,$status,$editid]);

	
		echo "Data Updated Successfully. Please refresh the page to see the changes";


	}
	catch(Exception $e){
		echo "Something went wrong";
	}

}

	public function detail(){

		if(session('sessionId')){
			$names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $checkcrops=DB::table('agriculturesubmenus')->get();
            $checkcropname=DB::table('agriculturecrops')->get();
            $checkland=DB::table('landdetails')->get();
            foreach($checkemail as $mail){
            	$name=$mail->name;
            }
	return view('detail')->with('name',$name)->with('checkcrops',$checkcrops)->with('checkcropname',$checkcropname)->with('checkland',$checkland);
	}
	else{
	return redirect('/');	
	}
		
	}



  public function season(){
    if(session('sessionId')){
            $names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $topic=DB::table('agriculturecrops')->get();
            foreach($checkemail as $mail){
                $name=$mail->name;
            }
    return view('season')->with('name',$name)->with('topic',$topic);
    }
    else{
    return redirect('/');   
    }
}


    public function sendseason(Request $request){

        try{
        $topic=$request->input('topic');
        $name=$request->input('name');      
        $agriculturesubmenu=new season;
        $agriculturesubmenu->topic=$topic;
        $agriculturesubmenu->name=$name;
        $agriculturesubmenu->status="Active";
        $agriculturesubmenu->save();
        echo "Data Saved Successfully";

    }
    catch(Exception $e){
        echo "Something went wrong";
    }
    }


    public function season_view(Request $request){
       if(session('sessionId')){
            $names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $checkmenu=DB::table('seasons')->orderBy('id', 'DESC')->get();
            foreach($checkemail as $mail){
                $name=$mail->name;
            }
            

    return view('season_view')->with('name',$name)->with('dataview',$checkmenu);
    }
    else{
    return redirect('/');   
    }

    }

    public function season_view_edit(){
        if(session('sessionId')){
            $names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            foreach($checkemail as $mail){
                $name=$mail->name;
            }
            $id=$_GET['id'];
            $checkid=DB::table('seasons')->where(["id"=>$id])->get();
            foreach($checkid as $i){
                $nameedit=$i->name;
                $topicedit=$i->topic;  
                $status=$i->status;
                       
            }
            $topic=DB::table('agriculturecrops')->where('name','<>',$topicedit)->get();

    return view('season_view_edit')->with('name',$name)->with('nameedit',$nameedit)->with('editid',$id)->with('topic',$topic)->with('topicedit',$topicedit)->with('status',$status);
    }
    else{
    return redirect('/');   
    }
    }


    public function sendseasonedit(Request $request){
    try{

        $name=$request->input('name');
        $topic=$request->input('topic');
        $status=$request->input('status');
        $editid=$request->input('id');
        
        DB::update('update seasons set name= ? ,topic=? ,status= ? where id=?',[$name,$topic,$status,$editid]);

    
        echo "Data Updated Successfully. Please refresh the page to see the changes";


    }
    catch(Exception $e){
        echo "Something went wrong";
    }

}


public function fetchcrop(Request $request)
    {
   $select = $request->get('select');
     $value = $request->get('value');
     $dependent = $request->get('dependent');
    $data = DB::table('agriculturecrops')->where(['topic'=>$value])->get();

    foreach ($data as $dat) {
    echo '<option value="'.$dat->name.'" id="'.$dat->name.'">'.$dat->name.'</option>';
    }

  }


  public function fetchmethod(Request $request)
    {
   $select = $request->get('select');
     $value = $request->get('value');
     $dependent = $request->get('dependent');
    $data = DB::table('cultivationmethods')->where(['topic'=>$value])->get();

    foreach ($data as $dat) {
    echo '<option value="'.$dat->name.'" id="'.$dat->name.'">'.$dat->name.'</option>';
    }

  }

  public function fetchseason(Request $request)
    {
   $select = $request->get('select');
     $value = $request->get('value');
     $dependent = $request->get('dependent');
    $data = DB::table('seasons')->where(['topic'=>$value])->get();

    foreach ($data as $dat) {
    echo '<option value="'.$dat->name.'" id="'.$dat->name.'">'.$dat->name.'</option>';
    }

  }



  public function fetchwater(Request $request)
    {
   $select = $request->get('select');
     $value = $request->get('value');
     $dependent = $request->get('dependent');
    $data = DB::table('waterdetails')->where(['topic'=>$value])->get();

    foreach ($data as $dat) {
    echo '<option value="'.$dat->name.'" id="'.$dat->name.'">'.$dat->name.'</option>';
    }

  }

  public function senddetails(Request $request){
    try{
        
        
        $crop=$request->input('crop');
        $cropname=$request->input('cropname');
        $method=$request->input('method');
        $season=$request->input('season');
        $land=$request->input('land');
        $water=$request->input('water');
        $desc=$request->input('desc');

        if($request->hasFile('image_sub')){
                            $filenamewithext=$request->file('image_sub')->getClientOriginalName();
                            $filename=pathinfo($filenamewithext, PATHINFO_FILENAME);
                            $extension =$request->file('image_sub')->getClientOriginalExtension();
                            $fileNametoabstract= time().'a.'.$extension;
                            $path=$request->file('image_sub')->storeAs('assets/others',$fileNametoabstract);
                           // $path1="http://localhost:8080/watertreatment/storage/app/assets/maindetails/".$fileNametoabstract;
                           // $encoded_data1=base64_encode(file_get_contents($path1));
                            $path1="http://ec2-13-126-162-143.ap-south-1.compute.amazonaws.com/watertreatment/storage/app/assets/others/".$fileNametoabstract;
                            $encoded_data1=base64_encode(file_get_contents($path1));    
                            }else{
                            $fileNametoabstract='nofile';
                            }

         if($request->hasFile('image_sub1')){
                            $filenamewithext1=$request->file('image_sub1')->getClientOriginalName();
                            $filename1=pathinfo($filenamewithext1, PATHINFO_FILENAME);
                            $extension1 =$request->file('image_sub1')->getClientOriginalExtension();
                            $fileNametoabstract1= time().'b.'.$extension1;
                            $path=$request->file('image_sub1')->storeAs('assets/others',$fileNametoabstract1);
                            //$path2="http://localhost:8080/watertreatment/storage/app/assets/maindetails/".$fileNametoabstract1;
                           // $encoded_data2=base64_encode(file_get_contents($path2));
                            $path1="http://ec2-13-126-162-143.ap-south-1.compute.amazonaws.com/watertreatment/storage/app/assets/others/".$fileNametoabstract1;
                            $encoded_data2=base64_encode(file_get_contents($path1));
                            }else{
                            $fileNametoabstract1='nofile';
                            }                   
        $agriculturesubmenu=new maindetail;
        $agriculturesubmenu->Name=$crop;
        $agriculturesubmenu->imageweb=$fileNametoabstract;
        $agriculturesubmenu->Type="Agriculture";
        $agriculturesubmenu->Subtype=$cropname;
        $agriculturesubmenu->Season=$season;
        $agriculturesubmenu->Land=$land;
        $agriculturesubmenu->Water=$water;
        $agriculturesubmenu->Method=$method;
        $agriculturesubmenu->Description=$desc;
        $agriculturesubmenu->imageweb2=$fileNametoabstract1;
        $agriculturesubmenu->Image=$encoded_data1;
        $agriculturesubmenu->Image2=$encoded_data2;
        $agriculturesubmenu->save();        
        echo "Data Saved Successfully";
       

    }
    catch(Exception $e){
        echo "Something went wrong";
    }
  }


  public function detail_view(){
  if(session('sessionId')){
            $names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            $checkmenu=DB::table('maindetails')->orderBy('id', 'DESC')->get();
            foreach($checkemail as $mail){
                $name=$mail->name;
            }
            

    return view('detail_view')->with('name',$name)->with('dataview',$checkmenu);
    }
    else{
    return redirect('/');   
    }

}

public function detail_view_edit(){
    if(session('sessionId')){
            $names=session()->get('sessionId');
            $email=$names[0];
            $checkemail=DB::table('users')->where(["email"=>$email])->get();
            foreach($checkemail as $mail){
                $name=$mail->name;
            }
            $id=$_GET['id'];
            $checkid=DB::table('maindetails')->where(["id"=>$id])->get();
            foreach($checkid as $i){
                $nameedit=$i->Name;  
                $image=$i->Image;
                $status=$i->Name;
                $Subtype=$i->Subtype;
                $method=$i->Method;
                $seasons=$i->Season;
                $land=$i->Land;
                $water=$i->Water;  
                $desc=$i->Description;
                $image1=$i->imageweb;
                $image2=$i->imageweb2;           
            }

            $checkcrops=DB::table('agriculturesubmenus')->get();
            $checkland=DB::table('landdetails')->get();
            $checksubcrops=DB::table('agriculturecrops')->where(['topic'=>$nameedit])->get();
            $checkmethod=DB::table('cultivationmethods')->where(['topic'=>$Subtype])->get();
            $checkseason=DB::table('seasons')->where(['topic'=>$Subtype])->get();
            $checkwater=DB::table('waterdetails')->where(['topic'=>$land])->get();

    return view('detail_view_edit')->with('name',$name)->with('nameedit',$nameedit)->with('editid',$id)->with('image',$image)->with('status',$status)->with('checkcrops',$checkcrops)->with('checkland',$checkland)->with('checkcrops',$checkcrops)->with('nameedit',$nameedit)->with('Subtype',$Subtype)->with('checksubcrops',$checksubcrops)->with('method',$method)->with('checkmethod',$checkmethod)->with('seasons',$seasons)->with('checkseason',$checkseason)->with('land',$land)->with('water',$water)->with('checkwater',$checkwater)->with('desc',$desc)->with('image1',$image1)->with('image2',$image2);
    }
    else{
    return redirect('/');   
    }
}


    public function updatedetails(Request $request){

        try{
        $crop=$request->input('crop');
        $cropname=$request->input('cropname');
        $method=$request->input('method');
        $season=$request->input('season');
        $land=$request->input('land');
        $water=$request->input('water');
        $desc=$request->input('desc');
        $editid=$request->input('editid');

        if($request->hasFile('image_sub') AND $request->hasFile('image_sub1') ){
                $filenamewithext=$request->file('image_sub')->getClientOriginalName();
                            $filename=pathinfo($filenamewithext, PATHINFO_FILENAME);
                            $extension =$request->file('image_sub')->getClientOriginalExtension();
                            $fileNametoabstract= time().'a.'.$extension;
                            $path=$request->file('image_sub')->storeAs('assets/others',$fileNametoabstract);
                           //  $path1="http://localhost:8080/watertreatment/storage/app/assets/maindetails/".$fileNametoabstract;
                           // $encoded_data1=base64_encode(file_get_contents($path1));
                            $path1="http://ec2-13-126-162-143.ap-south-1.compute.amazonaws.com/watertreatment/storage/app/assets/others/".$fileNametoabstract;
                            $encoded_data1=base64_encode(file_get_contents($path1));
                             $filenamewithext1=$request->file('image_sub1')->getClientOriginalName();
                            $filename1=pathinfo($filenamewithext1, PATHINFO_FILENAME);
                            $extension1 =$request->file('image_sub1')->getClientOriginalExtension();
                            $fileNametoabstract1= time().'b.'.$extension1;
                            $path=$request->file('image_sub1')->storeAs('assets/others',$fileNametoabstract1);
                            $path2="http://ec2-13-126-162-143.ap-south-1.compute.amazonaws.com/watertreatment/storage/app/assets/others/".$fileNametoabstract1;
                            $encoded_data2=base64_encode(file_get_contents($path2));
                            // $path2="http://localhost:8080/watertreatment/storage/app/assets/maindetails/".$fileNametoabstract1;
                           // $encoded_data2=base64_encode(file_get_contents($path2));
                             DB::update('update maindetails set Name= ? ,Subtype=? ,Method=?, Season=? ,Land=?, Water=?, Description=? ,imageweb=? ,imageweb2=? ,Image=? ,Image2=?  where id=?',[$crop,$cropname,$method,$season,$land,$water,$desc,$fileNametoabstract,$fileNametoabstract1,$encoded_data1,$encoded_data2,$editid]);
        }

        else if($request->hasFile('image_sub')){
                            $filenamewithext=$request->file('image_sub')->getClientOriginalName();
                            $filename=pathinfo($filenamewithext, PATHINFO_FILENAME);
                            $extension =$request->file('image_sub')->getClientOriginalExtension();
                            $fileNametoabstract= time().'.'.$extension;
                            $path=$request->file('image_sub')->storeAs('assets/others',$fileNametoabstract);
                           // $path1="http://localhost:8080/watertreatment/storage/app/assets/maindetails/".$fileNametoabstract;
                           // $encoded_data1=base64_encode(file_get_contents($path1));
                            $path1="http://192.168.43.28:8080/watertreatment/storage/app/assets/others/".$fileNametoabstract;
                            $encoded_data1=base64_encode(file_get_contents($path1));
                            DB::update('update maindetails set Name= ? ,Subtype=? ,Method=?, Season=? ,Land=?, Water=?, Description=?, imageweb=?,Image=?  where id=?',[$crop,$cropname,$method,$season,$land,$water,$desc,$fileNametoabstract,$encoded_data1,$editid]);
        }
        else if($request->hasFile('image_sub1')){
                            $filenamewithext1=$request->file('image_sub1')->getClientOriginalName();
                            $filename1=pathinfo($filenamewithext1, PATHINFO_FILENAME);
                            $extension1 =$request->file('image_sub1')->getClientOriginalExtension();
                            $fileNametoabstract1= time().'.'.$extension1;
                            $path=$request->file('image_sub1')->storeAs('assets/others',$fileNametoabstract1);
                           // $path2="http://localhost:8080/watertreatment/storage/app/assets/maindetails/".$fileNametoabstract1;
                           // $encoded_data2=base64_encode(file_get_contents($path2));
                            $path1="http://192.168.43.28:8080/watertreatment/storage/app/assets/others/".$fileNametoabstract1;
                            $encoded_data2=base64_encode(file_get_contents($path1));
                            DB::update('update maindetails set Name= ? ,Subtype=? ,Method=?, Season=? ,Land=?, Water=?, Description=? ,imageweb2=?, Image2=?  where id=?',[$crop,$cropname,$method,$season,$land,$water,$desc,$fileNametoabstract1,$encoded_data2,$editid]);
                        
        }
        else{


         DB::update('update maindetails set Name= ? ,Subtype=? ,Method=?, Season=? ,Land=?, Water=?, Description=?  where id=?',[$crop,$cropname,$method,$season,$land,$water,$desc,$editid]);
    }

    echo "Data Updated Successfully. Please refresh the page to see the changes";
}

catch(Exception $e){
        echo "Something went wrong";
    }
}


//For test purpose//
public function test(){

   $path="http://localhost:8080/watertreatment/storage/app/assets/home/Screenshot%20(10)_1536940354.png";
    $encoded_data=base64_encode(file_get_contents($path));
    echo $encoded_data;
   // $request_url = apache_getenv("HTTP_HOST") . apache_getenv("REQUEST_URI");
   // echo $request_url;
}


}
