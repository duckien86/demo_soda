<?php
    /**
     * @Package:
     * @Author : @MANH.TV
     * @Date   : 04/01/2017
     * @Time   : 15:31 PM
     */

    $socketPath = dirname(dirname(dirname(__DIR__))) . '/vendors/socket/';

    require $socketPath . 'Exception.php';
    require $socketPath . 'BadOpcodeException.php';
    require $socketPath . 'BadUriException.php';
    require $socketPath . 'ConnectionException.php';
    require $socketPath . '/Base.php';
    require $socketPath . 'Client.php';
    use WebSocket\Client;

    class SocketClient
    {
        public static function call($type, $send_data = array(), $debug = FALSE, $timeout = 5)
        {
            $start_time = microtime(TRUE);

            $socket_api = Yii::app()->params['socket_api_url'];

            $client = new Client($socket_api);
            $client->setTimeout($timeout);
            $dataSend['id']   = $type . '-' . date('YmdHis');
            $dataSend['type'] = $type;
            if (is_array($send_data)) {
                $dataSend['data'] = CJSON::encode($send_data);
            } else {
                $dataSend['data'] = $send_data;
            }
            $dataSend = CJSON::encode($dataSend);

            /*log-------------------------------------------------------------------------*/
            if (YII_DEBUG) {
                $debug = TRUE;
            }
            if ($debug) {
                $logMsg[]   = array('-----------------#START----------------', 'I');
                $logMsg[]   = array('address: ' . $socket_api);
                $logMsg[]   = array('data send: ' . $dataSend);
                $logFolder  = "Socket_Api/" . date("Y") . "/" . date("m");
                $logRequest = new SystemLog($logFolder);
                $logRequest->setLogFile('socket_api_community_' . date("Ymd") . '.log');
            }

            try {

                $client->send($dataSend);
                $res = $client->receive();
                unset($client);
                $res = CJSON::decode($res);
                if ($res) {
                    if ((isset($res['type']) && $res['type'] == 'ERROR') || (isset($res['error']['code']) && $res['error']['code'] == -1)) {    //Lỗi

                        $message  = isset($res['error']['msg']) ? $res['error']['msg'] : "Unknown error";
                        $logMsg[] = array('ERROR');
                        $logMsg[] = array('message: ' . $message);
                        $logMsg[] = array('executeTime: ' . (microtime(TRUE) - $start_time) . 's');
                        if ($debug) {
                            @$logRequest->processWriteLogs($logMsg);
                        }

                        return array(
                            'status'      => -1,
                            'message'     => $message,
                            'data'        => array(),
                            'dataSend'    => $dataSend,
                            'executeTime' => (microtime(TRUE) - $start_time) . 's',
                        );
                    } else {        //Thành công

                        $data_o = isset($res['data']) ? $res['data'] : '[]';
                        $data   = CJSON::decode($data_o, TRUE);

                        return array(
                            'status'      => 1,
                            'message'     => "Success",
                            'data'        => $data,
                            'dataSend'    => $dataSend,
                            'executeTime' => (microtime(TRUE) - $start_time) . 's',
                        );

                    }
                } else {

                    $logMsg[] = array('ERROR');
                    $logMsg[] = array('message: Can not decode Json data');
                    $logMsg[] = array('executeTime: ' . (microtime(TRUE) - $start_time) . 's');
                    if ($debug) {
                        @$logRequest->processWriteLogs($logMsg);
                    }

                    return array(
                        'status'      => -1,
                        'message'     => 'Can not decode Json data',
                        'dataSend'    => $dataSend,
                        'data'        => array(),
                        'executeTime' => (microtime(TRUE) - $start_time) . 's',
                    );
                }
            } catch (\WebSocket\Exception $ex) {

                $logMsg[] = array('ERROR');
                $logMsg[] = array('message: ' . $ex->getMessage());
                $logMsg[] = array('executeTime: ' . (microtime(TRUE) - $start_time) . 's');
                if ($debug) {
                    @$logRequest->processWriteLogs($logMsg);
                }

                return array(
                    'status'      => -1,
                    'message'     => $ex->getMessage(),
                    'dataSend'    => $dataSend,
                    'data'        => array(),
                    'executeTime' => (microtime(TRUE) - $start_time) . 's',
                );
            }
        }

    }