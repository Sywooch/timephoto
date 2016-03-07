<?php
/**
 * Created by PhpStorm.
 * User: slashman
 * Date: 15.03.15
 * Time: 16:22
 */

namespace app\components;

class RoboKassa
{
    public   $mrh_login = "timephoto.ru";
    private  $mrh_pass1 = "JoPE9001036"; // JoPE9001036
    private  $mrh_pass2 = "JoPE9001038"; // JoPE9001038
    public   $inv_id    = 0;
    public   $inv_desc  = "Оплата подписки на правовом портале Гладун ";
    public   $out_summ  = "0.01";
    public   $in_curr   = "";
    public   $shp_item  = 1;
    public   $culture   = "ru";
    public   $encoding  = "utf-8";
    public $transaction = null;

    private $actionUrl = 'http://test.robokassa.ru/Index.aspx';
//    private $actionUrl = 'https://auth.robokassa.ru/Merchant/Index.aspx';


    public function setAmount($amount)
    {
        $this->out_summ = $amount;
    }

    public function setSubscriptionId($id)
    {
        $this->inv_id = $id;
    }

    public function setDescription($description)
    {
        $this->inv_desc = $description;
    }

    public function valid($data)
    {
        if(isset($data['SignatureValue']) && isset($data['InvId']) && isset($data['Shp_transaction']))
        {
            $this->inv_id = $data['InvId'];
            $this->transaction = $data['Shp_transaction'];

            if($data['SignatureValue'] == $this->validSignature($data['OutSum'],$data['InvId'],$data['Shp_transaction']))
            {
                return true;
            }
            else
                return false;
        }
        return false;
    }

    public function getScript()
    {
        return  "<script language=JavaScript ".
        "src='https://auth.robokassa.ru/Merchant/PaymentForm/FormMS.js?".
        "MrchLogin=$this->mrh_login&OutSum=$this->out_summ&InvId=$this->inv_id&IncCurrLabel=$this->in_curr".
        "&Desc=$this->inv_desc&SignatureValue=".$this->signature()."&Shp_item=$this->shp_item".
        "&Culture=$this->culture&Encoding=$this->encoding'></script>";
    }

    public function getPaymentButton()
    {
        return "<form action='".$this->actionUrl."' id=".$this->inv_id." method=POST>".
        "<input type=hidden name=MrchLogin value=$this->mrh_login>".
        "<input type=hidden name=OutSum value=$this->out_summ>".
        "<input type=hidden name=InvId value=$this->inv_id>".
        "<input type=hidden name=Desc value='$this->inv_desc'>".
        "<input type=hidden name=SignatureValue value=".$this->signature().">".
        "<input type=hidden name=Shp_item value='$this->shp_item'>".
        "<input type=hidden name=IncCurrLabel value=$this->in_curr>".
        "<input type=hidden name=Culture value=$this->culture>".
        "<div class='col-md-12 text-center'><button type=submit class='btn btn-success'>Оплатить</button></div>".
//        "<a href='#' class=\"retryIcon\" form-id=\"".$this->inv_id."\" title=\"Попробовать еще раз\"><i class='fa fa-refresh text-success'></i></a>".
        "</form>";
    }

    public function getParams()
    {
        return json_encode([
            'mrh_login' => $this->mrh_login,
            'out_summ' => $this->out_summ,
            'inv_id' => $this->inv_id,
            'inv_desc' => $this->inv_desc,
            'signature' => $this->signature(),
            'in_curr' => $this->in_curr,
            'culture' => $this->culture,
            'actionUrl' => $this->actionUrl,
            'Shp_transaction' => $this->transaction,
        ]);
    }

    public function signature()
    {
        return md5("$this->mrh_login:$this->out_summ:$this->inv_id:$this->mrh_pass1:Shp_transaction=$this->transaction");
    }

    public function validSignature($out_summ, $inv_id, $transaction_id)
    {
        return strtoupper(md5("$out_summ:$inv_id:$this->mrh_pass2:Shp_transaction=$transaction_id"));
    }
}