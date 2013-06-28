<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SensePeople
 *
 * @author candasm
 */
class SensePeople extends CApplicationComponent {

    const REQUEST_URL = 'http://%s.pqpeople.com/importer/push';

    /**
     * @var string sense people account name exp: http://galatasaray.pqpeople.com must be a galatasaray
     */
    public $accountName = NULL;

    /**
     * @var string sense people campaign id
     */
    public $campaignId = NULL;

    /**
     * @var string sense people campaign token
     */
    public $campaignToken = NULL;
    public $saveHistoryToDb = FALSE;
    private $_curlError = FALSE;
    private $_curlResponse = FALSE;
    private $_curlRequestUrl = NULL;
    private $_curlPostData = NULL;
    protected $status = FALSE;
    protected $errors = array();
    protected $warnings = array();
    protected $response = NULL;

    public function getAccountName() {
        return $this->accountName;
    }

    public function getCampaignId() {
        return $this->campaignId;
    }

    public function getCampaignToken() {
        return $this->campaignToken;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getWarnings() {
        return $this->warnings;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setAccountName($accountName) {
        $this->accountName = $accountName;
    }

    public function setCampaingId($campaignId) {
        $this->campaignId = $campaignId;
    }

    public function setCampaignToken($campaignToken) {
        $this->campaignToken = $campaignToken;
    }

    /**
     * @param array $customer Customer data of sense people <code><pre>
      array(
      'facebook_id' => $userId,
      'facebook_username' => $fbUserData['username'],
      'facebook_name' => $fbUserData['name'],
      'facebook_likes' => $userLikeData,
      'facebook_gender' => $fbUserData['sex'],
      'facebook_phone' => $userFormData['phone'],
      'facebook_hometown' => array(
      'city' => isset($hometown->city) ? $hometown->city : '',
      'country' => isset($hometown->country) ? $hometown->country : '',
      ),
      'facebook_education' => $education,
      'facebook_location' => array(
      'city' => isset($currentLocation->city) ? $currentLocation->city : '',
      'country' => isset($currentLocation->country) ? $currentLocation->country : '',
      ),
      'facebook_birthday' => date('Y-m-d', strtotime($fbUserData['birthday_date'])),
      'facebook_work' => json_decode($fbUserData['work'], true),
      'facebook_relationship_status' => $fbUserData['relationship_status'],
      'facebook_access_token' => $fbUserData['long_access_token'],
      'email' => $userFormData['email'],
      'first_name' => $userFormData['first_name'],
      'last_name' => $userFormData['last_name'],
      'joined_at' => $userFormData['created_time'] . ' +2',
      'gender' => $fbUserData['sex'],
      ),
      </code></pre>
     * @return bool 
     */
    public function push($customer) {
        $this->_curlPostData = array(
            'campaign_id' => $this->campaignId,
            'campaign_token' => $this->campaignToken,
            'customer' => $customer,
        );
        $this->_curlRequestUrl = sprintf(self::REQUEST_URL, $this->accountName);
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $this->_curlRequestUrl);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($this->_curlPostData));
        $this->_curlResponse = curl_exec($c);
        $this->_curlError = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);

        $result = FALSE;

        if ($this->_curlResponse !== FALSE) {
            $jsonResponse = json_decode($this->_curlResponse);
            $this->status = $jsonResponse->status;
            $this->response = $jsonResponse;
            if ($jsonResponse->status === TRUE) {
                $result = TRUE;
            }
            $this->errors = $jsonResponse->errors;
            $this->warnings = $jsonResponse->warnings;
        }

        if ($this->saveHistoryToDb) {
            Yii::import('ext.sensepeople.models.*');
            $historyRecord = new SensePeopleDataSend();
            $historyRecord->request_url = $this->_curlRequestUrl;
            $historyRecord->post_data = json_encode($this->_curlPostData);
            $historyRecord->response_data = $this->_curlResponse;

            if (!$historyRecord->save()) {
                $error = $historyRecord->getErrors();
                Yii::log('sensepeopledatasend kaydedilemedi: ' . json_encode($error), 'error');
            }
        }
        return $result;
    }

}
