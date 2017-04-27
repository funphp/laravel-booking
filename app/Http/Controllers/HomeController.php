<?php
namespace App\Http\Controllers;

use App\Cleaner;
use Illuminate\Http\Request;
use App\City;
use App\Customer;
use App\Booking;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;
use Validator;
use Redirect;
use Session;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $city = City::all();
        return view('welcome', ['city'=>$city]);
    }

    public function booking()
    {
        $data = Input::all();

        $rules = array('first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'date' => 'required',
            'time' => 'required',
            'hour' => 'required');
           // 'phone_number'=>'required|email|unique:users_tbl',

        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            // send back to the page with the input data and errors
            return Redirect::to('/')->withInput()->withErrors($validator);
        } else {
            // check if customer exists
            $customer = Customer::where(['phone_number'=>$data['phone_number']])->first();
            if(!$customer) {
                $customer = Customer::create_new_customer($data);
            }
            $city = City::find($data['city_id']);
            $cleaner_id = array();
            $available_cleaner = array();
            foreach($city->cleaners as $cl) {
                $cleaner_id[] = $cl->id;
                $already_booking= Booking::getBookingByCleanerAndDate($cl->id, $data['date'], $data['time']);
                if(!$already_booking) {
                    $available_cleaner[] = $cl;
                }

            }
            if (count($cleaner_id)==0) {

                Session::flash('flash_error', 'Cleaner not found in this city');
                return Redirect::to('/')->withInput();
            }
            if(count($available_cleaner)== 0) {
                Session::flash('flash_error', 'All cleaner occupied');
                return Redirect::to('/')->withInput();
            }

            //cleaner valid with this date
            try{
                $booking = Booking::makeBooking($data, $available_cleaner[0]->id, $customer->id);

                Session::flash('flash_success', 'Booking confirmed');
                return view('success', ['booking'=>$booking, 'cleaner'=>$available_cleaner[0]]);
            } catch(Exception $e) {

            }





        }

        // $this->middleware('auth');
    }
}
