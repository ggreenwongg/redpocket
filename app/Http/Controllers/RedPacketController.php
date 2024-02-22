<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Transaction;
use App\Models\RedPacket;

/**
 * @OA\Info(
 *     title="Your API Title",
 *     version="1.0.0",
 *     description="Description of your API"
 * )
 */

class RedPacketController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/send_packet",
     *      operationId="Send Packet",
     *      tags={"sendPacket"},
     *      summary="SendPacket",
     *      description="SendPacket",
     * @OA\RequestBody(
     *    required=true,
     *    description="Send",
     *    @OA\JsonContent(
     *       @OA\Property(property="user_id", type="integer", format="integer", example="1,2,3,4,5"),
     *       @OA\Property(property="amount", type="float", format="float", example=""),
     *       @OA\Property(property="quantity", type="interger", format="interger", example=""),
     *       @OA\Property(property="random", type="boolean", format="boolean", example="1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=400,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(
     *                property="data",
     *                @OA\Property(property="status", type="string", format="string", example="fail"),
     *                @OA\Property(property="errormsg", type="string", format="string", example="wrong credentials or invalid imei and token."),
     *      ),
     *        )
     *     )
     * )
     */


    public function send(Request $request)
    {

        try{
            
            // $sender = User::where('email',$request['email'])->first();
            $sender = User::find($request['user_id']);

            $RedPacket = RedPacket::create([
                "user_id" => $sender->id,
                "amount" =>  $request['amount'],
                "remain_amount" =>  $request['amount'],
                "quantity" =>  $request['quantity'],
                "remain_quantity" =>  $request['quantity'],
                "random" => $request['random'],
            ]);

            // Transaction::create([
            //     "packet_id" => $RedPacket->id,
            //     "sender_id" =>  $RedPacket->user_id,
            //     "receiver_id" =>  $request['quantity'],
            //     "amount" => $request['random'],
            // ]);

        return response()->json([
                  'data' => 'Send Red Packet Successfully',
                  'packet_id' => $RedPacket->id
          ]);

        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()]);
        }
       
    }

    /**
     * @OA\Post(
     *      path="/api/receive_packet",
     *      operationId="receive Packet",
     *      tags={"receivePacket"},
     *      summary="receivePacket",
     *      description="receivePacket",
     * @OA\RequestBody(
     *    required=true,
     *    description="receive",
     *    @OA\JsonContent(
     *       @OA\Property(property="user_id", type="integer", format="integer", example="1,2,3,4,5"),
     *       @OA\Property(property="packet_id", type="integer", format="integer", example=""),
     *    ),
     * ),
     * @OA\Response(
     *    response=400,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(
     *                property="data",
     *                @OA\Property(property="status", type="string", format="string", example="fail"),
     *                @OA\Property(property="errormsg", type="string", format="string", example="wrong credentials or invalid imei and token."),
     *      ),
     *        )
     *     )
     * )
     */


    public function receive(Request $request)
    {

        try{
            // $sender = User::where('email',$request['email'])->first();
            $receiver = User::find($request['user_id']);
            $RedPacket = RedPacket::find($request['packet_id']);
            
            if(Transaction::where('packet_id',$RedPacket->id)->where('receiver_id',$receiver->id)->exists()){
                return response()->json(['error' => 'You cannot receive same packet']);
            }elseif($RedPacket->remain_quantity <= 0){
                return response()->json(['error' => 'This packet have finished']);
            }elseif($RedPacket->user_id  == $receiver->id){
                return response()->json(['error' => 'Sender cannot receive own packet']);
            }else{
            if($RedPacket->random == true){
                if($RedPacket->remain_amount >= 0.01){
                    $receiveAmount = mt_rand(0.01, $RedPacket->remain_amount);
                }else{
                    return response()->json(['error' => 'This packet amount have finished']);
                }
            }else{
                $receiveAmount = ($RedPacket->remain_amount/$RedPacket->remain_quantity);
            }

            $receiveAmount = number_format($receiveAmount,2);
            
            $RedPacket->update([
                'remain_amount' => ($RedPacket->remain_amount - $receiveAmount),
                'remain_quantity' => ($RedPacket->remain_quantity - 1)
            ]);

            Transaction::create([
                "packet_id" => $RedPacket->id,
                "sender_id" =>  $RedPacket->user_id,
                "receiver_id" =>  $receiver->id,
                "amount" => $receiveAmount,
              ]);

            return response()->json(['data' => 'Receive RM' . $receiveAmount . ' Red Packet Successfully','redpacket_id' => $RedPacket->id]);
        }


        }catch(\Exception $e){
            return response()->json(['data' => $e->getMessage()]);
        }
       
    }
}
