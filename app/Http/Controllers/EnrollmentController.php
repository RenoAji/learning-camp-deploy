<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class EnrollmentController extends Controller
{
    function __construct(){
        // Set midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        //Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    function enroll(Request $request, Course $course){
        //Redirect to learn session if user already enrolled
        $user_id = auth()->user()->id;
        if(!is_null($course->enrollments->where('user_id',$user_id)->first())){
            return redirect()->action([CourseController::class, 'learn'], ['section' => $course->sections->first()->id]); 
        }
        //jika course gratis, langsung insert enrollment
        if($course->price == 0){
            Enrollment::insert([
            'course_id' => $course->id,
            'sections_completed' => 0,
            'user_id' => $user_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->action([CourseController::class, 'learn'], ['section' => $course->sections->first()->id]);
        }else{ //jika course berbayar redirect ke pembayaran  
            $rand = rand();
            $transaction_details = array(
                    'order_id' => "User-$user_id-Course-$course->id-$rand",
                    'gross_amount' => $course->price,
            );

            $customer_details = array(
                'name' => auth()->user()->username,
                'email' => auth()->user()->email,
            );

            $item_details = [[
                'id' => $course->id,
                'price' => $course->price,
                'quantity' => 1,
                'name' => "Course"]];

            $params = array(
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details,
                'item_details' => $item_details,
            );

            //dd($params);
            try {
                
              // Get Snap Payment Page URL
              $paymentUrl = Snap::createTransaction($params)->redirect_url;
              
              // Redirect to Snap Payment Page
              return redirect($paymentUrl);
            }
            catch (Exception $e) {
              echo $e->getMessage();
            }
        }
    }

    public function notificationHandler(){
        $notif = new Notification();
        $notif = $notif->getResponse();

        $transaction = $notif->transaction_status;
        $fraud = $notif->fraud_status;
        $order_id = $notif->order_id; // string "User-$UserId-Course-$CourseId-rand"
        $order_detail = explode("-",$order_id); // array ["User", UserId, "Course", CourseId, rand]
        $course_id = $order_detail[3];
        $user_id = $order_detail[1];

        if($transaction == 'settlement'){
            Enrollment::insert([
                'course_id' => $course_id,
                'sections_completed' => 0,
                'user_id' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
    }
}

