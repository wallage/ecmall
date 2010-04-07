<?php
/**
 *    淘宝属性查询接口类
 *
 *    @author    Hyber
 *    @usage    none
 */

if (!defined('IN_ECM'))
{
    trigger_error('Hacking attempt', E_USER_ERROR);
}

class TaobaoProp extends Object
{
    var $_appKey = '12001643';
    var $_appSecret = 'f01ef1797a187bf7b395330276390ce2';
    var $_cid = null;
    var $_pvs = null;
    var $_fields = 'cid,pid,prop_name,vid,name,name_alias,is_parent,status,sort_order';

    function __construct($cid, $pvs, $appKey='', $appSecret='', $fields='')
    {
        $this->TaobaoProp($cid, $pvs, $appKey='', $appSecret='', $fields='');
    }

    function TaobaoProp($cid, $pvs, $appKey='', $appSecret='', $fields='')
    {
        $this->_cid = $cid;
        $this->_pvs = $pvs;
        if ($appKey)
        {
            $this->_appKey = $appKey;
        }
        if ($appSecret)
        {
            $this->_appSecret = $appSecret;
        }
        if ($fields)
        {
            $this->_fields = $fields;
        }
    }

    function get_prop()
    {
        //参数数组
        $paramArr = array(
            'app_key' => $this->_appKey,
            'method' => 'taobao.itempropvalues.get',
            'format' => 'json',
            'v' => '1.0',
            'timestamp' => date('Y-m-d H:i:s'),
            'fields' => $this->_fields,
            'pvs' => $this->_pvs,
            'cid' => $this->_cid
        );

        //生成签名
        $sign = $this->_createSign($paramArr, $appSecret);

        //组织参数
        $strParam = $this->_createStrParam($paramArr);
        $strParam .= 'sign='.$sign;

        //访问服务
        $url = 'http://gw.api.taobao.com/router/rest?'.$strParam;
        $result = @file_get_contents($url);
        $result = $this->_getJsonData($result);

        if (isset($result['msg']))
        {
            $this->_error($result['msg']);
            return false;
        }
        return $result;
    }

    //签名函数
    function _createSign ($paramArr) {
        $sign = $this->_appSecret;
        ksort($paramArr);
        foreach ($paramArr as $key => $val) {
           if ($key !='' && $val !='') {
               $sign .= $key.$val;
           }
        }
        $sign = strtoupper(md5($sign));
        return $sign;
    }

    //组参函数
    function _createStrParam ($paramArr)
    {
        $strParam = '';
        foreach ($paramArr as $key => $val)
        {
           if ($key != '' && $val !='')
           {
               $strParam .= $key.'='.urlencode($val).'&';
           }
        }
        return $strParam;
    }

    //解析Json函数
    function _getJsonData ($strJson)
    {
        $arrayCode = current(ecm_json_decode($strJson, 1));
        if (isset($arrayCode['prop_values']))
        {
            $arrayCode['prop_value'] = $arrayCode['prop_values'];
            unset($arrayCode['prop_values']);
        }
        return $arrayCode ;
    }

}
?>